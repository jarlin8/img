'use strict';
module.exports = function(grunt) {
	require('load-grunt-tasks')(grunt);
	require('time-grunt')(grunt);
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		makepot: {
			target: {
				options: {
					cwd: '..',
					domainPath: 'languages',
					exclude: ['tools', 'release', 'assets' ],
					potFilename: 'dwqa.pot',
					type: 'wp-plugin'
				}
			}
		},
		compress: {
			main: {
				options: {
					archive: '../release/<%= pkg.name %>-<%= pkg.version %>.zip'
				},
				files: [
					{
						expand: true,
						cwd: '../',
						src: [
							'*',
							'*/**',
							'!assets/vendor/**',
							'!tools/**',
							'!release/**',
							'!languages/**'
						],
						dest: '<%= pkg.name %>/'
					}
				]
			}
		}
	});
	grunt.registerTask('default', [
		'build'
	]);
	grunt.registerTask('build', [
		'makepot',
		'compress'
	]);
};
