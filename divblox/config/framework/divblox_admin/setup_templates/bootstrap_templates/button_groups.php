<?php
require("../../../../../divblox.php");
if (!isset($_SESSION["divblox_admin_access"])) {
    die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
}
$SelectedId = -1;
if (isset($_POST['selected'])) {
    $SelectedId = $_POST['selected'];
}
$Options = [];

// Simple button group
$Options['SimpleButtonGroup'] = '<div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" id="[element_id]_btn-group-left" class="btn btn-secondary">Left</button>
    <button type="button" id="[element_id]_btn-group-middle" class="btn btn-secondary">Middle</button>
    <button type="button" id="[element_id]_btn-group-right" class="btn btn-secondary">Right</button>
</div>';

$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '
// [element_id]_button-group Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
getComponentElementById(this,"[element_id]_btn-group-left").on("click", function() {
	// Add the trigger element to the loading element array. This shows a loading animation on the trigger
    // element while it waits for a response or function return
    let element_id = addTriggerElementToLoadingElementArray($(this).attr("id"),"Nice Loading text");
    // Example: once your function has executed, call removeTriggerElementFromLoadingElementArray to remove
    // loading animation
    setTimeout(function() {
        removeTriggerElementFromLoadingElementArray(element_id);
    },3000);
}.bind(this));
getComponentElementById(this,"[element_id]_btn-group-middle").on("click", function() {
	// Add the trigger element to the loading element array. This shows a loading animation on the trigger
    // element while it waits for a response or function return
    let element_id = addTriggerElementToLoadingElementArray($(this).attr("id"),"Nice Loading text");
    // Example: once your function has executed, call removeTriggerElementFromLoadingElementArray to remove
    // loading animation
    setTimeout(function() {
        removeTriggerElementFromLoadingElementArray(element_id);
    },3000);
}.bind(this));
getComponentElementById(this,"[element_id]_btn-group-right").on("click", function() {
	// Add the trigger element to the loading element array. This shows a loading animation on the trigger
    // element while it waits for a response or function return
    let element_id = addTriggerElementToLoadingElementArray($(this).attr("id"),"Nice Loading text");
    // Example: once your function has executed, call removeTriggerElementFromLoadingElementArray to remove
    // loading animation
    setTimeout(function() {
        removeTriggerElementFromLoadingElementArray(element_id);
    },3000);
}.bind(this));
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