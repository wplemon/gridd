#!/bin/sh

# Clone the repository in the build folder.
git clone git@github.com:wplemon/gridd.git build --recursive

# Remove extra files.
rm -rf build/.sass-cache
rm -rf build/node_modules
rm -f build/.browserslistrc
rm -f build/.editorconfig
rm -f build/.eslintignore
rm -f build/.eslintrc.json
rm -rf build/.git
rm -f build/.gitignore
rm -f build/.gitmodules
rm -f build/package-lock.json

rm -f build/**/**/**/**/**/*.css.map
rm -f build/**/**/**/**/*.css.map
rm -f build/**/**/**/*.css.map
rm -f build/**/**/*.css.map
rm -f build/**/*.css.map
rm -f build/*.css.map

rm -f build/**/**/**/**/**/*.scss
rm -f build/**/**/**/**/*.scss
rm -f build/**/**/**/*.scss
rm -f build/**/**/*.scss
rm -f build/**/*.scss
rm -f build/*.scss

rm -f build/grid-parts/breadcrumbs/hybrid-breadcrumbs/.git
rm -f build/grid-parts/breadcrumbs/hybrid-breadcrumbs/.editorconfig
rm -f build/grid-parts/breadcrumbs/hybrid-breadcrumbs/.gitattributes
rm -f build/grid-parts/breadcrumbs/hybrid-breadcrumbs/.gitignore
rm -f build/grid-parts/breadcrumbs/hybrid-breadcrumbs/changelog.md
rm -f build/grid-parts/breadcrumbs/hybrid-breadcrumbs/composer.json
rm -f build/grid-parts/breadcrumbs/hybrid-breadcrumbs/.contributing.md

rm -rf build/inc/kirki/.git
rm -rf build/inc/kirki/.github
rm -rf build/inc/kirki/.sass-cache
rm -rf build/inc/kirki/controls/css/*.map
rm -rf build/inc/kirki/controls/js/src
rm -rf build/inc/kirki/controls/scss
rm -rf build/inc/kirki/docs
rm -f build/inc/kirki/modules/custom-sections/*.scss
rm -f build/inc/kirki/modules/custom-sections/*.map
rm -f build/inc/kirki/modules/tooltips/*.scss
rm -f build/inc/kirki/modules/tooltips/*.map
rm -rf build/inc/kirki/node_modules
rm -rf build/inc/kirki/tests
rm -f build/inc/kirki/.codeclimate.yml
rm -f build/inc/kirki/.coveralls.yml
rm -f build/inc/kirki/.csslintrc
rm -f build/inc/kirki/.editorconfig
rm -f build/inc/kirki/.gitignore
rm -f build/inc/kirki/.jscsrc
rm -f build/inc/kirki/.jshintignore
rm -f build/inc/kirki/.jshintrc
rm -f build/inc/kirki/.jhintrc
rm -f build/inc/kirki/.phpcs.xml.dist
rm -f build/inc/kirki/.simplecov
rm -f build/inc/kirki/.travis.yml
rm -f build/inc/kirki/CODE_OF_CONDUCT.md
rm -f build/inc/kirki/composer.*
rm -f build/inc/kirki/example.php
rm -f build/inc/kirki/Gruntfile.js
rm -f build/inc/kirki/package.json
rm -f build/inc/kirki/phpunit.xml
rm -f build/inc/kirki/phpunit.xml.dist
rm -f build/inc/kirki/README.md
rm -f build/inc/kirki/*.sh
rm -f build/inc/kirki/package-lock.json
