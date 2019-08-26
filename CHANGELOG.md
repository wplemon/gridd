## 1.1.18

* Fix: Changed default values for some options for more reasonable results when the theme is first installed.
* New: Added a new header-padding option.
* New: Added a new option for navigation hover/focus styles.

## 1.1.17

* Fix: Horizontal padding on submenus when navigation is on vertical mode.
* Fix: Adding widgets on the header grid was not showing the widget areas in the customizer due to a partial refresh instead of a full refresh of the preview pane.
* Fix: Minor styling fixes for navigation parts.
* Fix: Added titles to buttons in the grid control to make it easier to understand what they do when they get hovered.
* Fix: Removed google-plus from list of social networks.
* Fix: Styling fixes for the header-search grid-part.
* Fix: Size of video embeds.
* Fix: Various improvements to the Grid control.

## 1.1.16

* New: Added slide-up mode for header searchform.
* New: Partial support for IE11.
* Fix: Improved accessibility for handheld navigation.
* Fix: Inlining the comment-reply link instead of enqueueing as a separate request.
* Fix: `<select>` element styles when using a dark background.
* Fix: Group block styles.
* Fix: Center-aligned elements inside group blocks.
* Fix: Customizer description typo.
* Fix: Accessibility improvements for button blocks.
* Fix: Improved breadcrumbs styles.
* Update: Updated dragselect to v1.12.2
* Update: Block styles for compatibility with latest Gutenberg plugin versions.

## 1.1.15

* Fix: Typo.

## 1.1.14

* New: Add option to override grid-parts background in the header.
* New: Add option to override grid-parts background in the footer.
* New: Added a new "Mobile" section in re-organized settings in the customizer.
* New: Added Changelog file.
* Fix: Improved customizer control descriptions & labels.
* Fix: Improved customizer styles.
* Fix: Changed some settings from radios to dropdowns to clean-up the UI.
* Fix: Show featured image options for single posts & archives regardless of the preview pane context in the customizer.
* Fix: Description of WordPress-Core background-color control (moved to the main grid section).
* Fix: Renamed "Grid" section to "Site Grid".
* Fix: Removed plus & docs buttons on the top of sections.

## 1.1.13

* Fix: Error in nav-handheld.

## 1.1.12

* Fix: JS conflict in the customizer when Gutenberg installed as a plugin.
* Fix: Typo in the main header grid's description.
* Fix: Header-Image control description.
* Fix: Removed Mobile Navigation from Deferred Parts.

## 1.1.11

* Fix: Improved block styles to further reduce their size.
* Fix: Content width when using percent (%) values.
* Fix: Improved the accessibility-colors script for automatic link colors selection.
* Fix: Improved comment form styles for dark backgrounds.
* Fix: Gutenberg styles were updated to include the most recent tweaks for new blocks.
* Fix: Improved searchform styles.
* Fix: PHP Warning when viewing some post-formats.
* Fix: Visibility of WooCommerce product images when there is a single image (no gallery) inside single posts.
* Fix: WooCommerce tabs styles.

## 1.1.10

* Fix: Text typos.
* Fix: Customizer performance improvement.
* Update: New screenshot.

## 1.1.9

* Fix: Improved Customizer performance.
* Fix: Accessibility improvement: Removed `<title>` tags from SVGs.
* Fix: Update DragSelect script to v1.12.1.
* Fix: Branding typography control was always hidden.
* Fix: Group block inner-container alignment.
* Fix: Style for initial notice when a menu is not assigned to a navigation grid-part.
* Fix: Moved text & link color options to the content section.

## 1.1.8

* Fix: Styles for undefined values.
* Fix: Content width when using `em` units.


## 1.1.7

* Fix: Improve accessibility of search forms
* Fix: Updated the Kirki framework to v3.0.44
* Fix: Updated editor block styles
* Fix: Changed default font-family to sans-serif.

## 1.1.6

* Fix: Handheld navigation behaviour on Safari.
* Dev: Added fallback values to all CSS custom-properties.

## 1.1.5

* Fix: googlefonts enqueueing issue when the site URL changes, or if the protocol (http/https) changes

## 1.1.4

* Fix: Documentation Links
* Fix: Minimum WordPress version required is 5.0 - fixed fallback method for previous versions of WordPress.

## 1.1

* Fix: Accessibility improvements

## 1.1.2

* Fix: Properly escape the read-me link for blog excerpts.

## 1.1.1

* Fix: WooCommerce categories widget styles
* Fix: Simplify & improve styles for widget lists.
* Fix: Improve styles for products-search widget.
* Fix: Improve WooCommerce price-filter widget styles.
* Fix: No edit links in products.
* Fix: WooCommerce product slides.
* Fix: WooCommerce image thumbnails in carousels.
* Fix: WooCommerce `.onsale` tags styling.
* Fix: blocks alignment in cover block.
* Fix: Added product-searchform template for WooCommerce.
* Fix: Content-width calculation when using `em` values for the main content area's max-width setting.
* Fix: Updated Block styles from latest Gutenberg-dev version.
* Fix: Simplified color palette.
* Fix: Updated block styles.
* New: Added support for the new "Group" editor block.
* New: Added option to hide page-title on the frontpage.

## 1.1

* New: Implemented lazy-loaded grid-parts using the REST API.
* Fix: Improved and simplified the navigation styles.
* Fix: Better implementation for toggle buttons in menus.

## 1.0.8

* Fix: Custom CSS priority
* Fix: CSS overflow fix for mobile navigation.
* Fix: Collapsed naviation position.
* Fix: Cover block focal-point when using a reusable editor block as a separate grid-part.
* Fix: Remove non-existing grid-parts (deleted reusable blocks) from the grid control in the customizer.
* Fix: Removed incomplete implementations for Layer-Slider and Revolution-Slider.
* New: Added option to enable boxed mode for navigation toggle button.
* New: Added edit links to reusable blocks.
* New: Added links-color setting to reusable blocks.
* New: Added `gridd_print_attributes` filter.

## 1.0.7

* Fix: Naviation depth is no longer limited to 3.
* Fix: CSS cleanup
* New: Added `gridd_get_toggle_button` filter.

## 1.0.6

* Fix: Customizer bugfix

## 1.0.5

* Fix: Improved navigation styles.
* Fix: The grid-parts order control now has a live preview.
* Fix: Minor CSS fixes & cleanup.

## 1.0.4

* New: Added a new "Overlay" mode for featured images on single posts
* New: Added support for Jetpack's `Tonesque` library.
* Fix: Minor CSS fixes & cleanups.

## 1.0.3

* New: Added grid parts for reusable Gutenberg Blocks.
* New: Added custom templates.

## 1.0.2

* New: CSS for blocks is now only loaded for active blocks.
* New: Added option for collapsed navigation label.
* Fix: Various CSS styling fixes.

## 1.0.1

* New: Added the "Features" section in the customizer.
* New: Implemented grid for archives.
* Fix: Various performance tweaks for CSS loading.

## 1.0

* Initial Release
