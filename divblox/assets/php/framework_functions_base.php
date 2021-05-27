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

// Exception and Error Handling. It is important to note that divblox will display all errors/exceptions thrown in php
// since php will never be used to render ui to the user (Except for divblox admin functions)
/**
 * @param $e
 */
function divbloxHandleException($e) {
    $ExceptionReflectionObj = new ReflectionObject($e);
    $Output = ob_get_clean();
    $ErrorArray = array("Result" => "Failed",
        "Message" => $e->getMessage(),
        "Type" => $ExceptionReflectionObj->getName(),
        "File" => $e->getFile(),
        "Line" => $e->getLine(),
        "Trace" => $e->getTrace(),
        "Output" => $Output,);
    error_log("divblox Exception. Details: ".json_encode($ErrorArray));
    die(json_encode($ErrorArray));
}

/**
 * @param $severity
 * @param $message
 * @param $file
 * @param $line
 * @throws ErrorException
 */
function divbloxErrorHandler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

///region Divblox code gen related
function handleBackendException($e) {
    $ExceptionReflectionObj = new ReflectionObject($e);
    $Output = ob_get_clean();
    $ErrorArray = array("Result" => "Failed",
        "Message" => $e->getMessage(),
        "Type" => $ExceptionReflectionObj->getName(),
        "File" => $e->getFile(),
        "Line" => $e->getLine(),
        "Trace" => $e->getTrace(),
        "Output" => $Output,);
    error_log("Handled backend exception: ".$e->getMessage());
}
//endregion

//region Email related
/**
 * A special exception handler used for PHPMailer to avoid returning to the client too early
 * @param $e
 */
function handleEmailException($e) {
    $ExceptionReflectionObj = new ReflectionObject($e);
    $Output = ob_get_clean();
    $ErrorArray = array("Result" => "Failed",
        "Message" => $e->getMessage(),
        "Type" => $ExceptionReflectionObj->getName(),
        "File" => $e->getFile(),
        "Line" => $e->getLine(),
        "Trace" => $e->getTrace(),
        "Output" => $Output,);
    error_log("Handled email exception: ".$e->getMessage());
}
//endregion

/**
 * Class FrameworkFunctions_base
 */
abstract class FrameworkFunctions_base {
    /**
     * @var
     */
    public static $Database;
    /**
     * @var
     */
    public static $ClassFile;
    /**
     * @var string
     */
    public static $objCacheProvider = 'dxCacheProviderNoCache';

    /**
     * @param $String: The string to check for valid json
     * @return bool: Returns true when $String is a valid JSON string
     */
    public static function isJson($String) {
        json_decode($String);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @param null $DataSet: This should be an array
     * @return int: Will return 0 if not a valid data set and 1 if data set is scalar. Otherwise it will return FrameworkFunctions::getDataSetSize(DataSet);
     */
    public static function getDataSetSize($DataSet = null) {
        if (is_null($DataSet)) {
            return 0;
        }
        if (!is_array($DataSet)) {
            return 1;
        }
        return count($DataSet);
    }

    /**
     * Checks for https protocol
     * @return bool
     */
    public static function isSecure() {
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
    }

    /**
     * Checks for www in url protocol
     * @return bool
     */
    public static function HostHasWWW() {
        if(preg_match('/^www/', $_SERVER['HTTP_HOST']))
            return true;
        return false;
    }

    /**
     * Returns the current app base url down to SUBDIRECTORY_STR
     * @return string
     */
    public static function getBaseUrl() {
        $protocol = 'http://';
        if (FrameworkFunctions::isSecure())
            $protocol = 'https://';
        $www = FrameworkFunctions::HostHasWWW()?'www.':'';
        $server = $_SERVER['SERVER_NAME'];
        $port = '';
        if (($_SERVER["SERVER_PORT"] != "80") && ($_SERVER["SERVER_PORT"] != "443")) {
            $port = ':'.$_SERVER["SERVER_PORT"];
        }
        $url = $protocol.$www.$server.$port.SUBDIRECTORY_STR;
        return $url;
    }

    /**
     * Checks if a url is valid
     * @param $url
     * @return bool
     */
    public static function isValidUrl($url) {
        if (is_null($url)) {return false;}
        if (strlen($url) > 0) {
            if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                return true;
            }
        }
        return false;
    }

