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

// Simple Nav Left
$Options['SimpleNavLeft'] = '<nav class="nav">
  <a class="nav-link active" href="#">Active</a>
  <a class="nav-link" href="#">Link</a>
  <a class="nav-link" href="#">Link</a>
  <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
</nav>';

// Basic Tabs
$Options['BasicTabs'] = '<div>
    <ul class="nav nav-tabs" id="[element_id]_nav_tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#[element_id]_tab-1" role="tab" aria-controls="tab_1" aria-selected="true">Tab 1</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#[element_id]_tab-2" role="tab" aria-controls="tab_2" aria-selected="false">Tab 2</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#[element_id]_tab-3" role="tab" aria-controls="tab_3" aria-selected="false">Tab 3</a>
      </li>
    </ul>
    <div class="tab-content" id="[element_id]_TabContent">
        <div class="tab-pane show fade active" id="[element_id]_tab-1" role="tabpanel" aria-labelledby="tab_1-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_tab-2" role="tabpanel" aria-labelledby="tab_2-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_tab-3" role="tabpanel" aria-labelledby="tab_3-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
    </div>
</div>';

// Basic Tabs With Dropdowns
$Options['BasicTabWithDropdowns'] = '<div>
    <ul class="nav nav-tabs" id="[element_id]_nav_tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#[element_id]_tab-1" role="tab" aria-controls="active" aria-selected="true">Active</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#[element_id]_tab-2" data-toggle="tab">Tab 2</a>
                <a class="dropdown-item" href="#[element_id]_tab-3" data-toggle="tab">Tab 3</a>
                <a class="dropdown-item" href="#[element_id]_tab-4" data-toggle="tab">Tab 4</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#[element_id]_tab-5" data-toggle="tab">Tab 5 (Separated Link)</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#[element_id]_tab-6" role="tab" aria-controls="link" aria-selected="false">Link</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="[element_id]_tab-1" role="tabpanel" aria-labelledby="active-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_tab-6" role="tabpanel" aria-labelledby="link-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_tab-2" role="tabpanel" aria-labelledby="dropdown-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_tab-3" role="tabpanel" aria-labelledby="dropdown-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_tab-4" role="tabpanel" aria-labelledby="dropdown-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_tab-5" role="tabpanel" aria-labelledby="dropdown-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
    </div>
</div>';

// Pills
$Options['SimplePills'] = '<div>
    <ul class="nav nav-pills mb-3" id="[element_id]_nav_tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#[element_id]_pill_1" role="tab" aria-controls="pills-pill_1" aria-selected="true">Pill 1</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#[element_id]_pill_2" role="tab" aria-controls="pills-pill_2" aria-selected="false">Pill 2</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#[element_id]_pill_3" role="tab" aria-controls="pills-pill_3" aria-selected="false">Pill 3</a>
        </li>
    </ul>
    <div class="tab-content" id="[element_id]_Content">
        <div class="tab-pane fade show active" id="[element_id]_pill_1" role="tabpanel" aria-labelledby="pills-pill_1-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_pill_2" role="tabpanel" aria-labelledby="pills-pill_2-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="[element_id]_pill_3" role="tabpanel" aria-labelledby="pills-pill_3-tab">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
    </div>
</div>';

// Vertical Pills
$Options['Vertical Pills'] = '<div class="row">
    <div class="col-3">
        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" data-toggle="pill" href="#[element_id]_pill_1" role="tab" aria-controls="v-pills-pill_1" aria-selected="true">Pill 1</a>
            <a class="nav-link" data-toggle="pill" href="#[element_id]_pill_2" role="tab" aria-controls="v-pills-pill_2" aria-selected="false">Pill 2</a>
            <a class="nav-link" data-toggle="pill" href="#[element_id]_pill_3" role="tab" aria-controls="v-pills-pill_3" aria-selected="false">Pill 3</a>
            <a class="nav-link" data-toggle="pill" href="#[element_id]_pill_4" role="tab" aria-controls="v-pills-pill_4" aria-selected="false">Pill 4</a>
        </div>
    </div>
    <div class="col-9">
        <div class="tab-content" id="[element_id]_Content">
            <div class="tab-pane show active" id="[element_id]_pill_1" role="tabpanel" aria-labelledby="v-pills-pill_1-tab">
                <div class="row">
                    <div class="col-12"></div>
                </div>
            </div>
            <div class="tab-pane" id="[element_id]_pill_2" role="tabpanel" aria-labelledby="v-pills-pill_2-tab">
                <div class="row">
                    <div class="col-12"></div>
                </div>
            </div>
            <div class="tab-pane" id="[element_id]_pill_3" role="tabpanel" aria-labelledby="v-pills-pill_3-tab">
                <div class="row">
                    <div class="col-12"></div>
                </div>
            </div>
            <div class="tab-pane" id="[element_id]_pill_4" role="tabpanel" aria-labelledby="v-pills-pill_4-tab">
                <div class="row">
                    <div class="col-12"></div>
                </div>
            </div>
        </div>
    </div>
</div>';


if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>