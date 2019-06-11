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

// Basic Modal
$Options['BasicModal'] = '<!-- Button trigger modal -->
<button type="button" id="[element_id]_btn-basic-modal" class="btn btn-primary" data-toggle="modal" data-target="#[element_id]_modal">
    Launch basic modal
</button>
<!-- Modal -->
<div class="modal fade" id="[element_id]_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="[element_id]_exampleModalLabel">Modal title</h5>
                <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="[element_id]_btn-footer-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="[element_id]_btn-save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>';
// Modal with Scrolling Text
$Options['ModalWithScrollingText'] = '<!-- Button trigger modal -->
<button type="button" id="[element_id]_btn-scrolling-modal"  class="btn btn-primary" data-toggle="modal" data-target="#[element_id]_modal">
    Launch Modal with Scrolling Text
</button>
<!-- Modal -->
<div class="modal fade" id="[element_id]_modal" tabindex="-1" role="dialog" aria-labelledby="[element_id]_exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="[element_id]_exampleModalScrollableTitle">Modal title</h5>
                <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="[element_id]_btn-footer-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="[element_id]_btn-save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>';
// Vertically Centered Modal
$Options['VerticallyCenteredModal'] = '<!-- Button trigger modal -->
<button type="button" id="[element_id]_btn-vertically-centered-modal" class="btn btn-primary" data-toggle="modal" data-target="#[element_id]_modal">
    Launch Vertically Centered Modal
</button>

<!-- Modal -->
<div class="modal fade" id="[element_id]_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="[element_id]_btn-footer-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="[element_id]_btn-save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>';
// Extra Large Modal
$Options['ExtraLargeModal'] = '<!-- Button trigger modal -->
<button type="button" id="[element_id]_btn-extra-large-modal" class="btn btn-primary" data-toggle="modal" data-target="#[element_id]_modal">Extra large modal</button>
<!-- Extra large modal -->
<div id="[element_id]_modal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalTitle">Modal title</h5>
                <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="[element_id]_btn-footer-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="[element_id]_btn-save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>';
// Large Modal
$Options['LargeModal'] = '<!-- Button trigger modal -->
<button type="button" id="[element_id]_btn-large-modal" class="btn btn-primary" data-toggle="modal" data-target="#[element_id]_modal">Large modal</button>
<!-- Large modal -->
<div id="[element_id]_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalTitle">Modal title</h5>
                <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="[element_id]_btn-footer-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="[element_id]_btn-save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>';
// Small Modal
$Options['SmallModal'] = '<!-- Button trigger modal -->
<button type="button" id="[element_id]_btn-small-modal" class="btn btn-primary" data-toggle="modal" data-target="#[element_id]_modal">Small modal</button>
<!-- Small modal -->
<div id="[element_id]_modal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">    
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalTitle">Modal title</h5>
                <button type="button" id="[element_id]_btn-close" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="[element_id]_btn-footer-close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="[element_id]_btn-save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>';

$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsJs[$Key] = '
// [element_id]_modal Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
getComponentElementById(this,"[element_id]_btn-close").on("click", function() {
    // Your custom code here
}.bind(this));
getComponentElementById(this,"[element_id]_btn-footer-close").on("click", function() {
    // Your custom code here
}.bind(this));
getComponentElementById(this,"[element_id]_btn-save").on("click", function() {
    // Your custom code here
}.bind(this));

// Modal functions
// Show the modal using javascript
//getComponentElementById(this,"[element_id]_modal").modal("show");
// Hide the modal using javascript
//getComponentElementById(this,"[element_id]_modal").modal("hide");
// Toggle the modal using javascript
//getComponentElementById(this,"[element_id]_modal").modal("toggle");

// Modal events
getComponentElementById(this,"[element_id]_modal").on("show.bs.modal", function(e) {
    // Your custom code here
}.bind(this));
getComponentElementById(this,"[element_id]_modal").on("shown.bs.modal", function(e) {
    // Your custom code here
}.bind(this));
getComponentElementById(this,"[element_id]_modal").on("hide.bs.modal", function(e) {
   // Your custom code here
}.bind(this));
getComponentElementById(this,"[element_id]_modal").on("hidden.bs.modal", function(e) {
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