    /**
     * This function posts to a url using curl and returns the content of the url
     * @param $url
     * @param $fields_string: Must be provided as variable=value&variable=value
     * @param string $client
     * @param int $DefaultTimeout The default amount of seconds to wait for a response from the remote server
     * @return mixed
     */
    public static function PostToUrl($url,$fields_string = '',$client = APP_NAME_STR,$DefaultTimeout = 120) {
        if (!FrameworkFunctions::isValidUrl($url)) {
            error_log("Tried calling 'PostToUrl' with invalid url");
            return '';
        }
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_USERAGENT      => $client, // name of client
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 10,    // time-out on connect
            CURLOPT_TIMEOUT        => $DefaultTimeout,    // time-out on response
            CURLOPT_POSTFIELDS     => $fields_string,
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content  = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    /**
     * This function posts to a url using curl and returns the content of the url
     * @param $url: The url to post to
     * @param $fields_string: Must be provided as variable=value&variable=value
     * @param $ErrorInfo: Must be provided as variable=value&variable=value
     * @return mixed
     */
    public static function PostToInternalUrl($url = '',$fields_string = '',&$ErrorInfo = '') {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 10,    // time-out on connect
            CURLOPT_POSTFIELDS     => $fields_string,
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content  = curl_exec($ch);
        $ErrorInfo = curl_error($ch);
        curl_close($ch);
        return $content;
    }

    /**
     * @return ?string The current client's IP address
     */
    public static function getClientIPAddress() {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        if (isset($_SERVER["REMOTE_ADDR"])) {
            return $_SERVER["REMOTE_ADDR"];
        }
        return null;
    }

    //region File and folder related functionality

    /**
     * @param int $Index. The index of the url parameter. If -1 is passed, the all the url components are returned as an array
     * @return array|null. Returns the url parameter at the provided index.
     */
    public static function getPathInfo($Index = 0) {
        if (!isset($_SERVER["PATH_INFO"])) {
            return null;
        }
        $CurrentPathInfo = $_SERVER["PATH_INFO"];
        $CurrentPathInfoArray = array_values(array_filter(explode("/", $CurrentPathInfo)));
        if ($Index == -1) {
            return $CurrentPathInfoArray;
        }
        if (isset($CurrentPathInfoArray[$Index])) {
            return $CurrentPathInfoArray[$Index];
        } else {
            return null;
        }
    }
    /**
     * Same as mkdir but correctly implements directory recursion.
     * At its core, it will use the php MKDIR function.
     * This method does no special error handling.  If you want to use special error handlers,
     * be sure to set that up BEFORE calling MakeDirectory.
     *
     * @param         string         $strPath actual path of the directoy you want created
     * @param         integer        $intMode optional mode
     * @return         boolean        the return flag from mkdir
     */
    public static function MakeDirectory($strPath, $intMode = null) {
        if (is_dir($strPath)) {
            // Directory Already Exists
            return true;
        }

        // Check to make sure the parent(s) exist, or create if not
        if (!FrameworkFunctions::MakeDirectory(dirname($strPath), $intMode)) {
            return false;
        }

        if (PHP_OS != "Linux") {
            // Create the current node/directory, and return its result
            $blnReturn = mkdir($strPath);

            if ($blnReturn && !is_null($intMode)) {
                // Manually CHMOD to $intMode (if applicable)
                // mkdir doesn't do it for mac, and this will error on windows
                // Therefore, ignore any errors that creep up
                set_error_handler(null);
                chmod($strPath, $intMode);
                restore_error_handler();
            }
        } else {
            $blnReturn = mkdir($strPath, $intMode);
        }

        return $blnReturn;
    }

    /**
     * Returns the entire app size, including the db as a string
     * @return string
     */
    public static function getAppSize() {
        return FrameworkFunctions::getSizeSymbolByQuantity(FrameworkFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR)+FrameworkFunctions::getCompleteDatabaseSizeInBytes());
    }

