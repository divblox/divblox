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
$OptionsWithJs = ['SimpleCard','SimpleCardHeaderFooter','SimpleCardTextCenter','SimpleCardNavigation'];
// Cards
$Options['SimpleCard'] = '<div class="card" style="width: 18rem;">
    <img src="divblox/assets/images/no_image.svg" class="card-img-top" alt="..."/>
    <div class="card-body">
        <h5 class="card-title">Card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
        <a href="#" id="[element_id]_btn-simple-card" class="btn btn-primary stretched-link">Go somewhere</a>
    </div>
</div>';
$Options['SimpleCardTextLinks'] = '<div class="card" style="width: 18rem;">
    <div class="card-body">
        <h5 class="card-title">Card title</h5>
        <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
        <a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>
    </div>
</div>';
$Options['SimpleCardImages'] = '<div class="card" style="width: 18rem;">
    <img src="divblox/assets/images/no_image.svg" class="card-img-top" alt="..."/>
    <div class="card-body">
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardKitchenSink'] = '<div class="card" style="width: 18rem;">
    <img src="divblox/assets/images/no_image.svg" class="card-img-top" alt="..."/>
    <div class="card-body">
        <h5 class="card-title">Card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Cras justo odio</li>
        <li class="list-group-item">Dapibus ac facilisis in</li>
        <li class="list-group-item">Vestibulum at eros</li>
    </ul>
    <div class="card-body">
        <a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>
    </div>
</div>';
$Options['SimpleCardHeaderFooter'] = '<div class="card">
    <div class="card-header">
        Featured
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" id="[element_id]_btn-simple-card" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
    </div>
</div>';
$Options['SimpleCardTextCenter'] = '<div class="card text-center">
    <div class="card-header">
        Featured
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" id="[element_id]_btn-simple-card" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
    </div>
    <div class="card-footer text-muted">
        2 days ago
    </div>
</div>';
$Options['SimpleCardNavigation'] = '<div class="card text-center">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#[element_id]_tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Tab 1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#[element_id]_tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Tab 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#[element_id]_tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Tab 3</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane show fade active" id="[element_id]_tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                <div class="row">
                    <div class="col-12">
                    
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="[element_id]_tab-2" role="tabpanel" aria-labelledby="tab-2-tab">
                <div class="row">
                    <div class="col-12">
                    
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="[element_id]_tab-3" role="tabpanel" aria-labelledby="tab-3-tab">
                <div class="row">
                    <div class="col-12">
                    
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" id="[element_id]_btn-simple-card" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
    </div>
</div>';
$Options['SimpleCardPrimary'] = '<div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Primary card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardSecondary'] = '<div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Secondary card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardSuccess'] = '<div class="card text-white bg-success mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Success card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardDanger'] = '<div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Danger card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardWarning'] = '<div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Warning card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardInfo'] = '<div class="card text-white bg-info mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Info card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardLight'] = '<div class="card bg-light mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Light card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';
$Options['SimpleCardDark'] = '<div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
    <div class="card-header">Header</div>
    <div class="card-body">
        <h5 class="card-title">Dark card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
    </div>
</div>';

$OptionsJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    if (!in_array($Key, $OptionsWithJs)) {
        continue;
    }
    $OptionsJs[$Key] = '
// [element_id]_card Related functionality
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
getComponentElementById(this,"[element_id]_btn-simple-card").on("click", function() {
	// Add the trigger element to the loading element array. This shows a loading animation on the trigger
    // element while it waits for a response or function return
    let element_id = addTriggerElementToLoadingElementArray($(this).attr("id"),"Nice Loading text");
    // Example: once your function has executed, call removeTriggerElementFromLoadingElementArray to remove
    // loading animation
    setTimeout(function() {
        removeTriggerElementFromLoadingElementArray(element_id);
    },3000);
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