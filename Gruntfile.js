/* global module */
module.exports = function( grunt ) {

    grunt.initConfig({

        // Compile CSS
        sass: {
            main: {
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
				files: [ {
					expand: true,
					cwd: 'grid-parts',
					src: [ '**/*.scss' ],
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
							'grid-parts/**/*.js',
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

		postcss: {
			options: {
				processors: [
					require( 'postcss-cssnext' )()
				]
			},
			assetsCSS: {
				src: 'assets/**/*.css'
			},
			gridPartsCSS: {
				src: 'grid-parts/**/*.css'
			}
		},

		// Generate .pot translation file.
		makepot: {
			target: {
				options: {
					type: 'wp-theme',
					domainPath: 'languages'
				}
			}
		},

		// Watch task (run with "grunt watch")
        watch: {
            cssMain: {
                files: [
					'assets/css/*.scss',
					'assets/css/**/*.scss'
                ],
                tasks: [ 'sass:main', 'postcss:assetsCSS', 'cssmin' ]
			},
            cssGridParts: {
                files: [
					'grid-parts/**/**.scss'
                ],
                tasks: [ 'sass:gridParts', 'postcss:gridPartsCSS', 'cssmin' ]
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
    grunt.loadNpmTasks( 'grunt-contrib-concat' );
    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-postcss' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-po2mo' );

	grunt.registerTask( 'default', [ 'sass:main', 'sass:gridParts', 'postcss:assetsCSS', 'postcss:gridPartsCSS', 'cssmin', 'uglify', 'makepot' ] );
	grunt.registerTask( 'css', [ 'sass:main', 'sass:gridParts', 'postcss:assetsCSS', 'postcss:gridPartsCSS', 'cssmin' ] );
	grunt.registerTask( 'js', [ 'uglify' ] );
};
