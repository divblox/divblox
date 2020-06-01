<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class AccountController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['FirstName','LastName','EmailAddress','Password',];
    protected $NumberValidationAttributeArray = [];
    protected $UserRoleToRegister = 'User';
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getObjectData() {
        $this->setResult(true);
        $this->setReturnValue("Message","");

        $this->presentOutput();
    }

    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setResult(false);
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
                        $this->setResult(false);
                        $this->setReturnValue("Message","$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($AccountObj[$Attribute])) {
                        $this->setResult(false);
                        $this->setReturnValue("Message","$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType("Account", $Attribute),["DATE","DATETIME"])) {
                    $DateObj = new dxDateTime($AccountObj[$Attribute]);
                    $AccountToCreateObj->$Attribute = $DateObj;
                } elseif ($Attribute == "Password") {
                    $AccountToCreateObj->$Attribute = password_hash($AccountObj[$Attribute],PASSWORD_BCRYPT);
                } elseif ($Attribute == "EmailAddress") {
                    $AccountToCreateObj->$Attribute = $AccountObj[$Attribute];
                    $ExistingAccountCount = Account::QueryCount(dxQ::Equal(dxQN::Account()->Username, $AccountObj[$Attribute]));
                    if ($ExistingAccountCount > 0) {
                        $this->setResult(false);
                        $this->setReturnValue("Message","$Attribute already exists");
                        $this->presentOutput();
                    }
                    $AccountToCreateObj->Username = $AccountObj[$Attribute];
                } else {
                    $AccountToCreateObj->$Attribute = $AccountObj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setResult(false);
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AccountToCreateObj->UserRoleObject = UserRole::LoadByRole($this->UserRoleToRegister);
        if (is_null($AccountToCreateObj->UserRoleObject)) {
            $this->setResult(false);
            $this->setReturnValue("Message","User role configuration incorrect");
            $this->presentOutput();
        }
        $AccountToCreateObj->FullName = $AccountToCreateObj->FirstName.' '.$AccountToCreateObj->MiddleNames.' '.$AccountToCreateObj->LastName;
        if (strlen(trim($AccountToCreateObj->FullName)) == 0) {
            $AccountToCreateObj->FullName = "N/A";
        }
        $AccountToCreateObj->Save();
        $this->setResult(true);
        $this->setReturnValue("Message","Object created");
        $this->setReturnValue("Id",$AccountToCreateObj->Id);
        $this->presentOutput();
    }
}
$ComponentObj = new AccountController("account_registration");
?>