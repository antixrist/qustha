<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

/* define package */
define('PKG_NAME', 'qustha');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));

define('PKG_VERSION', '0.0.4');
define('PKG_RELEASE', 'beta');


define('PKG_DEV_RELEASE', false);
define('PKG_AUTO_INSTALL', true);

$devFolderName = (PKG_DEV_RELEASE) ? PKG_NAME .'/' : '';

$nsCore = (PKG_DEV_RELEASE) ? '{base_path}'.PKG_NAME_LOWER.'/core/components/'.PKG_NAME_LOWER.'/' : '{core_path}components/'.PKG_NAME_LOWER.'/';
$nsAssets = (PKG_DEV_RELEASE) ? '{base_path}'.PKG_NAME_LOWER.'/assets/components/'.PKG_NAME_LOWER.'/' : '{assets_path}components/'.PKG_NAME_LOWER.'/';
define('PKG_NAMESPACE_PATH_CORE', $nsCore);
define('PKG_NAMESPACE_PATH_ASSETS', $nsAssets);

/* define paths */
if (isset($_SERVER['MODX_BASE_PATH'])) {
	define('MODX_BASE_PATH', $_SERVER['MODX_BASE_PATH']);
}
elseif (file_exists(dirname(dirname(dirname(__FILE__))) . '/core')) {
	define('MODX_BASE_PATH', dirname(dirname(dirname(__FILE__))) . '/');
}
else {
	define('MODX_BASE_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
}
define('MODX_CORE_PATH', dirname(MODX_BASE_PATH) . '/core/');
define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');

/* define urls */
define('MODX_BASE_URL', '/');
define('MODX_CORE_URL', MODX_BASE_URL . 'core/');
define('MODX_MANAGER_URL', MODX_BASE_URL . 'manager/');
define('MODX_CONNECTORS_URL', MODX_BASE_URL . 'connectors/');
define('MODX_ASSETS_URL', MODX_BASE_URL . 'assets/');

/* define build options */
//define('BUILD_MENU_UPDATE', false);
//define('BUILD_ACTION_UPDATE', false);
define('BUILD_SETTING_UPDATE', true);
define('BUILD_CHUNK_UPDATE', true);

define('BUILD_SNIPPET_UPDATE', true);
define('BUILD_PLUGIN_UPDATE', true);
//define('BUILD_EVENT_UPDATE', true);
//define('BUILD_POLICY_UPDATE', true);
//define('BUILD_POLICY_TEMPLATE_UPDATE', true);
//define('BUILD_PERMISSION_UPDATE', true);

define('BUILD_CHUNK_STATIC', PKG_DEV_RELEASE);
define('BUILD_SNIPPET_STATIC', PKG_DEV_RELEASE);
define('BUILD_PLUGIN_STATIC', PKG_DEV_RELEASE);

$BUILD_RESOLVERS = array(
	'files'
);