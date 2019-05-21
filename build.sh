#!/bin/sh

rm -rf gridd
rm -f gridd.zip
rm -rf ../gridd2
cp -R . ../gridd2
mv ../gridd2 ./gridd

# Remove extra files.
rm -rf gridd/vendor
rm -rf gridd/assets/css/blocks/block-library
rm -rf gridd/.sass-cache
rm -rf gridd/node_modules
rm -rf gridd/.git
rm -f gridd/.browserslistrc
rm -f gridd/.editorconfig
rm -f gridd/.eslintignore
rm -f gridd/.eslintrc.json
rm -f gridd/.gitignore
rm -f gridd/.gitmodules
rm -f gridd/package-lock.json
rm -f gridd/composer.lock
rm -f gridd/.codacy.yml
rm -f gridd/README.md
rm -f gridd/composer.json
rm -f gridd/Gruntfile.js
rm -f gridd/package.json
rm -f gridd/phpcs.xml.dist
rm -f gridd/.gitattributes
rm -rf gridd/docs

find ./gridd/ -name "*.map" -type f -delete
find ./gridd/ -name "*.scss" -type f -delete

rm -f gridd/grid-parts/breadcrumbs/hybrid-breadcrumbs/.git
rm -f gridd/grid-parts/breadcrumbs/hybrid-breadcrumbs/.editorconfig
rm -f gridd/grid-parts/breadcrumbs/hybrid-breadcrumbs/.gitattributes
rm -f gridd/grid-parts/breadcrumbs/hybrid-breadcrumbs/.gitignore
rm -f gridd/grid-parts/breadcrumbs/hybrid-breadcrumbs/changelog.md
rm -f gridd/grid-parts/breadcrumbs/hybrid-breadcrumbs/composer.json
rm -f gridd/grid-parts/breadcrumbs/hybrid-breadcrumbs/.contributing.md

rm -rf gridd/inc/kirki/.git
rm -rf gridd/inc/kirki/.github
rm -rf gridd/inc/kirki/.sass-cache
rm -rf gridd/inc/kirki/controls/css/*.map
rm -rf gridd/inc/kirki/controls/js/src
rm -rf gridd/inc/kirki/controls/scss
rm -rf gridd/inc/kirki/docs
rm -f gridd/inc/kirki/modules/custom-sections/*.scss
rm -f gridd/inc/kirki/modules/custom-sections/*.map
rm -f gridd/inc/kirki/modules/tooltips/*.scss
rm -f gridd/inc/kirki/modules/tooltips/*.map
rm -rf gridd/inc/kirki/node_modules
rm -rf gridd/inc/kirki/tests
rm -f gridd/inc/kirki/.codeclimate.yml
rm -f gridd/inc/kirki/.coveralls.yml
rm -f gridd/inc/kirki/.csslintrc
rm -f gridd/inc/kirki/.editorconfig
rm -f gridd/inc/kirki/.gitignore
rm -f gridd/inc/kirki/.jscsrc
rm -f gridd/inc/kirki/.jshintignore
rm -f gridd/inc/kirki/.jshintrc
rm -f gridd/inc/kirki/.jhintrc
rm -f gridd/inc/kirki/.phpcs.xml.dist
rm -f gridd/inc/kirki/.simplecov
rm -f gridd/inc/kirki/.travis.yml
rm -f gridd/inc/kirki/CODE_OF_CONDUCT.md
rm -f gridd/inc/kirki/composer.*
rm -f gridd/inc/kirki/example.php
rm -f gridd/inc/kirki/Gruntfile.js
rm -f gridd/inc/kirki/package.json
rm -f gridd/inc/kirki/phpunit.xml
rm -f gridd/inc/kirki/phpunit.xml.dist
rm -f gridd/inc/kirki/README.md
rm -f gridd/inc/kirki/*.sh
rm -f gridd/inc/kirki/package-lock.json
rm -f gridd/*.sh

zip -rq gridd.zip gridd
rm -rf gridd/