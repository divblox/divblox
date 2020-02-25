<?php
require("../../divblox/divblox.php");
// Start by declaring your operations and then calling the initApi function
PublicApi::addApiOperation("getUserRole",
    [],
    ["CurrentRole" => "[The user role of the current account]"],
    "Get User Role",
    "Returns the user role of the currently logged in user account");
PublicApi::addApiOperation("getCurrentAccountAttribute",
    ["attribute"],
    ["Attribute" => "[The value of the attribute requested for the current account.]"],
    "Get Current Account Attribute",
    "Returns the value of the requested attribute for the current account. If the value is not populated, NULL will be returned. The Password attribute will also NOT be returned as the actual value, but rather ********");
PublicApi::addApiOperation("logoutCurrentAccount",
    [],
    ["LogoutResult" => "[true/false]"],
    "Logout Current Account",
    "Logs out the current account on the server");
PublicApi::addApiOperation("updatePushRegistration",
    ["internal_id",
        "registration_id",
        "device_uuid",
        "device_platform",
        "device_os",
        "registration_date_time",
        "registration_status"],
    ["InternalId" => "[The internal ID for the push notification]"],
    "Update Push Registration",
    "Creates or modifies a push registration on the server. If something goes wrong, a message will be populated in the output.");
PublicApi::initApi("API endpoint for Divblox project global functions","Global Functions");

// Operations
function getUserRole() {
    PublicApi::addApiOutput("CurrentRole",ProjectFunctions::getCurrentUserRole());
    PublicApi::setApiResult(true); // You need to set this to true to indicate that the API executed successfully
    PublicApi::printApiResult();
}
function getCurrentAccountAttribute() {
    $AttributeStr = PublicApi::getInputParameter("attribute");
    if (is_null($AttributeStr)) {
        PublicApi::setApiResult(false); // You need to set this to true to indicate that the API executed successfully
        PublicApi::addApiOutput("Message","Invalid attribute provided");
        PublicApi::printApiResult();
    }
    $ExcludeArray = ["Password"];
    if (in_array($AttributeStr, $ExcludeArray)) {
        PublicApi::addApiOutput("Attribute","********");
    } else {
        $CandidateStr = ProjectFunctions::getCurrentAccountAttribute($AttributeStr);
        if (is_null($CandidateStr) ||
            strlen($CandidateStr) < 1) {
            PublicApi::addApiOutput("Attribute",null);
        } else {
            if ($AttributeStr == "ProfilePicturePath") {
                if (strtolower($CandidateStr) != 'anonymous') {
                    if (!file_exists(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$CandidateStr)) {
                        PublicApi::addApiOutput("Attribute",null);
                    }
                } else {
                    PublicApi::addApiOutput("Attribute",null);
                }
            } else {
                PublicApi::addApiOutput("Attribute",$CandidateStr);
            }
        }
    }
    
    PublicApi::setApiResult(true); // You need to set this to true to indicate that the API executed successfully
    PublicApi::printApiResult();
}
function logoutCurrentAccount() {
    PublicApi::addApiOutput("LogoutResult",ProjectFunctions::logoutCurrentAccount());
    PublicApi::setApiResult(true); // You need to set this to true to indicate that the API executed successfully
    PublicApi::printApiResult();
}
function updatePushRegistration() {
    $InternalUniqueId = PublicApi::getInputParameter("internal_id");
    $RegistrationId = PublicApi::getInputParameter("registration_id");
    $DeviceUuid = PublicApi::getInputParameter("device_uuid");
    $DevicePlatform = PublicApi::getInputParameter("device_platform");
    $DeviceOs = PublicApi::getInputParameter("device_os");
    if (is_null($DeviceOs)) {
        $DeviceOs = 'NOT SPECIFIED';
    }
    $RegistrationDateTime = PublicApi::getInputParameter("registration_date_time");
    if (!is_null($RegistrationDateTime)) {
        $RegistrationDateTime = new dxDateTime($RegistrationDateTime);
    }
    $Status = PublicApi::getInputParameter("registration_status");
    if (is_null($Status)) {
        $Status = NativeDevicePushRegistrationStatus::ACTIVE_STR;
    }
    
    if (!ProjectFunctions::updatePushRegistration($InternalUniqueId,$RegistrationId,$DeviceUuid,$DevicePlatform,$DeviceOs,$RegistrationDateTime,$Status,$ErrorInfo)) {
        PublicApi::setApiResult(false); // You need to set this to true to indicate that the API executed successfully
        PublicApi::addApiOutput("Message",$ErrorInfo);
        PublicApi::printApiResult();
    }
    PublicApi::setApiResult(true); // You need to set this to true to indicate that the API executed successfully
    PublicApi::addApiOutput("InternalId",$ErrorInfo[0]);
    PublicApi::printApiResult();
}