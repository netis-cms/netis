module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		cssmin: {
			target: {
				files: [
					{'www/css/style.min.css': [
						'node_modules/normalize.css/normalize.css',
						'node_modules/open-sans-fontface/open-sans.css',
						'node_modules/font-awesome/css/font-awesome.css',
						'node_modules/perfect-scrollbar/css/perfect-scrollbar.css',
						'node_modules/css-ui-simple/css/cssui.css',
						'node_modules/css-ui-responsive-menu/css/style.menu.css',
						'node_modules/css-ui-responsive-menu/css/style.menu.light.css',
						'node_modules/css-ui-dropdown-menu/css/style.dropdown.css',
						'node_modules/css-ui-dropdown-menu/css/style.dropdown.light.css',
						'node_modules/css-ui-tooltip/css/style.tooltip.css',
						'node_modules/css-ui-tooltip/css/style.tooltip.theme.css',
						'node_modules/css-ui-dashboard/css/style.dashboard.css',
						'assets/css/style.css'
					]},
					{'www/css/style.sign.min.css': [
						'node_modules/normalize.css/normalize.css',
						'node_modules/open-sans-fontface/open-sans.css',
						'node_modules/css-ui-simple/css/cssui.css',
						'node_modules/css-ui-sign-in/css/style.sign.in.css',
						'assets/css/style.css'
					]},
					{'www/css/style.install.min.css': [
						'node_modules/normalize.css/normalize.css',
						'node_modules/open-sans-fontface/open-sans.css',
						'node_modules/font-awesome/css/font-awesome.css',
						'node_modules/css-ui-simple/css/cssui.css',
						'assets/css/style.install.css',
						'assets/css/style.css'
					]}
				]
			}
		},
		copy: {
			main: {
				files: [
					{expand: true, cwd: 'node_modules/font-awesome/fonts/', src: ['**'], dest: 'www/fonts/'},
					{expand: true, cwd: 'node_modules/open-sans-fontface/fonts/', src: ['**'], dest: 'www/css/fonts/'}
				]
			}
		},
		uglify: {
			options: {
				reserved: ['jQuery'],
				sourceMap: true,
				sourceMapName: 'www/js/main.map'
			},
			my_target: {
				files: [
					{'www/js/dashboard.min.js': [
						'node_modules/jquery/dist/jquery.js',
						'node_modules/perfect-scrollbar/dist/perfect-scrollbar.js',
						'node_modules/nanobar/nanobar.js',
						'vendor/nette/forms/assets/netteForms.js',
						'node_modules/live-form-validation/live-form-validation.js',
						'node_modules/nette.ajax.js/nette.ajax.js',
						'assets/js/spinner.js',
						'assets/js/responsive.menu.js',
						'assets/js/dropdown.menu.js',
						'assets/js/flash.messages.js'
					]},
					{'www/js/main.min.js': [
						'node_modules/jquery/dist/jquery.js',
						'node_modules/nanobar/nanobar.js',
						'vendor/nette/forms/assets/netteForms.js',
						'node_modules/live-form-validation/live-form-validation.js',
						'node_modules/nette.ajax.js/nette.ajax.js',
						'assets/js/spinner.js'
					]}
				]
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.registerTask('minify-all', ['cssmin', 'uglify', 'copy']);
};
