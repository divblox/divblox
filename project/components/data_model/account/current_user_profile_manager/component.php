<?php
require("../../../../../divblox/divblox.php");

class AccountController extends EntityInstanceComponentController {
    protected $EntityNameStr = "Account";
    protected $RequiredAttributeArray = ['EmailAddress', 'Username',];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }

    public function getObjectData() {
        $EntityNodeNameStr = $this->EntityNameStr;
        $EntityObj = $EntityNodeNameStr::Load($this->getInputValue("Id", true));
        $EntityJsonDecoded = array();
        if (!is_null($EntityObj)) {
            $EntityJsonDecoded = json_decode($EntityObj->getJson());
            $ProfilePicturePath = 'project/assets/images/divblox_profile_picture_placeholder.svg';
            if (!is_null($EntityJsonDecoded->ProfilePicturePath) && (file_exists(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$EntityJsonDecoded->ProfilePicturePath))) {
                $ProfilePicturePath = $EntityJsonDecoded->ProfilePicturePath;
            }
            $EntityJsonDecoded->ProfilePicturePath = $ProfilePicturePath;
        }
        $this->setReturnValue("Object", $EntityJsonDecoded);
        foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayValue) {
            $RelationshipList = $this->getRelationshipList($EntityObj, $Relationship);
            $this->setReturnValue($Relationship."List", $RelationshipList);
        }

        $this->getAdditionalReturnData($EntityObj);
        $this->setResult(true);
        $this->setReturnValue("Message", "");
        $this->presentOutput();
    }

    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setResult(false);
            $this->setReturnValue("Message", "No ".$this->EntityNameStr." object provided");
            $this->presentOutput();
        }
        $EntityNodeNameStr = $this->EntityNameStr;
        $InputEntityObj = json_decode($this->getInputValue("ObjectData"), true);
        $AccountToUpdateObj = $EntityNodeNameStr::Load($this->getInputValue("Id", true));
        if (is_null($AccountToUpdateObj)) {
            $AccountToUpdateObj = new $EntityNodeNameStr();
            $this->IsCreatingBool = true;
        }
        foreach ($AccountToUpdateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($InputEntityObj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($InputEntityObj[$Attribute]) == 0) {
                        $this->setResult(false);
                        $this->setReturnValue("Message", "$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($InputEntityObj[$Attribute])) {
                        $this->setResult(false);
                        $this->setReturnValue("Message", "$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType($this->EntityNameStr, $Attribute), ["DATE", "DATETIME"])) {
                    if (is_string($InputEntityObj[$Attribute]) && (strlen($InputEntityObj[$Attribute]) > 0)) {
                        $DateObj = new dxDateTime($InputEntityObj[$Attribute]);
                        $AccountToUpdateObj->$Attribute = $DateObj;
                    }
                } else {
                    if ($this->EntityNameStr == "Account") {
                        if ($Attribute == "Password") {
                            if (strlen($InputEntityObj[$Attribute]) > 0) {
                                $AccountToUpdateObj->$Attribute = password_hash($InputEntityObj[$Attribute], PASSWORD_BCRYPT);
                            }
                        } else {
                            $AccountToUpdateObj->$Attribute = $InputEntityObj[$Attribute];
                        }
                    } else {
                        $AccountToUpdateObj->$Attribute = $InputEntityObj[$Attribute];
                    }
                }
            } else if (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setResult(false);
                $this->setReturnValue("Message", "$Attribute not provided");
                $this->presentOutput();
            }
        }
        if ((ProjectFunctions::getDataSetSize($this->ConstrainByArray) > 0) && $this->IsCreatingBool) {
            foreach ($this->ConstrainByArray as $item) {
                $ConstrainByObjStr = $item.'Object';
                $AccountToUpdateObj->$ConstrainByObjStr = $item::Load($this->getInputValue('Constraining'.$item.'Id', true));
            }
        }

        $AccountToUpdateObj->FullName = $AccountToUpdateObj->FirstName.' '.$AccountToUpdateObj->MiddleNames.' '.$AccountToUpdateObj->LastName;
        $AccountToUpdateObj->FullName = str_replace($AccountToUpdateObj->FullName, "  ", " ");
        if (strlen(trim($AccountToUpdateObj->FullName)) == 0) {
            $AccountToUpdateObj->FullName = "N/A";
        }

        $this->doBeforeSaveActions($AccountToUpdateObj);
        $AccountToUpdateObj->Save();
        $this->doAfterSaveActions($AccountToUpdateObj);
        $this->setResult(true);
        $this->setReturnValue("Message", "Object updated");
        $this->setReturnValue("Id", $AccountToUpdateObj->Id);
        $this->presentOutput();
    }
}

$ComponentObj = new AccountController("current_user_profile_manager");
?>