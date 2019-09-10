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
         // TODO: Override base access (At framework level) here per page or leave if no special functionality is required
         //region Example override
         /*$AnonymousComponentArray = ["default.html"];
         if (in_array($ComponentName, $AnonymousComponentArray)) {
             return true; // JGL: Anyone can access these components
         }
         $AccountObj = Account::Load($AccountId);
         if (is_null($AccountObj)) {
             if (in_array($ComponentName, $AnonymousComponentArray)) {
                 return true; // JGL: Anyone can access these components
             }
             return false;
         }
         $UserRoleObj = $AccountObj->UserRoleObject;
         if (is_null($UserRoleObj)) {
             if (in_array($ComponentName, $AnonymousComponentArray)) {
                 return true; // JGL: Anyone can access these components
             }
             return false;
         }
         $UserRoleSpecificComponentArray = array(
             "User" => ["sensitive_component1.html","sensitive_component2.html"],
             //TODO: Add additionals here
         );
         if (array_key_exists($UserRoleObj->Role, $UserRoleSpecificComponentArray)) {
             // JGL: Component access is defined for the user role, let's see if the specific component is allowed
             if (in_array($ComponentName, $UserRoleSpecificComponentArray[$UserRoleObj->Role])) {
                 return true;
             }
         }*/
         //endregion
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
    public static $AvailableOperationsArray = []; // This should be populated with the api's available operations (function names)
    public static $InputOperation;
    public static function initApi($ApiDescription = "API Description") {
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
        self::$ApiDescription = $ApiDescription;
        if (ProjectFunctions::getPathInfo(0) == "doc") {
            echo "<h4>".self::$ApiDescription."</h4>";
            echo "<strong>Available operations:</strong><br>";
            if (ProjectFunctions::getDataSetSize(self::$AvailableOperationsArray) > 0) {
                foreach(self::$AvailableOperationsArray as $AvailableOperation => $DetailArray) {
                    echo "<br>- <strong>$AvailableOperation:</strong><br><strong>Inputs:</strong><br>";
                    foreach($DetailArray[0] as $item) {
                        echo "- $item<br>";
                    }
                    echo "<strong>Expected Result</strong>:<br>- ".$DetailArray[1]."<br>";
                }
            } else {
                echo "- None<br>";
            }
            ProjectFunctions::printCleanOutput("Content-Type: text/html");
            die();
        }
        if (function_exists(PublicApi::getOperation())) {
            call_user_func(PublicApi::getOperation());
        } else {
            PublicApi::addApiOutput("Result","Failed");
            PublicApi::addApiOutput("Message","Invalid operation provided. Try providing swapping '".PublicApi::getOperation()."' for 'doc' at the end of the url to see documentation");
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
    public static function addApiOperation($Operation = "",$InputParaments = [],$ReturnValue = "") {
        self::$AvailableOperationsArray[$Operation] = array($InputParaments,$ReturnValue);
    }
    public static function addApiOutput($Key = "",$Value = "") {
        self::$ApiResultArray[$Key] = $Value;
    }
    public static function setApiResult($SuccessBool = false) {
        self::$ApiResultArray["Result"] = $SuccessBool?"Success":"Failed";
    }
    public static function printApiResult() {
        echo json_encode(self::$ApiResultArray);
        ProjectFunctions::printCleanOutput("Content-Type: text/plain");
        die();
    }
}
//endregion
?>
