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
define( 'DB_NAME', 'db6zpu2fhjf9we' );

/** MySQL database username */
define( 'DB_USER', 'u82z9wnm3cpjx' );

/** MySQL database password */
define( 'DB_PASSWORD', 'jk2gnxex36e2' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          ';4z6qjZyJq$LLKjxWm=6v3Oq&Ak0]{Cqb,.4t*J;!NqPn1.#U8gJ#.x[*E>|_(k*' );
define( 'SECURE_AUTH_KEY',   'v,QZ[<sDsl,6o,9dwq89}kUn|sK/I,W^Sm:WuKc^S9@7bPiHv+KQnBo%z2d`Ehgs' );
define( 'LOGGED_IN_KEY',     '*0kR[acG~.3v~uzCn}+kVS7S#@8$vf{qesd+z&n/EI>#blsv%oFw6qyB?z67D<;A' );
define( 'NONCE_KEY',         ':;KsH9!<NY`ONV$W~`GPvV8cdQrmu>G#G^**nZsKg}0o#orWu{5Xyk4-k?Z>*$kw' );
define( 'AUTH_SALT',         '^*2Q.ooirk<|At,l6sv=;/ZLDx O2PAWxgs+cv*iOpj)y9~n0(9.9K/S^.1}IBzz' );
define( 'SECURE_AUTH_SALT',  ' G{9sHW/^]X<7f&3p}C`.|evHfH7!Tq[BckmL3S>[O?72%4<8ebu,jX@qz=zy%JY' );
define( 'LOGGED_IN_SALT',    '+C`2$:{.u=lBbOnbL[?Hk7?e>1J3;#~s>v,#7U0|42yAp<LAa=Am@yAO,>Tne&a2' );
define( 'NONCE_SALT',        'U[bqFC[2abJ`0Ao(]^*QJnEOIkVL&ES69qpa<`rt[z9|YC~f,ge)|h:>&~W/KHd0' );
define( 'WP_CACHE_KEY_SALT', '+t-7/DRWlKDf9*T%T<b=m3cZPVrUE|/#IhVKLcGQPm6Gx,|%qXO9`D>uP3{TLt%g' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ndx_';






define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', true );
define( 'DOMAIN_CURRENT_SITE', 'staging-ed.it' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
define( 'FORCE_SSL_ADMIN', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
