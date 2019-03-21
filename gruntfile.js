module.exports = function (grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// sass compile
		sass: {
			dist: {
				options: {
					style: 'compressed'
				},
				files: {
					'www/css/bootstrap.css': 'assets/scss/base/bootstrap.scss',
					'www/css/install.css': 'assets/scss/install/install.scss',
					'www/css/sign-in.css': 'assets/scss/sign-in/sign-in.scss',
				}
			}
		},

		// copy files
		copy: {
			main: {
				files: [{
					expand: true,
					cwd: 'node_modules/font-awesome/fonts/',
					src: ['**'],
					dest: 'www/fonts/'
				}
				]
			}
		},

		// js minify
		uglify: {
			options: {
				reserved: ['jQuery']
			},
			my_target: {
				files: {
					'www/js/main.js': [
						'node_modules/jquery/dist/jquery.js',
						'node_modules/popper/dist/popper.js',
						'node_modules/bootstrap/dist/js/bootstrap.js',
						'node_modules/live-form-validation/live-form-validation.js',
						'node_modules/nette.ajax.js/nette.ajax.js',
						'assets/js/spinner.js'
					]
				}
			}
		}
	});

	// grunt tasks
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.registerTask('task-all', ['sass', 'uglify', 'copy']);
};
