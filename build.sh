#!/bin/sh

# Clone the repository in the build folder.
git clone git@github.com:wplemon/gridd.git build --recursive

cd build

# Install node modules & grunt.
rm package-lock.json
npm install
grunt

# Remove extra files.
rm -rf .sass-cache
rm -rf node_modules
rm -f .browserslistrc
rm -f .editorconfig
rm -f .eslintignore
rm -f .eslintrc.json
rm -rf .git
rm -f .gitignore
rm -f .gitmodules
rm -f package-lock.json

rm -f **/**/**/**/**/*.css.map
rm -f **/**/**/**/*.css.map
rm -f **/**/**/*.css.map
rm -f **/**/*.css.map
rm -f **/*.css.map
rm -f *.css.map

rm -f **/**/**/**/**/*.scss
rm -f **/**/**/**/*.scss
rm -f **/**/**/*.scss
rm -f **/**/*.scss
rm -f **/*.scss
rm -f *.scss

rm -f grid-parts/breadcrumbs/hybrid-breadcrumbs/.git
rm -f grid-parts/breadcrumbs/hybrid-breadcrumbs/.editorconfig
rm -f grid-parts/breadcrumbs/hybrid-breadcrumbs/.gitattributes
rm -f grid-parts/breadcrumbs/hybrid-breadcrumbs/.gitignore
rm -f grid-parts/breadcrumbs/hybrid-breadcrumbs/changelog.md
rm -f grid-parts/breadcrumbs/hybrid-breadcrumbs/composer.json
rm -f grid-parts/breadcrumbs/hybrid-breadcrumbs/.contributing.md

rm -rf inc/kirki/.git
rm -rf inc/kirki/.github
rm -rf inc/kirki/.sass-cache
rm -rf inc/kirki/controls/css/*.map
rm -rf inc/kirki/controls/js/src
rm -rf inc/kirki/controls/scss
rm -rf inc/kirki/docs
rm -f inc/kirki/modules/custom-sections/*.scss
rm -f inc/kirki/modules/custom-sections/*.map
rm -f inc/kirki/modules/tooltips/*.scss
rm -f inc/kirki/modules/tooltips/*.map
rm -rf inc/kirki/node_modules
rm -rf inc/kirki/tests
rm -f inc/kirki/.codeclimate.yml
rm -f inc/kirki/.coveralls.yml
rm -f inc/kirki/.csslintrc
rm -f inc/kirki/.editorconfig
rm -f inc/kirki/.gitignore
rm -f inc/kirki/.jscsrc
rm -f inc/kirki/.jshintignore
rm -f inc/kirki/.jshintrc
rm -f inc/kirki/.jhintrc
rm -f inc/kirki/.phpcs.xml.dist
rm -f inc/kirki/.simplecov
rm -f inc/kirki/.travis.yml
rm -f inc/kirki/CODE_OF_CONDUCT.md
rm -f inc/kirki/composer.*
rm -f inc/kirki/example.php
rm -f inc/kirki/Gruntfile.js
rm -f inc/kirki/package.json
rm -f inc/kirki/phpunit.xml
rm -f inc/kirki/phpunit.xml.dist
rm -f inc/kirki/README.md
rm -f inc/kirki/*.sh
rm -f inc/kirki/package-lock.json
