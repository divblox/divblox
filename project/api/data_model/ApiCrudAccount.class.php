<?php
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudBase.class.php');
class ApiCrudAccount extends ApiCrudBase {
    public static function initCrudApi($EntityNameStr = null) {
        parent::initCrudApi($EntityNameStr);
    }
    public static function doApiOperationDefinitions() {
        parent::doApiOperationDefinitions();
        PublicApi::addApiOperation("authenticateAccount",
            ["Username","Password"],
            ["Message" => "Populated if an error occurred",
                "UserRole" => "[The corresponding user role for the newly authenticated Account]",
                "AccountId" => "[The corresponding account id for the newly authenticated Account]"],
            "Authenticate Account",
            "Authenticates a user Account using the specified inputs");
    }
    public static function executeCrudApi() {
        parent::executeCrudApi();
    }
    
    public static function create() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::create();
    }
    public static function retrieve() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::retrieve();
    }
    public static function update() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::update();
    }
    public static function delete() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::delete();
    }
    public static function retrieveList() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::retrieveList();
    }
    public static function authenticate() {
        if (is_null(PublicApi::getInputParameter("Username")) ||
            is_null(PublicApi::getInputParameter("Password"))) {
            PublicApi::addApiOutput("Result","Failed");
            PublicApi::addApiOutput("Message","Invalid input provided");
            PublicApi::printApiResult();
        }
        $AccountObj = Account::LoadByUsername(PublicApi::getInputParameter("Username"));
        if (is_null($AccountObj)) {
            PublicApi::addApiOutput("Result","Failed");
            PublicApi::addApiOutput("Message","Invalid input provided");
            PublicApi::printApiResult();
        }
        if ($AccountObj->AccessBlocked == 1) {
            PublicApi::addApiOutput("Result","Failed");
            PublicApi::addApiOutput("Message","Account access blocked");
            PublicApi::printApiResult();
        }
        if (!password_verify(PublicApi::getInputParameter("Password"), $AccountObj->Password)) {
            PublicApi::addApiOutput("Result","Failed");
            PublicApi::addApiOutput("Message","Invalid input provided");
            PublicApi::printApiResult();
        }
        // JGL: We are authenticated. Let's link the current authentication token/client connection to the account obj
        $ClientAuthenticationTokenObj = ClientAuthenticationToken::LoadByToken(PublicApi::$AuthenticationToken);
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        if (is_null($ClientConnectionObj)) {
            PublicApi::addApiOutput("Result","Failed");
            PublicApi::addApiOutput("Message","Could not initialize authentication token");
            PublicApi::printApiResult();
        }
        $ClientConnectionObj->AccountObject = $AccountObj;
        $ClientConnectionObj->Save();
        PublicApi::addApiOutput("Result","Success");
        $UserRole = "";
        if (!is_null($AccountObj->UserRoleObject)) {
            $UserRole = $AccountObj->UserRoleObject->Role;
        }
        PublicApi::addApiOutput("UserRole",$UserRole);
        PublicApi::addApiOutput("AccountId",$AccountObj->Id);
        PublicApi::printApiResult();
        
        PublicApi::addApiOutput("Result","Failed");
        PublicApi::addApiOutput("Message","Unknown error occurred");
        PublicApi::printApiResult();
    }
}