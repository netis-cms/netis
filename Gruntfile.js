module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'assets/css/base.css': 	    ['assets/scss/base.scss'],
                    'assets/css/bootstrap.css': ['assets/scss/base/bootstrap.scss'],
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
                src: ['node_modules/bootstrap/dist/css/bootstrap.css'],
                dest: 'assets/combined/css/style.css'
            },
            js: {
                src: ['vendor/nette/forms/src/assets/netteForms.js'],
                dest: 'assets/combined/js/app.js'
            }
        },
        uglify: {
            js: {
                src: 'assets/combined/js/app.js',
                dest: 'www/js/app.min.js'
            }
        },
        cssmin: {
            css: {
                src: 'assets/combined/css/style.css',
                dest: 'www/css/style.min.css'
            }
        },
        processhtml: {
            dist: {
                files: {
                    'app/module/web/presenter/templates/@layout.latte': [
                        'app/module/web/presenter/templates/@dev.latte'
                    ]
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