    /**
     * Returns the entire app size, including the db as a float
     * @return int
     */
    public static function getAppSizeInBytes() {
        return FrameworkFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR)+FrameworkFunctions::getCompleteDatabaseSizeInBytes();
    }

    /**
     * Returns the entire app size, excluding the db as a string
     * @return string
     */
    public static function getAppStorageUsage() {
        return FrameworkFunctions::getSizeSymbolByQuantity(FrameworkFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR));
    }

    /**
     * * Returns the entire app size, excluding the db as an int
     * @return int
     */
    public static function getAppStorageUsageInBytes() {
        return FrameworkFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR);
    }

    /**
     * Provides a symbol based on the size provided
     * @param $bytes
     * @return string
     */
    public static function getSizeSymbolByQuantity($bytes) {
        $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $exp = floor(log($bytes)/log(1024));

        return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
    }

    /**
     * Provides the size on disk of the given path
     * @param $path
     * @return int
     */
    public static function getFolderSize($path) {
        $total_size = 0;
        $files = scandir($path);
        $cleanPath = rtrim($path, '/'). '/';

        foreach($files as $t) {
            if ($t<>"." && $t<>"..") {
                $currentFile = $cleanPath . $t;
                if (is_dir($currentFile)) {
                    $size = FrameworkFunctions::getFolderSize($currentFile);
                    $total_size += $size;
                }
                else {
                    $size = filesize($currentFile);
                    $total_size += $size;
                }
            }
        }

        return $total_size;
    }
    //endregion

    //region Database related functionality
    /**
     * This call will initialize the database connection(s) as defined by
     * the constants DB_CONNECTION_X, where "X" is the index number of a
     * particular database connection.
     *
     * @return void
     */
    public static function InitializeDatabaseConnections() {
        $ModuleArray = json_decode(APP_MODULES_STR);
        $intMaxIndex = FrameworkFunctions::getDataSetSize($ModuleArray);
        for ($intIndex = 1; $intIndex <= $intMaxIndex; $intIndex++) {
            $strConstantPrefix = strtoupper($ModuleArray[$intIndex-1]);
            $strConstantName = $strConstantPrefix."_DATABASE_SERVER_STR";

            if (defined($strConstantName)) {
                $objConfigArray['adapter'] = 'MySqli5';
                $objConfigArray['server'] = constant($strConstantName);
                $objConfigArray['port'] = constant($strConstantPrefix."_DATABASE_PORT_STR");
                $objConfigArray['database'] = constant($strConstantPrefix."_DATABASE_NAME_STR");
                $objConfigArray['username'] = constant($strConstantPrefix."_DATABASE_USER_STR");
                $objConfigArray['password'] = constant($strConstantPrefix."_DATABASE_PASSWORD_STR");
                $objConfigArray['cert_path'] = constant($strConstantPrefix."_DATABASE_SERVER_SSL_CERT_STR");
                $objConfigArray['profiling'] = false;
                $objConfigArray['dateformat'] = null;
                $objConfigArray['caching'] = false;

                $strDatabaseType = 'dx' . $objConfigArray['adapter'] . 'Database';
                FrameworkFunctions::$Database[$intIndex] = new $strDatabaseType($intIndex, $objConfigArray);
            }
        }
    }

    /**
     * Gets the database size as a string
     * @return string
     */
    public static function getDatabaseSize($DatabaseIndex = 0) {
        return FrameworkFunctions::getSizeSymbolByQuantity(FrameworkFunctions::getDatabaseSizeInBytes($DatabaseIndex));
    }

    /**
     * Gets the total of all configured databases' size in bytes
     * @return int
     */
    public static function getCompleteDatabaseSizeInBytes() {
        $TotalSizeInt = 0;
        for($i = 0;$i < FrameworkFunctions::getDataSetSize(FrameworkFunctions::$Database);$i++) {
            $TotalSizeInt += FrameworkFunctions::getDatabaseSizeInBytes($i);
        }
        return $TotalSizeInt;
    }

    /**
     * Gets the database size as an int
     * @return int
     */
    public static function getDatabaseSizeInBytes($DatabaseIndex = 0) {
        if (!isset(FrameworkFunctions::$Database[$DatabaseIndex])) {
            throw new Exception("Database at index $DatabaseIndex does not exists!");
        }
        $dbArray = FrameworkFunctions::$Database[$DatabaseIndex];
        $host = $dbArray['server'];
        $username = $dbArray['username'];
        $password = $dbArray['password'];
        $db_name = $dbArray['database'];

        $link = mysqli_connect("$host","$username","$password","$db_name")
        or die("Error " . mysqli_error($link));
        mysqli_select_db($link,"$db_name");
        $q = mysqli_query($link,"SHOW TABLE STATUS");
        $size = 0;
        while($row = mysqli_fetch_array($q)) {
            $size += $row["Data_length"] + $row["Index_length"];
        }
        return $size;
    }
    //endregion

    /**
     * Global/Central HtmlEntities command to perform the PHP equivalent of htmlentities.
     * Feel free to override to specify encoding/quoting specific preferences (e.g. ENT_QUOTES/ENT_NOQUOTES, etc.)
     *
     * This method is also used by the global print "_p" function.
     *
     * @param string $strText text string to perform html escaping
     * @return string the html escaped string
     */
    public static function HtmlEntities($strText) {
        return htmlentities($strText, ENT_COMPAT, APP_ENCODING_TYPE_STR);
    }

    /**
     * This is called by the PHP5 Autoloader.  This static method can be overridden.
     *
     * @param $strClassName
     * @return boolean whether or not a class was found / included
     */
    public static function Autoload($strClassName) {
        if (isset(FrameworkFunctions::$ClassFile[strtolower($strClassName)])) {
            include_once(FrameworkFunctions::$ClassFile[strtolower($strClassName)]);
            return true;
        }

        return false;
    }

    /**
     * Generates a random string with a default length of 200 characters
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 200) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    /**
     * Generates a random string that is an md5 hash of the current timestamp concatenated with 5 random characters
     * @return string
     */
    public static function generateTimeBasedRandomString() {
        return md5(dxDateTime::Now()->getTimestamp().self::generateRandomString(5));
    }

    /**
     * @param string $InputString
     * @param string $SplitChar
     * @return mixed|string
     */
    public static function getCamelCaseSplitted($InputString = '', $SplitChar = ' ') {
        $Result = trim(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $InputString));
        $FinalResult = '';
        if (($SplitChar != ' ') && (strlen($Result) > 0)){
            $Words = explode(' ',$Result);
            foreach ($Words as $Word) {
                $FinalResult .= $Word.$SplitChar;
            }
        } else {
            return $Result;
        }

        if (strlen($FinalResult) > 0)
            return substr($FinalResult,0,strlen($FinalResult)-strlen($SplitChar));
        return $FinalResult;
    }

    /**
     * @param string $String
     * @param string $AllowableCharacters
     * @return null|string|string[]
     */
    public static function removeSpecialCharactersFromString($String = '', $AllowableCharacters = '') {
        $String = str_replace(' ', '-', $String); // Replaces all spaces with hyphens.
        return preg_replace("/[^A-Za-z0-9\-$AllowableCharacters]/", '', $String); // Removes special chars.
    }

    /**
     * @param string $String
     * @return mixed
     */
    public static function removeSpacesFromString($String = '') {
        return str_replace(' ', '', $String);
    }

    /**
     * @return null
     */
    public static function getCurrentAuthenticationToken() {
        if (isset($_POST['AuthenticationToken'])) {
            return $_POST['AuthenticationToken'];
        }
        if (isset($_SESSION['AuthenticationToken'])) {
            return $_SESSION['AuthenticationToken'];
        }
        return null;
    }

    /**
     * @param string $TokenStr
     * @return ClientAuthenticationToken
     */
    public static function getCurrentAuthTokenObject($TokenStr = '') {
        $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($TokenStr);
        if (is_null($ClientAuthenticationTokenObj)) {
            $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByExpiredToken($TokenStr);
        }
        return $ClientAuthenticationTokenObj;
    }
    
    /**
     * Links all valid PushRegistrations to the provided Account. This is called during authentication to ensure that
     * we can send push notifications to specific users and not just devices
     * @param ClientAuthenticationToken|null $ClientAuthenticationTokenObj
     * @param Account|null $AccountObj
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public static function linkPushRegistrationsToAccount(
        ClientAuthenticationToken $ClientAuthenticationTokenObj = null,
        Account $AccountObj = null) {
        if (is_null($ClientAuthenticationTokenObj) || is_null($AccountObj)) {return;}
        
        $PushRegistrations = PushRegistration::QueryArray(
            dxQ::Equal(
                dxQN::PushRegistration()->ClientAuthenticationTokenObject->Id, $ClientAuthenticationTokenObj->Id)
        );
        if (self::getDataSetSize($PushRegistrations) > 0) {
            foreach ($PushRegistrations as $pushRegistration) {
                $pushRegistration->AccountObject = $AccountObj;
                $pushRegistration->Save();
            }
        }
    }
    
    /**
     * @param string $attr The attribute you would like to receive
     * @return string
     */
    public static function getCurrentAccountAttribute($attr = 'Id') {
        $CurrentAccountObj = null;
        $CurrentAuthenticationToken = self::getCurrentAuthenticationToken();
        if (!is_null($CurrentAuthenticationToken)) {
            $ClientAuthenticationTokenObj = self::getCurrentAuthTokenObject($CurrentAuthenticationToken);
            if (is_null($ClientAuthenticationTokenObj) ||
                is_null($ClientAuthenticationTokenObj->ClientConnectionObject) ||
                is_null($ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject)) {
                if (strtoupper($attr) == 'ID') {
                    return -1;
                }
                return 'Anonymous';
            }
            $CurrentAccountObj = $ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject;
        }
        if (is_null($CurrentAccountObj))
            return 'Anonymous';
        if (strtoupper($attr) == 'ID')
            return $CurrentAccountObj->Id;
        if (strtoupper($attr) == 'EMAILADDRESS')
            return $CurrentAccountObj->EmailAddress;
        if (strtoupper($attr) == 'FULLNAME')
            return $CurrentAccountObj->FullName;
        if (strtoupper($attr) == 'FIRSTNAME')
            return $CurrentAccountObj->FirstName;
        if (strtoupper($attr) == 'LASTNAME')
            return $CurrentAccountObj->LastName;
        if (strtoupper($attr) == 'USERROLE') {
            if (!is_null($CurrentAccountObj->UserRoleObject)) {
                return $CurrentAccountObj->UserRoleObject->Role;
            }
        }
        return $CurrentAccountObj->__get($attr);
    }

    /**
     * @return bool
     */
    public static function logoutCurrentAccount() {
        $CurrentAuthenticationToken = self::getCurrentAuthenticationToken();
        if (!is_null($CurrentAuthenticationToken)) {
            $ClientAuthenticationTokenObj = self::getCurrentAuthTokenObject($CurrentAuthenticationToken);
            if (is_null($ClientAuthenticationTokenObj) ||
                is_null($ClientAuthenticationTokenObj->ClientConnectionObject) ||
                is_null($ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject)) {
                return true;
            }
            $ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject = null;
            $ClientConnectionObject = $ClientAuthenticationTokenObj->ClientConnectionObject;
            $ClientConnectionObject->AccountObject = null;
            try {
                $ClientConnectionObject->Save();
                return true;
            } catch (dxCallerException $e) {

            }
            return false;
        }
        return true;
    }

    /**
     * Wrapper function for getCurrentAccountAttribute
     */
    public static function getCurrentAccountId() {
        $CurrentAccountId = FrameworkFunctions::getCurrentAccountAttribute();
        if (!is_numeric($CurrentAccountId)) {
            return -1;
        }
        return $CurrentAccountId;
    }

    /**
     * @return string
     */
    public static function getCurrentUserEmailForAudit() {
        return FrameworkFunctions::getCurrentAccountAttribute('EmailAddress');
    }

    /**
     * @return null|string
     */
    public static function getCurrentUserRole() {
        if (isset($_SESSION["divblox_admin_access"])) {
            return 'dxAdmin';
        }
        $CurrentAccount = Account::Load(FrameworkFunctions::getCurrentAccountAttribute("ID"));
        if (is_null($CurrentAccount))
            return 'Anonymous';
        if (!is_null($CurrentAccount->UserRoleObject)) {
            return $CurrentAccount->UserRoleObject->Role;
        }
        return 'Anonymous';
    }

    /**
     * @param string $ContentType
     */
    public static function printCleanOutput($ContentType = "Content-Type: text/plain") {
        $Output = ob_get_clean();
        header($ContentType);
        echo trim($Output);
    }

    /**
     * @return array
     */
    public static function get_divblox_Attributes() {
        return ["Id","SearchMetaInfo","LastUpdated","ObjectOwner"];
    }

    /**
     * @return string
     */
    public static function generateUniqueClientAuthenticationToken() {
        $TokenCandidate = '';
        $Done = false;
        while (!$Done) {
            $TokenCandidate = md5(dxDateTime::Now()->getTimestamp().FrameworkFunctions::generateRandomString(20));
            $ExistingTokenObj = self::getCurrentAuthTokenObject($TokenCandidate);
            if (is_null($ExistingTokenObj)) {
                $Done = true;
            }
        }
        return $TokenCandidate;
    }

    //region Native support related

    /**
     * @param null $InternalUniqueId
     * @param null $RegistrationId
     * @param null $DeviceUuid
     * @param null $DevicePlatform
     * @param string $DeviceOs
     * @param null $RegistrationDateTime
     * @param string $Status
     * @param null $ErrorInfo
     * @return bool
     * @throws dxCallerException
     */
    public static function updatePushRegistration($InternalUniqueId = null,
                                                  $RegistrationId = null,
                                                  $DeviceUuid = null,
                                                  $DevicePlatform = null,
                                                  $DeviceOs = 'NOT SPECIFIED',
                                                  $RegistrationDateTime = null,
                                                  $Status = NativeDevicePushRegistrationStatus::ACTIVE_STR,
                                                  &$ErrorInfo = null) {
        $ErrorInfo = array();
        $isUpdating = false;
        $PushRegistrationObj = null;
        if (is_null($InternalUniqueId)) {
            if (is_null($DeviceUuid)) {
                array_push($ErrorInfo,"No device uuid provided");
                return false;
            }
            if (is_null($DevicePlatform)) {
                array_push($ErrorInfo,"No device platform provided");
                return false;
            }
            $PushRegistrationObj = PushRegistration::QuerySingle(dxQ::AndCondition(
                dxQ::Equal(dxQN::PushRegistration()->DeviceUuid, $DeviceUuid),
                dxQ::Equal(dxQN::PushRegistration()->DevicePlatform, $DevicePlatform)
            ));
            if (!is_null($PushRegistrationObj)) {
                $isUpdating = true;
            } else {
                //JGL: This means we are creating a new Push Registration
                $PushRegistrationObj = new PushRegistration();
            }
        } else {
            $isUpdating = true;
            $PushRegistrationObj = PushRegistration::LoadByInternalUniqueId($InternalUniqueId);
            if (is_null($PushRegistrationObj)) {
                array_push($ErrorInfo,"Invalid internal id provided");
                return false;
            }
        }
        if (is_null($RegistrationId)) {
            array_push($ErrorInfo,"No registration id provided");
            return false;
        }
        
        if (is_null($RegistrationDateTime)) {
            array_push($ErrorInfo,"No registration time provided");
            $RegistrationDateTime = dxDateTime::Now();
        }
        
        if (!$isUpdating) {
            $PushRegistrationObj->DeviceUuid = $DeviceUuid;
            $PushRegistrationObj->DevicePlatform = $DevicePlatform;
            $PushRegistrationObj->DeviceOs = $DeviceOs;
            $PushRegistrationObj->RegistrationDateTime = $RegistrationDateTime;
            $PushRegistrationObj->InternalUniqueId = self::generateUniquePushRegistrationId();
        }
        $PushRegistrationObj->RegistrationId = $RegistrationId;
        $PushRegistrationObj->RegistrationStatus = $Status;
        $ClientAuthenticationTokenObj = self::getCurrentAuthTokenObject(self::getCurrentAuthenticationToken());
        if (!is_null($ClientAuthenticationTokenObj)) {
            $PushRegistrationObj->ClientAuthenticationTokenObject = $ClientAuthenticationTokenObj;
            if (!is_null($ClientAuthenticationTokenObj->ClientConnectionObject)) {
                if (!is_null($ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject)) {
                    $PushRegistrationObj->AccountObject = $ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject;
                }
            }
        }
        
        $PushRegistrationObj->Save(false,true);
        $ErrorInfo = [$PushRegistrationObj->Id];
        return true;
    }

    /**
     * @return string
     */
    public static function generateUniquePushRegistrationId() {
        $TokenCandidate = '';
        $Done = false;
        while (!$Done) {
            $TokenCandidate = md5(dxDateTime::Now()->getTimestamp().FrameworkFunctions::generateRandomString(20));
            $ExistingTokenObj = PushRegistration::LoadByInternalUniqueId($TokenCandidate);
            if (is_null($ExistingTokenObj)) {
                $Done = true;
            }
        }
        return $TokenCandidate;
    }

    /**
     * @param null $InternalUniqueId
     * @param string $Priority
     * @param string $Title
     * @param string $Message
     * @param string $LaunchImage
     * @param string $Sound
     * @param int $NotificationId
     * @param string $Category
     * @param string $CustomKey1
     * @param string $CustomKey2
     * @param array $ErrorInfo
     * @return bool
     */
    public static function deliverSinglePushPayload($InternalUniqueId = null,
                                              $Priority = NativePushPriority::HIGH_STR, /*Android only*/
                                              $TitleStr = 'A short string describing the purpose of the notification',
                                              $BodyStr = 'The text of the alert message',
                                              $LaunchImage = ''/*iOS Only: The filename of an image file in the app bundle, with or without the filename extension. The image is used as the launch image when users tap the action button or move the action slider*/,
                                              $SoundStr = 'default'/* play default sound ... or "soundname"*/,
                                              $NotificationId = 1/*unique ID for the message, used for grouping*/,
                                              $Category = 'identifier'/*iOS Only: Provide this key with a string value that represents the notification’s type*/,
                                              $CustomKey1 = '',
                                              $CustomKey2 = '',
                                              &$ErrorInfo = []) {
        if (is_null($InternalUniqueId)) {
            array_push($ErrorInfo,"Invalid internal id provided");
            return false;
        }
        
        $PushRegistrationObj = PushRegistration::LoadByInternalUniqueId($InternalUniqueId);
        if (is_null($PushRegistrationObj)) {
            array_push($ErrorInfo,"Invalid internal id provided. Push registration not found.");
            return false;
        }
        $BadgeCountInt = 1;
        if (is_numeric($PushRegistrationObj->CurrentBadgeCount)) {
            if ($PushRegistrationObj->CurrentBadgeCount > 0) {
                $BadgeCountInt += $PushRegistrationObj->CurrentBadgeCount;
            }
        }
        $NotificationObj = array('title' => $TitleStr , 'body' => $BodyStr, 'sound' => $SoundStr, 'badge' => $BadgeCountInt);
        $PostBodyArray = array('to' => $PushRegistrationObj->RegistrationId, 'notification' => $NotificationObj,'priority'=>'high');
        $EncodedPostBodyArrayStr = json_encode($PostBodyArray);
        $HeadersArray = array();
        //header with content_type & api key
        $HeadersArray[] = 'Content-Type: application/json';
        $HeadersArray[] = 'Authorization: key='. FIREBASE_SERVER_KEY_STR;
        $CurlObj = curl_init();
        curl_setopt($CurlObj, CURLOPT_URL, FIREBASE_FCM_ENDPOINT_STR);
        curl_setopt($CurlObj, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($CurlObj, CURLOPT_POSTFIELDS, $EncodedPostBodyArrayStr);
        curl_setopt($CurlObj, CURLOPT_HTTPHEADER, $HeadersArray);
        curl_setopt($CurlObj, CURLOPT_RETURNTRANSFER,true);
        //Send the request
        $ResponseStr = curl_exec($CurlObj);
        //Close request
        $ErrorStr = curl_error($CurlObj);
        curl_close($CurlObj);
        if ($ResponseStr === FALSE) {
            throw new Exception("FCM Send Error: $ErrorStr");
        }
        $PushRegistrationObj->CurrentBadgeCount = $BadgeCountInt;
        $PushRegistrationObj->Save();
        return true;
    }
    
    /**
     * @param array $PushTokenArray
     * @param string $TitleStr
     * @param string $BodyStr
     * @throws Exception
     */
    public static function deliverBatchedPushPayload($PushTokenArray = [],
                                                     $TitleStr = "Notification title",
                                                     $BodyStr = "Notification body") {
        $NotificationObj = array('title' => $TitleStr , 'body' => $BodyStr, 'sound' => 'default');
        $PostBodyArray = array('registration_ids' => $PushTokenArray, 'notification' => $NotificationObj,'priority'=> NativePushPriority::HIGH_STR);
        $EncodedPostBodyArrayStr = json_encode($PostBodyArray);
        $HeadersArray = array();
        //header with content_type & api key
        $HeadersArray[] = 'Content-Type: application/json';
        $HeadersArray[] = 'Authorization: key='. FIREBASE_SERVER_KEY_STR;
        $CurlObj = curl_init();
        curl_setopt($CurlObj, CURLOPT_URL, FIREBASE_FCM_ENDPOINT_STR);
        curl_setopt($CurlObj, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($CurlObj, CURLOPT_POSTFIELDS, $EncodedPostBodyArrayStr);
        curl_setopt($CurlObj, CURLOPT_HTTPHEADER, $HeadersArray);
        curl_setopt($CurlObj, CURLOPT_RETURNTRANSFER,true);
        //Send the request
        $ResponseStr = curl_exec($CurlObj);
        //Close request
        $ErrorStr = curl_error($CurlObj);
        curl_close($CurlObj);
        if ($ResponseStr === FALSE) {
            throw new Exception("FCM Send Error: $ErrorStr");
        }
    }
    //endregion
}