<?php
require("../../../../divblox/divblox.php");
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);

class AccountController extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = ['FirstName', 'LastName', 'EmailAddress', 'Password',];
    protected $NumberValidationAttributeArray = [];
    protected $UserRoleToRegister = 'User';

    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }

    public function getObjectData() {
        $this->setResult(true);
        $this->setReturnValue("Message", "");

        $this->presentOutput();
    }

    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setResult(false);
            $this->setReturnValue("Message", "No Account object provided");
            $this->presentOutput();
        }
        $AccountObj = json_decode($this->getInputValue("ObjectData"), true);
        $AccountToCreateObj = new Account();
        foreach ($AccountToCreateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($AccountObj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($AccountObj[$Attribute]) == 0) {
                        $this->setResult(false);
                        $this->setReturnValue("Message", "$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($AccountObj[$Attribute])) {
                        $this->setResult(false);
                        $this->setReturnValue("Message", "$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType("Account", $Attribute), ["DATE", "DATETIME"])) {
                    $DateObj = new dxDateTime($AccountObj[$Attribute]);
                    $AccountToCreateObj->$Attribute = $DateObj;
                } else if ($Attribute == "Password") {
                    $AccountToCreateObj->$Attribute = password_hash($AccountObj[$Attribute], PASSWORD_BCRYPT);
                } else if ($Attribute == "EmailAddress") {
                    $AccountToCreateObj->$Attribute = $AccountObj[$Attribute];
                    $ExistingAccountCount = Account::QueryCount(dxQ::Equal(dxQN::Account()->Username, $AccountObj[$Attribute]));
                    if ($ExistingAccountCount > 0) {
                        $this->setResult(false);
                        $this->setReturnValue("Message", "$Attribute already exists");
                        $this->presentOutput();
                    }
                    $AccountToCreateObj->Username = $AccountObj[$Attribute];
                } else {
                    $AccountToCreateObj->$Attribute = $AccountObj[$Attribute];
                }
            } else if (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setResult(false);
                $this->setReturnValue("Message", "$Attribute not provided");
                $this->presentOutput();
            }
        }
        $AccountToCreateObj->UserRoleObject = UserRole::LoadByRole($this->UserRoleToRegister);
        if (is_null($AccountToCreateObj->UserRoleObject)) {
            $this->setResult(false);
            $this->setReturnValue("Message", "User role configuration incorrect");
            $this->presentOutput();
        }
        $AccountToCreateObj->FullName = '';
        if (strlen(trim($AccountToCreateObj->FirstName)) > 0) {
            $AccountToCreateObj->FullName .= $AccountToCreateObj->FirstName;
        }
        if (strlen(trim($AccountToCreateObj->MiddleNames)) > 0) {
            $AccountToCreateObj->FullName .= ' '.$AccountToCreateObj->MiddleNames;
        }
        if (strlen(trim($AccountToCreateObj->LastName)) > 0) {
            $AccountToCreateObj->FullName .= ' '.$AccountToCreateObj->LastName;
        }
        if (strlen(trim($AccountToCreateObj->FullName)) == 0) {
            $AccountToCreateObj->FullName = "N/A";
        }
        $AccountToCreateObj->Save();

        // JGL: We are authenticated. Let's link the current authentication token/client connection to the account obj
        $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($this->CurrentClientAuthenticationToken);
        if (is_null($ClientAuthenticationTokenObj)) {
            if ($this->initializeNewAuthenticationToken()) {
                $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($this->CurrentClientAuthenticationToken);
                if (is_null($ClientAuthenticationTokenObj)) {
                    $this->setResult(false);
                    $this->setReturnValue("Message", "Could not initialize authentication token");
                    $this->presentOutput();
                }
            }
        }
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        if (is_null($ClientConnectionObj)) {
            $this->setResult(false);
            $this->setReturnValue("Message", "Could not initialize authentication token");
            $this->presentOutput();
        }
        $ClientConnectionObj->AccountObject = $AccountToCreateObj;
        ProjectFunctions::linkPushRegistrationsToAccount($ClientAuthenticationTokenObj, $AccountToCreateObj);
        try {
            $ClientConnectionObj->Save();
            $this->setResult(true);
            $UserRole = "";
            if (!is_null($AccountToCreateObj->UserRoleObject)) {
                $UserRole = $AccountToCreateObj->UserRoleObject->Role;
                $this->setReturnValue("UserRole", $UserRole);
            }
        } catch (dxCallerException $e) {

        }

        $this->setResult(true);
        $this->setReturnValue("Message", "Object created");
        $this->setReturnValue("Id", $AccountToCreateObj->Id);
        $this->presentOutput();
    }
}

$ComponentObj = new AccountController("account_registration");
?>