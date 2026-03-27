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
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'wp_user' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

/** Database hostname */
define( 'DB_HOST', 'db' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '9_f]Jq .nzY913F2R3HuT=D/X7v3HPJZ7{vjTq>BB.paToatimgVlv730/UJY?x#' );
define( 'SECURE_AUTH_KEY',  'Ma+x$YhN]#C>,n>N!-LA=sN4;^$%-/<V/&Y|6s&A=1!IW&@?=d(IWSW|oD2VjLJH' );
define( 'LOGGED_IN_KEY',    'Al FB_Qj~3/a=kSo$`L@v6_6n87a4]-M7}o[RAc43u5wLM2xZcLb4u4A?5bSFpiF' );
define( 'NONCE_KEY',        '|XtYF~Gp;zYxkO@ y1B}rU}EwVA%c2_j,{nF64x2rnZ23OcQXw}-aEN[f8qt*+!/' );
define( 'AUTH_SALT',        '$Q|nNO;bXeN7pG0QI8Q;Uc.tlNt)rKX_XP>VO5;rJ$3FsVP3=IWzlIa2nQuQIAR=' );
define( 'SECURE_AUTH_SALT', ',>^}CP^.}HSUFZ_J>P[m_|PX}zzH7D--KyX8<@hp^Bh~*d<kd.p]R:ax^EaS^1Ui' );
define( 'LOGGED_IN_SALT',   '$#qv]t.m|3p(/~z!+!^vW x//V;Mk%i5At8t3k5VF|t&S@d@1XO?v,B!2(-mL*~[' );
define( 'NONCE_SALT',       '?@IS}RZ>-zr1:N(KAjhg{Aks)c-FblF&SP6)9aX+]A xFM vqnY+I5`j7R#Bs=$>' );

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
$table_prefix = 'billard_';

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
