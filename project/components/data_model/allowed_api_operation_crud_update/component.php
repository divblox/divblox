<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class AllowedApiOperationController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['ApiOperation',];
    protected $NumberValidationAttributeArray = [];
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getObjectData() {
        if (is_null($this->getInputValue("Id"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No AllowedApiOperation Id provided");
            $this->presentOutput();
        }
        $AllowedApiOperationObj = AllowedApiOperation::Load($this->getInputValue("Id"));
        if (is_null($AllowedApiOperationObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","AllowedApiOperation not found");
            $this->presentOutput();
        } else {
            $this->setReturnValue("Object",json_decode($AllowedApiOperationObj->getJson()));
            $this->setReturnValue("ApiOperationList",$this->getApiOperationList($AllowedApiOperationObj));
            
            $this->presentOutput();
        }
    }
    public function getApiOperationList(AllowedApiOperation $AllowedApiOperationObj = null) {
        if (ApiOperation::QueryCount(dxQ::All()) > 50) {
            if (!is_null($AllowedApiOperationObj) && !is_null($AllowedApiOperationObj->ApiOperationObject)) {
                // JGL: We only return the selected ApiOperation, since the selectable list will be too big.
                // In this case, the developer will need to implement select functionality to link another object
                $ObjectArray = DatabaseHelper::getObjectArray('ApiOperation', array("Id","OperationName"), "Id = '".$AllowedApiOperationObj->ApiOperationObject->Id."'", null, 50, null, $ErrorInfo);
                array_push($ObjectArray, array("Id" => "DATASET TOO LARGE"));
                return $ObjectArray;
            }
        }
        $ObjectArray = DatabaseHelper::getObjectArray('ApiOperation', array("Id","OperationName"), null, null, 50, null, $ErrorInfo);
        return $ObjectArray;
    }
    
    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No AllowedApiOperation object provided");
            $this->presentOutput();
        }
        $AllowedApiOperationObj = json_decode($this->getInputValue("ObjectData"),true);
        $AllowedApiOperationToUpdateObj = AllowedApiOperation::Load($this->getInputValue("Id"));
        if (is_null($AllowedApiOperationToUpdateObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","AllowedApiOperation not found");
            $this->presentOutput();
        }
        foreach($AllowedApiOperationToUpdateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($AllowedApiOperationObj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($AllowedApiOperationObj[$Attribute]) == 0) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($AllowedApiOperationObj[$Attribute])) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType("AllowedApiOperation", $Attribute),["DATE","DATETIME"])) {
                    if (is_string($AllowedApiOperationObj[$Attribute]) && (strlen($AllowedApiOperationObj[$Attribute]) > 0)) {
                        $DateObj = new dxDateTime($AllowedApiOperationObj[$Attribute]);
                        $AllowedApiOperationToUpdateObj->$Attribute = $DateObj;
                    }
                } else {
                    $AllowedApiOperationToUpdateObj->$Attribute = $AllowedApiOperationObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AllowedApiOperationToUpdateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object updated");
        $this->setReturnValue("Id",$AllowedApiOperationToUpdateObj->Id);
        $this->presentOutput();
    }
    public function deleteObjectData() {
        if (is_null($this->getInputValue("Id"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No AllowedApiOperation Id provided");
            $this->presentOutput();
        }
        $AllowedApiOperationObj = AllowedApiOperation::Load($this->getInputValue("Id"));
        if (is_null($AllowedApiOperationObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","AllowedApiOperation not found");
            $this->presentOutput();
        } else {
            $AllowedApiOperationObj->Delete();
            $this->setReturnValue("Result","Success");
            $this->setReturnValue("Message","AllowedApiOperation deleted");
            $this->presentOutput();
        }
    }
}
$ComponentObj = new AllowedApiOperationController("allowed_api_operation_crud_update");
?>