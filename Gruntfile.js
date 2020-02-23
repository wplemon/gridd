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
							cwd: 'assets/css',
							src: [ '*.css', '!**/*.min.css' ],
							dest: 'assets/css',
							ext: '.min.css'
						},
						{
						expand: true,
						cwd: 'grid-parts',
						src: [ '**/*.css', '!**/*.min.css' ],
						dest: 'grid-parts',
						ext: '.min.css'
					},
					{
						expand: true,
						src: [ 'grid-parts/**/*.css', '!grid-parts/**/*.min.css' ],
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

		// Replace textdomains.
		addtextdomain: {
			options: {
				textdomain: 'gridd',
				updateDomains: [
					'hybrid-core',
					'kirki-pro'
				]
			},
			target: {
				files: {
					src: [
						'packages/justintadlock/*.php',
						'packages/justintadlock/**/*.php',
						'packages/justintadlock/**/**/*.php',
						'packages/justintadlock/**/**/**/*.php',
						'packages/justintadlock/**/**/**/**/**/*.php',
						'packages/justintadlock/**/**/**/**/**/**/*.php',
						'packages/wplemon/**/*.php',
						'packages/wplemon/**/**/*.php',
						'packages/wplemon/**/**/**/*.php',
						'packages/wplemon/**/**/**/**/*.php'
					]
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
                tasks: [ 'sass:main', 'cssmin' ]
			},
            cssCoreBlocks: {
                files: [
					'assets/css/blocks/core/*.css',
					'!assets/css/blocks/core/*.min.css'
                ],
                tasks: [ 'cssmin' ]
			},
            cssGridParts: {
                files: [
					'grid-parts/**/**.scss',
					'grid-parts/**/**.css'
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
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.registerTask( 'default', [ 'sass:main', 'sass:gridParts', 'cssmin', 'uglify', 'addtextdomain' ] );
	grunt.registerTask( 'css', [ 'sass:main', 'sass:gridParts', 'cssmin' ] );
	grunt.registerTask( 'js', [ 'uglify' ] );
};
