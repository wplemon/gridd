<?php
/**
 * Init all grid-parts.
 *
 * @package Gridd
 * @since 1.0.3
 */

// Require classes.
require_once __DIR__ . '/Breadcrumbs/Breadcrumbs.php';
require_once __DIR__ . '/Content/Content.php';
require_once __DIR__ . '/Footer/Footer.php';
require_once __DIR__ . '/Header/Header.php';
require_once __DIR__ . '/NavHandheld/NavHandheld.php';
require_once __DIR__ . '/Navigation/Navigation.php';
require_once __DIR__ . '/ReusableBlock/ReusableBlock.php';
require_once __DIR__ . '/Sidebar/Sidebar.php';

// Require customizer files.
require_once __DIR__ . '/Breadcrumbs/customizer.php';
require_once __DIR__ . '/Content/customizer.php';
require_once __DIR__ . '/Footer/customizer-sidebars.php';
require_once __DIR__ . '/Footer/customizer-social-media.php';
require_once __DIR__ . '/Footer/customizer.php';
require_once __DIR__ . '/Header/customizer-branding.php';
require_once __DIR__ . '/Header/customizer.php';
require_once __DIR__ . '/NavHandheld/customizer.php';
require_once __DIR__ . '/Navigation/customizer.php';
require_once __DIR__ . '/Sidebar/customizer.php';

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
