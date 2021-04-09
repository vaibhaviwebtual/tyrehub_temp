<?php
define( 'WP_CACHE', false ); // Added by WP Rocket





/** Enable W3 Total Cache */
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
define('DB_NAME', 'tyrehub_update_plugin');
//define('DB_NAME', 'tyrehub');

/** MySQL database username */
define('DB_USER', 'root');
//define('DB_USER', 'tyrehub');
/** MySQL database password */
define('DB_PASSWORD', '');
//define('DB_PASSWORD', 'Gf8mTtcGjEbHY53m');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('FS_METHOD','direct');

define( 'WP_DEBUG_LOG', true );
define( 'WP_MEMORY_LIMIT', '256M' );
define('GOOGLE_API_KEY', "AIzaSyD2x7GPshXaTMUf8XA1IUBoO2aSPTbmNqk");
// define( 'SMTP_HOST', 'email-smtp.ap-south-1.amazonaws.com' );  // A2 Hosting server name. For example, "a2ss10.a2hosting.com"
// define( 'SMTP_AUTH', true );
// define( 'SMTP_PORT', '465' );
// define( 'SMTP_SECURE', 'ssl' );
// define( 'SMTP_USERNAME', 'AKIARLFHNYFMKUJS4RXJ' );  // Username for SMTP authentication
// define( 'SMTP_PASSWORD', 'BFgPNtCshvnz5BwZUBnGVoyGrb1uuP362qJC/tL+axI8' );          // Password for SMTP authentication
// define( 'SMTP_FROM',     'info@tyrehub.com' );  // SMTP From address
// define( 'SMTP_FROMNAME', 'Tyre Hub');         // SMTP From name


// define( 'WPMS_ON', true );
// define( 'WPMS_SMTP_PASS', 'BFgPNtCshvnz5BwZUBnGVoyGrb1uuP362qJC/tL+axI8' );


//define('DISABLE_WP_CRON', 'true');
//define( 'WP_DEBUG', true );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4}+h8-bGz sDy~Jk uZyK|ZVM+ln^WM|@bsGzC{n_Zo|M%VD~QG8MG5P?~-UbjL.');
define('SECURE_AUTH_KEY',  '$oa]]|HZI93+i(W*b1Ohx:QYyY}kVkS/N}06 d-{dZHIz3%KL&&QS;t:ry+p*^8?');
define('LOGGED_IN_KEY',    'xJ>bE5ZSHxfDX0;JzFY|F-<oSEr>R 2sR-f,}6jJ7)FHA|.3h-?$UTKiFz3$xqrL');
define('NONCE_KEY',        'N-JqGqG!@klI8s2Gd[]MbYp|8Ow.Dx89]lyn*Yu I?I0X+vP11W/vD/_ZM67L1wR');
define('AUTH_SALT',        '~AQJKr= Agzts<+sp(I,O}+$4]lB8*cxk2m~L2BCw^ckam1>aSW#xnIJ>+7VVr1*');
define('SECURE_AUTH_SALT', 'G_;DRa^q[-7,bk) JkDXL=h&]E/w3~)py:+vj{Q0}(heQ3e*c;+s,GB.x3V|Q<KV');
define('LOGGED_IN_SALT',   '_U}Nf|!A 6mYho^rH]6nP)ltY|IIB~yS&PO1)%lS?;,-.|ID|t4*ILY!||[G=t<T');
define('NONCE_SALT',       'C5{}&0E2)aN`i4t0{>cqH0-F2S~hVd:#q>BFr]8+H.Y]ti2{D;u!@--B{1 f/S5@');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
