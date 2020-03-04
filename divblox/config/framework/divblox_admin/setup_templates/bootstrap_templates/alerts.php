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

// Simple alerts
$Options['SimplePrimaryAlert'] = '<div class="alert alert-primary" role="alert">
    A simple primary alert—check it out!
</div>';
$Options['SimpleSecondaryAlert'] = '<div class="alert alert-secondary" role="alert">
    A simple secondary alert—check it out!
</div>';
$Options['SimpleSuccessAlert'] = '<div class="alert alert-success" role="alert">
    A simple success alert—check it out!
</div>';
$Options['SimpleDangerAlert'] = '<div class="alert alert-danger" role="alert">
    A simple danger alert—check it out!
</div>';
$Options['SimpleWarningAlert'] = '<div class="alert alert-warning" role="alert">
    A simple warning alert—check it out!
</div>';
$Options['SimpleInfoAlert'] = '<div class="alert alert-info" role="alert">
    A simple info alert—check it out!
</div>';
$Options['SimpleLightAlert'] = '<div class="alert alert-light" role="alert">
    A simple light alert—check it out!
</div>';
$Options['SimpleDarkAlert'] = '<div class="alert alert-dark" role="alert">
    A simple dark alert—check it out!
</div>';

// Additional content alert
$Options['AdditionalInfoPrimaryAlert'] = '<div class="alert alert-primary" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';
$Options['AdditionalInfoSecondaryAlert'] = '<div class="alert alert-secondary" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';
$Options['AdditionalInfoSuccessAlert'] = '<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';
$Options['AdditionalInfoWarningAlert'] = '<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';
$Options['AdditionalInfoDangerAlert'] = '<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';
$Options['AdditionalInfoInfoAlert'] = '<div class="alert alert-info" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';
$Options['AdditionalInfoLightAlert'] = '<div class="alert alert-light" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';
$Options['AdditionalInfoDarkAlert'] = '<div class="alert alert-dark" role="alert">
    <h4 class="alert-heading">Well done!</h4>
    <p>Aww yeah, you successfully read this important alert message. </p>
    <hr/>
    <p class="mb-0">Be sure to use margin utilities to keep things nice and tidy.</p>
</div>';

// Dismissible alert
$Options['DismissiblePrimaryAlert'] = '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';
$Options['DismissibleSecondaryAlert'] = '<div class="alert alert-secondary alert-dismissible fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';
$Options['DismissibleSuccessAlert'] = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';
$Options['DismissibleWarningAlert'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';
$Options['DismissibleDangerAlert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';
$Options['DismissibleInfoAlert'] = '<div class="alert alert-danger alert-info fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';
$Options['DismissibleLightAlert'] = '<div class="alert alert-light alert-info fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';
$Options['DismissibleDarkAlert'] = '<div class="alert alert-dark alert-info fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
    </button>
</div>';


$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    if (strpos($Key,'Dismissible') === false) {
        continue;
    }
    $OptionsJs[$Key] = '// [element_id]_alert Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
getComponentElementById(this,"[element_id]_btn-close").on("click", function() {
    // Your custom code here
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