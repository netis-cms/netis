import { defineConfig } from 'vite';
import nette from '@nette/vite-plugin';
import fg from 'fast-glob';
import path from 'path';

const files = fg.sync('assets/*.js', {
	ignore: ['assets/core/**', 'assets/naja/**'],
});

const entries = files.map(
	file => path.resolve(file)
);

export default defineConfig({
	root: 'assets',
	publicDir: 'public',
	server: {
		cors: {
			origin: true,
		},
	},
	build: {
		outDir: '../www/dist',
		emptyOutDir: true,
		cssMinify: false,
		rollupOptions: {
			input: entries,
		},
	},
	css: {
		preprocessorOptions: {
			scss: {
				silenceDeprecations: [
					'import',
					'if-function',
					'global-builtin',
					'color-functions'
				]
			}
		}
	},
	plugins: [
		nette(),
	],
});
