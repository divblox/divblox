<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudClientAuthenticationToken.class.php');
ApiCrudClientAuthenticationToken::initCrudApi("ClientAuthenticationToken");
ApiCrudClientAuthenticationToken::doApiOperationDefinitions();
PublicApi::addApiOperation("registerDevice",
    ["DeviceUuid","DevicePlatform","DeviceOs"],
    ["Message" => "Populated if an error occurred",
        "DeviceLinkedAuthenticationToken" => "[Resulting authentication token that must be stored on the device for future requests]"],
    "Register Device",
    "Used to register a native mobile device on the system.
    This process acquires a more permanent AuthenticationToken to improve user experience on native devices. All input values are optional");
ApiCrudClientAuthenticationToken::executeCrudApi();

// Operations
function createClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::create();
}
function retrieveClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::retrieve();
}
function updateClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::update();
}
function deleteClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::delete();
}
function listClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::retrieveList();
}
function registerDevice() {
    $ClientAuthenticationObj = ProjectFunctions::getCurrentAuthTokenObject(PublicApi::$AuthenticationToken);
    if (is_null($ClientAuthenticationObj)) {
        PublicApi::setApiResult(false);
        PublicApi::addApiOutput("Message","Failed to initialize authentication token. Please retry.");
        PublicApi::printApiResult();
    }
    if ($ClientAuthenticationObj->IsNative) {
        PublicApi::setApiResult(true);
        PublicApi::addApiOutput("DeviceLinkedAuthenticationToken",$ClientAuthenticationObj->Token);
        PublicApi::printApiResult();
    }
    
    $DeviceUuid = PublicApi::getInputParameter("DeviceUuid");
    $DevicePlatform = PublicApi::getInputParameter("DevicePlatform");
    $DeviceOs = PublicApi::getInputParameter("DeviceOs");
    
    PublicApi::initializeNewAuthenticationToken(); //JGL: This is to ensure that we generate a NEW token. If we don't do this, someone could've copied this token and be using it to get indefinite access to a user's account
    $ClientAuthenticationObj = ProjectFunctions::getCurrentAuthTokenObject(PublicApi::$AuthenticationToken);
    if (is_null($ClientAuthenticationObj)) {
        PublicApi::setApiResult(false);
        PublicApi::addApiOutput("Message","Failed to initialize authentication token. Please retry.");
        PublicApi::printApiResult();
    }
    $ClientAuthenticationObj->IsNative = 1;
    $ClientAuthenticationObj->DeviceRegistrationDateTime = dxDateTime::Now();
    $ClientAuthenticationObj->DeviceUuid = $DeviceUuid;
    $ClientAuthenticationObj->DevicePlatform = $DevicePlatform;
    $ClientAuthenticationObj->DeviceOs = $DeviceOs;
    $ClientAuthenticationObj->Save();
    PublicApi::setApiResult(true);
    PublicApi::addApiOutput("DeviceLinkedAuthenticationToken",$ClientAuthenticationObj->Token);
    PublicApi::printApiResult();
}
?>
