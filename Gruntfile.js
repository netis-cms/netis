module.exports = function (grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// task scss
		sass: {
			dist: {
				options: {
					style: 'compressed'
				},
				files: {
					'www/css/base.css': 	['assets/scss/base/base.scss'],
					'www/css/install.css': 	['assets/scss/install/install.scss'],
					'www/css/sign-in.css': 	['assets/scss/sign-in.scss']
				}
			}
		},

		// task minify css
		cssmin: {
			options: {
				sourceMap: true
			},
			target: {
				files: {
					'www/css/base.css': [
						'www/css/base.css',
						'node_modules/font-awesome/css/font-awesome.css'
					],
					'www/css/sidebar.css': [
						'node_modules/open-sans-fontface/open-sans.css',
						'node_modules/perfect-scrollbar/css/perfect-scrollbar.css',
						'node_modules/sidebar-menu-accgit/css/sidebar.css',
					]
				}
			}
		},

		// task copy files or folder
		copy: {
			main: {
				files: [
					{
						expand: true, cwd: 'node_modules/font-awesome/fonts/',
						src: ['**'],
						dest: 'www/fonts/'
					}
				]
			}
		},

		// task minify js
		uglify: {
			options: {
				reserved: ['jQuery']
			},
			my_target: {
				files: {
					'www/js/main.js': [
						'node_modules/jquery/dist/jquery.js',
						'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
						'node_modules/live-form-validation/live-form-validation.js',
						'vendor/nette/forms/src/assets/netteForms.js',
						'node_modules/nette.ajax.js/nette.ajax.js',
						'assets/js/spinner-btn.js'
					],
					'www/js/sidebar.js': [
						'node_modules/perfect-scrollbar/dist/perfect-scrollbar.js',
						'node_modules/nanobar/nanobar.js',
						'node_modules/sidebar-menu-accgit/js/sidebar.menu.js'
					]
				}
			}
		}
	});

	// grunt tasks
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.registerTask('task-all', ['sass', 'uglify', 'copy', 'cssmin']);
};
