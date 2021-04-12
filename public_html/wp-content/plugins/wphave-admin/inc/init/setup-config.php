<?php
//MAIN CONFIG FILE OF AUTO PHP LICENSER. CAN BE EDITED MANUALLY OR GENERATED USING Extra Tools > Configuration Generator TAB IN AUTO PHP LICENSER DASHBOARD. THE FILE MUST BE INCLUDED IN YOUR SCRIPT BEFORE YOU PROVIDE IT TO USER.


//-----------BASIC SETTINGS-----------//


//Random salt used for encryption. It should contain random symbols (16 or more recommended) and be different for each application you want to protect. Cannot be modified after installing script.
define("WPHAVE_ADMIN_APL_SALT", WPHAVE_ADMIN_GET_APL_SALT);

//The URL (without / at the end) where Auto PHP Licenser from /WEB directory is installed on your server. No matter how many applications you want to protect, a single installation is enough.
define("WPHAVE_ADMIN_APL_ROOT_URL", "https://www.creative-dive.de/themes/envato/license-server");

//Unique numeric ID of product that needs to be licensed. Can be obtained by going to Products > View Products tab in Auto PHP Licenser dashboard and selecting product to be licensed. At the end of URL, you will see something like products_edit.php?product_id=NUMBER, where NUMBER is unique product ID. Cannot be modified after installing script.
define("WPHAVE_ADMIN_APL_PRODUCT_ID", WPHAVE_ADMIN_GET_APL_PRODUCT_ID);

//Time period (in days) between automatic license verifications. The lower the number, the more often license will be verified, but if many end users use your script, it can cause extra load on your server. Available values are between 1 and 365. Usually 7 or 14 days are the best choice.
define("WPHAVE_ADMIN_APL_DAYS", 14);

//Place to store license signature and other details. "DATABASE" means data will be stored in MySQL database (recommended), "FILE" means data will be stored in local file. Only use "FILE" if your application doesn't support MySQL. Otherwise, "DATABASE" should always be used. Cannot be modified after installing script.
define("WPHAVE_ADMIN_APL_STORAGE", "DATABASE");

//Name of table (will be automatically created during installation) to store license signature and other details. Only used when "WPHAVE_ADMIN_APL_STORAGE" set to "DATABASE". The more "harmless" name, the better. Cannot be modified after installing script.
// !!!
// Notice: Notice: The table prefix is required for multiple licenses in the same database
// Notice: "$wpdb->base_prefix" returns only the table prefix from the main page of all subpages, if "Multisite" is used. Therefore use "$wpdb->prefix" instead
// !!!
global $wpdb;
define("WPHAVE_ADMIN_APL_DATABASE_TABLE", $wpdb->prefix . "wphave_admin_license_data");

//Name and location (relative to directory where "apl_core_configuration.php" file is located, cannot be moved outside this directory) of file to store license signature and other details. Can have ANY name and extension. The more "harmless" location and name, the better. Cannot be modified after installing script. Only used when "WPHAVE_ADMIN_APL_STORAGE" set to "FILE" (file itself can be safely deleted otherwise).
define("WPHAVE_ADMIN_APL_LICENSE_FILE_LOCATION", "signature/license.key.example");

//Notification to be displayed when connection to server can't be established. Other notifications will be automatically fetched from server.
define("WPHAVE_ADMIN_APL_NOTIFICATION_NO_CONNECTION", "Can't connect to licensing server.");

//Notification to be displayed when response received from server is invalid. Other notifications will be automatically fetched from server.
define("WPHAVE_ADMIN_APL_NOTIFICATION_INVALID_RESPONSE", "Invalid server response.");

//Notification to be displayed when updating database fails. Only used when WPHAVE_ADMIN_APL_STORAGE set to DATABASE.
define("WPHAVE_ADMIN_APL_NOTIFICATION_DATABASE_WRITE_ERROR", "Can't write to database.");

