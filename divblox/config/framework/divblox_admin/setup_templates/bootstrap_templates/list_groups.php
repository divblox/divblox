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

// Basic List Items
$Options['BasicListItems'] = '<ul class="list-group">
    <li class="list-group-item">Cras justo odio</li>
    <li class="list-group-item">Dapibus ac facilisis in</li>
    <li class="list-group-item">Morbi leo risus</li>
    <li class="list-group-item">Porta ac consectetur ac</li>
    <li class="list-group-item">Vestibulum at eros</li>
</ul>';
// Basic Active List Items
$Options['BasicActiveListItems'] = '<ul class="list-group">
    <li class="list-group-item active">Cras justo odio</li>
    <li class="list-group-item">Dapibus ac facilisis in</li>
    <li class="list-group-item">Morbi leo risus</li>
    <li class="list-group-item">Porta ac consectetur ac</li>
    <li class="list-group-item">Vestibulum at eros</li>
</ul>';
// Basic Disabled List Items
$Options['BasicDisabledListItems'] = '<ul class="list-group">
    <li class="list-group-item disabled" aria-disabled="true">Cras justo odio</li>
    <li class="list-group-item">Dapibus ac facilisis in</li>
    <li class="list-group-item">Morbi leo risus</li>
    <li class="list-group-item">Porta ac consectetur ac</li>
    <li class="list-group-item">Vestibulum at eros</li>
</ul>';
// Basic Flush List Items
$Options['BasicFlushListItems'] = '<ul class="list-group list-group-flush">
    <li class="list-group-item">Cras justo odio</li>
    <li class="list-group-item">Dapibus ac facilisis in</li>
    <li class="list-group-item">Morbi leo risus</li>
    <li class="list-group-item">Porta ac consectetur ac</li>
    <li class="list-group-item">Vestibulum at eros</li>
</ul>';
// Basic Horizontal List Items
$Options['BasicHorizontalListItems'] = '<ul class="list-group list-group-horizontal-sm">
    <li class="list-group-item">Cras justo odio</li>
    <li class="list-group-item">Dapibus ac facilisis in</li>
    <li class="list-group-item">Morbi leo risus</li>
</ul>';
// Basic List Group with Badges
$Options['BasicListGroupBadges'] = '<ul class="list-group">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Cras justo odio
        <span class="badge badge-primary badge-pill">14</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Dapibus ac facilisis in
        <span class="badge badge-success badge-pill">2</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Morbi leo risus
        <span class="badge badge-danger badge-pill">1</span>
    </li>
</ul>';
// Basic List Group Custom Content
$Options['BasicListGroupCustomContent'] = '<div class="list-group">
    <a href="#" id="[element_id]_item_id1" class="list-group-item list-group-item-action list-group-item-[element_id] active">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">List group item heading</h5>
            <small>3 days ago</small>
        </div>
        <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
        <small>Donec id elit non mi porta.</small>
    </a>
    <a href="#" id="[element_id]_item_id2" class="list-group-item list-group-item-action list-group-item-[element_id]">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">List group item heading</h5>
            <small class="text-muted">3 days ago</small>
        </div>
        <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
        <small class="text-muted">Donec id elit non mi porta.</small>
        </a>
    <a href="#" id="[element_id]_item_id3" class="list-group-item list-group-item-action list-group-item-[element_id]">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">List group item heading</h5>
            <small class="text-muted">3 days ago</small>
        </div>
        <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
        <small class="text-muted">Donec id elit non mi porta.</small>
    </a>
</div>';

$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    if (strpos($Key,'BasicListGroupCustomContent') === false) {
        continue;
    }
    $OptionsJs[$Key] = '
// [element_id]_list-group Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(".list-group-item-[element_id]").on("click", function() {
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