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
        if (is_null($this->getInputValue("Id"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No AdditionalAccountInformation Id provided");
            $this->presentOutput();
        }
        $AdditionalAccountInformationObj = AdditionalAccountInformation::Load($this->getInputValue("Id"));
        if (is_null($AdditionalAccountInformationObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","AdditionalAccountInformation not found");
            $this->presentOutput();
        } else {
            $this->setReturnValue("Object",json_decode($AdditionalAccountInformationObj->getJson()));
            
            $this->presentOutput();
        }
    }
    
    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No AdditionalAccountInformation object provided");
            $this->presentOutput();
        }
        $AdditionalAccountInformationObj = json_decode($this->getInputValue("ObjectData"),true);
        $AdditionalAccountInformationToUpdateObj = AdditionalAccountInformation::Load($this->getInputValue("Id"));
        if (is_null($AdditionalAccountInformationToUpdateObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","AdditionalAccountInformation not found");
            $this->presentOutput();
        }
        foreach($AdditionalAccountInformationToUpdateObj->getIterator() as $Attribute => $Value) {
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
                        $AdditionalAccountInformationToUpdateObj->$Attribute = $DateObj;
                    }
                } else {
                    $AdditionalAccountInformationToUpdateObj->$Attribute = $AdditionalAccountInformationObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AdditionalAccountInformationToUpdateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object updated");
        $this->setReturnValue("Id",$AdditionalAccountInformationToUpdateObj->Id);
        $this->presentOutput();
    }
    public function deleteObjectData() {
        if (is_null($this->getInputValue("Id"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No AdditionalAccountInformation Id provided");
            $this->presentOutput();
        }
        $AdditionalAccountInformationObj = AdditionalAccountInformation::Load($this->getInputValue("Id"));
        if (is_null($AdditionalAccountInformationObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","AdditionalAccountInformation not found");
            $this->presentOutput();
        } else {
            $AdditionalAccountInformationObj->Delete();
            $this->setReturnValue("Result","Success");
            $this->setReturnValue("Message","AdditionalAccountInformation deleted");
            $this->presentOutput();
        }
    }
}
$ComponentObj = new AdditionalAccountInformationController("account_additional_info_manager_update");
?>