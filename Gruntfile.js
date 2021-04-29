module.exports = function (grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		cssmin: {
			dist: {
				files: {
					'www/css/dashboard.min.css': [
						'assets/dist/css/bootstrap.css',
						'assets/vendor/**/css/open-sans.css',
						'assets/vendor/**/css/fontawesome.css',
						'assets/vendor/**/css/perfect-scrollbar.css',
						'assets/vendor/**/css/sidebar.css',
						'assets/vendor/**/css/sidebar.menu.css',
						'assets/vendor/**/css/dashboard.css',
						'assets/dist/css/base.cs',
					],
					'www/css/install.min.css': [
						'assets/dist/css/bootstrap.css',
						'assets/vendor/**/css/open-sans.css',
						'assets/vendor/**/css/fontawesome.css',
						'assets/dist/css/install.css',
						'assets/dist/css/base.css',
					],
					'www/css/sign.min.css': [
						'assets/dist/css/bootstrap.css',
						'assets/vendor/**/css/open-sans.css',
						'assets/dist/css/sign.css',
						'assets/dist/css/base.css',
					],
				}
			}
		},
		uglify: {
			dist: {
				files: {
					'www/js/dashboard.min.js': [
						'assets/vendor/**/js/jquery.js',
						'assets/vendor/**/js/bootstrap.bundle.js',
						'assets/vendor/**/js/perfect-scrollbar.js',
						'assets/vendor/**/js/nanobar.js',
						'assets/vendor/**/js/sidebar.js',
						'assets/vendor/**/sidebar.menu.js',
					],
					'www/js/app.min.js': [
						'assets/vendor/**/js/jquery.js',
						'assets/vendor/**/js/bootstrap.bundle.js',
						'assets/vendor/**/js/netteForms.js',
						'assets/vendor/**/js/nette.ajax.js',
						'assets/vendor/**/js/live-form-validation.js',
						'assets/dist/js/spinner.btn.js',
					],
				},
			}
		}
	});
	grunt.registerTask('run-minify', [
		'cssmin', 'uglify'
	]);
};
