import { defineConfig } from 'vite';

export default defineConfig(({ mode }) => {
	const DEV = mode === 'development';

	return {
		publicDir: './assets/public',
		base: '/dist/',
		server: {
			open: false,
			hmr: false,
		},
		css: {
			postcss: [
				"autoprefixer"
			]
		},
		build: {
			assetsDir: '',
			outDir: './www/dist/',
			emptyOutDir: true,
			minify: DEV ? false : 'esbuild',
			rollupOptions: {
				output: {
					manualChunks: undefined,
					chunkFileNames: '[name].js',
					entryFileNames: '[name].js',
					assetFileNames: '[name].[ext]',
				},
				input: {
					admin: './assets/js/admin.js',
					sign: './assets/js/sign.js',
					install: './assets/js/install.js',
				}
			}
		},
	}
});
