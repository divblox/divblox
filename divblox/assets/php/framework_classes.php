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
include(FRAMEWORK_ROOT_STR."/assets/php/framework_classes_base.php");
//region Component controller related
/**
 * Class ProjectComponentController
 * Responsible for managing the framework-level behaviour of all server-side component scripts
 */
class ComponentController extends ComponentController_base {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
//endregion
//region Security and Authentication related
abstract class AccessManager extends AccessManager_Base {
     public static function getObjectAccess($AccountId = -1, $ObjectType = null, $ObjectId = -1) {
         $ReturnArray = parent::getObjectAccess($AccountId,$ObjectType,$ObjectId);
         // TODO: Override base access (At framework level) here per object type or leave if no special functionality is required
         //region Example override
         // E.g Let's say that only administrators can delete objects of type AuditLogEntry:
         /*if ($ObjectType == "AuditLogEntry") {
             $AccountObj = Account::Load($AccountId);
             if (is_null($AccountObj)) {
                 return $ReturnArray; // JGL: Return the default permissions (Create & Read)
             }
             $UserRoleObj = $AccountObj->UserRoleObject;
             if (is_null($UserRoleObj)) {
                 return $ReturnArray; // JGL: Return the default permissions (Create & Read)
             }
             if ($UserRoleObj == "Administrator") {
                 return [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
             }
         }*/
         //endregion
         return $ReturnArray;
     }
     public static function getComponentAccess($AccountId = -1, $ComponentName = '') {
         $InitialReturn = parent::getComponentAccess($AccountId,$ComponentName);
         // Framework level overrides happen here...
         return $InitialReturn;
     }
}
//endregion
//region Project API related
abstract class PublicApi_Base {
    // TODO: Build some base functions here like authenticate. This will use an API key entity that will have additional
    // access entities for api end points and operations

