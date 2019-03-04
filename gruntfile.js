module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // css minify
    cssmin: {
      target: {
        files: [{
          'www/css/main.style.min.css': [
            'node_modules/open-sans-fontface/open-sans.css',
            'node_modules/font-awesome/css/font-awesome.css',
            'node_modules/perfect-scrollbar/css/perfect-scrollbar.css',
            'node_modules/sidebar-menu-accgit/css/sidebar.css',
          ]
        }]
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

    // js minify
    uglify: {
      options: {
        reserved: ['jQuery']
      },
      my_target: {
        files: {
          'www/js/main.min.js': [
            'node_modules/jquery/dist/jquery.js',
            'node_modules/perfect-scrollbar/dist/perfect-scrollbar.js',
            'node_modules/popper/dist/popper.js',
            'node_modules/bootstrap/dist/js/bootstrap.js',
            'node_modules/nanobar/nanobar.js',
            'node_modules/sidebar-menu-accgit/js/sidebar.menu.js',
            'node_modules/live-form-validation/live-form-validation.js',
            'node_modules/nette.ajax.js/nette.ajax.js',
            'assets/js/spinner.js'
          ]
        }
      }
    }
  });

  // grunt tasks
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.registerTask('minify-all', ['cssmin', 'uglify', 'copy']);
};
