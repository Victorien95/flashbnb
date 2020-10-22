<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bluegob377');

/** MySQL database username */
define('DB_USER', 'bluegob377');

/** MySQL database password */
define('DB_PASSWORD', 'k8ng9zJ9j8be');

/** MySQL hostname */
define('DB_HOST', 'bluegob377.mysql.db:3306');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4fiM+4ZD0mWFaMDlAK6DmIULU4of6DNTEGuxycqAUAMG+76jCss21PbGwZUC');
define('SECURE_AUTH_KEY',  'aObIuR31BQZqt/lzVtIvk/WbHnXzXE8iNH5iWJyJRsjLweaGqGpP6dSxNzdM');
define('LOGGED_IN_KEY',    'HYKfUV/j46QfLD/OMQ+8Ta7sPQoOm1IxD187ELYb6Q3wEfONr6O/jAqO/+Mk');
define('NONCE_KEY',        'g4wqUZYqnjBG5JqGK3mAgbBWhys6EAwy62g5hHxhh2NxRhN3xoR7JAYdE+Z3');
define('AUTH_SALT',        'lRRUMNxyrPTpERn4CjmVRBbrXcA09QyqlP5HnEM9T8AZQzBByMzOF2Hje/Xp');
define('SECURE_AUTH_SALT', '59Se7To9oL+5/lcBG8dgJnlhqrPYuXbOQfwE6XorcfDxhLvx68Q9RpwvYHtO');
define('LOGGED_IN_SALT',   'gpgq5B2Oe/RuboXHHOesHiLQNoccsXssHEr1YzJAuYHuhUBtVaNr6Y2MCGHB');
define('NONCE_SALT',       '2MN2IOUxvZB3Y6WE/rJouMbHLkKNNqjI9GgtEcGpWlaHBXtcx/ATWnzHpw33');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mod171_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/* Fixes "Add media button not working", see http://www.carnfieldwebdesign.co.uk/blog/wordpress-fix-add-media-button-not-working/ */
define('CONCATENATE_SCRIPTS', false );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
