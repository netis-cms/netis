// Releases the drago-ex packages cloned next to this repository, in dependency order.
//
//   node netis/tools/release.mjs                      plan: what needs a release, in which order, with which version
//   node netis/tools/release.mjs project-tools=major  the same with a chosen bump (major|minor|patch) or version (2.4.0)
//   node netis/tools/release.mjs utils                also packages without shipped changes, when named
//   node netis/tools/release.mjs --publish            release the plan: checks, GitHub release, waits for Packagist
//
// A package needs a release when files shipped to users changed since its last tag; CI, tests, readme, agent
// guides and require-dev do not count. Release notes are drafted from the commit subjects into
// temp/release-notes/<package>.md of this repository, where they can be edited before --publish.
// The packages are expected in the parent directory of this repository (DRAGO_WORKSPACE overrides it).
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { spawnSync } from 'node:child_process';

const netis = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const root = process.env.DRAGO_WORKSPACE ?? path.dirname(netis);
const notesDir = path.join(netis, 'temp', 'release-notes');

// Never released by this script: retired, unfinished or not repositories of released packages.
const skipped = new Set(['authorization', 'project-blog', 'project-static-page', 'commerce', 'code-order', 'cms', 'project-test', 'netis']);

// Files that are not shipped to users, so changing them alone needs no release.
const devOnly = file => /^(\.github\/|tests\/)/.test(file)
	|| /\.md$/i.test(file)
	|| ['phpstan.neon', '.gitattributes', '.gitignore', 'license', '.editorconfig'].includes(file);

const run = (cmd, args, cwd = root) => {
	const result = spawnSync(cmd, args, { cwd, encoding: 'utf8' });
	return { ok: result.status === 0, out: (result.stdout || '').trim(), err: (result.stderr || '').trim() };
};
const git = (dir, ...args) => run('git', args, path.join(root, dir)).out;

const args = process.argv.slice(2);
const publish = args.includes('--publish');
const requested = new Map(args.filter(a => !a.startsWith('--')).map(a => a.split('=')).map(([n, b]) => [n, b ?? 'minor']));

// composer.json without the parts that do not reach users.
const shippedComposer = json => {
	if (!json) return null;
	const { 'require-dev': _dev, scripts: _scripts, config: _config, ...rest } = JSON.parse(json);
	return JSON.stringify(rest);
};

const packages = [];
for (const dir of fs.readdirSync(root).sort()) {
	const composerFile = path.join(root, dir, 'composer.json');
	if (skipped.has(dir) || !fs.existsSync(composerFile) || !fs.existsSync(path.join(root, dir, '.git'))) continue;
	const composer = JSON.parse(fs.readFileSync(composerFile, 'utf8'));
	if (!composer.name?.startsWith('drago-ex/')) continue;

	// Releases are created on GitHub, so the local repository may not know the latest tag yet.
	run('git', ['fetch', '-q', '--tags', 'origin'], path.join(root, dir));
	const tag = git(dir, 'describe', '--tags', '--abbrev=0');
	if (!tag) continue;

	const changed = git(dir, 'diff', '--name-only', `${tag}..HEAD`).split('\n').filter(Boolean);
	const composerChanged = changed.includes('composer.json')
		&& shippedComposer(git(dir, 'show', `${tag}:composer.json`)) !== shippedComposer(fs.readFileSync(composerFile, 'utf8'));
	const shipped = changed.filter(f => f !== 'composer.json' && !devOnly(f)).concat(composerChanged ? ['composer.json'] : []);

	// Commits that changed something shipped become the draft of the release notes.
	const commits = git(dir, 'log', '--format=%H %s', `${tag}..HEAD`).split('\n').filter(Boolean).map(line => {
		const [hash, ...subject] = line.split(' ');
		const files = git(dir, 'show', '--name-only', '--format=', hash).split('\n').filter(Boolean);
		return { subject: subject.join(' '), shipped: files.some(f => !devOnly(f)) };
	});

	packages.push({
		dir, name: composer.name, tag, shipped, commits,
		requires: Object.keys(composer.require ?? {}).filter(n => n.startsWith('drago-ex/')),
		constraints: composer.require ?? {},
		// create-project installs require-dev too, so these also keep new versions out of projects.
		devConstraints: composer['require-dev'] ?? {},
	});
}

const nextVersion = (tag, bump) => {
	if (/^\d+\.\d+\.\d+$/.test(bump)) return bump;
	const [major, minor, patch] = tag.replace(/^v/, '').split('.').map(Number);
	return bump === 'major' ? `${major + 1}.0.0` : bump === 'patch' ? `${major}.${minor}.${patch + 1}` : `${major}.${minor + 1}.0`;
};

// Caret constraints (^2.3, ^2.3 || ^3.0) are the only kind used between drago-ex packages.
const allows = (constraint, version) => constraint.split('||').map(c => c.trim()).some(c => {
	const m = c.match(/^\^(\d+)(?:\.(\d+))?(?:\.(\d+))?$/);
	if (!m) return true;
	const [maj, min, pat] = version.split('.').map(Number);
	const [cMin, cPat] = [Number(m[2] ?? 0), Number(m[3] ?? 0)];
	return maj === Number(m[1]) && (min > cMin || (min === cMin && pat >= cPat));
});

const plan = packages
	.filter(p => p.shipped.length > 0 || requested.has(p.dir))
	.map(p => ({ ...p, version: nextVersion(p.tag, requested.get(p.dir) ?? 'minor') }));
for (const name of requested.keys()) {
	if (!packages.some(p => p.dir === name)) console.log(`WARN ${name} is not a releasable package of this workspace`);
}

