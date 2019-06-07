module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            dev: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'assets/css/bootstrap.css': ['assets/scss/base/bootstrap.scss'],
                    'assets/css/base.css': 	    ['assets/scss/base.scss'],
                    'assets/css/install.css':   ['assets/scss/install/install.scss'],
                    'assets/css/sign.css':      ['assets/scss/sign.scss']
                }
            }
        },
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
        concat: {
            css: {
                files: {
                    'assets/combined/css/install.css':  [
                        'assets/css/bootstrap.css',
                        'assets/css/base.css',
                        'assets/css/install.css'
                    ],
                    'assets/combined/css/sign.css':     [
                        'assets/css/bootstrap.css',
                        'assets/css/base.css',
                        'assets/css/sign.css'
                    ],
                },
            },
            js: {
                files: {
                    'assets/combined/js/app.js':  [
                        'node_modules/jquery/dist/jquery.js',
                        'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
                        'vendor/nette/forms/src/assets/netteForms.js',
                        'node_modules/nette.ajax.js/nette.ajax.js',
                        'node_modules/live-form-validation/live-form-validation.js'
                    ],
                },
            }
        },
        uglify: {
            dist: {
                files: {
                    'www/js/app.min.js': ['assets/combined/js/app.js']
                },
            }
        },
        cssmin: {
            dist: {
                files: {
                    'www/css/install.min.css': ['assets/combined/css/install.css'],
                    'www/css/sign.min.css':    ['assets/combined/css/sign.css']
                },
            }
        },
        processhtml: {
            dist: {
                files: {
                    'app/module/install/presenter/templates/@layout.latte':    ['app/module/install/presenter/templates/@dev.latte'],
                    'app/module/admin/presenter/templates/Sign/@layout.latte': ['app/module/admin/presenter/templates/Sign/@dev.latte']
                }
            }
        },
    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-processhtml');
    grunt.registerTask('grunt-sass', ['sass',]);
    grunt.registerTask('grunt-run', [
        'copy', 'concat', 'uglify', 'cssmin', 'processhtml'
    ]);
};
