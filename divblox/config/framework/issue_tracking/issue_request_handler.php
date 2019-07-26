<?php
require('../../../divblox.php');
require(FRAMEWORK_CONFIG_STR.'/framework/divblox_admin/setup_functions/divblox_admin_helper_classes.class.php');
if (!isset($_GET['f'])) {
    die(json_encode(array("Result" => "Failed","Message" => "No function provided")));
}
switch($_GET['f']) {
    case 'newIssue': logNewIssue();
        break;
    case 'getIssue': getIssueById();
        break;
    case 'getIssueList': getIssueList();
        break;
    case 'getActiveIssueList': getActiveIssueList();
        break;
    case 'getIssueListDetail': getIssueListDetail();
        break;
    case 'updateIssueStatus': updateIssueStatus();
        break;
    default: die(json_encode(array("Result" => "Failed","Message" => "Invalid function ".$_GET['f']." provided")));
}
function logNewIssue() {
    $Type = IssueTracker::ISSUE_STR;
    $Title = 'No title provided';
    $Description = 'No description provided';
    $ComponentName = 'Not provided';
    $ComponentUid = 'Not provided';
    $CapturePage = 'Not provided';
    $CapturePagePath = 'Not provided';
    $UserFullName = ProjectFunctions::getCurrentAccountAttribute('FullName');
    $UserEmail = ProjectFunctions::getCurrentAccountAttribute('EmailAddress');

    if (isset($_POST['type'])) {
        $Type = $_POST['type'];
    }
    if (isset($_POST['title'])) {
        $Title = $_POST['title'];
    }
    if (isset($_POST['description'])) {
        $Description = $_POST['description'];
    }
    if (isset($_POST['component_name'])) {
        $ComponentName = $_POST['component_name'];
    }
    if (isset($_POST['component_uid'])) {
        $ComponentUid = $_POST['component_uid'];
    }
    if (isset($_POST['capture_page'])) {
        $CapturePagePath = $_POST['capture_page'];
        $CapturePage = str_replace("/", "_", $_POST['capture_page']);
    }
    $Id = IssueTracker::addIssue($Type,$Title,$Description,$ComponentName,$ComponentUid,$CapturePage,$CapturePagePath,$UserFullName,$UserEmail);
    die(json_encode(array("Result" => "Success","Message" => "ID: $Id")));
}
function getIssueById() {
    if (!isset($_SESSION["divblox_admin_access"])) {
        die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
    }
    $Id = -1;
    if (isset($_POST["id"])) {
        $Id = $_POST["id"];
    }
    $IssueObj = IssueTracker::getIssueById($Id);
    if (is_null($IssueObj)) {
        die(json_encode(array("Result" => "Failed","Message" => "Not found")));
    }
    die(json_encode(array("Result" => "Success","IssueObject" => $IssueObj)));
}
function getIssueList() {
    if (!isset($_SESSION["divblox_admin_access"])) {
        die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
    }
    $ComponentName = null;
    if (isset($_POST['component_name'])) {
        $ComponentName = str_replace("/", "_", $_POST['component_name']);;
    }
    $IssueList = IssueTracker::getIssueList($ComponentName);
    if (is_null($IssueList)) {
        die(json_encode(array("Result" => "Failed","Message" => "Not found")));
    }
    die(json_encode(array("Result" => "Success","IssueList" => $IssueList)));
}
function getActiveIssueList() {
    if (!isset($_SESSION["divblox_admin_access"])) {
        die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
    }
    $ComponentName = null;
    if (isset($_POST['component_name'])) {
        $ComponentName = str_replace("/", "_", $_POST['component_name']);;
    }
    $IssueList = IssueTracker::getActiveIssueList($ComponentName);
    if (is_null($IssueList)) {
        die(json_encode(array("Result" => "Failed","Message" => "Not found")));
    }
    die(json_encode(array("Result" => "Success","IssueList" => $IssueList)));
}
function getIssueListDetail() {
    if (!isset($_SESSION["divblox_admin_access"])) {
        die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
    }
    $IssueIdArray = [];
    if (isset($_POST['list'])) {
        $IssueIdArray = $_POST['list'];
    }
    $ReturnArray = [];
    foreach($IssueIdArray as $Id) {
        $IssueObj = IssueTracker::getIssueById($Id);
        $ReturnArray[$Id] = $IssueObj;
    }
    die(json_encode(array("Result" => "Success","IssueListDetail" => $ReturnArray)));
}
function updateIssueStatus() {
    if (!isset($_SESSION["divblox_admin_access"])) {
        die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
    }
    $Id = -1;
    if (isset($_POST["id"])) {
        $Id = $_POST["id"];
    }
    $Status = IssueTracker::ISSUE_STATUS_NEW;
    if (isset($_POST["status"])) {
        $Status = $_POST["status"];
    }
    IssueTracker::updateIssueStatusById($Id,$Status);
    die(json_encode(array("Result" => "Success")));
}