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

// Border Spinners
$Options['BorderSpinnerPrimary'] = '<div class="spinner-border text-primary" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['BorderSpinnerSecondary'] = '<div class="spinner-border text-secondary" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['BorderSpinnerSuccess'] = '<div class="spinner-border text-success" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['BorderSpinnerDanger'] = '<div class="spinner-border text-danger" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['BorderSpinnerWarning'] = '<div class="spinner-border text-warning" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['BorderSpinnerInfo'] = '<div class="spinner-border text-info" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['BorderSpinnerLight'] = '<div class="spinner-border text-light" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['BorderSpinnerDark'] = '<div class="spinner-border text-dark" role="status">
  <span class="sr-only">Loading...</span>
</div>';
// Growing Spinners
$Options['GrowSpinnerPrimary'] = '<div class="spinner-grow text-primary" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['GrowSpinnerSecondary'] = '<div class="spinner-grow text-secondary" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['GrowSpinnerSuccess'] = '<div class="spinner-grow text-success" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['GrowSpinnerDanger'] = '<div class="spinner-grow text-danger" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['GrowSpinnerWarning'] = '<div class="spinner-grow text-warning" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['GrowSpinnerInfo'] = '<div class="spinner-grow text-info" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['GrowSpinnerLight'] = '<div class="spinner-grow text-light" role="status">
  <span class="sr-only">Loading...</span>
</div>';
$Options['GrowSpinnerDark'] = '<div class="spinner-grow text-dark" role="status">
  <span class="sr-only">Loading...</span>
</div>';


$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '';}
//Otherwise, specify the relevant js per key


if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options,"OptionsJs" => $OptionsJs)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>