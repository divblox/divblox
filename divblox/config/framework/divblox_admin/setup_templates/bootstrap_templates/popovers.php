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

// Popovers
$Options['PopoverTop'] = '<button type="button" class="btn btn-outline-dark" data-toggle="popover" data-placement="top" title="Popover on top" data-content="Some amazing content. It\'s very engaging. Right?">Click to toggle popover on top</button>';
$Options['PopoverRight'] = '<button type="button" class="btn btn-outline-dark" data-toggle="popover" data-placement="right" title="Popover on right" data-content="Some amazing content. It\'s very engaging. Right?">Click to toggle popover on right</button>';
$Options['PopoverLeft'] = '<button type="button" class="btn btn-outline-dark" data-toggle="popover" data-placement="left" title="Popover on left" data-content="Some amazing content. It\'s very engaging. Right?">Click to toggle popover on left</button>';
$Options['PopoverBottom'] = '<button type="button" class="btn btn-outline-dark" data-toggle="popover" data-placement="bottom" title="Popover on bottom" data-content="Some amazing content. It\'s very engaging. Right?">Click to toggle popover on bottom</button>';
$Options['PopoverDismissible'] = '<button type="button" class="btn btn-outline-dark" data-toggle="popover" data-trigger="focus" title="Popover title" data-content="Some amazing content. It\'s very engaging. Right?">Click to toggle dismissible popover</button>';
$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '//Popover Related JS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(function () {
    $(\'[data-toggle="popover"]\').popover();
});
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
';
}

if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options,"OptionsJs" => $OptionsJs)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>
