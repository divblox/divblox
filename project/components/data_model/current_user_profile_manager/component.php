<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class AccountController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['EmailAddress','Username',];
    protected $NumberValidationAttributeArray = [];
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getObjectData() {
        if (is_null($this->getInputValue("Id"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No Account Id provided");
            $this->presentOutput();
        }
        $AccountObj = Account::Load($this->getInputValue("Id"));
        $ProfilePicturePath =  'project/assets/images/divblox_profile_picture_placeholder.svg';
        if (!is_null($AccountObj->ProfilePicturePath) && (file_exists(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$AccountObj->ProfilePicturePath))) {
            $ProfilePicturePath = $AccountObj->ProfilePicturePath;
        }
        if (is_null($AccountObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Account not found");
            $this->presentOutput();
        } else {
            $AccountJson = json_decode($AccountObj->getJson());
            $AccountJson->ProfilePicturePath = $ProfilePicturePath;
            $this->setReturnValue("Object",$AccountJson);
            $this->presentOutput();
        }
    }

    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No Account object provided");
            $this->presentOutput();
        }
        $AccountObj = json_decode($this->getInputValue("ObjectData"),true);
        $AccountToUpdateObj = Account::Load($this->getInputValue("Id"));
        if (is_null($AccountToUpdateObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Account not found");
            $this->presentOutput();
        }
        foreach($AccountToUpdateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($AccountObj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($AccountObj[$Attribute]) == 0) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($AccountObj[$Attribute])) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType("Account", $Attribute),["DATE","DATETIME"])) {
                    if (strlen($AccountObj[$Attribute]) > 2) {
                        $DateObj = new dxDateTime($AccountObj[$Attribute]);
                        $AccountToUpdateObj->$Attribute = $DateObj;
                    }
                } elseif ($Attribute == "Password") {
                    if (strlen($AccountObj[$Attribute]) > 0) {
                        $AccountToUpdateObj->$Attribute = password_hash($AccountObj[$Attribute],PASSWORD_BCRYPT);
                    }
                } else {
                    $AccountToUpdateObj->$Attribute = $AccountObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AccountToUpdateObj->FullName = $AccountToUpdateObj->FirstName.' '.$AccountToUpdateObj->MiddleNames.' '.$AccountToUpdateObj->LastName;
        if (strlen(trim($AccountToUpdateObj->FullName)) == 0) {
            $AccountToUpdateObj->FullName = "N/A";
        }
        $AccountToUpdateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object updated");
        $this->setReturnValue("Id",$AccountToUpdateObj->Id);
        $this->presentOutput();
    }
}
$ComponentObj = new AccountController("current_user_profile_manager");
?>