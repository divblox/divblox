<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class ApiKeyController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['ValidFromDate',];
    protected $NumberValidationAttributeArray = [];
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getObjectData() {
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        
        $this->presentOutput();
    }
    
    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No ApiKey object provided");
            $this->presentOutput();
        }
        $ApiKeyObj = json_decode($this->getInputValue("ObjectData"),true);
        $ApiKeyToCreateObj = new ApiKey();
        foreach($ApiKeyToCreateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($ApiKeyObj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($ApiKeyObj[$Attribute]) == 0) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($ApiKeyObj[$Attribute])) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType("ApiKey", $Attribute),["DATE","DATETIME"])) {
                    if (is_string($ApiKeyObj[$Attribute]) && (strlen($ApiKeyObj[$Attribute]) > 0)) {
                        $DateObj = new dxDateTime($ApiKeyObj[$Attribute]);
                        $ApiKeyToCreateObj->$Attribute = $DateObj;
                    }
                } else {
                    $ApiKeyToCreateObj->$Attribute = $ApiKeyObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $Done = false;
        while (!$Done) {
            $ApiKeyCandidate = ProjectFunctions::generateRandomString(48);
            $ExistingApiKeyObj = ApiKey::LoadByApiKey($ApiKeyCandidate);
            if (is_null($ExistingApiKeyObj)) {
                $Done = true;
            }
        }
        $ApiKeyToCreateObj->ApiKey = $ApiKeyCandidate;
        $ApiKeyToCreateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object created");
        $this->setReturnValue("Id",$ApiKeyToCreateObj->Id);
        $this->presentOutput();
    }
}
$ComponentObj = new ApiKeyController("api_key_administration_create");
?>