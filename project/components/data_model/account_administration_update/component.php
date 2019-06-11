<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class AccountController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['EmailAddress','Username'];
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
        if (is_null($AccountObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Account not found");
            $this->presentOutput();
        } else {
            $this->setReturnValue("Object",json_decode($AccountObj->getJson()));
            $this->setReturnValue("UserRoleList",$this->getUserRoleList($AccountObj));

            $this->presentOutput();
        }
    }
    public function getUserRoleList(Account $AccountObj = null) {
        if (UserRole::QueryCount(dxQ::All()) > 50) {
            if (!is_null($AccountObj) && !is_null($AccountObj->UserRoleObject)) {
                // JGL: We only return the selected UserRole, since the selectable list will be too big.
                // In this case, the developer will need to implement select functionality to link another object
                $ObjectArray = DatabaseHelper::getObjectArray('UserRole', array("Id","Role"), "Id = '".$AccountObj->UserRoleObject->Id."'", null, 50, null, $ErrorInfo);
                array_push($ObjectArray, array("Id" => "DATASET TOO LARGE"));
                return $ObjectArray;
            }
        }
        $ObjectArray = DatabaseHelper::getObjectArray('UserRole', array("Id","Role"), null, null, 50, null, $ErrorInfo);
        return $ObjectArray;
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
    public function deleteObjectData() {
        if (is_null($this->getInputValue("Id"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No Account Id provided");
            $this->presentOutput();
        }
        $AccountObj = Account::Load($this->getInputValue("Id"));
        if (is_null($AccountObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Account not found");
            $this->presentOutput();
        } else {
            $AccountObj->Delete();
            $this->setReturnValue("Result","Success");
            $this->setReturnValue("Message","Account deleted");
            $this->presentOutput();
        }
    }
}
$ComponentObj = new AccountController("account_administration_update");
?>