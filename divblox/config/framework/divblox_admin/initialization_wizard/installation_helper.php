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
define('ALLOW_WIZARD_ACCESS',true); //JGL: IMPORTANT: set this to false in production to disable access to the setup wizard
define('DX_BASECAMP_URL_STR',"https://basecamp.divblox.com/api/");

if (!defined('ALLOW_WIZARD_ACCESS')) {
    die("Access not allowed. Check flag 'ALLOW_WIZARD_ACCESS'");
}
if (!ALLOW_WIZARD_ACCESS) {
    die("Access not allowed. Check flag 'ALLOW_WIZARD_ACCESS'");
}

if (isset($_GET["check"])) {
    die("");
}
unset($_COOKIE['PHPSESSID']);

if (isset($_GET["checkCurl"])) {
    set_error_handler('installation_helper_error_handler');
    $CurlError = checkCurlConnect(DX_BASECAMP_URL_STR);
    restore_error_handler();
    if (strlen($CurlError) == 0) {
        die(json_encode(array("Success" => "")));
    } else {
        die(json_encode(array("Failed" => "Error: ".$CurlError)));
    }
}
if (isset($_GET['checkWritePermissions'])) {
    set_error_handler('installation_helper_error_handler');
    $DataModelOrm = isWritable('../../../database/data_model_orm/');
    $Project = isWritable('../../../../../project/');
    restore_error_handler();
    if ($DataModelOrm && $Project) {
        die(json_encode(array("Success" => "")));
    }

    die(json_encode(array("Failed" => "Required paths not writeable","Datamodel" => $DataModelOrm,"Project" => $Project)));
}
function installation_helper_error_handler($error_level, $error_message, $error_file, $error_line, $error_context) {
    die(json_encode(array("Failed" => "Error: ".$error_message)));
}
function checkCurlConnect($url) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_USERAGENT      => "dx_installation_helper", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 10,    // time-out on connect
        CURLOPT_TIMEOUT        => 120,    // time-out on response
        CURLOPT_POSTFIELDS     => ''
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    error_log("Curl returned content: ".$content);
    $error = curl_error($ch);
    curl_close($ch);
    return $error;
}
function printCleanPhpInfo() {
    ob_start();
    phpinfo();
    $pinfo = ob_get_contents();
    ob_end_clean();

    $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
    echo $pinfo;
}
/**
 * Will work despite of Windows ACLs bug
 *
 * NOTE: use a trailing slash for folders!!!
 *
 * See http://bugs.php.net/bug.php?id=27609 AND http://bugs.php.net/bug.php?id=30931
 * Source: <http://www.php.net/is_writable#73596>
 *
 */
function isWritable($path) {
    // recursively return a temporary file path
    if($path{strlen($path) - 1} == '/') {
        return isWritable($path.uniqid(mt_rand()).'.tmp');
    } elseif(is_dir($path)) {
        return isWritable($path.'/'.uniqid(mt_rand()).'.tmp');
    }

    // check file for read/write capabilities
    $rm = file_exists($path);
    $handle = @fopen($path, 'a');
    if($handle === false) {
        return false;
    }

    fclose($handle);

    if (strpos(strtolower(PHP_OS),"linux") !== false) {
        if(!$rm) {
            if (!chmod($path, 0666)) {
                unlink($path);
                return false;
            }
            unlink($path);
        }
    }

    return true;
}
?>
