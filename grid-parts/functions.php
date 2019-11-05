<?php
/**
 * Init all grid-parts.
 *
 * @package Gridd
 * @since 1.0.3
 */

// Require classes.
require_once get_theme_file_path( 'grid-parts/Breadcrumbs/Breadcrumbs.php' );
require_once get_theme_file_path( 'grid-parts/Content/Content.php' );
require_once get_theme_file_path( 'grid-parts/Footer/Footer.php' );
require_once get_theme_file_path( 'grid-parts/Header/Header.php' );
require_once get_theme_file_path( 'grid-parts/NavHandheld/NavHandheld.php' );
require_once get_theme_file_path( 'grid-parts/Navigation/Navigation.php' );
require_once get_theme_file_path( 'grid-parts/ReusableBlock/ReusableBlock.php' );
require_once get_theme_file_path( 'grid-parts/Sidebar/Sidebar.php' );

// Require customizer files.
require_once get_theme_file_path( 'grid-parts/Breadcrumbs/customizer.php' );
require_once get_theme_file_path( 'grid-parts/Content/customizer.php' );
require_once get_theme_file_path( 'grid-parts/Footer/customizer-copyright.php' );
require_once get_theme_file_path( 'grid-parts/Footer/customizer-sidebars.php' );
require_once get_theme_file_path( 'grid-parts/Footer/customizer-social-media.php' );
require_once get_theme_file_path( 'grid-parts/Footer/customizer.php' );
require_once get_theme_file_path( 'grid-parts/Header/customizer-branding.php' );
require_once get_theme_file_path( 'grid-parts/Header/customizer-contact-info.php' );
require_once get_theme_file_path( 'grid-parts/Header/customizer-search.php' );
require_once get_theme_file_path( 'grid-parts/Header/customizer-social-media.php' );
require_once get_theme_file_path( 'grid-parts/Header/customizer.php' );
require_once get_theme_file_path( 'grid-parts/NavHandheld/customizer.php' );
require_once get_theme_file_path( 'grid-parts/Navigation/customizer.php' );
require_once get_theme_file_path( 'grid-parts/ReusableBlock/customizer.php' );
require_once get_theme_file_path( 'grid-parts/Sidebar/customizer.php' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
