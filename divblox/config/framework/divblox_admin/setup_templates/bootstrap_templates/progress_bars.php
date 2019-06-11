<?php
/**
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */

require("../../../../../divblox.php");
if (!isset($_SESSION["divblox_admin_access"])) {
    die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
}
$SelectedId = -1;
if (isset($_POST['selected'])) {
    $SelectedId = $_POST['selected'];
}
$Options = [];

// Basic Progress bars
$Options['BasicProgressBar'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['BasicProgressBarSuccess'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['BasicProgressBarInfo'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar bg-info" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['BasicProgressBarWarning'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar bg-warning" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['BasicProgressBarDanger'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar bg-danger" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
// Striped Progress bars
$Options['StripedProgressBar'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarSuccess'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarInfo'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarWarning'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarDanger'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
// Striped animated Progress bars
$Options['StripedProgressBarAnimated'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarSuccessAnimated'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarInfoAnimated'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarWarningAnimated'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
$Options['StripedProgressBarDangerAnimated'] = '<div class="progress">
    <div id="[element_id]_progress_bar" class="progress-bar progress-bar-striped bg-danger progress-bar-animated" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>';
// Thin Progress bars
$Options['BasicThinProgressBar'] = '<div class="progress" style="height: 1px;">
    <div id="[element_id]_progress_bar" class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
</div>';
$Options['BasicThinProgressBarSuccess'] = '<div class="progress" style="height: 1px;">
    <div id="[element_id]_progress_bar" class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
</div>';
$Options['BasicThinProgressBarInfo'] = '<div class="progress" style="height: 1px;">
    <div id="[element_id]_progress_bar" class="progress-bar bg-info" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
</div>';
$Options['BasicThinProgressBarWarning'] = '<div class="progress" style="height: 1px;">
    <div id="[element_id]_progress_bar" class="progress-bar bg-warning" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
</div>';
$Options['BasicThinProgressBarDanger'] = '<div class="progress" style="height: 1px;">
    <div id="[element_id]_progress_bar" class="progress-bar bg-danger" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
</div>';

$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $TextUpdate = '.text(value_now+\'%\')';
    if (strpos($Key,"Thin") !== false) {
        $TextUpdate = '';
    }
    $OptionsJs[$Key] = '// [element_id]_progress_bar Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
this.updateProgressFor_[element_id]_progress_bar = function(value_now) {
    if (typeof value_now === "undefined") {
        value_now = 0;
    }
    getComponentElementById(this,"[element_id]_progress_bar").css("width",value_now+"%").attr("aria-valuenow",value_now)'.$TextUpdate.';
    if (value_now === 100) {
        return;
    }
    // Call some function here to get the current value of the progress (An update script on the server for example)
    // In this example, we simply increase the value by 1 with every call, then wait 0.2s before calling again
    setTimeout(function() {
        this.updateProgressFor_[element_id]_progress_bar(value_now+1);
    }.bind(this),200);
}.bind(this);
this.updateProgressFor_[element_id]_progress_bar(0);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
';
}
//Otherwise, specify the relevant js per key


if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options,"OptionsJs" => $OptionsJs)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>