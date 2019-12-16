<?php
require('../../../divblox/divblox.php');
if (!isset($_POST["f"])) {
    die(json_encode(array("Result" => "Failed","Message" => "Invalid function")));
}
/**
 * Determine which server function to call based on the input parameter "f"
 */
switch($_POST["f"]) {
    case 'getUserRole': die(json_encode(array("Result" => "Success","CurrentRole" => ProjectFunctions::getCurrentUserRole())));
        break;
    case 'getCurrentAccountId': die(json_encode(array("Result" => "Success","CurrentAccountId" => ProjectFunctions::getCurrentAccountAttribute())));
        break;
    case 'getCurrentAccountAttribute': die(json_encode(array("Result" => "Success","Attribute" => getCurrentAccountAttribute())));
        break;
    case 'logoutCurrentAccount': die(json_encode(array("Result" => "Success","LogoutResult" => ProjectFunctions::logoutCurrentAccount())));
        break;
    case 'updatePushRegistration': updatePushRegistration();
        break;
        // TODO: Define custom function handlers here...
    default:  die(json_encode(array("Result" => "Failed","Message" => "Invalid function")));
}
/**
 * Gets the attribute for the current account based on the variable $_POST['attribute']
 * @return null|string null if not found or attribute value
 */
function getCurrentAccountAttribute() {
    if (!isset($_POST['attribute'])) {
        return null;
    }
    $ExcludeArray = ["Password"];
    if (in_array($_POST['attribute'], $ExcludeArray)) {
        return "********";
    }
    $Candidate = ProjectFunctions::getCurrentAccountAttribute($_POST['attribute']);
    if (is_null($Candidate) ||
        strlen($Candidate) < 1) {
        return null;
    }
    if ($_POST['attribute'] == "ProfilePicturePath") {
        if (strtolower($Candidate) != 'anonymous') {
            if (!file_exists(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$Candidate)) {
                return null;
            }
        } else {
            return null;
        }
    }
    return $Candidate;
}

/**
 * Creates an entry of PushRegistration for the given variables in $_POST
 * @throws dxCallerException
 */
function updatePushRegistration() {
    $InternalUniqueId = null;
    if (isset($_POST["internal_id"])) {
        $InternalUniqueId = $_POST["internal_id"];
    }
    $RegistrationId = null;
    if (isset($_POST["registration_id"])) {
        $RegistrationId = $_POST["registration_id"];
    }
    $DeviceUuid = null;
    if (isset($_POST["device_uuid"])) {
        $DeviceUuid = $_POST["device_uuid"];
    }
    $DevicePlatform = null;
    if (isset($_POST["device_platform"])) {
        $DevicePlatform = $_POST["device_platform"];
    }
    $DeviceOs = 'NOT SPECIFIED';
    if (isset($_POST["device_os"])) {
        $DeviceOs = $_POST["device_os"];
    }
    $RegistrationDateTime = null;
    if (isset($_POST["registration_date_time"])) {
        $RegistrationDateTime = new dxDateTime($_POST["registration_date_time"]);
    }
    $Status = NativeDevicePushRegistrationStatus::ACTIVE_STR;
    if (isset($_POST["registration_status"])) {
        $Status = $_POST["registration_status"];
    }
    if (!ProjectFunctions::updatePushRegistration($InternalUniqueId,$RegistrationId,$DeviceUuid,$DevicePlatform,$DeviceOs,$RegistrationDateTime,$Status,$ErrorInfo)) {
        die(json_encode(array("Result" => "Failed","Message" => $ErrorInfo)));
    }
    die(json_encode(array("Result" => "Success","InternalId" => $ErrorInfo[0])));
}