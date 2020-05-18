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

// Simple buttons
$Options['SimplePrimaryButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-primary">Primary</button>';
$Options['SimpleSecondaryButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-secondary">Secondary</button>';
$Options['SimpleSuccessButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-success">Success</button>';
$Options['SimpleWarningButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-warning">Warning</button>';
$Options['SimpleDangerButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-danger">Danger</button>';
$Options['SimpleInfoButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-info">Info</button>';
$Options['SimpleLightButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-light">Light</button>';
$Options['SimpleDarkButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-dark">Dark</button>';

// Outline buttons
$Options['OutlinePrimaryButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-primary">Primary</button>';
$Options['OutlineSecondaryButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-secondary">Secondary</button>';
$Options['OutlineSuccessButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-success">Success</button>';
$Options['OutlineWarningButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-warning">Warning</button>';
$Options['OutlineDangerButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-danger">Danger</button>';
$Options['OutlineInfoButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-info">Info</button>';
$Options['OutlineLightButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-light">Light</button>';
$Options['OutlineDarkButton'] = '<button type="button" id="[element_id]_btn" class="btn btn-outline-dark">Dark</button>';

// Large buttons
$Options['SimplePrimaryButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-primary btn-lg">Primary</button>';
$Options['SimpleSecondaryButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-secondary btn-lg">Secondary</button>';
$Options['SimpleSuccessButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-success btn-lg">Success</button>';
$Options['SimpleWarningButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-warning btn-lg">Warning</button>';
$Options['SimpleDangerButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-danger btn-lg">Danger</button>';
$Options['SimpleInfoButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-info btn-lg">Info</button>';
$Options['SimpleLightButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-light btn-lg">Light</button>';
$Options['SimpleDarkButtonLarge'] = '<button type="button" id="[element_id]_btn" class="btn btn-dark btn-lg">Dark</button>';

// Small buttons
$Options['SimplePrimaryButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-primary btn-sm">Primary</button>';
$Options['SimpleSecondaryButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-secondary btn-sm">Secondary</button>';
$Options['SimpleSuccessButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-success btn-sm">Success</button>';
$Options['SimpleWarningButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-warning btn-sm">Warning</button>';
$Options['SimpleDangerButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-danger btn-sm">Danger</button>';
$Options['SimpleInfoButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-info btn-sm">Info</button>';
$Options['SimpleLightButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-light btn-sm">Light</button>';
$Options['SimpleDarkButtonSmall'] = '<button type="button" id="[element_id]_btn" class="btn btn-dark btn-sm">Dark</button>';

$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '
// [element_id]_button Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
getComponentElementById(this,"[element_id]_btn").on("click", function() {
    // Example code that executes when the button is clicked
	// Add the trigger element to the loading element array. This shows a loading animation on the trigger
    // element while it waits for a response or function return
    let element_id = addTriggerElementToLoadingElementArray(getComponentElementById(this,"[element_id]_btn"),"Nice Loading text");
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