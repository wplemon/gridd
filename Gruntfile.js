/* global module */
module.exports = function( grunt ) {

    grunt.initConfig({

        // Compile CSS
        sass: {
            main: {
				options: {
					sourcemap: 'none'
				},
				files: [ {
					expand: true,
					cwd: 'assets/css/',
					src: [ '**/*.scss', '!blocks/block-library/**/*.scss' ],
					dest: 'assets/css',
					ext: '.css',
					extDot: 'first'
				} ]
            },
            gridParts: {
				options: {
					sourcemap: 'none'
				},
				files: [ {
					expand: true,
					cwd: 'grid-parts',
					src: [ 'styles/**/*.scss', 'styles/**/**/*.scss', 'styles/**/**/**/*.scss' ],
					dest: 'grid-parts',
					ext: '.css'
				} ]
            }
		},

		cssmin: {
			target: {
					options: {
						sourceMap: false
					},
					files: [
					{
						expand: true,
						cwd: 'assets/css',
						src: [ '**/*.css', '!**/*.min.css' ],
						dest: 'assets/css',
						ext: '.min.css'
					},
					{
						expand: true,
						cwd: 'grid-parts',
						src: [ '**/*.css', '!**/*.min.css' ],
						dest: 'grid-parts',
						ext: '.min.css'
					}
				]
			}
		},

		uglify: {
			dev: {
				options: {
					mangle: true
				},
				files: [
					{
						expand: true,
						src: [
							'assets/js/*.js',
							'!assets/js/*.min.js',
							'grid-parts/scripts/*.js',
							'!grid-parts/**/*.min.js'
						],
						dest: '.',
						cwd: '.',
						rename: function( dst, src ) {
							return dst + '/' + src.replace( '.js', '.min.js' );
						}
					}
				]
			}
		},

		// Watch task (run with "grunt watch")
        watch: {
            cssMain: {
                files: [
					'assets/css/*.scss',
					'assets/css/**/*.scss'
                ],
                tasks: [ 'sass:main', 'cssmin' ]
			},
            cssGridParts: {
                files: [
					'grid-parts/**/**.scss'
                ],
                tasks: [ 'sass:gridParts', 'cssmin' ]
			},
            js: {
                files: [
					'assets/js/*.js',
					'grid-parts/**/*.js',
					'!assets/js/*.min.js',
					'!grid-parts/**/*.min.js'
                ],
                tasks: [ 'uglify' ]
			}
        }
    });

    grunt.loadNpmTasks( 'grunt-contrib-sass' );
    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	grunt.registerTask( 'default', [ 'sass:main', 'sass:gridParts', 'cssmin', 'uglify' ] );
	grunt.registerTask( 'css', [ 'sass:main', 'sass:gridParts', 'cssmin' ] );
	grunt.registerTask( 'js', [ 'uglify' ] );
};
