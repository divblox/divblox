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

// Toasts
$Options['TopLeftToast'] = '';
$Options['TopRightToast'] = '';
$Options['BottomLeftToast'] = '';
$Options['BottomRightToast'] = '';
$Options['CenteredToast'] = '';
$OptionsWithJs['TopLeftToast'] = 'showToast("Toast Title","Toast Message",{y:"top",x:"left"},
    /*icon path:*/undefined,/*custom toast timestamp:*/undefined,/*auto hide delay (ms): */undefined);';
$OptionsWithJs['TopRightToast'] = 'showToast("Toast Title","Toast Message",{y:"top",x:"right"},
    /*icon path:*/undefined,/*custom toast timestamp:*/undefined,/*auto hide delay (ms): */undefined);';
$OptionsWithJs['BottomLeftToast'] = 'showToast("Toast Title","Toast Message",{y:"bottom",x:"left"},
    /*icon path:*/undefined,/*custom toast timestamp:*/undefined,/*auto hide delay (ms): */undefined);';
$OptionsWithJs['BottomRightToast'] = 'showToast("Toast Title","Toast Message",{y:"bottom",x:"right"},
    /*icon path:*/undefined,/*custom toast timestamp:*/undefined,/*auto hide delay (ms): */undefined);';
$OptionsWithJs['CenteredToast'] = 'showToast("Toast Title","Toast Message",{y:"middle"},
    /*icon path:*/undefined,/*custom toast timestamp:*/undefined,/*auto hide delay (ms): */undefined);';

if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options,"OptionsJs" => $OptionsWithJs)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>