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
                    },

                    {
                        expand: true,
                        cwd: 'node_modules/open-sans-fontface/fonts/',
                        src: ['**'],
                        dest: 'www/css/fonts/'
                    }
                ]
            }
        },
        concat: {
            css: {
                files: {
                    'assets/combined/css/base.css':  [
                        'assets/css/bootstrap.css',
                        'assets/css/base.css',
                        'node_modules/open-sans-fontface/open-sans.css',
                        'node_modules/font-awesome/css/font-awesome.css'
                    ],
                    'assets/combined/css/install.css':  [
                        'assets/combined/css/base.css',
                        'assets/css/install.css'
                    ],
                    'assets/combined/css/sign.css':     [
                        'assets/combined/css/base.css',
                        'assets/css/sign.css'
                    ],
                    'assets/combined/css/admin.css':     [
                        'assets/combined/css/base.css',
                        'node_modules/perfect-scrollbar/css/perfect-scrollbar.css',
                        'node_modules/sidebar-menu-compostrap/dist/css/sidebar.css'
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
                        'node_modules/live-form-validation/live-form-validation.js',
                        'assets/js/spinner-btn.js'
                    ],
                    'assets/combined/js/admin.js':  [
                        'assets/combined/js/app.js',
                        'node_modules/nanobar/nanobar.js',
                        'node_modules/sidebar-menu-compostrap/dist/js/sidebar.menu.js'
                    ],
                },
            }
        },
        uglify: {
            dist: {
                files: {
                    'www/js/app.min.js':   ['assets/combined/js/app.js'],
                    'www/js/admin.min.js': ['assets/combined/js/admin.js']
                },
            }
        },
        cssmin: {
            dist: {
                files: {
                    'www/css/install.min.css': ['assets/combined/css/install.css'],
                    'www/css/sign.min.css':    ['assets/combined/css/sign.css'],
                    'www/css/admin.min.css':   ['assets/combined/css/admin.css']
                },
            }
        },
        processhtml: {
            dist: {
                files: {
                    'app/module/install/presenter/templates/@layout.latte':    ['app/module/install/presenter/templates/@dev.latte'],
                    'app/module/admin/presenter/templates/Sign/@layout.latte': ['app/module/admin/presenter/templates/Sign/@dev.latte'],
                    'app/module/admin/presenter/templates/Admin/@layout.latte': ['app/module/admin/presenter/templates/Admin/@dev.latte']
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
