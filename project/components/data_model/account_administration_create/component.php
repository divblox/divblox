<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class AccountController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['FirstName','EmailAddress','Username',];
    protected $NumberValidationAttributeArray = [];
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getObjectData() {
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("UserRoleList",$this->getUserRoleList());

        $this->presentOutput();
    }
    public function getUserRoleList() {
        if (UserRole::QueryCount(dxQ::All()) > 50) {
            return [array("Id" => "DATASET TOO LARGE")];
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
        $AccountToCreateObj = new Account();
        foreach($AccountToCreateObj->getIterator() as $Attribute => $Value) {
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
                    if (is_string($AccountObj[$Attribute])) {
                        $DateObj = new dxDateTime($AccountObj[$Attribute]);
                        $AccountToCreateObj->$Attribute = $DateObj;
                    }
                } elseif ($Attribute == "Password") {
                    $AccountToCreateObj->$Attribute = password_hash($AccountObj[$Attribute],PASSWORD_BCRYPT);
                } else {
                    $AccountToCreateObj->$Attribute = $AccountObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AccountToCreateObj->FullName = $AccountToCreateObj->FirstName.' '.$AccountToCreateObj->MiddleNames.' '.$AccountToCreateObj->LastName;
        if (strlen(trim($AccountToCreateObj->FullName)) == 0) {
            $AccountToCreateObj->FullName = "N/A";
        }
        $AccountToCreateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object created");
        $this->setReturnValue("Id",$AccountToCreateObj->Id);
        $this->presentOutput();
    }
}
$ComponentObj = new AccountController("account_administration_create");
?>