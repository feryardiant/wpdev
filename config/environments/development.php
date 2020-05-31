<?php
/**
 * Configuration overrides for WP_ENV === 'development'
 */

use Roots\WPConfig\Config;

/**
 * Debugging Settings
 */
Config::define('WP_DEBUG',         true);
Config::define('WP_DEBUG_DISPLAY', true);
Config::define('WP_DEBUG_LOG',     true);
Config::define('SAVEQUERIES',      true);

Config::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

ini_set('display_errors', 1);

// Enable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', false);
// Disable automatic update
Config::define('AUTOMATIC_UPDATER_DISABLED', true);

// Cut down post revisions
Config::define('WP_POST_REVISIONS', 2);
