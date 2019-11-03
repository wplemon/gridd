## 2.0.0

### Fixed
* Removed underline for featured-image links.
* Fixed Color for widget-nav-menu toggles.
* Fixed "Leave a Comment" link not visible in post-archives.
* Vertically center copyright text in the footer.
* Fixed content-width when using `em` units.
* Coding Standards fixes

### Changed
* Updated Kirki to v4.0 packages.
* Reorganized customizer settings.
* Refactored button styles.
* Refactored navigation styles.
* Refactored link-color controls.
* Updated block styles.
* New colorpickers.
* Refactored customizer select controls.
* Refactored customizer radio-buttonset controls.
* Refactored styles for the header-search slide-up mode.

### Added
* New Color Options Customizer section.
* New palette builder.
* New Color-Accessibility mode switch (AA/AAA/Auto).
* New Hue-linking switch for more consistent colors.
* New card mode for post-archives.
* Automated logic for grid-parts order in the document's HTML structure.
* New content-area horizontal & vertical padding controls.
* New `gridd_get_number_of_widget_areas` filter.

### Removed

* Removed Theme Upsell link in the customizer.
* Removed the Square Buttons Block Variation.
* Removed Collapsed navigation boxed mode.
* Removed the manual grid-parts ordering controls (the order is now auto-calculated).
* Removed parts of AMP support.

## 1.1.18 - 2019-09-04

### Fixed
* Removed duplicate comments link.
* Changed default values for some options for more reasonable results when the theme is first installed.
* Improved content-width calculation when using `em` or `ch` units.
* Navigation styling (wrong css-vars used for 2nd navigation).
* Coding Standards fixes
* Improved accessible styles for default links and menus.
* Vertical scollbars for mobile navigation containers.

### Changed
* Improved default font stack.
* Improved css-variables names.
* Refactored the changelog following principles from https://keepachangelog.com/en/1.0.0/
* Removed backup-fonts styles. They are no longer needed since google-fonts are not served via Google's CDN.
* Improved default link styles for better accessibility.
* Changed the move/resize icon for grid-parts inside the grid control.

### Added
* New header-padding option.
* Upgrader implementation to handle future option changes.
* Checkboxes to enable/disable custom typography options.
* New option to allow users to disable editor styles.

### Removed
* Edit link from post titles.

## 1.1.17 - 2019-08-25

### Fixed
* Horizontal padding on submenus when navigation is on vertical mode.
* Adding widgets on the header grid was not showing the widget areas in the customizer due to a partial refresh instead of a full refresh of the preview pane.
* Minor styling fixes for navigation parts.
* Added titles to buttons in the grid control to make it easier to understand what they do when they get hovered.
* Removed google-plus from list of social networks.
* Styling fixes for the header-search grid-part.
* Size of video embeds.
* Various improvements to the Grid control.

## 1.1.16 - 2019-08-18

### Fixed
* Improved accessibility for handheld navigation.
* Inlining the comment-reply link instead of enqueueing as a separate request.
* `<select>` element styles when using a dark background.
* Group block styles.
* Center-aligned elements inside group blocks.
* Customizer description typo.
* Accessibility improvements for button blocks.
* Improved breadcrumbs styles.

### Added
* Added slide-up mode for header searchform.
* Partial support for IE11.

### Changed
* Updated dragselect to v1.12.2
* Block styles for compatibility with latest Gutenberg plugin versions.

## 1.1.15 - 2019-08-04

### Fixed
* Typos

## 1.1.14 - 2019-08-04

### Fixed
* Improved customizer control descriptions & labels.
* Improved customizer styles.
* Changed some settings from radios to dropdowns to clean-up the UI.
* Show featured image options for single posts & archives regardless of the preview pane context in the customizer.
* Description of WordPress-Core background-color control (moved to the main grid section).

### Added
* Add option to override grid-parts background in the header.
* Add option to override grid-parts background in the footer.
* Added a new "Mobile" section in re-organized settings in the customizer.
* Added Changelog file.

### Changed
* Renamed "Grid" section to "Site Grid".
* Removed plus & docs buttons on the top of sections.

## 1.1.13 - 2019-07-29

### Fixed
* Error in nav-handheld.

## 1.1.12 - 2019-07-29

### Fixed
* JS conflict in the customizer when Gutenberg installed as a plugin.
* Typo in the main header grid's description.
* Header-Image control description.
* Removed Mobile Navigation from Deferred Parts.

## 1.1.11 - 2019-07-28