//Notification to be displayed when updating license file fails. Only used when WPHAVE_ADMIN_APL_STORAGE set to FILE.
define("WPHAVE_ADMIN_APL_NOTIFICATION_LICENSE_FILE_WRITE_ERROR", "Can't write to license file.");

//Notification to be displayed when installation wizard is launched again after script was installed.
define("WPHAVE_ADMIN_APL_NOTIFICATION_SCRIPT_ALREADY_INSTALLED", "Script is already installed (or database not empty).");

//Notification to be displayed when license could not be verified because license is not installed yet or corrupted.
define("WPHAVE_ADMIN_APL_NOTIFICATION_LICENSE_CORRUPTED", "License is not installed yet or corrupted.");

//Notification to be displayed when license verification does not need to be performed. Used for debugging purposes only, should never be displayed to end user.
define("WPHAVE_ADMIN_APL_NOTIFICATION_BYPASS_VERIFICATION", "No need to verify");


//-----------NOTIFICATIONS FOR USER INPUT VERIFICATIONS. SAFE TO DISPLAY TO END USER-----------//


define("WPHAVE_ADMIN_APL_USER_INPUT_NOTIFICATION_INVALID_ROOT_URL", "User input error: Invalid installation URL (it should have a valid scheme and no / symbol at the end)");
define("WPHAVE_ADMIN_APL_USER_INPUT_NOTIFICATION_EMPTY_LICENSE_DATA", "User input error: empty license data (licensed email or license code should be provided)");
define("WPHAVE_ADMIN_APL_USER_INPUT_NOTIFICATION_INVALID_EMAIL", "User input error: invalid licensed email (it should be a valid email address)");
define("WPHAVE_ADMIN_APL_USER_INPUT_NOTIFICATION_INVALID_LICENSE_CODE", "User input error: invalid license code (it should be a code in plain text)");


//-----------ADVANCED SETTINGS-----------//


//Secret key used to verify if configuration file included in your script is genuine (not replaced with 3rd party files). It can contain any number of random symbols and should be different for each application you want to protect. You should also change its name from "WPHAVE_ADMIN_APL_INCLUDE_KEY_CONFIG" to something else, let's say "MY_CUSTOM_SECRET_KEY"
define("WPHAVE_ADMIN_APL_INCLUDE_KEY_CONFIG", WPHAVE_ADMIN_GET_APL_INCLUDE_KEY_CONFIG);

//IP address of your Auto PHP Licenser installation. If IP address is set, script will always check if "WPHAVE_ADMIN_APL_ROOT_URL" resolves to this IP address (very useful against users who may try blocking or nullrouting your domain on their servers). However, use it with caution because if IP address of your server is changed in future, old installations of protected script will stop working (you will need to update this file with new IP and send updated file to end user). If you want to verify licensing server, but don't want to lock it to specific IP address, you can use APL_ROOT_NAMESERVERS option (because nameservers change is unlikely).
define("WPHAVE_ADMIN_APL_ROOT_IP", "");

//Nameservers of your domain with Auto PHP Licenser installation (only works with domains and NOT subdomains). If nameservers are set, script will always check if "APL_ROOT_NAMESERVERS" match actual DNS records (very useful against users who may try blocking or nullrouting your domain on their servers). However, use it with caution because if nameservers of your domain are changed in future, old installations of protected script will stop working (you will need to update this file with new nameservers and send updated file to end user). Nameservers should be formatted as an array. For example: array("ns1.phpmillion.com", "ns2.phpmillion.com"). Nameservers are NOT CAse SensitIVE.
//define("APL_ROOT_NAMESERVERS", array()); //ATTENTION! THIS FEATURE ONLY WORKS WITH PHP 7.0 AND HIGHER, ONLY UNCOMMENT THIS LINE IF PROTECTED SCRIPT WILL RUN ON COMPATIBLE SERVER!

//-----------SOME EXTRA STUFF. SHOULD NEVER BE REMOVED OR MODIFIED-----------//
define("WPHAVE_ADMIN_APL_DIRECTORY", __DIR__);
