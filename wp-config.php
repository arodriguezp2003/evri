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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'vlh0jg83l17krkka' );

/** MySQL database username */
define( 'DB_USER', 'z3gqe4ynvdujb2w3' );

/** MySQL database password */
define( 'DB_PASSWORD', 'iiqz5rcye36pai59' );

/** MySQL hostname */
define( 'DB_HOST', 'l0ebsc9jituxzmts.cbetxkdyhwsb.us-east-1.rds.amazonaws.com' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '31cae5ef7b6e7690af7dfdc4630aef44bdaf1c3eceb5d0c737e8acdc00acf123');
define('SECURE_AUTH_KEY', '4ad6473e022a099a47b76e51fcb6632deaa4a23ab2acea34bd24906dda4aa8aa');
define('LOGGED_IN_KEY', 'e6ab7988b9f674cfef79a8cb9808e7c72774cf093a43c3b37e97c9e759cd47cb');
define('NONCE_KEY', 'f2364a685a4c61d638f00782bf6c230141950be2ebedc919ebfbc5a2ad4280a1');
define('AUTH_SALT', '89c2e36447df69c4686342c595fd6fef17cc4f6c48389b9d2a3dda5041fb0ce4');
define('SECURE_AUTH_SALT', 'e8a297abef1ba6038861948d783e331592e4e7f0ca05a1e5ef8bfccab4aad1f7');
define('LOGGED_IN_SALT', '6a4b08eb2c31ad9460459db82b36071ad1a89ea10209138b041e5ffe6e144131');
define('NONCE_SALT', 'fa79bd8dc45d8d7c8654f2774e2cce5787e301f9d493f3e03c920a75c62a5a0a');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

define('FS_METHOD', 'direct');

/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
*/

if ( defined( 'WP_CLI' ) ) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}

define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/');


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define('WP_TEMP_DIR', '/opt/bitnami/apps/wordpress/tmp');


//  Disable pingback.ping xmlrpc method to prevent Wordpress from participating in DDoS attacks
//  More info at: https://docs.bitnami.com/general/apps/wordpress/troubleshooting/xmlrpc-and-pingback/

if ( !defined( 'WP_CLI' ) ) {
    // remove x-pingback HTTP header
    add_filter('wp_headers', function($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    });
    // disable pingbacks
    add_filter( 'xmlrpc_methods', function( $methods ) {
            unset( $methods['pingback.ping'] );
            return $methods;
    });
    add_filter( 'auto_update_translation', '__return_false' );
}
