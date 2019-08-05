<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class AdditionalAccountInformationController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['Value',];
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
            $this->setReturnValue("Message","No AdditionalAccountInformation object provided");
            $this->presentOutput();
        }
        $AdditionalAccountInformationObj = json_decode($this->getInputValue("ObjectData"),true);
        $AdditionalAccountInformationToCreateObj = new AdditionalAccountInformation();
        foreach($AdditionalAccountInformationToCreateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($AdditionalAccountInformationObj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($AdditionalAccountInformationObj[$Attribute]) == 0) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($AdditionalAccountInformationObj[$Attribute])) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType("AdditionalAccountInformation", $Attribute),["DATE","DATETIME"])) {
                    if (is_string($AdditionalAccountInformationObj[$Attribute])) {
                        $DateObj = new dxDateTime($AdditionalAccountInformationObj[$Attribute]);
                        $AdditionalAccountInformationToCreateObj->$Attribute = $DateObj;
                    }
                } else {
                    $AdditionalAccountInformationToCreateObj->$Attribute = $AdditionalAccountInformationObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AdditionalAccountInformationToCreateObj->AccountObject = Account::Load($this->getInputValue("ConstrainingAccountId",true));
        
        $AdditionalAccountInformationToCreateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object created");
        $this->setReturnValue("Id",$AdditionalAccountInformationToCreateObj->Id);
        $this->presentOutput();
    }
}
$ComponentObj = new AdditionalAccountInformationController("account_additional_info_manager_create");
?>