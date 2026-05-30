import { execSync } from "child_process";
import { existsSync, readdirSync, statSync, readFileSync } from "fs";
import { join } from "path";

const vendorDir = "./vendor/drago-ex/";
if (!existsSync("package.json")) {
	console.log("No package.json, skipping npm install.");
	process.exit(0);
}

// Find all packages that are valid npm packages (have package.json with a name)
const packagesToInstall = readdirSync(vendorDir).filter(name => {
	const pkgPath = join(vendorDir, name);
	if (!statSync(pkgPath).isDirectory()) return false;

	const pkgJsonPath = join(pkgPath, "package.json");
	if (existsSync(pkgJsonPath)) {
		try {
			const pkgJson = JSON.parse(readFileSync(pkgJsonPath, "utf-8"));
			// Only install if it has a name - npm requires this
			return !!pkgJson.name;
		} catch (e) {
			return false;
		}
	}
	return false;
}).map(name => join(vendorDir, name).replace(/\\/g, '/')); // Use forward slashes for npm

if (packagesToInstall.length > 0) {
	console.log(`Installing and registering local packages: ${packagesToInstall.join(', ')}...`);
	try {
		// Batch install for speed
		execSync(`npm install ${packagesToInstall.join(' ')}`, { stdio: "inherit" });
	} catch (error) {
		console.error("Failed to install local packages. Falling back to sequential install...");

		// Fallback to sequential if batch fails
		for (const pkg of packagesToInstall) {
			try {
				console.log(`Installing ${pkg}...`);
				execSync(`npm install ${pkg}`, { stdio: "inherit" });
			} catch (e) {
				console.error(`Failed to install ${pkg}:`, e.message);
			}
		}
	}
} else {
	console.log("No local drago-ex packages found to install.");
}