    public static $ApiResultArray = []; // This should be populated with the api's result as key/value pairs
    public static $ApiDescription;
    public static $ApiEndPointName;
    public static $AvailableOperationsArray = []; // This should be populated with the api's available operations (function names)
    public static $NamedOperationsArray = []; // This should be populated with the api's available operations names. Used to determine if an api key has access to the operation
    public static $DescribedOperationsArray = []; // This should be populated with the api's available operations names. Used to determine if an api key has access to the operation
    public static $InputOperation;
    public static $AuthenticationToken;
    public static $UserAgent;
    public static function initApi($ApiDescriptionStr = "API Description",$EndPointNameStr = "API Endpoint") {
        $_SESSION["API_CALL_ACTIVE"] = 1;
        $_SESSION["API_CALL_KEY"] = self::getInputParameter("api_key");
        self::$ApiResultArray["Result"] = "Failed";
        self::$InputOperation = ProjectFunctions::getPathInfo(0);
        if (is_null(self::$InputOperation)) {
            if (isset($_GET['operation'])) {
                self::$InputOperation = $_GET['operation'];
            }
            if (is_null(self::$InputOperation)) {
                self::addApiOutput("Result","Failed");
                self::addApiOutput("Message","No operation provided. Try providing 'doc' at the end of the url to see documentation");
                self::printApiResult();
            }
        }
        self::$ApiDescription = $ApiDescriptionStr;
        self::$ApiEndPointName = $EndPointNameStr;
        if (ProjectFunctions::getPathInfo(0) == "doc") {
            $DocumentationHtml = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_documentation_wrapper.html');
            $DocumentationHtml = str_replace('{api_endpoint}', self::$ApiEndPointName, $DocumentationHtml);
            $DocumentationHtml = str_replace('{introduction}', self::$ApiDescription, $DocumentationHtml);
            
            $OperationHtml = '';
            if (ProjectFunctions::getDataSetSize(self::$AvailableOperationsArray) > 0) {
                foreach(self::$AvailableOperationsArray as $AvailableOperation => $DetailArray) {
                    $OperationTemplateHtml = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_operation_wrapper.html');
                    $OperationTemplateHtml = str_replace('{operation_name}', self::$NamedOperationsArray[$AvailableOperation], $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_header_id}', $AvailableOperation.'_header', $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_content_id}', $AvailableOperation.'_content', $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_description}', self::$DescribedOperationsArray[$AvailableOperation], $OperationTemplateHtml);
                    $CurlInputListStr = '';
                    $InputListArray = [];
                    $ApiOperationCountInt = ApiOperation::QueryCount(dxQ::Equal(dxQueryN::ApiOperation()->OperationName, self::$NamedOperationsArray[$AvailableOperation]));
                    if ($ApiOperationCountInt > 0) {
                        $InputListArray["api_key"] = "[your api key]";
                        $CurlInputListStr .= "\
--header 'Content-Type: application/json' \
--data-raw '{";
                        $CurlInputListStr .= "\n    \"api_key\":\"[your api key]\",\n";
                        $OperationTemplateHtml = str_replace('{authentication_required_label}', '<span style="color:red;">This operation requires authentication using an API key</span>', $OperationTemplateHtml);
                    } else {
                        $OperationTemplateHtml = str_replace('{authentication_required_label}', '<span style="color:green;">This operation does not require authentication using an API key</span>', $OperationTemplateHtml);
                    }
                    foreach ($DetailArray[0] as $item) {
                        if (strlen($CurlInputListStr) == 0) {
                            $CurlInputListStr .= "\
--header 'Content-Type: application/json' \
--data-raw '{\n";
                        }
                        $CurlInputListStr .= "    \"$item\":\"[provided input value]\",\n";
                        $InputListArray[$item] = "[provided input value]";
                    }
                    $InputListArray["AuthenticationToken"] = "[Optional token that is used to identify a user or device]";
                    if (strlen($CurlInputListStr) > 0) {
                        $CurlInputListStr = substr($CurlInputListStr,0,strlen($CurlInputListStr)-2)."\n}'";
                    }
                    if (ProjectFunctions::getDataSetSize($InputListArray) > 0) {
                        $OperationTemplateHtml = str_replace('{operation_inputs}', json_encode($InputListArray,JSON_PRETTY_PRINT), $OperationTemplateHtml);
                    } else {
                        $OperationTemplateHtml = str_replace('{operation_inputs}', 'No inputs required', $OperationTemplateHtml);
                    }
                    
                    $OutputArray = array_merge(["Result" => "Success"],$DetailArray[1],["AuthenticationToken" => "[Optional token that should be sent along with the next request to identify the user or device]"]);
                    $OperationTemplateHtml = str_replace('{operation_outputs}', json_encode($OutputArray,JSON_PRETTY_PRINT), $OperationTemplateHtml);
                    
                    //Example Code Blocks
                    $ExampleCodeJquery = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_jquery.html');
                    $OperationTemplateHtml = str_replace('{example_code_jquery}', $ExampleCodeJquery, $OperationTemplateHtml);
                    $ExampleCodeJsFetch = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_js_fetch.html');
                    $OperationTemplateHtml = str_replace('{example_code_js_fetch}', $ExampleCodeJsFetch, $OperationTemplateHtml);
                    $ExampleCodeJsXhr = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_js_xhr.html');
                    $OperationTemplateHtml = str_replace('{example_code_js_xhr}', $ExampleCodeJsXhr, $OperationTemplateHtml);
                    $ExampleCodePhpCurl = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_php_curl.html');
                    $OperationTemplateHtml = str_replace('{example_code_php_curl}', $ExampleCodePhpCurl, $OperationTemplateHtml);
                    $ExampleCodePhpHttp2 = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_php_http2.html');
                    $OperationTemplateHtml = str_replace('{example_code_php_http2}', $ExampleCodePhpHttp2, $OperationTemplateHtml);
                    $ExampleCodePhpPecl = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_php_pecl.html');
                    $OperationTemplateHtml = str_replace('{example_code_php_pecl}', $ExampleCodePhpPecl, $OperationTemplateHtml);
                    $ExampleCodeHttp = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_http.html');
                    $OperationTemplateHtml = str_replace('{example_code_http}', $ExampleCodeHttp, $OperationTemplateHtml);
                    $ExampleCodeSwift = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_swift.html');
                    $OperationTemplateHtml = str_replace('{example_code_swift}', $ExampleCodeSwift, $OperationTemplateHtml);
                    $ExampleCodeObjectiveC = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_objective_c.html');
                    $OperationTemplateHtml = str_replace('{example_code_objective_c}', $ExampleCodeObjectiveC, $OperationTemplateHtml);
                    $ExampleCodeJavaOkHttp = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_java_okhttp.html');
                    $OperationTemplateHtml = str_replace('{example_code_java_okhttp}', $ExampleCodeJavaOkHttp, $OperationTemplateHtml);
                    $ExampleCodeJavaUnirest = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_java_unirest.html');
                    $OperationTemplateHtml = str_replace('{example_code_java_unirest}', $ExampleCodeJavaUnirest, $OperationTemplateHtml);
                    $ExampleCodeC = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_c.html');
                    $OperationTemplateHtml = str_replace('{example_code_c}', $ExampleCodeC, $OperationTemplateHtml);
                    $ExampleCodeCSharp = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_csharp.html');
                    $OperationTemplateHtml = str_replace('{example_code_csharp}', $ExampleCodeCSharp, $OperationTemplateHtml);
                    $ExampleCodeNodeJsRequest = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_nodejs_request.html');
                    $OperationTemplateHtml = str_replace('{example_code_nodejs_request}', $ExampleCodeNodeJsRequest, $OperationTemplateHtml);
                    $ExampleCodePythonRequests = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_python_requests.html');
                    $OperationTemplateHtml = str_replace('{example_code_python_requests}', $ExampleCodePythonRequests, $OperationTemplateHtml);
                    $ExampleCodeRuby = file_get_contents(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.'/divblox/assets/html_templates/api_example_code_ruby.html');
                    $OperationTemplateHtml = str_replace('{example_code_ruby}', $ExampleCodeRuby, $OperationTemplateHtml);
    
                    $OperationTemplateHtml = str_replace('{operation_curl_inputs}', $CurlInputListStr, $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_inputs_json}', json_encode($InputListArray), $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_inputs_json_escaped}', str_replace('"','\"',json_encode($InputListArray,JSON_PRETTY_PRINT)), $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_endpoint}', self::getApiEndpointUrl().'/'.$AvailableOperation, $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_endpoint_relative}', self::getApiEndpointRelativeUrl().'/'.$AvailableOperation, $OperationTemplateHtml);
                    $OperationTemplateHtml = str_replace('{operation_host}', self::getApiHost(), $OperationTemplateHtml);
                    $OperationHtml .= $OperationTemplateHtml;
                    
                }
            } else {
                $OperationHtml .= '<h4>No operations available</h4>';
            }
            $DocumentationHtml = str_replace('{operation_wrapper}', $OperationHtml, $DocumentationHtml);
            echo $DocumentationHtml;
            ProjectFunctions::printCleanOutput("Content-Type: text/html");
            die();
        }
        if (function_exists(PublicApi::getOperation())) {
            if (isset(self::$NamedOperationsArray[self::getOperation()])) {
                if (!is_null(self::$NamedOperationsArray[self::getOperation()])) {
                    $ApiOperationCountInt = ApiOperation::QueryCount(dxQ::Equal(dxQueryN::ApiOperation()->OperationName, self::$NamedOperationsArray[self::getOperation()]));
                    if ($ApiOperationCountInt > 0) {
                        // JGL: If we've reached this point, it means we need authentication to call this api operation
                        $OperationNameStr = self::$NamedOperationsArray[self::getOperation()];
                        $AllowedOperationNameCount = 0;
                        $ApiKeyStr = self::getInputParameter("api_key");
                        $ApiKeyObj = null;
                        if (is_null($ApiKeyStr)) {
                            self::addApiOutput("Result","Failed");
                            self::addApiOutput("Message","Not authorized. This operation requires an api key.");
                            self::printApiResult();
                        }
                        $ApiKeyObj = ApiKey::LoadByApiKey($ApiKeyStr);
                        if (!is_null($ApiKeyObj)) {
                            //JGL: First, let's check if the api key is currently valid:
                            if (!is_null($ApiKeyObj->ValidFromDate)) {
                                if ($ApiKeyObj->ValidFromDate > dxDateTime::Now()) {
                                    self::addApiOutput("Result","Failed");
                                    self::addApiOutput("Message","Not authorized.");
                                    self::printApiResult();
                                }
                            } else {
                                self::addApiOutput("Result","Failed");
                                self::addApiOutput("Message","Not authorized.");
                                self::printApiResult();
                            }
        
                            if (!is_null($ApiKeyObj->ValidToDate)) {
                                if ($ApiKeyObj->ValidToDate < dxDateTime::Now()) {
                                    self::addApiOutput("Result","Failed");
                                    self::addApiOutput("Message","Not authorized.");
                                    self::printApiResult();
                                }
                            }
        
                            $AllowedOperationNameCount = AllowedApiOperation::QueryCount(
                                dxQ::AndCondition(
                                    dxQ::Equal(dxQN::AllowedApiOperation()->ApiKeyObject->Id, $ApiKeyObj->Id),
                                    dxQ::Equal(dxQN::AllowedApiOperation()->IsActive, 1),
                                    dxQ::Equal(dxQN::AllowedApiOperation()->ApiOperationObject->OperationName, $OperationNameStr)
                                )
                            );
                        }
                        if ($AllowedOperationNameCount == 0) {
                            //JGL: This operation is not allowed for this api key
                            self::addApiOutput("Result","Failed");
                            self::addApiOutput("Message","Not authorized.");
                            self::printApiResult();
                        }
                    }
                }
            }
            self::processAuthenticationToken();
            call_user_func(PublicApi::getOperation());
        } else {
            PublicApi::addApiOutput("Result","Failed");
            PublicApi::addApiOutput("Message","[1]Invalid operation provided. Try providing swapping '".PublicApi::getOperation()."' for 'doc' at the end of the url to see documentation");
            PublicApi::printApiResult();
        }
    }
    public static function getOperation() {
        if (is_null(self::$InputOperation)) {
            self::addApiOutput("Result","Failed");
            self::addApiOutput("Message","No operation provided. Try providing 'doc' at the end of the url to see documentation");
            self::printApiResult();
        }
        return self::$InputOperation;
    }
    public static function getInputParameter($InputParameter = "") {
        if (isset($_GET[$InputParameter])) {
            return $_GET[$InputParameter];
        }
        if (isset($_POST[$InputParameter])) {
            return $_POST[$InputParameter];
        }
        if (isset($_REQUEST[$InputParameter])) {
            return $_REQUEST[$InputParameter];
        }
        $RawInputObj = self::getRawInputAsArray();
        if (!is_null($RawInputObj)) {
            if (isset($RawInputObj[$InputParameter])) {
                if (is_array($RawInputObj[$InputParameter])) {
                    return json_encode($RawInputObj[$InputParameter]);
                }
                return $RawInputObj[$InputParameter];
            }
        }
        
        $PathInfoArray = ProjectFunctions::getPathInfo(-1);
        if (ProjectFunctions::getDataSetSize($PathInfoArray) == 0) {
            return null;
        }
        foreach($PathInfoArray as $item) {
            $ItemComponents = explode("=", $item);
            if (ProjectFunctions::getDataSetSize($ItemComponents) == 2) {
                if ($ItemComponents[0] == $InputParameter) {
                    return $ItemComponents[1];
                }
            }
        }
        return null;
    }
    public static function getRawInputAsArray() {
        $RawInput = file_get_contents('php://input');
        $RawInputObj = null;
        if (ProjectFunctions::isJson($RawInput)) {
            $RawInputObj = json_decode($RawInput, true);
        }
        return $RawInputObj;
    }
    public static function addApiOperation($Operation = "",$InputParameters = [],$ReturnValue = [],$OperationNameStr = null,$OperationDescriptionStr = "No description provided") {
        self::$AvailableOperationsArray[$Operation] = array($InputParameters,$ReturnValue);
        if (!is_null($OperationNameStr)) {
            self::$NamedOperationsArray[$Operation] = $OperationNameStr;
        }
        self::$DescribedOperationsArray[$Operation] = $OperationDescriptionStr;
    }
    public static function addApiOutput($Key = "",$Value = "") {
        self::$ApiResultArray[$Key] = $Value;
    }
    public static function setApiResult($SuccessBool = false) {
        self::$ApiResultArray["Result"] = $SuccessBool?"Success":"Failed";
    }
    public static function printApiResult() {
        echo json_encode(self::$ApiResultArray);
        ProjectFunctions::printCleanOutput("Content-Type: application/json");
        die();
    }
    
    public static function getApiHost() {
        $protocol = 'http://';
        if (ProjectFunctions::isSecure())
            $protocol = 'https://';
        $www = ProjectFunctions::HostHasWWW()?'www.':'';
        $server = $_SERVER['SERVER_NAME'];
        $port = '';
        if (($_SERVER["SERVER_PORT"] != "80") && ($_SERVER["SERVER_PORT"] != "443")) {
            $port = ':'.$_SERVER["SERVER_PORT"];
        }
        return $protocol.$www.$server.$port;
    }
    public static function getApiEndpointRelativeUrl() {
        return str_replace('/doc','',$_SERVER['REQUEST_URI']);
    }
    public static function getApiEndpointUrl() {
        return self::getApiHost().self::getApiEndpointRelativeUrl();
    }
    
    public static function processAuthenticationToken() {
        $HttpUserAgent = 'None';
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $HttpUserAgent = $_SERVER["HTTP_USER_AGENT"];
        }
        $HttpOrigin = 'None';
        if (isset($_SERVER["HTTP_ORIGIN"])) {
            $HttpOrigin = $_SERVER["HTTP_ORIGIN"];
        }
        self::$UserAgent = $HttpUserAgent.';'.$HttpOrigin;
        self::$AuthenticationToken = self::getInputParameter("AuthenticationToken");
        if (is_null(self::$AuthenticationToken)) {
            self::initializeNewAuthenticationToken();
        } else {
            self::checkValidAuthenticationToken(ProjectFunctions::getCurrentAuthTokenObject(self::$AuthenticationToken));
        }
    }
    public static function initializeNewAuthenticationToken() {
        // JGL: We need to create a new auth token that will initially not be linked to an account
        $ClientAuthenticationTokenObj = new ClientAuthenticationToken();
        $ClientAuthenticationTokenObj->Token = ProjectFunctions::generateUniqueClientAuthenticationToken();
        $ClientAuthenticationTokenObj->UpdateDateTime = dxDateTime::Now();
        $ClientConnectionObj = new ClientConnection();
        $RemoteAddress = 'Unknown';
        if (isset($_SERVER["REMOTE_ADDR"])) {
            $RemoteAddress = $_SERVER["REMOTE_ADDR"];
        }
        $ClientConnectionObj->ClientIpAddress = $RemoteAddress;
        $ClientConnectionObj->ClientUserAgent = self::$UserAgent;
        $ClientConnectionObj->UpdateDateTime = dxDateTime::Now();
        $ClientConnectionObj->Save();
        $ClientAuthenticationTokenObj->ClientConnectionObject = $ClientConnectionObj;
        $ClientAuthenticationTokenObj->Save();
        
        self::$AuthenticationToken = $ClientAuthenticationTokenObj->Token;
        self::$UserAgent = $ClientConnectionObj->ClientUserAgent;
        $_SESSION['AuthenticationToken'] = self::$AuthenticationToken;
        self::setReturnAuthenticationToken(self::$AuthenticationToken);
    }
    public static function checkValidAuthenticationToken(ClientAuthenticationToken $ClientAuthenticationTokenObj = null) {
        if (is_null($ClientAuthenticationTokenObj)) {
            self::initializeNewAuthenticationToken();
            return;
        }
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        if (is_null($ClientConnectionObj)) {
            $ClientAuthenticationTokenObj->Delete();
            self::initializeNewAuthenticationToken();
            return;
        }
        if ($ClientConnectionObj->ClientUserAgent != self::$UserAgent) {
            // JGL: This token could have been stolen since it is being used on another device. Let's create a new token
            $ClientAuthenticationTokenObj->Delete();
            self::initializeNewAuthenticationToken();
            return;
        }
        
        if ($ClientAuthenticationTokenObj->UpdateDateTime < dxDateTime::Now()->AddMinutes(-AUTHENTICATION_REGENERATION_INT)) {
            // JGL: The authentication should be regenerated
            self::regenerateAuthenticationToken($ClientAuthenticationTokenObj);
            return;
        }
        self::updateAuthenticationToken($ClientAuthenticationTokenObj);
    }
    public static function updateAuthenticationToken(ClientAuthenticationToken $ClientAuthenticationTokenObj = null) {
        if (is_null($ClientAuthenticationTokenObj)) {
            self::initializeNewAuthenticationToken();
            return;
        }
        $ClientAuthenticationTokenObj->UpdateDateTime = dxDateTime::Now();
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        $ClientConnectionObj->UpdateDateTime = dxDateTime::Now();
        $ClientAuthenticationTokenObj->Save();
        $ClientConnectionObj->Save();
        self::$AuthenticationToken = $ClientAuthenticationTokenObj->Token;
        $_SESSION['AuthenticationToken'] = self::$AuthenticationToken;
        self::setReturnAuthenticationToken(self::$AuthenticationToken);
    }
    public static function regenerateAuthenticationToken(ClientAuthenticationToken $ClientAuthenticationTokenObj = null) {
        if (is_null($ClientAuthenticationTokenObj)) {
            self::initializeNewAuthenticationToken();
            return;
        }
        $ClientAuthenticationTokenObj->ExpiredToken = $ClientAuthenticationTokenObj->Token;
        $ClientAuthenticationTokenObj->Token = ProjectFunctions::generateUniqueClientAuthenticationToken();
        $ClientAuthenticationTokenObj->UpdateDateTime = dxDateTime::Now();
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        $ClientConnectionObj->UpdateDateTime = dxDateTime::Now();
        $ClientAuthenticationTokenObj->Save();
        $ClientConnectionObj->Save();
        self::$AuthenticationToken = $ClientAuthenticationTokenObj->Token;
        $_SESSION['AuthenticationToken'] = self::$AuthenticationToken;
        self::setReturnAuthenticationToken(self::$AuthenticationToken);
    }
    public static function setReturnAuthenticationToken($TokenStr = null) {
        self::addApiOutput("AuthenticationToken",$TokenStr);
    }
}
//endregion
//region Email Related
abstract class EmailManager_Framework extends EmailManager_Base {

}
abstract class EmailSettings_Framework extends EmailSettings_Base {
}
//endregion
?>
