<?php
/**
 * Created by Stratusolve (Pty) Ltd in South Africa.
 * @author     johangriesel <info@stratusolve.com>
 *
 * Copyright (C) 2018 Stratusolve (Pty) Ltd - All Rights Reserved
 * Modification or removal of this script is not allowed. In order
 * to include this script within your solution you require express
 * permission from Stratusolve (Pty) Ltd.
 * Please reference the divblox SaaS Subscription license agreement. A
 * copy of this agreement can be obtained by sending an email to
 * info@stratusolve.co
 *
 *
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file.
 * If you edit this file, it could be overridden by a future divblox update
 */
require_once(DATA_MODELLER_PATH_STR."/data_model/DataModel_Data.inc.php");
class DataModel_Base {
    protected $UserRoleArray = array("Administrator","User");

    protected $ProjectEntityArray = array();
    protected $ProjectEntityAttributeArray = array();
    protected $ProjectEntityAttributeTypeArray = array();
    protected $ProjectEntitySingleRelationshipArray = array();

    protected $BaseEntityArray = array(
        "Account",
        "AdditionalAccountInformation",
        "UserRole",
        "AuditLogEntry",
        "EmailMessage",
        "PasswordReset",
        "FileDocument",
        "PageView",
        "BackgroundProcess",
        "BackgroundProcessUpdate",
        "ClientConnection",
        "ClientAuthenticationToken",
        "PushRegistration");
    protected $BaseEntityAttributeArray = array (
        "Account" => array(
            "FullName",
            "FirstName",
            "MiddleNames",
            "LastName",
            "EmailAddress",
            "Username",
            "Password",
            "MaidenName",
            "ProfilePicturePath",
            "MainContactNumber",
            "Title",
            "DateOfBirth",
            "PhysicalAddressLineOne",
            "PhysicalAddressLineTwo",
            "PhysicalAddressPostalCode",
            "PhysicalAddressCountry",
            "PostalAddressLineOne",
            "PostalAddressLineTwo",
            "PostalAddressPostalCode",
            "PostalAddressCountry",
            "IdentificationNumber",
            "Nickname",
            "Status",
            "Gender",
            "AccessBlocked",
            "BlockedReason"),
        "AdditionalAccountInformation" => array(
            "Type",
            "Label",
            "Value"
        ),
        "UserRole" => array(
            "Role"), // e.g Administrator,User
        "AuditLogEntry" => array(
            "EntryTimeStamp",
            "ObjectName",
            "ModificationType",// Type = Updated,Created,Deleted
            "UserEmail",
            "ObjectId",
            "AuditLogEntryDetail"),
        "EmailMessage" => array(
            "SentDate",
            "FromAddress",
            "ReplyEmail",
            "Recipients",
            "Cc",
            "Bcc",
            "Subject",
            "EmailMessage",
            "Attachments",
            "ErrorInfo"),
        "PasswordReset" => array(
            "Token",
            "CreatedDateTime"),
        "FileDocument" => array(
            "FileName",
            "Path",
            "UploadedFileName",
            "FileType",
            "SizeInKilobytes",
            "CreatedDate"),
        "PageView" => array(
            "TimeStamped",
            "IPAddress",
            "PageDetails",
            "UserAgentDetails",
            "UserRole",
            "Username"),
	    "BackgroundProcess" => array(
	        "PId",
            "UserId",
            "UpdateDateTime",
            "Status",
            "Summary",
            "StartDateTime"),
	    "BackgroundProcessUpdate" => array(
	        "UpdateDateTime",
            "UpdateMessage"),
        "ClientConnection" => array(
            "ClientIpAddress",
            "ClientUserAgent",
            "UpdateDateTime"),
        "ClientAuthenticationToken" => array(
            "Token",
            "UpdateDateTime",
            "ExpiredToken"),
        "PushRegistration" => array(
            "RegistrationId",
            "DeviceUuid",
            "DevicePlatform",
            "DeviceOs",
            "RegistrationDateTime",
            "RegistrationStatus",
            "InternalUniqueId",
        ),
    );
    protected $BaseEntityAttributeTypeArray = array (// The attribute type for each of the defined object attributes (Defines how it is stored in the db)
        "Account"  => array(
            "VARCHAR(50)",
            "VARCHAR(50)",
            "VARCHAR(150)",
            "VARCHAR(50)",
            "VARCHAR(150)",
            "VARCHAR(50) UNIQUE",
            "VARCHAR(250)",
            "VARCHAR(50)",
            "VARCHAR(250)",
            "VARCHAR(25)",
            "VARCHAR(25)",
            "DATE",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(50)",
            "VARCHAR(50)",
            "VARCHAR(250)",
            "VARCHAR(25)",
            "BOOLEAN",
            "TEXT"),
        "AdditionalAccountInformation" => array(
            "VARCHAR(150)",
            "VARCHAR(150)",
            "TEXT"
        ),
        "UserRole" => array(
            "VARCHAR(50) UNIQUE"),
        "AuditLogEntry" => array(
            "DATETIME",
            "VARCHAR(50)",
            "VARCHAR(15)",
            "VARCHAR(100)",
            "TEXT",
            "TEXT"),
        "EmailMessage" => array(
            "DATETIME",
            "TEXT",
            "TEXT",
            "TEXT",
            "TEXT",
            "TEXT",
            "TEXT",
            "TEXT",
            "TEXT",
            "TEXT"),
        "PasswordReset" => array(
            "VARCHAR(50) UNIQUE",
            "DATETIME"),
        "FileDocument" => array(
            "VARCHAR(200)",
            "VARCHAR(300)",
            "VARCHAR(300)",
            "VARCHAR(50)",
            "DOUBLE",
            "DATETIME"),
        "PageView" => array(
            "DATETIME",
            "VARCHAR(50)",
            "VARCHAR(500)",
            "TEXT",
            "VARCHAR(200)",
            "VARCHAR(200)"),
	    "BackgroundProcess" => array(
	        "VARCHAR(50)",
            "VARCHAR(50)",
            "DATETIME",
            "VARCHAR(50)",
            "TEXT",
            "DATETIME"),
	    "BackgroundProcessUpdate" => array(
	        "DATETIME",
            "TEXT"),
        "ClientConnection" => array(
            "VARCHAR(50)",
            "VARCHAR(250)",
            "DATETIME"),
        "ClientAuthenticationToken" => array(
            "VARCHAR(50) UNIQUE NOT NULL",
            "DATETIME",
            "VARCHAR(50) UNIQUE"),
        "PushRegistration" => array(
            "TEXT",
            "VARCHAR(150)",
            "VARCHAR(150)",
            "VARCHAR(50)",
            "DATETIME",
            "VARCHAR(50)",
            "VARCHAR(50) UNIQUE NOT NULL"
        ),
    );
    protected $BaseEntitySingleRelationshipArray = array ( // The list of entities that each object is related to once
        "Account" => array(
            "UserRole"),
        "AdditionalAccountInformation" => array(
            "Account"),
        "PasswordReset" => array(
            "Account"),
	    "BackgroundProcessUpdate" => array(
	        "BackgroundProcess"),
        "ClientConnection" => array(
            "Account"),
        "ClientAuthenticationToken" => array(
            "ClientConnection"),
        "PushRegistration" => array(
            "ClientAuthenticationToken","Account"),
    );

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // To merge the Base Entities with the Project-Defined Entities
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    protected $EntityArray;
    protected $EntityAttributeArray;
    protected $EntityAttributeTypeArray;
    protected $EntitySingleRelationshipArray;

