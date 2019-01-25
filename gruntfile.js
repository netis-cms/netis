module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		cssmin: {
			target: {
				files: [
					{'www/css/cssui.min.css': [
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
						'node_modules/css-ui-table/css/style.tables.css',
						'node_modules/css-ui-modal-box/css/style.modal.box.css',
						'node_modules/css-ui-dashboard/css/style.dashboard.css',
						'www/css/src/style.netis.css'
					]},
					{'www/css/cssui.sign.in.min.css': [
						'node_modules/normalize.css/normalize.css',
						'node_modules/open-sans-fontface/open-sans.css',
						'node_modules/css-ui-simple/css/cssui.css',
						'node_modules/css-ui-sign-in/css/style.sign.in.css',
						'www/css/src/style.netis.css'
					]},
					{'www/css/style.install.min.css': [
						'node_modules/normalize.css/normalize.css',
						'node_modules/open-sans-fontface/open-sans.css',
						'node_modules/css-ui-simple/css/cssui.css',
						'www/css/src/style.install.css'
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
						'node_modules/nette-forms/assets/netteForms.js',
						'node_modules/live-form-validation/live-form-validation.js',
						'node_modules/nette.ajax.js/nette.ajax.js',
						'www/js/src/nette.ajax.spinner.js',
						'www/js/src/nette.responsive.menu.js',
						'www/js/src/nette.dropdown.menu.js'
					]},
					{'www/js/main.min.js': [
						'node_modules/jquery/dist/jquery.js',
						'node_modules/nanobar/nanobar.js',
						'node_modules/nette-forms/assets/netteForms.js',
						'node_modules/live-form-validation/live-form-validation.js',
						'node_modules/nette.ajax.js/nette.ajax.js',
						'www/js/src/nette.ajax.spinner.js'
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
