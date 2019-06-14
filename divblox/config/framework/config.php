<?php
/**
 * Created by PhpStorm.
 * User: Johan Griesel (Stratusolve (Pty) Ltd)
 * Date: 2017/02/18
 * Time: 10:07 AM
 */
require_once("environments.php");
if (!isset($EnvironmentArray)) {
    die(json_encode(array("Error" => 'Error in config file environments.php. Invalid environments array. For a quick fix, delete the file environments.php')));
}

$CurrentDocumentRoot = $_SERVER["DOCUMENT_ROOT"];
$CurrentEnvironment = null;
$CurrentEnvironmentInstanceName = 'default';
foreach($EnvironmentArray as $EnvironmentInstance => $Environment) {
    if (array_key_exists("DOCUMENT_ROOT", $Environment)) {
        if ($Environment["DOCUMENT_ROOT"] == $CurrentDocumentRoot) {
            $CurrentPath = str_replace('\\','/',dirname(__FILE__));
            $CurrentSubdirectory = str_replace("/divblox/config/framework", "", $CurrentPath);
            $CurrentSubdirectory = str_replace($CurrentDocumentRoot, "", $CurrentSubdirectory);
            if ($CurrentSubdirectory == $Environment["SUBDIRECTORY"]) {
                $CurrentEnvironment = $Environment;
                $CurrentEnvironmentInstanceName = $EnvironmentInstance;
            }
        }
    }
}
if (is_null($CurrentEnvironment)) {
    error_log("The current environment is not configured in environments.php; ".$_SERVER["DOCUMENT_ROOT"]." vs ".json_encode($EnvironmentArray));
    die(json_encode(array("Error" => 'The current environment is not configured in environments.php. Ensure that the values for DOCUMENT_ROOT and SUBDIRECTORY are present and correct. For a quick fix, delete the file environments.php')));
}
if (is_null($ModuleArray)) {
    error_log("Modules are not configured in environments.php");
    die(json_encode(array("Error" => 'Modules are not configured in environments.php. For a quick fix, delete the file environments.php')));
}
define("PHP_MIN_VERSION_REQUIRED","7.1");
define("MARIADB_MIN_VERSION_REQUIRED","10.2");
define("MYSQL_MIN_VERSION_REQUIRED","5.6.38");
define("DIVBLOX_ADMIN_PASSWORD_STR","1");
define("ENVIRONMENT_INSTANCE_STR",$CurrentEnvironmentInstanceName);
define("APP_ENCODING_TYPE_STR", "UTF-8");
define("APP_NAME_STR",$CurrentEnvironment["APP_NAME"]);
define("DOCUMENT_ROOT_STR",$CurrentEnvironment["DOCUMENT_ROOT"]);
define("SUBDIRECTORY_STR",$CurrentEnvironment["SUBDIRECTORY"]);
define("APP_ROOT_STR",DOCUMENT_ROOT_STR.SUBDIRECTORY_STR);
define("FRAMEWORK_ROOT_STR",DOCUMENT_ROOT_STR.SUBDIRECTORY_STR."/divblox");
define("FRAMEWORK_CONFIG_STR", FRAMEWORK_ROOT_STR."/config");
define("DATA_MODELLER_PATH_STR", FRAMEWORK_CONFIG_STR."/database/data_modeller");
define("DATA_MODEL_CLASS_PATH_STR", DATA_MODELLER_PATH_STR."/data_model/DataModel.class.php");
define("DATA_MODEL_ORM_PATH_STR", FRAMEWORK_CONFIG_STR."/database/data_model_orm");
define("PROJECT_ROOT_STR",DOCUMENT_ROOT_STR.SUBDIRECTORY_STR."/project");
define("MAINTENANCE_PASSWORD_STR",$CurrentEnvironment["MAINTENANCE_PASSWORD"]);
define("DX_API_KEY_STR",$dxApiKey);
define("AUTHENTICATION_TIMEOUT_INT",60); //Minutes
define("AUTHENTICATION_REGENERATION_INT",5); //Minutes
define("APP_MODULES_STR",json_encode($ModuleArray));
define("APP_MODULES_COLORS_STR",json_encode($ModuleColorArray));
foreach($CurrentEnvironment["DATABASES"] as $Database=>$Settings) {
    define(strtoupper($Database)."_DATABASE_SERVER_STR",$Settings["server"]);
    define(strtoupper($Database)."_DATABASE_PORT_STR",$Settings["port"]);
    define(strtoupper($Database)."_DATABASE_NAME_STR",$Settings["database"]);
    define(strtoupper($Database)."_DATABASE_USER_STR",$Settings["username"]);
    define(strtoupper($Database)."_DATABASE_PASSWORD_STR",$Settings["password"]);
    if (strlen($Settings['ssl_cert_path']) > 0) {
        define(strtoupper($Database)."_DATABASE_SERVER_SSL_CERT_STR",DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$Settings['ssl_cert_path']);
    } else {
        define(strtoupper($Database)."_DATABASE_SERVER_SSL_CERT_STR",NULL);
    }
}
define("MYSQL_DUMP_LOCATION_OVERRIDE_STR","/Applications/MAMP/Library/bin/mysqldump");
if ((function_exists('date_default_timezone_set')) && (!ini_get('date.timezone'))) {
    date_default_timezone_set('Africa/Johannesburg');
}
define ('DATE_TIME_FORMAT_JS_STR','dd-M-yy');
define ('DATE_TIME_FORMAT_PHP_STR','d M Y');
define ('DATE_TIME_FORMAT_HTML_STR','DD-MMM-YYYY');

define("AUTHENTICATION_TOKEN_STR","auth_token_".str_replace(" ", "_", strtolower(APP_NAME_STR)));

require_once(DATA_MODELLER_PATH_STR.'/data_model/DataModel_Config.inc.php');
require_once(DATA_MODELLER_PATH_STR.'/data_model/DataModel.class.php');
include(PROJECT_ROOT_STR."/assets/php/project_classes.php");
include(PROJECT_ROOT_STR."/assets/php/project_functions.php");
include(DATA_MODEL_ORM_PATH_STR.'/generated/_class_paths.inc.php');
include(DATA_MODEL_ORM_PATH_STR.'/generated/dxQueryN.class.php');


spl_autoload_register(array('ProjectFunctions', 'Autoload'));
ProjectFunctions::InitializeDatabaseConnections();
session_start();
set_error_handler("divbloxErrorHandler");
set_exception_handler('divbloxHandleException');
?>