<?php
/*
 * This file is used throughout your project and is loaded by default by divblox.
 * When making changes to this file, keep in mind that every back-end process that
 * your project will run will load this file
 * */
include(FRAMEWORK_ROOT_STR."/assets/php/framework_classes.php");
include(PROJECT_ROOT_STR.'/assets/php/component_role_based_access.class.php');
include(PROJECT_ROOT_STR.'/assets/php/data_model_role_based_access.class.php');
include(PROJECT_ROOT_STR.'/assets/php/user_role_hierarchy.class.php');

//region Project Access related

/**
 * Class ProjectAccessManager
 * Responsible for system-wide access for components and objects
 */
abstract class ProjectAccessManager extends AccessManager {
    /**
     * Determines the access rights for a given object and account combination
     * @param int $AccountId The id of the account trying to access the object
     * @param null $ObjectType The entity name of the object
     * @param int $ObjectId The primary key id of the object in the database
     * @return array Best case: [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR]
     */
    public static function getObjectAccess($AccountId = -1, $ObjectType = null, $ObjectId = -1) {
        $ReturnArray = parent::getObjectAccess($AccountId,$ObjectType,$ObjectId);
        // TODO: Override your specialized access here per object type or leave if no special functionality is required
        $UserRoleStr = 'Anonymous';
        $AccountObj = Account::Load($AccountId);
        if (!is_null($AccountObj)) {
            $UserRoleObj = $AccountObj->UserRoleObject;
            if (!is_null($UserRoleObj)) {
                $UserRoleStr = $UserRoleObj->Role;
            }
        }
        if (array_key_exists($ObjectType, DataModelRoleBasedAccess::$AccessArray['Any'])) {
            return DataModelRoleBasedAccess::$AccessArray['Any'][$ObjectType];
        }
        
        if ($ObjectType == "Account") {
            if ($AccountId == $ObjectId) {
                return [AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
            }
        }
        if ($ObjectType == 'AdditionalAccountInformation') {
            $AdditionalAccountInfoObj = AdditionalAccountInformation::Load($ObjectId);
            if (!is_null($AdditionalAccountInfoObj)) {
                if (!is_null($AdditionalAccountInfoObj->AccountObject)) {
                    if ($AdditionalAccountInfoObj->AccountObject->Id == $AccountId) {
                        return [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
                    }
                }
            }
        }
        
        $HierarchyBasedAccessArray = [];
        if (isset(DataModelRoleBasedAccess::$AccessArray[$UserRoleStr])) {
            foreach (DataModelRoleBasedAccess::$AccessArray[$UserRoleStr] as $ObjectName => $ObjectArray) {
                $HierarchyBasedAccessArray[$ObjectName] = $ObjectArray;
            }
        }
        if (isset(UserRoleHierarchy::$PermissionInheritanceArray[$UserRoleStr])) {
            $InheritedUserRoleArray = UserRoleHierarchy::$PermissionInheritanceArray[$UserRoleStr];
            foreach ($InheritedUserRoleArray as $InheritedUserStr) {
                if (isset(DataModelRoleBasedAccess::$AccessArray[$InheritedUserStr])) {
                    foreach (DataModelRoleBasedAccess::$AccessArray[$InheritedUserStr] as $ObjectName => $ObjectArray) {
                        if (!isset($HierarchyBasedAccessArray[$ObjectName])) {
                            $HierarchyBasedAccessArray[$ObjectName] = $ObjectArray;
                        }
                    }
                }
            }
        }
        
        if (!isset($HierarchyBasedAccessArray[$ObjectType])) {
            return $ReturnArray;
        }
        return $HierarchyBasedAccessArray[$ObjectType];
    }

    /**
     * Determines whether a user has access to the specified component, based on their user role
     * @param int $AccountId The id of the account trying to access the object
     * @param string $ComponentName The name of the component being accessed
     * @return bool true if access is allowed, false if not
     */
    public static function getComponentAccess($AccountId = -1, $ComponentName = '') {
        $InitialReturn = parent::getComponentAccess($AccountId,$ComponentName);
        if ($InitialReturn == true) {return true;}
        
        if (DISABLE_COMPONENT_SECURITY_CHECKS_BOOL) {return true;}
        
        $UserRoleStr = 'Anonymous';
        $AccountObj = Account::Load($AccountId);
        if (!is_null($AccountObj)) {
            $UserRoleObj = $AccountObj->UserRoleObject;
            if (!is_null($UserRoleObj)) {
                $UserRoleStr = $UserRoleObj->Role;
            }
        }
    
        $HierarchyBasedAccessArray = [];
        if (isset(ComponentRoleBasedAccess::$AccessArray[$UserRoleStr])) {
            foreach (ComponentRoleBasedAccess::$AccessArray[$UserRoleStr] as $ComponentNameStr) {
                $HierarchyBasedAccessArray[] = $ComponentNameStr;
            }
        }
    
        if (isset(UserRoleHierarchy::$PermissionInheritanceArray[$UserRoleStr])) {
            $InheritedUserRoleArray = UserRoleHierarchy::$PermissionInheritanceArray[$UserRoleStr];
            foreach ($InheritedUserRoleArray as $InheritedUserStr) {
                if (isset(ComponentRoleBasedAccess::$AccessArray[$InheritedUserStr])) {
                    foreach (ComponentRoleBasedAccess::$AccessArray[$InheritedUserStr] as $ComponentNameStr) {
                        $HierarchyBasedAccessArray[] = $ComponentNameStr;
                    }
                }
            }
        }
        
        if (in_array($ComponentName, $HierarchyBasedAccessArray)) {
            return true;
        }
        
        return $InitialReturn;
    }
}
//endregion

//region Component controller related
/**
 * Class ProjectComponentController
 * Responsible for managing the project-level behaviour of all server-side component scripts
 */
class ProjectComponentController extends ComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
/**
 * Class EntityInstanceComponentController
 * Responsible for managing the project-level behaviour of all server-side entity create/update component scripts
 */
class EntityInstanceComponentController extends ProjectComponentController {
    protected $DataModelObj;
    protected $EntityNameStr = "";
    protected $RelationshipListLimit = 100;
    protected $IncludedAttributeArray = [];
    protected $IncludedRelationshipArray = []; // Indicates the relationships to include, along with witch attribute to display. e.g. ["Account" => "FirstName"]
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];
    protected $IsCreatingBool = false;
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getObjectData() {
        $EntityObj = $this->EntityNameStr::Load($this->getInputValue("Id",true));
        $EntityJsonDecoded = array();
        if (!is_null($EntityObj)) {
            $EntityJsonDecoded = json_decode($EntityObj->getJson());
        }
        $this->setReturnValue("Object",$EntityJsonDecoded);
        foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayValue) {
            $RelationshipList = $this->getRelationshipList($EntityObj,$Relationship);
            $this->setReturnValue($Relationship."List",$RelationshipList);
        }
        $this->setResult(true);
        $this->setReturnValue("Message","");
        $this->presentOutput();
        
    }
    public function getRelationshipList($EntityObj = null,$RelationshipNameStr = null) {
        $ReturnArray = [];
        if (is_null($RelationshipNameStr)) {
            return $ReturnArray;
        }
        if (!isset($this->IncludedRelationshipArray[$RelationshipNameStr])) {
            return [];
        }
        $DisplayAttribute = $this->IncludedRelationshipArray[$RelationshipNameStr];
        if ($RelationshipNameStr::QueryCount(dxQ::All()) > $this->RelationshipListLimit) {
            if (!is_null($EntityObj)) {
                if ($EntityObj->$RelationshipNameStr > 0) {
                    $RelationshipObj = $RelationshipNameStr::Load($EntityObj->$RelationshipNameStr);
                    if (!is_null($RelationshipObj)) {
                        $ReturnArray[] = array("Id" => $EntityObj->$RelationshipNameStr,"DisplayValue"=>$RelationshipObj->$DisplayAttribute);
                    }
                }
            }
            $ReturnArray[] = array("Id" => "DATASET TOO LARGE");
            return $ReturnArray;
        }
        $ObjectArray = $RelationshipNameStr::QueryArray(dxQ::All(),
            dxQ::Clause(
                dxQ::Select(dxQN::$RelationshipNameStr()->$DisplayAttribute),
                dxQ::LimitInfo($this->RelationshipListLimit,0)
            ));
        
        if (ProjectFunctions::getDataSetSize($ObjectArray) > 0) {
            foreach ($ObjectArray as $item) {
                $ReturnArray[] = array("Id" => $item->Id,"DisplayValue"=>$item->$DisplayAttribute);
            }
        }
        return $ReturnArray;
    }
    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setResult(false);
            $this->setReturnValue("Message","No ".$this->EntityNameStr." object provided");
            $this->presentOutput();
        }
        $EntityNodeNameStr = $this->EntityNameStr;
        $InputEntityObj = json_decode($this->getInputValue("ObjectData"),true);
        $EntityToUpdateObj = $EntityNodeNameStr::Load($this->getInputValue("Id",true));
        if (is_null($EntityToUpdateObj)) {
            $EntityToUpdateObj = new $EntityNodeNameStr();
            $this->IsCreatingBool = true;
        }
        foreach($EntityToUpdateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($InputEntityObj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($InputEntityObj[$Attribute]) == 0) {
                        $this->setResult(false);
                        $this->setReturnValue("Message","$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($InputEntityObj[$Attribute])) {
                        $this->setResult(false);
                        $this->setReturnValue("Message","$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType($this->EntityNameStr, $Attribute),["DATE","DATETIME"])) {
                    if (is_string($InputEntityObj[$Attribute]) && (strlen($InputEntityObj[$Attribute]) > 0)) {
                        $DateObj = new dxDateTime($InputEntityObj[$Attribute]);
                        $EntityToUpdateObj->$Attribute = $DateObj;
                    }
                } else {
                    if ($this->EntityNameStr == "Account") {
                        if ($Attribute == "Password") {
                            if (strlen($InputEntityObj[$Attribute]) > 0) {
                                $EntityToUpdateObj->$Attribute = password_hash($InputEntityObj[$Attribute],PASSWORD_BCRYPT);
                            }
                        } else {
                            $EntityToUpdateObj->$Attribute = $InputEntityObj[$Attribute];
                        }
                    } else {
                        $EntityToUpdateObj->$Attribute = $InputEntityObj[$Attribute];
                    }
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setResult(false);
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        if ((ProjectFunctions::getDataSetSize($this->ConstrainByArray) > 0) && $this->IsCreatingBool) {
            foreach ($this->ConstrainByArray as $item) {
                $ConstrainByObjStr = $item.'Object';
                $EntityToUpdateObj->$ConstrainByObjStr = $item::Load($this->getInputValue('Constraining'.$item.'Id',true));
            }
        }
        $this->doBeforeSaveActions($EntityToUpdateObj);
        $EntityToUpdateObj->Save();
        $this->doAfterSaveActions($EntityToUpdateObj);
        $this->setResult(true);
        $this->setReturnValue("Message","Object updated");
        $this->setReturnValue("Id",$EntityToUpdateObj->Id);
        $this->presentOutput();
    }
    public function deleteObjectData() {
        if (is_null($this->getInputValue("Id"))) {
            $this->setResult(false);
            $this->setReturnValue("Message","No ".$this->EntityNameStr." Id provided");
            $this->presentOutput();
        }
        $EntityNodeNameStr = $this->EntityNameStr;
        $EntityObj = $EntityNodeNameStr::Load($this->getInputValue("Id",true));
        if (is_null($EntityObj)) {
            $this->setResult(false);
            $this->setReturnValue("Message",$this->EntityNameStr." not found");
            $this->presentOutput();
        } else {
            $this->doBeforeDeleteActions($EntityObj);
            $EntityObj->Delete();
            $this->doAfterDeleteActions();
            $this->setResult(true);
            $this->setReturnValue("Message",$this->EntityNameStr." deleted");
            $this->presentOutput();
        }
    }
    public function doBeforeSaveActions($EntityToUpdateObj = null) {
        // JGL: This function is intended to be overridden in the child class for additional functionality
        if (is_null($EntityToUpdateObj)) {
            return;
        }
    }
    public function doAfterSaveActions($EntityToUpdateObj = null) {
        // JGL: This function is intended to be overridden in the child class for additional functionality
        if (is_null($EntityToUpdateObj)) {
            return;
        }
    }
    public function doBeforeDeleteActions($EntityToUpdateObj = null) {
        // JGL: This function is intended to be overridden in the child class for additional functionality
        if (is_null($EntityToUpdateObj)) {
            return;
        }
    }
    public function doAfterDeleteActions() {
        // JGL: This function is intended to be overridden in the child class for additional functionality
    }
}
/**
 * Class EntityDataSeriesComponentController
 * Responsible for managing the project-level behaviour of all server-side entity data table and list component scripts
 */
class EntityDataSeriesComponentController extends ProjectComponentController {
    protected $DataModelObj;
    protected $EntityNameStr = "";
    protected $IncludedAttributeArray = [];
    protected $IncludedRelationshipArray = []; // Indicates the relationships to include, along with witch attribute to display. e.g. ["Account" => "FirstName"]
    protected $ConstrainByArray = [];
    
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getPage() {
        $EntityNodeNameStr = $this->EntityNameStr;
        $DefaultSortAttribute = $this->IncludedAttributeArray[0];
        
        if (is_null($this->getInputValue("ItemsPerPage"))) {
            $this->setResult(false);
            $this->setReturnValue("Message","No items per page provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),$this->EntityNameStr);
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setResult(false);
            $this->setReturnValue("Message","Read access denied");
            $this->presentOutput();
        }
        $Offset = $this->getInputValue("CurrentOffset",true);
        if ($Offset < 0) {
            $Offset = ($this->getInputValue("CurrentPage",true) - 1) * $this->getInputValue("ItemsPerPage",true);
        }
        if ($Offset < 0) {
            $Offset = 0;
        }
        $QueryCondition = dxQ::All();
    
        foreach ($this->ConstrainByArray as $Relationship) {
            $RelationshipNodeStr = $Relationship.'Object';
            $QueryCondition = dxQ::AndCondition(
                $QueryCondition,
                dxQ::Equal(
                    dxQN::$EntityNodeNameStr()->$RelationshipNodeStr->Id, $this->getInputValue('Constraining'.$Relationship.'Id',true)
                )
            );
        }
        $QueryOrConditions = null;
        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $SearchInputStr = "%".$this->getInputValue("SearchText")."%";
                foreach ($this->IncludedAttributeArray as $Attribute) {
                    if (is_null($QueryOrConditions)) {
                        $QueryOrConditions = dxQ::Like(dxQueryN::$EntityNodeNameStr()->$Attribute,$SearchInputStr);
                    } else {
                        $QueryOrConditions = dxQ::OrCondition($QueryOrConditions,
                            dxQ::Like(dxQueryN::$EntityNodeNameStr()->$Attribute,$SearchInputStr));
                    }
                };
                foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayAttribute) {
                    $RelationshipNodeStr = $Relationship.'Object';
                    if (is_null($QueryOrConditions)) {
                        $QueryOrConditions = dxQ::Like(dxQueryN::$EntityNodeNameStr()->$RelationshipNodeStr->$DisplayAttribute,$SearchInputStr);
                    } else {
                        $QueryOrConditions = dxQ::OrCondition($QueryOrConditions,
                            dxQ::Like(dxQueryN::$EntityNodeNameStr()->$RelationshipNodeStr->$DisplayAttribute,$SearchInputStr));
                    }
                };
            }
        }
        if (!is_null($QueryOrConditions)) {
            $QueryCondition = dxQ::AndCondition($QueryCondition,$QueryOrConditions);
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::$EntityNodeNameStr()->$DefaultSortAttribute);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::$EntityNodeNameStr()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $EntityArray = $EntityNodeNameStr::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage",true),$Offset)
            ));
        $EntityReturnArray = [];
        foreach($EntityArray as $EntityObj) {
            $CompleteReturnArray = ["Id" => $EntityObj->Id];
            foreach ($this->IncludedAttributeArray as $Attribute) {
                if ($this->DataModelObj->getEntityAttributeType($this->EntityNameStr, $Attribute) == "DATE") {
                    $CompleteReturnArray[$Attribute] = is_null($EntityObj->$Attribute)? 'N/A':$EntityObj->$Attribute->format(DATE_TIME_FORMAT_PHP_STR);
                } elseif ($this->DataModelObj->getEntityAttributeType($this->EntityNameStr, $Attribute) == "DATETIME") {
                    $CompleteReturnArray[$Attribute] = is_null($EntityObj->$Attribute)? 'N/A':$EntityObj->$Attribute->format(DATE_TIME_FORMAT_PHP_STR." H:i:s");
                } else {
                    $CompleteReturnArray[$Attribute] = is_null($EntityObj->$Attribute)? 'N/A':$EntityObj->$Attribute;
                }
            }
            
            foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayAttribute) {
                $RelationshipReturnStr = "N/A";
                $RelationshipNodeStr = $this->DataModelObj->getEntityRelationshipPathAsNode($EntityObj,$this->EntityNameStr,$Relationship,[]);
                if (!is_null($RelationshipNodeStr)) {
                    if (!is_null($RelationshipNodeStr->$DisplayAttribute)) {
                        if ($this->DataModelObj->getEntityAttributeType($Relationship, $DisplayAttribute) == "DATE") {
                            $RelationshipReturnStr = $RelationshipNodeStr->$DisplayAttribute->format(DATE_TIME_FORMAT_PHP_STR);
                        } elseif ($this->DataModelObj->getEntityAttributeType($Relationship, $DisplayAttribute) == "DATETIME") {
                            $RelationshipReturnStr = $RelationshipNodeStr->$DisplayAttribute->format(DATE_TIME_FORMAT_PHP_STR." H:i:s");
                        } else {
                            $RelationshipReturnStr = $RelationshipNodeStr->$DisplayAttribute;
                        }
                    } else {
                        $RelationshipReturnStr = 'N/A';
                    }
                }
                $CompleteReturnArray[$Relationship] = $RelationshipReturnStr;
            }
            array_push($EntityReturnArray,$CompleteReturnArray);
        }
        $this->setResult(true);
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$EntityReturnArray);
        $this->setReturnValue("TotalCount",$EntityNodeNameStr::QueryCount($QueryCondition));
        $this->presentOutput();
    }
    public function deleteSelection() {
        $EntityNodeNameStr = $this->EntityNameStr;
        if (is_null($this->getInputValue("SelectedItemArray"))) {
            $this->setResult(false);
            $this->setReturnValue("Message","No items provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),$this->EntityNameStr);
        if (!in_array(AccessOperation::DELETE_STR, $AccessArray)) {
            $this->setResult(false);
            $this->setReturnValue("Message","Delete permission denied");
            $this->presentOutput();
        }
        $DeleteItemsArray = json_decode($this->getInputValue("SelectedItemArray"));
        $DeleteCount = 0;
        foreach($DeleteItemsArray as $item) {
            $EntityToDeleteObj = $EntityNodeNameStr::Load($item);
            if (is_null($EntityToDeleteObj)) {
                continue;
            }
            $EntityToDeleteObj->Delete();
            $DeleteCount++;
        }
        $this->setResult(true);
        $this->setReturnValue("Message","$DeleteCount items deleted");
        $this->presentOutput();
    }
}
//endregion

//region 3rd Party classes
# ======================================================================== #
#
#  Title      [PHP] FileUploader
#  Author:    innostudio.de
#  Website:   http://innostudio.de/fileuploader/
#  Version:   2.1
#  Date:      26-Nov-2018
#  Purpose:   Validate, Remove, Upload, Sort files and Resize images on server.
#  Information: Don't forget to check the options file_uploads, upload_max_filesize, max_file_uploads and post_max_size in the php.ini
#
# ======================================================================== #

class FileUploader {
    private $default_options = array(
        'limit' => null,
        'maxSize' => null,
        'fileMaxSize' => null,
        'extensions' => null,
        'required' => false,
        'uploadDir' => 'uploads/',
        'title' => array('auto', 12),
        'replace' => false,
        'editor' => null,
        'listInput' => true,
        'files' => array(),
        'move_uploaded_file' => null,
        'validate_file' => null
    );
    private $field = null;
    private $options = null;

    /**
     * __construct method
     *
     * @public
     * @param $name {$_FILES key}
     * @param $options {null, Array}
     */
    public function __construct($name, $options = null) {
        $this->default_options['move_uploaded_file'] = function($tmp, $dest) {
            return move_uploaded_file($tmp, $dest);
        };
        $this->default_options['validate_file'] = function($file, $options) {
            return true;
        };
        return $this->initialize($name, $options);
    }

    /**
     * initialize method
     * initialize the plugin
     *
     * @private
     * @param $name {String} Input name
     * @param $options {null, Array}
     */
    private function initialize($name, $options) {
        // merge options
        $this->options = $this->default_options;
        if ($options)
            $this->options = array_merge($this->options, $options);
        if (!is_array($this->options['files']))
            $this->options['files'] = array();

        // create field array
        $this->field = array(
            'name' => $name,
            'input' => null,
            'listInput' => $this->getListInputFiles($name)
        );

        if (isset($_FILES[$name])) {
            // set field input
            $this->field['input'] = $_FILES[$name];

            // tranform an no-multiple input to multiple
            // made only to simplify the next uploading steps
            if (!is_array($this->field['input']['name'])) {
                $this->field['input'] = array_merge($this->field['input'], array(
                    "name" => array($this->field['input']['name']),
                    "tmp_name" => array($this->field['input']['tmp_name']),
                    "type" => array($this->field['input']['type']),
                    "error" => array($this->field['input']['error']),
                    "size" => array($this->field['input']['size'])
                ));
            }

            // remove empty filenames
            // only for addMore option
            foreach($this->field['input']['name'] as $key=>$value){ if (empty($value)) { unset($this->field['input']['name'][$key]); unset($this->field['input']['type'][$key]); unset($this->field['input']['tmp_name'][$key]); unset($this->field['input']['error'][$key]); unset($this->field['input']['size'][$key]); } }

            // set field length (files count)
            $this->field['count'] = count($this->field['input']['name']);
            return true;
        } else {
            return false;
        }
    }

    /**
     * upload method
     * Call the uploadFiles method
     *
     * @public
     * @return {Array}
     */
    public function upload() {
        return $this->uploadFiles();
    }

    /**
     * getFileList method
     * Get the list of the preloaded and uploaded files
     *
     * @public
     * @param @customKey {null, String} File attrbite that should be in the list
     * @return {null, Array}
     */
    public function getFileList($customKey = null) {
        $result = null;

        if ($customKey != null) {
            $result = array();
            foreach($this->options['files'] as $key=>$value) {
                $attribute = $this->getFileAttribute($value, $customKey);
                $result[] = $attribute ? $attribute : $value['file'];
            }
        } else {
            $result = $this->options['files'];
        }

        return $result;
    }

    /**
     * getUploadedFiles method
     * Get a list with all uploaded files
     *
     * @public
     * @return {Array}
     */
    public function getUploadedFiles() {
        $result = array();

        foreach($this->getFileList() as $key=>$item) {
            if (isset($item['uploaded']))
                $result[] = $item;
        }

        return $result;
    }

    /**
     * getPreloadedFiles method
     * Get a list with all preloaded files
     *
     * @public
     * @return {Array}
     */
    public function getPreloadedFiles() {
        $result = array();

        foreach($this->getFileList() as $key=>$item) {
            if (!isset($item['uploaded']))
                $result[] = $item;
        }

        return $result;
    }

    /**
     * getRemovedFiles method
     * Get removed files as array
     *
     * @public
     * @param $customKey {String} The file attribute which is also defined in listInput element
     * @return {Array}
     */
    public function getRemovedFiles($customKey = 'file') {
        $removedFiles = array();

        if (is_array($this->field['listInput']['list']) && is_array($this->options['files'])) {
            foreach($this->options['files'] as $key=>$value) {
                if (!in_array($this->getFileAttribute($value, $customKey), $this->field['listInput']['list']) && (!isset($value['uploaded']) || !$value['uploaded'])) {
                    $removedFiles[] = $value;
                    unset($this->options['files'][$key]);
                }
            }
        }

        if (is_array($this->options['files']))
            $this->options['files'] = array_values($this->options['files']);

        return $removedFiles;
    }

    /**
     * getListInput method
     * Get the listInput value as null or array
     *
     * @public
     * @return {null, Array}
     */
    public function getListInput() {
        return $this->field['listInput'];
    }

    /**
     * generateInput method
     * Generate a string with HTML input
     *
     * @public
     * @return {String}
     */
    public function generateInput() {
        $attributes = array();

        // process options
        foreach(array_merge(array('name'=>$this->field['name']), $this->options) as $key=>$value) {
            if ($value) {
                switch($key) {
                    case 'limit':
                    case 'maxSize':
                    case 'fileMaxSize':
                        $attributes['data-fileuploader-' . $key] = $value;
                        break;
                    case 'listInput':
                        $attributes['data-fileuploader-' . $key] = is_bool($value) ? var_export($value, true) : $value;
                        break;
                    case 'extensions':
                        $attributes['data-fileuploader-' . $key] = implode(',', $value);
                        break;
                    case 'name':
                        $attributes[$key] = $value;
                        break;
                    case 'required':
                        $attributes[$key] = '';
                        break;
                    case 'files':
                        $value = array_values($value);
                        $attributes['data-fileuploader-' . $key] = json_encode($value);
                        break;
                }
            }
        }

        // generate input attributes
        $dataAttributes = array_map(function($value, $key) {
            return $key . "='" . (str_replace("'", '"', $value)) . "'";
        }, array_values($attributes), array_keys($attributes));

        return '<input type="file"' . implode(' ', $dataAttributes) . '>';
    }

    /**
     * resize method
     * Resize, crop and rotate images
     *
     * @public
     * @static
     * @param $filename {String} file source
     * @param $width {Number} new width
     * @param $height {Number} new height
     * @param $destination {String} file destination
     * @param $crop {boolean, Array} crop property
     * @param $quality {Number} quality of destination
     * @param $rotation {Number} rotation degrees
     * @return {boolean} resizing was successful
     */
    public static function resize($filename, $width = null, $height = null, $destination = null, $crop = false, $quality = 90, $rotation = 0) {
        if (!is_file($filename) || !is_readable($filename))
            return false;

        $source = null;
        $destination = !$destination ? $filename : $destination;
        if (file_exists($destination) && !is_writable($destination))
            return false;
        $imageInfo = getimagesize($filename);
        if (!$imageInfo)
            return false;
        $exif = @exif_read_data($filename);

        // detect actions
        $hasRotation = $rotation || isset($exif['Orientation']);
        $hasCrop = is_array($crop) || $crop == true;
        $hasResizing = $width || $height;

        if (!$hasRotation && !$hasCrop && !$hasResizing)
            return;

        // store image information
        list ($imageWidth, $imageHeight, $imageType) = $imageInfo;
        $imageRatio = $imageWidth / $imageHeight;

        // create GD image
        switch($imageType) {
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($filename);
                break;
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($filename);
                break;
            default:
                return false;
        }

        // rotation
        if ($hasRotation) {
            $cacheWidth = $imageWidth;
            $cacheHeight = $imageHeight;

            // exif rotation
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $source = imagerotate($source, 180, 0);
                        break;
                    case 6:
                        $imageWidth = $cacheHeight;
                        $imageHeight = $cacheWidth;

                        $source = imagerotate($source, -90, 0);
                        break;
                    case 8:
                        $imageWidth = $cacheHeight;
                        $imageHeight = $cacheWidth;

                        $source = imagerotate($source, 90, 0);
                        break;
                }

                $cacheWidth = $imageWidth;
                $cacheHeight = $imageHeight;
            }

            // param rotation
            if ($rotation == 90 || $rotation == 270) {
                $imageWidth = $cacheHeight;
                $imageHeight = $cacheWidth;
            }
            $rotation = $rotation * -1;
            $source = imagerotate($source, $rotation, 0);
        }

        // crop
        $crop = array_merge(array(
            'left' => 0,
            'top' => 0,
            'width' => $imageWidth,
            'height' => $imageHeight,
            '_paramCrop' => $crop
        ), is_array($crop) ? $crop : array());
        if (is_array($crop['_paramCrop'])) {
            $crop['left'] = $crop['_paramCrop']['left'];
            $crop['top'] = $crop['_paramCrop']['top'];
            $crop['width'] = $crop['_paramCrop']['width'];
            $crop['height'] = $crop['_paramCrop']['height'];
        }

        // set default $width and $height
        $width = !$width ? $crop['width'] : $width;
        $height = !$height ? $crop['height'] : $height;

        // resize
        if ($crop['width'] < $width && $crop['height'] < $height) {
            $width = $crop['width'];
            $height = $crop['height'];
            $hasResizing = false;
        }
        if ($hasResizing) {
            $ratio = $width/$height;

            if ($crop['_paramCrop'] === true) {
                if ($imageRatio >= $ratio) {
                    $crop['newWidth'] = $crop['width'] / ($crop['height'] / $height);
                    $crop['newHeight'] = $height;
                } else {
                    $crop['newHeight'] = $crop['height'] / ($crop['width'] / $width);
                    $crop['newWidth'] = $width;
                }

                $crop['left'] = 0 - ($crop['newWidth'] - $width) / 2;
                $crop['top'] = 0 - ($crop['newHeight'] - $height) / 2;
            } else {
                $newRatio = $crop['width'] / $crop['height'];

                if ($ratio > $newRatio) {
                    $width = $height * $newRatio;
                } else {
                    $height = $width / $newRatio;
                }
            }
        }

        // save
        $dest = null;
        $destExt = strtolower(substr($destination, strrpos($destination, '.') + 1));
        if (pathinfo($destination, PATHINFO_EXTENSION)) {
            if (in_array($destExt, array('gif', 'jpg', 'jpeg', 'png'))) {
                if ($destExt == 'gif')
                    $imageType = IMAGETYPE_GIF;
                if ($destExt == 'jpg' || $destExt == 'jpeg')
                    $imageType = IMAGETYPE_JPEG;
                if ($destExt == 'png')
                    $imageType = IMAGETYPE_PNG;
            }
        } else {
            $imageType = IMAGETYPE_JPEG;
            $destination .= '.jpg';
        }
        switch($imageType) {
            case IMAGETYPE_GIF:
                $dest = imagecreatetruecolor($width, $height);
                $background = imagecolorallocatealpha($dest, 255, 255, 255, 1);
                imagecolortransparent($dest, $background);
                imagefill($dest, 0, 0 , $background);
                imagesavealpha($dest, true);
                break;
            case IMAGETYPE_JPEG:
                $dest = imagecreatetruecolor($width, $height);
                $background = imagecolorallocate($dest, 255, 255, 255);
                imagefilledrectangle($dest, 0, 0, $width, $height, $background);
                break;
            case IMAGETYPE_PNG:
                if (!imageistruecolor($source)) {
                    $dest = imagecreate($width, $height);
                    $background = imagecolorallocatealpha($dest, 255, 255, 255, 1);
                    imagecolortransparent($dest, $background);
                    imagefill($dest, 0, 0 , $background);
                } else {
                    $dest = imagecreatetruecolor($width, $height);
                }
                imagealphablending($dest, false);
                imagesavealpha($dest, true);
                break;
            default:
                return false;
        }

        imageinterlace($dest, true);

        imagecopyresampled(
            $dest,
            $source,
            isset($crop['newWidth']) ? $crop['left'] : 0,
            isset($crop['newHeight']) ? $crop['top'] : 0,
            !isset($crop['newWidth']) ? $crop['left'] : 0,
            !isset($crop['newHeight']) ? $crop['top'] : 0,
            isset($crop['newWidth']) ? $crop['newWidth'] : $width,
            isset($crop['newHeight']) ? $crop['newHeight'] : $height,
            $crop['width'],
            $crop['height']
        );

        switch ($imageType) {
            case IMAGETYPE_GIF:
                imagegif($dest, $destination);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($dest, $destination, $quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($dest, $destination, 10-$quality/10);
                break;
        }

        imagedestroy($source);
        imagedestroy($dest);

        return true;
    }

    /**
     * uploadFiles method
     * Process and upload the files
     *
     * @private
     * @return {null, Array}
     */
    private function uploadFiles() {
        $data = array(
            "hasWarnings" => false,
            "isSuccess" => false,
            "warnings" => array(),
            "files" => array()
        );
        $listInput = $this->field['listInput'];
        $uploadDir = str_replace(getcwd() . '/', '', $this->options['uploadDir']);
        $chunk = isset($_POST['_chunkedd']) && count($this->field['input']['name']) == 1 ? json_decode($_POST['_chunkedd'], true) : false;
        if ($this->field['input']) {
            // validate ini settings and some generally options
            $validate = $this->validate();
            $data['isSuccess'] = true;

            if ($validate === true) {
                // process the files
                for($i = 0; $i < count($this->field['input']['name']); $i++) {
                    $file = array(
                        'name' => $this->field['input']['name'][$i],
                        'tmp_name' => $this->field['input']['tmp_name'][$i],
                        'type' => $this->field['input']['type'][$i],
                        'error' => $this->field['input']['error'][$i],
                        'size' => $this->field['input']['size'][$i]
                    );

                    // chunk
                    if ($chunk) {
                        if (isset($chunk['isFirst']))
                            $chunk['temp_name'] = ProjectFunctions::generateTimeBasedRandomString();//$this->random_string(6) . time();

                        $tmp_name = $uploadDir . '.unconfirmed_' . $this->filterFilename($chunk['temp_name']);
                        if (!isset($chunk['isFirst']) && !file_exists($tmp_name))
                            continue;
                        $sp = fopen($file['tmp_name'], 'r');
                        $op = fopen($tmp_name, isset($chunk['isFirst']) ? 'w' : 'a');
                        while (!feof($sp)) {
                            $buffer = fread($sp, 512);
                            fwrite($op, $buffer);
                        }

                        // close handles
                        fclose($op);
                        fclose($sp);

                        if (isset($chunk['isLast'])) {
                            $file['tmp_name'] = $tmp_name;
                            $file['name'] = $chunk['name'];
                            $file['type'] = $chunk['type'];
                            $file['size'] = $chunk['size'];
                        } else {
                            echo json_encode(array(
                                'fileuploader' => array(
                                    'temp_name' => $chunk['temp_name']
                                )
                            ));
                            exit;
                        }
                    }

                    $metas = array();
                    $metas['tmp_name'] = $file['tmp_name'];
                    $metas['extension'] = strtolower(substr(strrchr($file['name'], "."), 1));
                    $metas['type'] = $file['type'];
                    $metas['old_name'] = $file['name'];
                    $metas['old_title'] = substr($metas['old_name'], 0, (strlen($metas['extension']) > 0 ? -(strlen($metas['extension'])+1) : strlen($metas['old_name'])));
                    $metas['size'] = $file['size'];
                    $metas['size2'] = $this->formatSize($file['size']);
                    $metas['name'] = $this->generateFileName($this->options['title'], array(
                        'title' => $metas['old_title'],
                        'size' => $metas['size'],
                        'extension' => $metas['extension']
                    ));
                    $metas['title'] = substr($metas['name'], 0, (strlen($metas['extension']) > 0 ? -(strlen($metas['extension'])+1) : strlen($metas['name'])));
                    $metas['file'] = $uploadDir . $metas['name'];
                    $metas['replaced'] = file_exists($metas['file']);
                    $metas['date'] = date('r');
                    $metas['error'] = $file['error'];
                    $metas['chunked'] = $chunk;
                    ksort($metas);

                    // validate file
                    $validateFile = $this->validate(array_merge($metas, array('index' => $i, 'tmp' => $file['tmp_name'])));

                    // check if file is in listInput
                    $listInputName = '0:/' . $metas['old_name'];
                    $fileInList = $listInput === null || in_array($listInputName, $listInput['list']);

                    // add file to memory
                    if ($validateFile === true) {
                        if ($fileInList) {
                            $fileListIndex = 0;

                            if ($listInput) {
                                $fileListIndex = array_search($listInputName, $listInput['list']);
                                $metas['listProps'] = $listInput['values'][$fileListIndex];
                                unset($listInput['list'][$fileListIndex]);
                                unset($listInput['values'][$fileListIndex]);
                            }

                            $data['files'][] = $metas;
                        }
                    } else {
                        if ($metas['chunked'] && file_exists($metas['tmp_name']))
                            unlink($metas['tmp_name']);
                        if (!$fileInList)
                            continue;

                        $data['isSuccess'] = false;
                        $data['hasWarnings'] = true;
                        $data['warnings'][] = $validateFile;
                        $data['files'] = array();

                        break;
                    }
                }

                // upload the files
                if (!$data['hasWarnings']) {
                    foreach($data['files'] as $key => $file) {
                        if ($file['chunked'] ? rename($file['tmp_name'], $file['file']) : $this->options['move_uploaded_file']($file['tmp_name'], $file['file'])) {
                            unset($data['files'][$key]['chunked']);
                            unset($data['files'][$key]['error']);
                            unset($data['files'][$key]['tmp_name']);
                            $data['files'][$key]['uploaded'] = true;
                            $this->options['files'][] = $data['files'][$key];
                        } else {
                            unset($data['files'][$key]);
                        }
                    }
                }
            } else {
                $data['isSuccess'] = false;
                $data['hasWarnings'] = true;
                $data['warnings'][] = $validate;
            }
        } else {
            $lastPHPError = error_get_last();
            if ($lastPHPError && $lastPHPError['type'] == E_WARNING && $lastPHPError['line'] == 0) {
                $errorMessage = null;

                if (strpos($lastPHPError['message'], "POST Content-Length") != false)
                    $errorMessage = $this->codeToMessage(UPLOAD_ERR_INI_SIZE);
                if (strpos($lastPHPError['message'], "Maximum number of allowable file uploads") != false)
                    $errorMessage = $this->codeToMessage('max_number_of_files');

                if ($errorMessage != null) {
                    $data['isSuccess'] = false;
                    $data['hasWarnings'] = true;
                    $data['warnings'][] = $errorMessage;
                }

            }

            if ($this->options['required'] && (isset($_SERVER) && strtolower($_SERVER['REQUEST_METHOD']) == "post")) {
                $data['hasWarnings'] = true;
                $data['warnings'][] = $this->codeToMessage('required_and_no_file');
            }
        }

        // add listProp attribute to the files
        if ($listInput)
            foreach($this->getFileList() as $key=>$item) {
                if (!isset($item['listProps'])) {
                    $fileListIndex = array_search($item['file'], $listInput['list']);

                    if ($fileListIndex !== null) {
                        $this->options['files'][$key]['listProps'] = $listInput['values'][$fileListIndex];
                    }
                }

                if (isset($item['listProps'])) {
                    unset($this->options['files'][$key]['listProps']['file']);

                    if (empty($this->options['files'][$key]['listProps']))
                        unset($this->options['files'][$key]['listProps']);
                }
            }

        $data['files'] = $this->getUploadedFiles();

        // call file editor
        $this->editFiles();

        // call file sorter
        $this->sortFiles($data['files']);

        return $data;
    }

    /**
     * validation method
     * Check ini settings, field and files
     *
     * @private
     * @param $file {Array} File metas
     * @return {boolean, String}
     */
    private function validate($file = null) {
        if ($file == null) {
            // check ini settings and some generally options
            $ini = array(
                (boolean) ini_get('file_uploads'),
                (int) ini_get('upload_max_filesize'),
                (int) ini_get('post_max_size'),
                (int) ini_get('max_file_uploads'),
                (int) ini_get('memory_limit')
            );

            if (!$ini[0])
                return $this->codeToMessage('file_uploads');
            if ($this->options['required'] && (isset($_SERVER) && strtolower($_SERVER['REQUEST_METHOD']) == "post") && $this->field['count'] + count($this->options['files']) == 0)
                return $this->codeToMessage('required_and_no_file');
            if (($this->options['limit'] && $this->field['count'] + count($this->options['files']) > $this->options['limit']) || ($ini[3] != 0 && ($this->field['count']) > $ini[3]))
                return $this->codeToMessage('max_number_of_files');
            if (!file_exists($this->options['uploadDir']) && !is_writable($this->options['uploadDir']))
                return $this->codeToMessage('invalid_folder_path');

            $total_size = 0; foreach($this->field['input']['size'] as $key=>$value){ $total_size += $value; } $total_size = $total_size/1000000;
            if ($ini[2] != 0 && $total_size > $ini[2])
                return $this->codeToMessage('post_max_size');
            if ($this->options['maxSize'] && $total_size > $this->options['maxSize'])
                return $this->codeToMessage('max_files_size');
        } else {
            // check file
            if ($file['error'] > 0)
                return $this->codeToMessage($file['error'], $file);
            if ($this->options['extensions'] && (!in_array(strtolower($file['extension']), $this->options['extensions']) && !in_array(strtolower($file['type']), $this->options['extensions'])))
                return $this->codeToMessage('accepted_file_types', $file);
            if ($this->options['fileMaxSize'] && $file['size']/1000000 > $this->options['fileMaxSize'])
                return $this->codeToMessage('max_file_size', $file);
            if ($this->options['maxSize'] && $file['size']/1000000 > $this->options['maxSize'])
                return $this->codeToMessage('max_file_size', $file);
            $custom_validation = $this->options['validate_file']($file, $this->options);
            if ($custom_validation != true)
                return $custom_validation;
        }

        return true;
    }

    /**
     * getListInputFiles method
     * Get value from listInput
     *
     * @private
     * @param $name {String} FileUploader $_FILES name
     * @return {null, Array}
     */
    private function getListInputFiles($name = null) {
        $inputName = 'fileuploader-list-' . ($name ? $name : $this->field['name']);
        if (is_string($this->options['listInput']))
            $inputName = $this->options['listInput'];

        if (isset($_POST[$inputName]) && $this->isJSON($_POST[$inputName])) {
            $list = array(
                'list' => array(),
                'values' => json_decode($_POST[$inputName], true)
            );

            foreach($list['values'] as $key=>$value) {
                $list['list'][] = $value['file'];
            }

            return $list;
        }

        return null;
    }

    /**
     * editFiles method
     * Edit all files that have an editor from Front-End
     *
     * @private
     * @return void
     */
    private function editFiles() {
        if ($this->options['editor'] === false)
            return;

        foreach($this->getFileList() as $key=>$item) {
            $file = !isset($item['relative_file']) ? $item['file'] : $item['relative_file'];

            // add editor to files
            if (isset($item['listProps']) && isset($item['listProps']['editor'])) {
                $item['editor'] = $item['listProps']['editor'];
            }
            if (isset($item['uploaded']) && isset($_POST['_editorr']) && $this->isJSON($_POST['_editorr']) && count($this->field['input']['name']) == 1) {
                $item['editor'] = json_decode($_POST['_editorr'], true);
            }

            // edit file
            if (($this->options['editor'] != null || isset($item['editor']) && file_exists($file) && strpos($item['type'], 'image/') === 0)) {
                $width = isset($this->options['editor']['maxWidth']) ? $this->options['editor']['maxWidth'] : null;
                $height = isset($this->options['editor']['maxHeight']) ? $this->options['editor']['maxHeight'] : null;
                $quality = isset($this->options['editor']['quality']) ? $this->options['editor']['quality'] : 90;
                $rotation = isset($item['editor']['rotation']) ? $item['editor']['rotation'] : 0;
                $crop = isset($this->options['editor']['crop']) ? $this->options['editor']['crop'] : false;
                $crop = isset($item['editor']['crop']) ? $item['editor']['crop'] : $crop;

                // edit
                self::resize($file, $width, $height, null, $crop, $quality, $rotation);
                unset($this->options['files'][$key]['editor']);
            }
        }
    }

    /**
     * sortFiles method
     * Sort all files that have an index from Front-End
     *
     * @private
     * @param $data - file list that also needs to be sorted
     * @return void
     */
    private function sortFiles(&$data = null) {
        foreach($this->options['files'] as $key=>$item) {
            if (isset($item['listProps']) && isset($item['listProps']['index']))
                $this->options['files'][$key]['index'] = $item['listProps']['index'];
        }

        $freeIndex = count($this->options['files']);
        if(isset($this->options['files'][0]['index']))
            usort($this->options['files'], function($a, $b) {
                global $freeIndex;

                if (!isset($a['index'])) {
                    $a['index'] = $freeIndex;
                    $freeIndex++;
                }

                if (!isset($b['index'])) {
                    $b['index'] = $freeIndex;
                    $freeIndex++;
                }

                return $a['index'] - $b['index'];
            });

        if ($data && isset($data[0]['index'])) {
            $freeIndex = count($data);
            usort($data, function($a, $b) {
                global $freeIndex;

                if (!isset($a['index'])) {
                    $a['index'] = $freeIndex;
                    $freeIndex++;
                }

                if (!isset($b['index'])) {
                    $b['index'] = $freeIndex;
                    $freeIndex++;
                }

                return $a['index'] - $b['index'];
            });
        }
    }

    /**
     * clean_chunked_files method
     * Remove chunked files from directory
     *
     * @public
     * @static
     * @param $directory {String} Directory scan
     * @param $time {String} Time difference
     * @return {String}
     */
    public static function clean_chunked_files($directory, $time = '-1 hour') {
        if (!is_dir($directory))
            return;

        $dir = scandir($directory);
        $files = array_diff($dir, array('.', '..'));
        foreach($files as $key=>$name) {
            $file = $directory . $name;
            if (strpos($name, '.unconfirmed_') === 0 && filemtime($file) < strtotime($time))
                unlink($file);
        }
    }

    /**
     * codeToMessage method
     * Translate a warning code into text
     *
     * @private
     * @param $code {Number, String}
     * @param $file {null, Array}
     * @return {String}
     */
    private function codeToMessage($code, $file = null) {
        $message = null;

        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;
            case 'accepted_file_types':
                $message = "File type is not allowed for " . $file['old_name'];
                break;
            case 'file_uploads':
                $message = "File uploading option in disabled in php.ini";
                break;
            case 'max_file_size':
                $message = $file['old_name'] . " is too large";
                break;
            case 'max_files_size':
                $message = "Files are too big";
                break;
            case 'max_number_of_files':
                $message = "Maximum number of files is exceeded";
                break;
            case 'required_and_no_file':
                $message = "No file was choosed. Please select one";
                break;
            case 'invalid_folder_path':
                $message = "Upload folder doesn't exist or is not writable";
                break;
            default:
                $message = "Unknown upload error";
                break;
        }

        return $message;
    }

    private function getFileAttribute($file, $attribute) {
        $result = null;

        if (isset($file['data'][$attribute]))
            $result = $file['data'][$attribute];
        if (isset($file[$attribute]))
            $result = $file[$attribute];

        return $result;
    }

    /**
     * formatSize method
     * Cover bytes to readable file size format
     *
     * @private
     * @param $bytes {Number}
     * @return {Number}
     */
    private function formatSize($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }elseif ($bytes > 0) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }else{
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * isJson method
     * Check if string is a valid json
     *
     * @private
     * @param $string {String}
     * @return {boolean}
     */
    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * filterFilename method
     * Remove invalid characters from filename
     *
     * @private
     * @param $filename {String}
     * @return {String}
     */
    private function filterFilename($filename) {
        $delimiter = '_';
        $invalidCharacters = array_merge(array_map('chr', range(0,31)), array("<", ">", ":", '"', "/", "\\", "|", "?", "*"));

        $filename = str_replace($invalidCharacters, $delimiter, $filename);
        $filename = preg_replace('/(' . preg_quote($delimiter, '/') . '){2,}/', '$1', $filename);

        return $filename;
    }

    /**
     * generateFileName method
     * Generated a new file name
     *
     * @private
     * @param $conf {null, String, Array} FileUploader title option
     * @param $file {Array} File data as title, extension and size
     * @param $skip_replace_check {boolean} Used only for recursive auto generating file name to exclude replacements
     * @return {String}
     */
    private function generateFilename($conf, $file, $skip_replace_check = false) {
        $conf = !is_array($conf) ? array($conf) : $conf;
        $type = $conf[0];
        $length = isset($conf[1]) ? (int) $conf[1] : 12;
        $random_string = ProjectFunctions::generateTimeBasedRandomString();//$this->random_string($length);
        $extension = !empty($file['extension']) ? "." . $file['extension'] : "";
        $string = "";

        switch($type) {
            case null:
            case "auto":
                $string = $random_string;
                break;
            case "name":
                $string = $file['title'];
                break;
            default:
                $string = $type;
                $string_extension = substr(strrchr($string, "."), 1);

                $string = str_replace("{random}", $random_string, $string);
                $string = str_replace("{file_name}", $file['title'], $string);
                $string = str_replace("{file_size}", $file['size'], $string);
                $string = str_replace("{timestamp}", time(), $string);
                $string = str_replace("{date}", date('Y-n-d_H-i-s'), $string);
                $string = str_replace("{extension}", $file['extension'], $string);

                if (!empty($string_extension)) {
                    if ($string_extension != "{extension}") {
                        $type = substr($string, 0, -(strlen($string_extension) + 1));
                        $extension = $file['extension'] = $string_extension;
                    } else {
                        $type = substr($string, 0, -(strlen($file['extension']) + 1));
                        $extension = '';
                    }
                }
        }
        if ($extension && !preg_match('/'.$extension.'$/', $string))
            $string .= $extension;

        // generate another filename if a file with the same name already exists
        // only when replace options is true
        if (!$this->options['replace'] && !$skip_replace_check) {
            $title = $file['title'];
            $i = 1;
            while (file_exists($this->options['uploadDir'] . $string)) {
                $file['title'] = $title . " ({$i})";
                $conf[0] = $type == "auto" || $type == "name" || strpos($string, "{random}") !== false ? $type : $type  . " ({$i})";
                $string = $this->generateFileName($conf, $file, true);
                $i++;
            }
        }

        return $this->filterFilename($string);
    }

    /**
     * random_string method
     * Generate a random string
     *
     * @public
     * @param $length {Number} Number of characters
     * @return {String}
     */
    private function random_string($length = 12) {
        //JGL: Improving this to use the current timestamp
        return substr(str_shuffle("_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    /**
     * mime_content_type method
     * Get the mime_content_type of a file
     *
     * @public
     * @static
     * @param $file {String} File location
     * @return {String}
     */
    public static function mime_content_type($file) {
        if (function_exists('mime_content_type')) {
            return mime_content_type($file);
        } else {
            $mime_types = array(
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',

                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',

                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

                // audio/video
                'mp3' => 'audio/mpeg',
                'mp4' => 'video/mp4',
                'webM' => 'video/webm',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',

                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',

                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            );
            $ext = strtolower(substr(strrchr($file, "."), 1));

            if (array_key_exists($ext, $mime_types)) {
                return $mime_types[$ext];
            } elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $file);
                finfo_close($finfo);
                return $mimetype;
            } else {
                return 'application/octet-stream';
            }
        }
    }
}
//endregion

//region Project API related
abstract class PublicApi extends PublicApi_Base {

}
//endregion

//region Email Related
abstract class EmailManager extends EmailManager_Framework {
}
abstract class EmailSettings extends EmailSettings_Framework {
    public static $SMTPServer = 'smtp1.example.com';
    public static $SMTPUsername = 'user@example.com';
    public static $SMTPPassword = 'secret';
    public static $SMTPPort = 587;
    public static $SMTPDebugMode = \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;// To enable verbose debug output, use DEBUG_SERVER
    public static $SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    public static $SMTPForceSecurityProtocol = false;
    public static $SMTPAutoTLS = false;
}
//endregion
?>