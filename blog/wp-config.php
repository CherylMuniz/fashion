<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'fashione_blog');

/** MySQL database username */
define('DB_USER', 'fashione_blogger');

/** MySQL database password */
define('DB_PASSWORD', ')vp$Nx-fpf#w');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'yBfqR_.Ld~g0sSso-1m-x+|*2@Xmo-N~7Q=B>8NaLIUJL)P$<GYJxkpx9/$Y?rev');
define('SECURE_AUTH_KEY',  'YNMd7=}gT-~N(D<`i  .p},-d-J;[RHChZjw-vP9H!C:h Z{^}*)4$}^5`p@kP+1');
define('LOGGED_IN_KEY',    'No]ITo8Wyo(GD<YTS>]!V84Lif+< #f@}*v G#$v-Yr$.|,L=/MxL{9zk-g#|%8)');
define('NONCE_KEY',        '8_k3IhCD) 1[K[<wPU31E7h-LuVVJ;-}zt$G7O[76nq^#lr~7zZhX3F=-PkZ#ydd');
define('AUTH_SALT',        'o5!d3`I<XvUtw;I<rlafzCEHKQ9@t_,k>(JwJKLEK !fU}l!(:bWflB+}Q9O>L H');
define('SECURE_AUTH_SALT', '?A2FJhEBT_4Zl,}aD&,Y4FSnthj+0]uErxVw(tLkW}) 2HhEM|b=7r8x`++DMLF)');
define('LOGGED_IN_SALT',   '*2W-Ve~+-z00769v+NoBz`:kE];*|wCUyH2[87!uYb;-),0>|ff+RO>?s=~6o,]b');
define('NONCE_SALT',       'f!5.#6(9F0 NN%Uq$|@Dox8.3iZ$^QDfq9d}1kT 5<PF3EAhR<nZsN-WH2-LM._R');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