    protected $CurrentModule;

    public function __construct() {
	    if (class_exists("DataModelData")) {
		    $this->EntityArray = array_merge($this->BaseEntityArray,DataModelData::$ProjectEntityArray);
		    $this->EntityAttributeArray = array_merge($this->BaseEntityAttributeArray,DataModelData::$ProjectEntityAttributeArray);
		    $this->EntityAttributeTypeArray = array_merge($this->BaseEntityAttributeTypeArray,DataModelData::$ProjectEntityAttributeTypeArray);
		    $this->EntitySingleRelationshipArray = array_merge($this->BaseEntitySingleRelationshipArray,DataModelData::$ProjectEntitySingleRelationshipArray);
		    $this->UserRoleArray = DataModelData::$UserRoleArray;
	    } else {
	        throw new Exception("FATAL error: Class DataModelData does not exists");
        }
        $this->resetCurrentModule();
    }
    public function setCurrentModule($ModuleName = '') {
        $ModuleArray = $this->getModuleArray();
        if (!array_key_exists($ModuleName, $ModuleArray)) {
            throw new Exception("FATAL error: Data model tried to set invalid module name (input: $ModuleName) for db sync");
        }
        $this->CurrentModule = $ModuleName;
    }
    public function resetCurrentModule() {
        $this->CurrentModule = null;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Retrieve functions
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getEntities($Constraint = "ALL"/*"PROJECT","SYSTEM","ALL"*/) {
        if (is_null($this->CurrentModule)) {
            switch($Constraint) {
                case "ALL": return $this->EntityArray;
                    break;
                case "PROJECT": return DataModelData::$ProjectEntityArray;
                    break;
                case "SYSTEM": return $this->BaseEntityArray;
                    break;
                default: return $this->EntityArray;
            }
        } else {
            // When the current module is set, it means that we are synchronizing the database. For this reason, we return ALL by default
            if ($Constraint != 'ALL') {
                throw new Exception("Cannot get project or system entities for a specific module");
            }
            $ReturnArray = $this->getModuleArray();
            return $ReturnArray[$this->CurrentModule];
        }
    }
    public function getEntityModule($EntityNameStr = '') {
        foreach(DataModelData::$ModuleArray as $Module => $EntityArray) {
            if (in_array($EntityNameStr, $EntityArray)) {
                return $Module;
            }
        }
        return null;
    }
    public function getAllAttributes() {
    	return $this->EntityAttributeArray;
    }
    public function getAllAttributeTypes() {
		return $this->EntityAttributeTypeArray;
	}
    public function getAllSingleRelationships() {
		return $this->EntitySingleRelationshipArray;
	}
    public function getAllUserRoles() {
		return $this->UserRoleArray;
	}
    public function getAttributeTypeArrayForEntity($Entity) {
		if (isset($this->EntityAttributeTypeArray[$Entity])) {
            return $this->EntityAttributeTypeArray[$Entity];
        }
		return null;
	}
    public function getEntityAttributes($Entity) {
        if (array_key_exists($Entity,$this->EntityAttributeArray)) {
            return $this->EntityAttributeArray[$Entity];
        }
        return array("");
    }
    public function getEntityAttributeType($Entity,$EntityAttribute) {
        if (array_key_exists($Entity,$this->EntityAttributeArray)) {
            $Index = array_search($EntityAttribute, $this->EntityAttributeArray[$Entity]);
            return $this->EntityAttributeTypeArray[$Entity][$Index];
        }
        return "Not Defined";
    }
    public function getEntitySingleRelationships($Entity) {
        if (array_key_exists($Entity,$this->EntitySingleRelationshipArray)) {
            return $this->EntitySingleRelationshipArray[$Entity];
        }
        return null;
    }
    public function getEntityPossibleRelationshipsRecursive($Entity,$ReturnArray = []) {
        if (array_key_exists($Entity,$this->EntitySingleRelationshipArray)) {
            if (ProjectFunctions::getDataSetSize($this->EntitySingleRelationshipArray[$Entity]) > 0) {
                foreach($this->EntitySingleRelationshipArray[$Entity] as $item) {
                    if ($item == $Entity) {
                        continue;
                    }
                    if (!in_array($item, $ReturnArray)) {
                        array_push($ReturnArray, $item);
                    }
                    $ReturnArray = $this->getEntityPossibleRelationshipsRecursive($item,$ReturnArray);
                }
            }
            return $ReturnArray;
        }
        return $ReturnArray;
    }
    public function getEntityRelationshipPathAsString($Entity = "",$Relationship = "",$PathArray) {
        $ReturnString = "";
        if (array_key_exists($Entity,$this->EntitySingleRelationshipArray)) {
            if (in_array($Relationship,$this->EntitySingleRelationshipArray[$Entity])) {
                // Loop over path array to build path string here
                if (ProjectFunctions::getDataSetSize($PathArray) > 0) {
                    foreach($PathArray as $item) {
                        $ReturnString .= $item.'Object->';
                    }
                }
                return $ReturnString.$Relationship.'Object->';
            } else {
                foreach($this->EntitySingleRelationshipArray[$Entity] as $RelatedEntity) {
                    array_push($PathArray,$RelatedEntity);
                    $ReturnString = $this->getEntityRelationshipPathAsString($RelatedEntity,$Relationship,$PathArray);
                    if (strlen($ReturnString) > 0) {
                        break;
                    } else {
                        $PathArray = [];
                    }
                }
                return $ReturnString;
            }
        } else {
            return '';
        }
    }
    public function getModuleArray() {
        $ConfiguredModuleArray = [];
        if (ProjectFunctions::isJson(APP_MODULES_STR)) {
            $ConfiguredModuleArray = json_decode(APP_MODULES_STR);
        }
        if (!isset(DataModelData::$ModuleArray) ||
            is_null(DataModelData::$ModuleArray) ||
            ProjectFunctions::getDataSetSize(DataModelData::$ModuleArray) == 0) {
            $CurrentDataModelDataModuleArray = [];
            foreach($ConfiguredModuleArray as $Module) {
                $CurrentDataModelDataModuleArray[$Module] = [];
            }
            return $CurrentDataModelDataModuleArray;
        } else {
            $ReturnArray = DataModelData::$ModuleArray;
            foreach($ConfiguredModuleArray as $Module) {
                if (!array_key_exists($Module, $ReturnArray)) {
                    $ReturnArray[$Module] = [];
                }
            }
            $AvailableModuleArray = array_keys($ReturnArray);
            foreach($AvailableModuleArray as $ModuleName) {
                if (!in_array($ModuleName, $ConfiguredModuleArray)) {
                    unset($ReturnArray[$ModuleName]);
                }
            }
            return $ReturnArray;
        }
        throw new Exception("FATAL error: No app modules are defined.");
    }
}
?>