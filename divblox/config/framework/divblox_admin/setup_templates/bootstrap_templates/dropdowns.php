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

// Single button
$Options['DropdownSingleButton'] = '<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropdown button
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a id="[element_id]_single_button" class="dropdown-item" href="#">Single Button</a>
        <a id="[element_id]_another_action" class="dropdown-item" href="#">Another action</a>
        <a id="[element_id]_something_else" class="dropdown-item" href="#">Something else here</a>
        <div class="dropdown-divider"></div>
        <a id="[element_id]_separated_link" class="dropdown-item" href="#">Separated link</a>
    </div>
</div>';
// Split button
$Options['DropdownSplitButton'] = '<div class="btn-group">
    <button type="button" class="btn btn-danger">Split Button</button>
    <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu">
        <a id="[element_id]_single_button" class="dropdown-item" href="#">Single Button</a>
        <a id="[element_id]_another_action" class="dropdown-item" href="#">Another action</a>
        <a id="[element_id]_something_else" class="dropdown-item" href="#">Something else here</a>
        <div class="dropdown-divider"></div>
        <a id="[element_id]_separated_link" class="dropdown-item" href="#">Separated link</a>
    </div>
</div>';
// Directions
$Options['DropupButton'] = '<div class="btn-group dropup">
    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropup
    </button>
    <div class="dropdown-menu">
        <a id="[element_id]_single_button" class="dropdown-item" href="#">Single Button</a>
        <a id="[element_id]_another_action" class="dropdown-item" href="#">Another action</a>
        <a id="[element_id]_something_else" class="dropdown-item" href="#">Something else here</a>
        <div class="dropdown-divider"></div>
        <a id="[element_id]_separated_link" class="dropdown-item" href="#">Separated link</a>
    </div>
</div>';
$Options['SplitDropupButton'] = '<div class="btn-group dropup">
    <button type="button" class="btn btn-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropup
    </button>
    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="sr-only">Toggle Dropright</span>
    </button>
    <div class="dropdown-menu">
        <a id="[element_id]_single_button" class="dropdown-item" href="#">Single Button</a>
        <a id="[element_id]_another_action" class="dropdown-item" href="#">Another action</a>
        <a id="[element_id]_something_else" class="dropdown-item" href="#">Something else here</a>
        <div class="dropdown-divider"></div>
        <a id="[element_id]_separated_link" class="dropdown-item" href="#">Separated link</a>
    </div>
</div>';
$Options['DroprightButton'] = '<div class="btn-group dropright">
    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Dropright
    </button>
    <div class="dropdown-menu">
        <a id="[element_id]_single_button" class="dropdown-item" href="#">Single Button</a>
        <a id="[element_id]_another_action" class="dropdown-item" href="#">Another action</a>
        <a id="[element_id]_something_else" class="dropdown-item" href="#">Something else here</a>
        <div class="dropdown-divider"></div>
        <a id="[element_id]_separated_link" class="dropdown-item" href="#">Separated link</a>
    </div>
</div>';
$Options['DroprightSplitButton'] = '<div class="btn-group dropright">
    <button type="button" class="btn btn-secondary">
        Split dropright
    </button>
    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="sr-only">Toggle Dropright</span>
    </button>
    <div class="dropdown-menu">
        <a id="[element_id]_single_button" class="dropdown-item" href="#">Single Button</a>
        <a id="[element_id]_another_action" class="dropdown-item" href="#">Another action</a>
        <a id="[element_id]_something_else" class="dropdown-item" href="#">Something else here</a>
        <div class="dropdown-divider"></div>
        <a id="[element_id]_separated_link" class="dropdown-item" href="#">Separated link</a>
    </div>
</div>';

$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '
// [element_id]_dropdowns Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
getComponentElementById(this,"[element_id]_single_button").on("click", function() {
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
getComponentElementById(this,"[element_id]_another_action").on("click", function() {
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
getComponentElementById(this,"[element_id]_something_else").on("click", function() {
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
getComponentElementById(this,"[element_id]_separated_link").on("click", function() {
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

if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options,"OptionsJs" => $OptionsJs)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>