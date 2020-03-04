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

// Tooltips
$Options['TooltipTop'] = '<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></i>';
$Options['TooltipRight'] = '<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Tooltip on right"></i>';
$Options['TooltipLeft'] = '<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Tooltip on left"></i>';
$Options['TooltipBottom'] = '<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></i>';
$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '//Tooltip Related JS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(function () {
    $(\'[data-toggle="tooltip"]\').tooltip();
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