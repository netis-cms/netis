export default class InstallWizard {
	#naja;
	#installBtn;

	initialize(naja) {
		this.#naja = naja;
		this.#init();
		naja.snippetHandler.addEventListener('afterUpdate', () => this.#init());
	}

	#init() {
		this.#initButton('btn-send', (btn) => {
			this.#naja.makeRequest('GET', btn.dataset.url, null, { history: false })
				.catch(() => { btn.disabled = false; });
		});

		this.#initButton('install-btn', async (btn) => {
			await this.#runInstall(btn);
		});
	}

	#initButton(id, callback) {
		const btn = document.getElementById(id);
		if (!btn || btn.dataset.initialized) return;

		btn.dataset.initialized = 'true';
		btn.addEventListener('click', () => {
			btn.disabled = true;
			callback(btn);
		});
	}

	#updateChip(state, file = null, done = null, total = null) {
		const container = document.getElementById('install-status-container');
		if (!container) return;

		container.classList.remove('d-none');

		['running', 'success', 'error'].forEach(s => {
			const icon = document.getElementById(`status-icon-${s}`);
			if (icon) icon.classList.toggle('d-none', s !== state);
		});

		const statusFile = document.getElementById('status-file');
		const statusCounter = document.getElementById('status-counter');

		if (file && statusFile) statusFile.textContent = file;
		if (done !== null && statusCounter) statusCounter.textContent = `${done} / ${total}`;
	}

	async #runInstall(btn) {
		this.#installBtn = btn;
		const steps = document.querySelectorAll('#steps .step');
		const total = steps.length;
		let done = 0;
		let allOk = true;
		let lastFile = null;

		if (total === 0) {
			this.#finalize(false, done, total, lastFile);
			return;
		}

		const spinner = document.querySelector('.spinner');
		if (spinner) spinner.style.display = 'block';

		for (const step of steps) {
			const { url, step: file } = step.dataset;
			lastFile = file;

			this.#updateChip('running', file, done + 1, total);

			try {
				const res = await this.#naja.makeRequest('GET', url, null, { history: false });
				console.log(`Migration response for ${file}:`, res);

				if (res.status === 'success') {
					done++;
				} else {
					allOk = false;
					break;
				}
			} catch (e) {
				allOk = false;
				console.error(`Migration request failed for ${file}:`, e);
				break;
			}
		}

		if (spinner) spinner.style.display = 'none';
		this.#finalize(allOk, done, total, lastFile);
	}

	#finalize(allOk, done, total, lastFile) {
		this.#updateChip(allOk ? 'success' : 'error', lastFile, done, total);
		if (!allOk) this.#installBtn.disabled = false;

		const targetUrl = allOk ? this.#installBtn.dataset.url : this.#installBtn.dataset.urlError;
		if (targetUrl) {
			this.#naja.makeRequest('GET', targetUrl, null, { history: false }).then(() => {
				if (!allOk) this.#updateChip('error', lastFile, done, total);
			});
		}
	}
}
