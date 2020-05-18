<?php
require("../../../../divblox/divblox.php");
class AuthenticationController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function doAuthentication() {
        if (is_null($this->getInputValue("Username")) ||
            is_null($this->getInputValue("Password"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Invalid input provided");
            $this->presentOutput();
        }
        $AccountObj = Account::LoadByUsername($this->getInputValue("Username"));
        if (is_null($AccountObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Invalid input provided");
            $this->presentOutput();
        }
        if ($AccountObj->AccessBlocked == 1) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Account access blocked");
            $this->presentOutput();
        }
        if (!password_verify($this->getInputValue("Password"), $AccountObj->Password)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Invalid input provided");
            $this->presentOutput();
        }
        // JGL: We are authenticated. Let's link the current authentication token/client connection to the account obj
        $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($this->CurrentClientAuthenticationToken);
        if (is_null($ClientAuthenticationTokenObj)) {
            if ($this->initializeNewAuthenticationToken()) {
                $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken($this->CurrentClientAuthenticationToken);
                if (is_null($ClientAuthenticationTokenObj)) {
                    $this->setReturnValue("Result","Failed");
                    $this->setReturnValue("Message","Could not initialize authentication token");
                    $this->presentOutput();
                }
            }
        }
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        if (is_null($ClientConnectionObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Could not initialize authentication token");
            $this->presentOutput();
        }
        $ClientConnectionObj->AccountObject = $AccountObj;
        ProjectFunctions::linkPushRegistrationsToAccount($ClientAuthenticationTokenObj,$AccountObj);
        try {
            $ClientConnectionObj->Save();
            $this->setReturnValue("Result","Success");
            $UserRole = "";
            if (!is_null($AccountObj->UserRoleObject)) {
                $UserRole = $AccountObj->UserRoleObject->Role;
            }
            $this->setReturnValue("UserRole",$UserRole);
            $this->setReturnValue("AccountId",$AccountObj->Id);
            $this->presentOutput();
        } catch (dxCallerException $e) {

        }
        $this->setReturnValue("Result","Failed");
        $this->setReturnValue("Message","Unknown error occurred");
        $this->presentOutput();
    }
}

$ComponentObj = new AuthenticationController("authentication");
?>