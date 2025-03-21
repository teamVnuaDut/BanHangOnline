<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'BanHangOnline' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '4c8nGg8MQPdkWOHUGbiAMrqUFOVtuEKmtFBbeOtLBhCThynt9MW7VQSW8F7jKYEp' );
define( 'SECURE_AUTH_KEY',  'w7tSPyN9R394DTOsvZhLXsGf3WJKSqeKxFzuGkeD7SVz98S0hLxXKyIWFJJ70UgP' );
define( 'LOGGED_IN_KEY',    'Z2PmXQttRe3NjZVZlMfV0sXzp5y3hbI8CFwN9PZwBudfYFmQqK9PzfzVeOmSyPRg' );
define( 'NONCE_KEY',        'DUehu2d6cnj6BorhS1xjcHK4N13MnadR0AIdXYU88eEmjO8fcOCxS8y3ftHp67Ms' );
define( 'AUTH_SALT',        'By8DfXo5l6WiXYPQuiEz3JAGuWmNwG2DCPpg6rA5A8BTu9A1lcQN71omSCpxvsbP' );
define( 'SECURE_AUTH_SALT', '3D94ZBediDsqSQILRG5n5RgaHVovsfQihlb4010O6no1VVdzzkNb2lyZbgpweFpC' );
define( 'LOGGED_IN_SALT',   'd5qVx5kGPWQNCXIGDO5dV1eNAPbzLbk0DYjI34bBzLZx1AObhEyLDMcNxrscs6wo' );
define( 'NONCE_SALT',       'enZq7ic66XfbvYzuaUic4Mf9C34JO7tnVbqOXpfN3m1d2r3biK5YHoqPEspUfTI2' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
