#!/bin/bash

##########################################
## Presequisite:
## npm install -g node-sass
##########################################

cd "$(dirname "$0")"

# Delete previous dirs & files.
rm -rf dist
rm -rf gutenberg-master
rm -rf master.zip
rm -rf temp-styles

# Get latest master from the github repo.
wget https://github.com/WordPress/gutenberg/archive/master.zip

# Unzip file.
unzip -u -o master.zip

# Create temp folder for the styles.
mkdir temp-styles
mkdir dist

# Copy the base styles.
cp -r gutenberg-master/packages/base-styles temp-styles/base-styles

# Loop directories
for dir in gutenberg-master/packages/block-library/src/*     # list directories in the form "/tmp/dirname/"
do
	# Only loop folders, not files.
	if [ -d ${dir} ]; then

		# Get the block-name.
		blockName=${dir%*/}  # remove the trailing "/"
		blockName=${dir##*/} # get everything after the final "/"

		# Create a file if it doesn't already exist.
		if [ ! -f "temp-styles/${blockName}" ]; then
			touch temp-styles/${blockName}.scss
			echo "@import \"base-styles/animations\";" >> temp-styles/${blockName}.scss
			echo "@import \"base-styles/breakpoints\";" >> temp-styles/${blockName}.scss
			echo "@import \"base-styles/colors\";" >> temp-styles/${blockName}.scss
			echo "@import \"base-styles/mixins\";" >> temp-styles/${blockName}.scss
			echo "@import \"base-styles/variables\";" >> temp-styles/${blockName}.scss
			echo "@import \"base-styles/z-index\";" >> temp-styles/${blockName}.scss
		fi

		# Loop files in each block.
		for blockFile in ${dir}/*
		do
			fileName=${blockFile%*/} # Remove the trailing "/"
			fileName=${fileName##*/} # Get everything after the final "/"
			if [ "style.scss" == ${fileName} ] || [ "theme.scss" == ${fileName} ]; then

				# Create the folder if it doesn't already exist.
				if [ ! -d "temp-styles/${blockName}" ]; then
					mkdir temp-styles/${blockName}
				fi

				# Copy the file
				cp ${blockFile} temp-styles/${blockName}/${fileName}

				# Import the file in our block styles.
				echo "@import \"${blockName}/${fileName}\";" >> temp-styles/${blockName}.scss

				# Compile file.
				node-sass -o dist/${blockName} temp-styles/${blockName}.scss

				# Move file.
				mv dist/${blockName}/${blockName}.css dist/${blockName}.css
			fi
		done
		rm -Rf dist/${blockName}
	fi
done
