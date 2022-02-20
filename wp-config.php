<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ensat_project' );

/** Database username */
define( 'DB_USER', 'kali' );

/** Database password */
define( 'DB_PASSWORD', 'kalikali' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '_y=/G3w>.RG=eb16T_Op(;L!<8v#PrL/aj$EyNL_Bu|qdK~Xfersfxwk]e]Z]tTu' );
define( 'SECURE_AUTH_KEY',  'ZxR~URYL S]`MJE2cw.M.b.gem943Gv5EC?taam6xnb&!v8/Inj27i2O|pze]o(8' );
define( 'LOGGED_IN_KEY',    'aerd6vy)4zb::pp9!k]<m(5;_{4V3nuG;#u8^j7=1s[1-n+%x:Tb,K]?c`2TTNQd' );
define( 'NONCE_KEY',        'sLr_aNAlxs6]1>KyO3.+|*GgP;=8[k/MR`ygEx?,YA.D??(0fqM%/F+{bUi>l~hA' );
define( 'AUTH_SALT',        'UL2Chb{OC@m0j5^SX2P;G+#|]T2Q4ZGrEJwnm2u3nS1w#?$:>OypS%%;qOn> D1+' );
define( 'SECURE_AUTH_SALT', '!C.A.?d] *RysM^v_C[70/QHbPBmqn*%S`Zx02ZO?LeFBjO=<o(J1x#H%KUcA81J' );
define( 'LOGGED_IN_SALT',   'OM>VQixdgHG#7v2N`K$_Yap-EIYeFp|T #Q|@5V#*R+=ir#Mu`D6v]nGS+Z@{<h}' );
define( 'NONCE_SALT',       'evOz<gDmq#ZO5~P=>hd~]v4L;Fik4,4abo1)=6[Ki~j0w ^:owX.l{DvCTV9pT9|' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_ensat';

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