### Fixed
* Improved block styles to further reduce their size.
* Content width when using percent (%) values.
* Improved the accessibility-colors script for automatic link colors selection.
* Improved comment form styles for dark backgrounds.
* Gutenberg styles were updated to include the most recent tweaks for new blocks.
* Improved searchform styles.
* PHP Warning when viewing some post-formats.
* Visibility of WooCommerce product images when there is a single image (no gallery) inside single posts.
* WooCommerce tabs styles.

## 1.1.10 - 2019-07-20

### Fixed
* Text typos.
* Customizer performance improvement.

### Changed
* New screenshot.

## 1.1.9 - 2019-07-18

### Fixed
* Improved Customizer performance.
* Accessibility improvement: Removed `<title>` tags from SVGs.
* Update DragSelect script to v1.12.1.
* Branding typography control was always hidden.
* Group block inner-container alignment.
* Style for initial notice when a menu is not assigned to a navigation grid-part.
* Moved text & link color options to the content section.

## 1.1.8 - 2019-07-06

### Fixed
* Styles for undefined values.
* Content width when using `em` units.


## 1.1.7 - 2019-06-30

### Fixed
* Improve accessibility of search forms
* Updated the Kirki framework to v3.0.44
* Updated editor block styles
* Changed default font-family to sans-serif.

## 1.1.6 - 2019-06-17

### Fixed
* Handheld navigation behaviour on Safari.
* Added fallback values to all CSS custom-properties.

## 1.1.5 - 2019-06-09

### Fixed
* Googlefonts enqueueing issue when the site URL changes, or if the protocol (http/https) changes

## 1.1.4 - 2019-05-21

### Fixed
* Documentation Links
* Minimum WordPress version required is 5.0 - fixed fallback method for previous versions of WordPress.

## 1.1.3 - 2019-05-18

### Fixed
* Accessibility improvements

## 1.1.2 - 2019-05-15

### Fixed
* Properly escape the read-me link for blog excerpts.

## 1.1.1 - 2019-05-11

### Fixed
* WooCommerce categories widget styles
* Simplify & improve styles for widget lists.
* Improve styles for products-search widget.
* Improve WooCommerce price-filter widget styles.
* No edit links in products.
* WooCommerce product slides.
* WooCommerce image thumbnails in carousels.
* WooCommerce `.onsale` tags styling.
* blocks alignment in cover block.
* Added product-searchform template for WooCommerce.
* Content-width calculation when using `em` values for the main content area's max-width setting.
* Updated Block styles from latest Gutenberg-dev version.
* Simplified color palette.
* Updated block styles.

### Added
* Added support for the new "Group" editor block.
* Added option to hide page-title on the frontpage.

## 1.1 - 2019-04-06

### Fixed
* Improved and simplified the navigation styles.
* Better implementation for toggle buttons in menus.

### Added
* Implemented lazy-loaded grid-parts using the REST API.

## 1.0.8 - 2019-04-03

### Fixed
* Custom CSS priority
* CSS overflow fix for mobile navigation.
* Collapsed naviation position.
* Cover block focal-point when using a reusable editor block as a separate grid-part.
* Remove non-existing grid-parts (deleted reusable blocks) from the grid control in the customizer.
* Removed incomplete implementations for Layer-Slider and Revolution-Slider.

### Added
* Added option to enable boxed mode for navigation toggle button.
* Added edit links to reusable blocks.
* Added links-color setting to reusable blocks.
* Added `gridd_print_attributes` filter.

## 1.0.7 - 2019-03-16

### Fixed
* Naviation depth is no longer limited to 3.
* CSS cleanup

### Added
* Added `gridd_get_toggle_button` filter.

## 1.0.6 - 2019-03-16

### Fixed
* Customizer bugfix

## 1.0.5 - 2019-03-14

### Fixed
* Improved navigation styles.
* The grid-parts order control now has a live preview.
* Minor CSS fixes & cleanup.

## 1.0.4 - 2019-03-10

### Fixed
* Minor CSS fixes & cleanups.

### Added
* Added a new "Overlay" mode for featured images on single posts
* Added support for Jetpack's `Tonesque` library.

## 1.0.3 - 2019-03-05

### Added
* Added grid parts for reusable Gutenberg Blocks.
* Added custom templates.

## 1.0.2 - 2019-02-23

### Fixed
* Various CSS styling fixes.

### Added
* CSS for blocks is now only loaded for active blocks.
* Added option for collapsed navigation label.

## 1.0.1 - 2019-02-11

### Fixed
* Various performance tweaks for CSS loading.

### Added
* Added the "Features" section in the customizer.
* Implemented grid for archives.

## 1.0 - 2019-02-04

* Initial Release
