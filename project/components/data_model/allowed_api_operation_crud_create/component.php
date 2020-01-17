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
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("ApiOperationList",$this->getApiOperationList());
        
        $this->presentOutput();
    }
    public function getApiOperationList() {
        $ObjectArray = DatabaseHelper::getObjectArray('ApiOperation', array("Id","OperationName"), null, null, 1000, null, $ErrorInfo);
        return $ObjectArray;
    }
    
    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No AllowedApiOperation object provided");
            $this->presentOutput();
        }
        $AllowedApiOperationObj = json_decode($this->getInputValue("ObjectData"),true);
        $AllowedApiOperationToCreateObj = new AllowedApiOperation();
        foreach($AllowedApiOperationToCreateObj->getIterator() as $Attribute => $Value) {
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
                        $AllowedApiOperationToCreateObj->$Attribute = $DateObj;
                    }
                } else {
                    $AllowedApiOperationToCreateObj->$Attribute = $AllowedApiOperationObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AllowedApiOperationToCreateObj->ApiKeyObject = ApiKey::Load($this->getInputValue("ConstrainingApiKeyId",true));
        
        $AllowedApiOperationToCreateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object created");
        $this->setReturnValue("Id",$AllowedApiOperationToCreateObj->Id);
        $this->presentOutput();
    }
}
$ComponentObj = new AllowedApiOperationController("allowed_api_operation_crud_create");
?>