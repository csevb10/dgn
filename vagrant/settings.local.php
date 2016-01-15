<?php
/**
 * @file
 * This is a template for configuring your local development environment.
 *
 * Copy and rename this file as settings.local.php in the sites/default
 * folder.
 */

/**
 * Local Varnish/Reverse Proxy configuration.
 */
//$conf['reverse_proxy'] = TRUE;
//$conf['reverse_proxy_addresses'] = array('127.0.0.1');
//$conf['omit_vary_cookie'] = TRUE;

/**
 * Local database configuration settings.
 */
$databases['default']['default'] = array(
  'driver' => 'mysql',
  'database' => 'drupal',
  'username' => 'root',
  'password' => 'root',
  'host' => '127.0.0.1',
  'port' => 3306,
  'prefix' => '',
  'collation' => 'utf8_general_ci',
);

/**
 * Local files directory settings, set these to match the paths on your
 * local environment.
 */
// TODO: Replace [project name] with the correct name for your project.
$conf['file_public_path'] = 'sites/default/files';
$conf['file_private_path'] = '/mnt/sites/dgn2/files/private';
$conf['file_temporary_path'] = '/tmp';

/**
 * Disable Acquia Network integration to prevent submitting dev content to
 * Acquia's Solr servers.
 */
//$conf['acquia_subscription_name'] = '';
//$conf['acquia_spi_def_vars'] = '';
//$conf['acquia_search_derived_key_salt'] = '';

/**
 * Local Apache Solr configuration overrides.
 */
$serial_number = isset($_SERVER["VAGRANT_HOST_SERIAL"]) ? $_SERVER["VAGRANT_HOST_SERIAL"] : 'vagrant';
$host_user = isset($_SERVER["VAGRANT_HOST_USERNAME"]) ? $_SERVER["VAGRANT_HOST_USERNAME"] : 'vagrant';
$site_solr_hash =  substr(base_convert(sha1($serial_number), 16, 36), 0, 6);
$conf['apachesolr_site_hash'] = $site_solr_hash;
$conf['search_api_solr_site_hash'] = $site_solr_hash;


/**
 * Ensure all errors and warnings are output and logged.
 */
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('log_errors', 1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('html_errors', 'off');

// Set this to a real path on your local environment.
// ini_set('error_log', '/opt/project_files/files/php_error.log');
$conf['error_level'] = 2;  // Show all errors, notices, warnings.


/**
 * Misc other settings.
 */

//$conf['page_compression'] = FALSE;
//$conf['preprocess_css'] = FALSE;
//$conf['preprocess_js'] = FALSE;
//ini_set('memory_limit', '256M');
//ini_set('max_execution_time', 120);

// Stage File Proxy Settings
// TODO: Configure the source URL for the stage_file_proxy module if used.
// $conf['stage_file_proxy_origin'] = 'https://[username]:[password]@dgn2.prod.acquia-sites.com';


// Allow the dev to temporarily override the default settings in their local dev environment.
if (file_exists('/var/www/html/dgn2/vagrant/settings.local.override.php')) {
  $local_settings_file = '/var/www/html/dgn2/vagrant/settings.local.override.php';
}
