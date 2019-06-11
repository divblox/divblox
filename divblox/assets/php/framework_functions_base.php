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
    die(json_encode($ErrorArray));
}
function divbloxErrorHandler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

abstract class FrameworkFunctions_base {
    /**
     * @var
     */
    public static $Database;
    public static $ClassFile;
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
     * @return int: Will return 0 if not a valid data set and 1 if data set is scalar. Otherwise it will return ProjectFunctions::getDataSetSize(DataSet);
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
        if (ProjectFunctions::isSecure())
            $protocol = 'https://';
        $www = ProjectFunctions::HostHasWWW()?'www.':'';
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
        if ($url) {
            if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                return true;
            }
        }
        return false;
    }
    /**
     * This function posts to a url using curl and returns the content of the url
     * @param $url
     * @param $fields_string provided as variable=value&variable=value
     * @param string $client
     * @param int $DefaultTimeout The default amount of seconds to wait for a response from the remote server
     * @return mixed
     */
    public static function PostToUrl($url,$fields_string = '',$client = APP_NAME_STR,$DefaultTimeout = 120) {
        if (!ProjectFunctions::isValidUrl($url))
            return '';
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
        if (!ProjectFunctions::MakeDirectory(dirname($strPath), $intMode)) {
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
        return ProjectFunctions::getSizeSymbolByQuantity(ProjectFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR)+ProjectFunctions::getCompleteDatabaseSizeInBytes());
    }

    /**
     * Returns the entire app size, including the db as a float
     * @return int
     */
    public static function getAppSizeInBytes() {
        return ProjectFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR)+ProjectFunctions::getCompleteDatabaseSizeInBytes();
    }

    /**
     * Returns the entire app size, excluding the db as a string
     * @return string
     */
    public static function getAppStorageUsage() {
        return ProjectFunctions::getSizeSymbolByQuantity(ProjectFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR));
    }

    /**
     * * Returns the entire app size, excluding the db as an int
     * @return int
     */
    public static function getAppStorageUsageInBytes() {
        return ProjectFunctions::getFolderSize(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR);
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
                    $size = ProjectFunctions::getFolderSize($currentFile);
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
        $intMaxIndex = ProjectFunctions::getDataSetSize($ModuleArray);
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
                $objConfigArray['profiling'] = false;
                $objConfigArray['dateformat'] = null;
                $objConfigArray['caching'] = false;

                $strDatabaseType = 'dx' . $objConfigArray['adapter'] . 'Database';
                ProjectFunctions::$Database[$intIndex] = new $strDatabaseType($intIndex, $objConfigArray);
            }
        }
    }

    /**
     * Gets the database size as a string
     * @return string
     */
    public static function getDatabaseSize($DatabaseIndex = 0) {
        return ProjectFunctions::getSizeSymbolByQuantity(ProjectFunctions::getDatabaseSizeInBytes($DatabaseIndex));
    }

    /**
     * Gets the total of all configured databases' size in bytes
     * @return int
     */
    public static function getCompleteDatabaseSizeInBytes() {
        $TotalSizeInt = 0;
        for($i = 0;$i < FrameworkFunctions::getDataSetSize(ProjectFunctions::$Database);$i++) {
            $TotalSizeInt += ProjectFunctions::getDatabaseSizeInBytes($i);
        }
        return $TotalSizeInt;
    }

    /**
     * Gets the database size as an int
     * @return int
     */
    public static function getDatabaseSizeInBytes($DatabaseIndex = 0) {
        if (!isset(ProjectFunctions::$Database[$DatabaseIndex])) {
            throw new Exception("Database at index $DatabaseIndex does not exists!");
        }
        $dbArray = ProjectFunctions::$Database[$DatabaseIndex];
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
        if (isset(ProjectFunctions::$ClassFile[strtolower($strClassName)])) {
            include_once(ProjectFunctions::$ClassFile[strtolower($strClassName)]);
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
     * @param string $attr The attribute you would like to receive
     * @return string
     */
    public static function getCurrentAccountAttribute($attr = 'Id') {
        $CurrentAccountObj = null;
        $CurrentAuthenticationToken = self::getCurrentAuthenticationToken();
        if (!is_null($CurrentAuthenticationToken)) {
            $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($CurrentAuthenticationToken);
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
            $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($CurrentAuthenticationToken);
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
        $CurrentAccountId = ProjectFunctions::getCurrentAccountAttribute();
        if (!is_numeric($CurrentAccountId)) {
            return -1;
        }
        return $CurrentAccountId;
    }

    /**
     * @return string
     */
    public static function getCurrentUserEmailForAudit() {
        return ProjectFunctions::getCurrentAccountAttribute('EmailAddress');
    }

    /**
     * @return null|string
     */
    public static function getCurrentUserRole() {
        if (isset($_SESSION["divblox_admin_access"])) {
            return 'dxAdmin';
        }
        $CurrentAccount = Account::Load(ProjectFunctions::getCurrentAccountAttribute("ID"));
        if (is_null($CurrentAccount))
            return 'Anonymous';
        if (!is_null($CurrentAccount->UserRoleObject)) {
            return $CurrentAccount->UserRoleObject->Role;
        }
        return 'Anonymous';
    }

    public static function printCleanOutput($ContentType = "Content-Type: text/plain") {
        $Output = ob_get_clean();
        header($ContentType);
        echo trim($Output);
    }

    public static function get_divblox_Attributes() {
        return ["Id","SearchMetaInfo","LastUpdated","ObjectOwner"];
    }

    public static function generateUniqueClientAuthenticationToken() {
        $TokenCandidate = '';
        $Done = false;
        while (!$Done) {
            $TokenCandidate = md5(dxDateTime::Now()->getTimestamp().ProjectFunctions::generateRandomString(20));
            $ExistingTokenObj = ClientAuthenticationToken::LoadByToken($TokenCandidate);
            if (is_null($ExistingTokenObj)) {
                $Done = true;
            }
        }
        return $TokenCandidate;
    }

    //region Native support related
    public static function updatePushRegistration($InternalUniqueId = null,
                                                  $RegistrationId = null,
                                                  $DeviceUuid = null,
                                                  $DevicePlatform = null,
                                                  $DeviceOs = 'NOT SPECIFIED',
                                                  $RegistrationDateTime = null,
                                                  $Status = NativeDevicePushRegistrationStatus::ACTIVE_STR,
                                                  $AuthenticationToken = null,
                                                  &$ErrorInfo = null) {
        $ErrorInfo = array();
        $isUpdating = false;
        $PushRegistrationObj = null;
        if (is_null($InternalUniqueId)) {
            //JGL: This means we are creating a new Push Registration
            $PushRegistrationObj = new PushRegistration();
            if (is_null($DeviceUuid)) {
                array_push($ErrorInfo,"No device uuid provided");
                return false;
            }
            if (is_null($DevicePlatform)) {
                array_push($ErrorInfo,"No device platform provided");
                return false;
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
        if (is_null($AuthenticationToken)) {
            array_push($ErrorInfo,"No authentication token provided");
        } else {
            $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($AuthenticationToken);
            if (!is_null($ClientAuthenticationTokenObj)) {
                $PushRegistrationObj->ClientAuthenticationTokenObject = $ClientAuthenticationTokenObj;
                if (!is_null($ClientAuthenticationTokenObj->ClientConnectionObject)) {
                    if (!is_null($ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject)) {
                        $PushRegistrationObj->AccountObject = $ClientAuthenticationTokenObj->ClientConnectionObject->AccountObject;
                    }
                }
            }
        }
        $PushRegistrationObj->Save(false,true);
        $ErrorInfo = [$PushRegistrationObj->Id];
        return true;
    }
    public static function generateUniquePushRegistrationId() {
        $TokenCandidate = '';
        $Done = false;
        while (!$Done) {
            $TokenCandidate = md5(dxDateTime::Now()->getTimestamp().ProjectFunctions::generateRandomString(20));
            $ExistingTokenObj = PushRegistration::LoadByInternalUniqueId($TokenCandidate);
            if (is_null($ExistingTokenObj)) {
                $Done = true;
            }
        }
        return $TokenCandidate;
    }
    public static function deliverPushPayload($InternalUniqueId = null,
                                              $Priority = NativePushPriority::NORMAL_STR, /*Android only*/
                                              $Title = 'A short string describing the purpose of the notification',
                                              $Message = 'The text of the alert message',
                                              $LaunchImage = ''/*iOS Only: The filename of an image file in the app bundle, with or without the filename extension. The image is used as the launch image when users tap the action button or move the action slider*/,
                                              $BadgeCount = 1,
                                              $Sound = 'default'/* play default sound ... or "soundname"*/,
                                              $NotificationId = 1/*unique ID for the message, used for grouping*/,
                                              $Category = 'identifier'/*iOS Only: Provide this key with a string value that represents the notification’s type*/,
                                              $CustomKey1 = '',
                                              $CustomKey2 = '',
                                              &$ErrorInfo = []) {
        if (is_null($InternalUniqueId)) {
            array_push($ErrorInfo,"Invalid internal id provided");
            return false;
        }
        array_push($ErrorInfo,"Not implemented");
        return false;
        //TODO:Implement this
    }
    //endregion

}