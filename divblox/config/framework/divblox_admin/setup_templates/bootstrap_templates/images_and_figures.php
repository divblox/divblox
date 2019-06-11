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

// Images
$Options['ResponsiveImage'] = '<img src="divblox/assets/images/component_placeholder_image_responsive.svg" class="img-fluid" alt="Responsive image"/>';
$Options['Image'] = '<img src="divblox/assets/images/component_placeholder_image_200x200.svg" class="img-fluid" alt="Responsive image"/>';
// Figures
$Options['FigureWithCaptionLeft'] = '<figure class="figure">
    <img src="divblox/assets/images/component_placeholder_figure_400x300.svg" class="figure-img img-fluid rounded" alt="..."/>
    <figcaption class="figure-caption">A caption for the above image.</figcaption>
</figure>';
$Options['FigureWithCaptionRight'] = '<figure class="figure">
    <img src="divblox/assets/images/component_placeholder_figure_400x300.svg" class="figure-img img-fluid rounded" alt="..."/>
    <figcaption class="figure-caption text-right">A caption for the above image.</figcaption>
</figure>';
$Options['FigureWithCaptionCenter'] = '<figure class="figure">
    <img src="divblox/assets/images/component_placeholder_figure_400x300.svg" class="figure-img img-fluid rounded" alt="..."/>
    <figcaption class="figure-caption text-center">A caption for the above image.</figcaption>
</figure>';


$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '';
}
//Otherwise, specify the relevant js per key


if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options,"OptionsJs" => $OptionsJs)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>