<?php
/**
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */

header("Content-type: text/plain");
if (!file_exists("config.php")) {
    die(json_encode(array("Failed" => "Config is not present")));
}
if (!file_exists("environments.php")) {
    die(json_encode(array("Failed" => "Environment is not present")));
}
require("../../divblox.php");
function checkMinimumPhpVersion() {
    $CurrentVersionArray = explode('.',phpversion());
    $CurrentVersionComponents = array(
        'Major'      =>  $CurrentVersionArray[0],
        'Minor'      =>  $CurrentVersionArray[1],
        'Release'    =>  $CurrentVersionArray[2]);

    $RequiredVersionArray = explode('.',PHP_MIN_VERSION_REQUIRED);
    $Release = 0;
    if (isset($RequiredVersionArray[2])) {
        $Release = $RequiredVersionArray[2];
    }
    $RequiredVersionComponents = array(
        'Major'      =>  $RequiredVersionArray[0],
        'Minor'      =>  $RequiredVersionArray[1],
        'Release'    =>  $Release);
    return $CurrentVersionComponents["Major"] >= $RequiredVersionComponents["Major"] &&
        $CurrentVersionComponents["Minor"] >= $RequiredVersionComponents["Minor"] &&
        $CurrentVersionComponents["Minor"] >= $RequiredVersionComponents["Minor"];

}
function checkMinimumDbVersion($Database = 'mariadb') {
    $CurrentDbVersion = getCurrentDbVersion();
    if ($CurrentDbVersion["Version"] == 'N/A') {
        return false;
    }
    $CurrentVersionArray = explode('.',$CurrentDbVersion["Version"]);
    $CurrentVersionComponents = array(
        'Major'      =>  $CurrentVersionArray[0],
        'Minor'      =>  $CurrentVersionArray[1],
        'Release'    =>  $CurrentVersionArray[2]);

    if ($CurrentDbVersion['Server'] == "mariadb") {
        $RequiredVersionArray = explode('.',MARIADB_MIN_VERSION_REQUIRED);
    } else {
        $RequiredVersionArray = explode('.',MYSQL_MIN_VERSION_REQUIRED);
    }
    $Release = 0;
    if (isset($RequiredVersionArray[2])) {
        $Release = $RequiredVersionArray[2];
    }
    $RequiredVersionComponents = array(
        'Major'      =>  $RequiredVersionArray[0],
        'Minor'      =>  $RequiredVersionArray[1],
        'Release'    =>  $Release);
    return $CurrentVersionComponents["Major"] >= $RequiredVersionComponents["Major"] &&
        $CurrentVersionComponents["Minor"] >= $RequiredVersionComponents["Minor"] &&
        $CurrentVersionComponents["Minor"] >= $RequiredVersionComponents["Minor"];

}
function getCurrentDbVersion() {
    $ModuleArray = json_decode(APP_MODULES_STR);
    $intMaxIndex = ProjectFunctions::getDataSetSize($ModuleArray);
    if ($intMaxIndex < 1) {
        return array("Server" => "mariadb", "Version" => "N/A","Reason" => 'No database configured. Configure databases in the divblox setup page: <a target="_blank" href="'.ProjectFunctions::getBaseUrl().'/divblox/config/framework/divblox_admin/setup.php">here</a>');
    }
    $strConstantPrefix = strtoupper($ModuleArray[0]);
    $strConstantName = $strConstantPrefix."_DATABASE_SERVER_STR";

    $host = constant($strConstantName);
    $username = constant($strConstantPrefix."_DATABASE_USER_STR");
    $password = constant($strConstantPrefix."_DATABASE_PASSWORD_STR");
    try {
        $link = mysqli_connect("$host","$username","$password");
    } catch (ErrorException $e) {
        return array("Server" => "mariadb", "Version" => "N/A","Reason" => 'Could not connect to database server. Error: '.$e->getMessage().'<br>Configure databases in the divblox setup page: <a target="_blank" href="'.ProjectFunctions::getBaseUrl().'/divblox/config/framework/divblox_admin/setup.php">here</a>');
    }
    if (mysqli_connect_errno()) {
        return array("Server" => "mariadb", "Version" => "N/A","Reason" => "Could not connect to database server");
    }
    $Result = $link->query("SHOW VARIABLES like '%version%'");
    $VersionStr = '';
    if ($Result) {
        $ResultArray = $Result->fetch_assoc();
        $VersionStr = $ResultArray["Value"];
    }
    $Version = mysqli_get_server_info($link);
    $Server = 'mariadb';
    if (strpos(strtolower($Version),"mariadb") == false) {
        if (floatval($VersionStr) < 10) {
            $Server = 'mysql';
        }
    }
    $LowerCaseTableNamesOk = false;
    $Result = $link->query("SHOW VARIABLES LIKE 'lower_case_table_names'");
    if ($Result) {
        $ResultArray2 = $Result->fetch_assoc();
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $LowerCaseTableNamesOk = $ResultArray2["Value"] == 2;
        } else {
            $LowerCaseTableNamesOk = $ResultArray2["Value"] != 1;
        }
    }

    $link->close();
    return array("Server" => $Server, "Version" => $VersionStr,"Reason" => $ResultArray,"LowerCaseTableNamesOK" => $LowerCaseTableNamesOk);
}
function getRequiredDbVersion() {
    $CurrentDbVersion = getCurrentDbVersion();
    if ($CurrentDbVersion["Server"] == 'mariadb') {
        return MARIADB_MIN_VERSION_REQUIRED;
    } else {
        return MYSQL_MIN_VERSION_REQUIRED;
    }
}
echo json_encode(array("Success" => "Environment & config is present",
    "PhpVersion" => phpversion(),
    "RequiredPhpVersion" => PHP_MIN_VERSION_REQUIRED,
    "PhpVersionOk" => checkMinimumPhpVersion(),
    "DbVersion" => getCurrentDbVersion(),
    "RequiredDbVersion" => getRequiredDbVersion(),
    "DbVersionOk" => checkMinimumDbVersion()));
ProjectFunctions::printCleanOutput();
?>