// Dependencies first; packages that do not depend on each other keep alphabetical order.
const ordered = [];
const visit = (p, stack = []) => {
	if (ordered.includes(p)) return;
	if (stack.includes(p)) throw new Error(`Dependency cycle: ${[...stack, p].map(x => x.dir).join(' -> ')}`);
	for (const dep of plan.filter(d => p.requires.includes(d.name))) visit(dep, [...stack, p]);
	ordered.push(p);
};
plan.forEach(p => visit(p));

if (ordered.length === 0) {
	console.log('Nothing to release: no shipped changes since the last tags.');
	process.exit(0);
}

// Constraints that would keep the new versions out, e.g. ^1.0 against a new 2.0.0.
const problems = [];
for (const p of ordered) {
	for (const dependent of packages) {
		for (const [key, constraints] of [['require', dependent.constraints], ['require-dev', dependent.devConstraints]]) {
			const constraint = constraints[p.name];
			if (constraint && !allows(constraint, p.version)) {
				problems.push(`${dependent.dir} ${key} ${p.name} ${constraint} does not allow ${p.version}`);
			}
		}
	}
}

fs.mkdirSync(notesDir, { recursive: true });
console.log(`Release plan (${ordered.length} packages, in this order):\n`);
for (const [i, p] of ordered.entries()) {
	const deps = ordered.filter(d => p.requires.includes(d.name)).map(d => d.dir);
	console.log(`${String(i + 1).padStart(2)}. ${p.dir.padEnd(20)} ${p.tag} -> v${p.version}${deps.length ? `   after ${deps.join(', ')}` : ''}`);
	console.log(`    shipped changes: ${p.shipped.length ? p.shipped.slice(0, 6).join(', ') + (p.shipped.length > 6 ? ', ...' : '') : 'none (requested)'}`);

	const notesFile = path.join(notesDir, `${p.dir}.md`);
	if (!fs.existsSync(notesFile)) {
		const lines = p.commits.filter(c => c.shipped).map(c => `- ${c.subject}`);
		fs.writeFileSync(notesFile, (lines.length ? lines : ['- ']).join('\n') + '\n');
	}
	console.log(`    notes: ${path.relative(root, notesFile).replaceAll(path.sep, '/')}`);
}

if (problems.length) {
	console.log('\nConstraints to raise before releasing:');
	problems.forEach(p => console.log(`  - ${p}`));
}

if (!publish) {
	console.log('\nEdit the notes, then release with: node netis/tools/release.mjs --publish' + (requested.size ? ' ' + args.filter(a => !a.startsWith('--')).join(' ') : ''));
	process.exit(problems.length ? 1 : 0);
}

if (problems.length) {
	console.log('\nERROR Raise the constraints above first.');
	process.exit(1);
}

// Everything must be committed, pushed and green before anything is tagged.
const blockers = [];
for (const p of ordered) {
	const dirty = git(p.dir, 'status', '--porcelain').split('\n').filter(l => l && !l.endsWith('.idea/'));
	if (dirty.length) blockers.push(`${p.dir}: uncommitted changes`);
	run('git', ['fetch', '-q', 'origin'], path.join(root, p.dir));
	if (git(p.dir, 'rev-parse', 'HEAD') !== git(p.dir, 'rev-parse', '@{u}')) blockers.push(`${p.dir}: HEAD is not pushed`);
	const runs = run('gh', ['run', 'list', '-R', p.name, '--commit', git(p.dir, 'rev-parse', 'HEAD'), '--json', 'name,status,conclusion']);
	const failed = runs.ok ? JSON.parse(runs.out || '[]').filter(r => r.status !== 'completed' || r.conclusion !== 'success') : [{ name: 'gh' }];
	if (failed.length) blockers.push(`${p.dir}: CI not green (${failed.map(r => r.name).join(', ')})`);
}
if (blockers.length) {
	console.log('\nERROR Not released:');
	blockers.forEach(b => console.log(`  - ${b}`));
	process.exit(1);
}

const onPackagist = (name, version) => {
	const res = run('curl', ['-fsSL', `https://repo.packagist.org/p2/${name}.json?t=${Date.now()}`]);
	return res.ok && res.out.includes(`"version":"v${version}"`);
};
const sleep = ms => Atomics.wait(new Int32Array(new SharedArrayBuffer(4)), 0, 0, ms);

console.log('');
for (const [i, p] of ordered.entries()) {
	const notesFile = path.join(notesDir, `${p.dir}.md`);
	const notes = fs.readFileSync(notesFile, 'utf8').trim()
		+ `\n\n**Full Changelog**: https://github.com/${p.name}/compare/${p.tag}...v${p.version}\n`;
	const release = run('gh', ['release', 'create', `v${p.version}`, '--repo', p.name, '--target', 'main', '--title', `v${p.version}`, '--notes', notes]);
	if (!release.ok) {
		console.log(`ERROR ${p.dir}: ${release.err}`);
		process.exit(1);
	}
	fs.rmSync(notesFile);
	console.log(`OK ${p.dir} v${p.version}  ${release.out}`);

	// Dependents later in the plan are installed from Packagist, so it has to know the new version first.
	if (ordered.slice(i + 1).some(d => d.requires.includes(p.name))) {
		process.stdout.write(`   waiting for Packagist`);
		for (let attempt = 0; !onPackagist(p.name, p.version); attempt++) {
			if (attempt === 40) {
				console.log(`\nERROR ${p.name} v${p.version} is not on Packagist after 10 minutes; continue later with the rest of the plan.`);
				process.exit(1);
			}
			process.stdout.write('.');
			sleep(15000);
		}
		console.log(' ok');
	}
}

console.log(`\nOK Released ${ordered.length} packages.`);
