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

// Carousels
$Options['BasicCarousel'] = '<div id="[element_id]_carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
    </div>
</div>';
$Options['CarouselWithControls'] = '<div id="[element_id]_carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
    </div>
    <a class="carousel-control-prev" href="#[element_id]_carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#[element_id]_carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>';
$Options['CarouselWithIndicators'] = '<div id="[element_id]_carousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#[element_id]_carousel" data-slide-to="0" class="active"></li>
        <li data-target="#[element_id]_carousel" data-slide-to="1"></li>
        <li data-target="#[element_id]_carousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
    </div>
    <a class="carousel-control-prev" href="#[element_id]_carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#[element_id]_carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>';
$Options['CarouselWithCaptions'] = '<div class="bd-example">
    <div id="[element_id]_carousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#[element_id]_carousel" data-slide-to="0" class="active"></li>
            <li data-target="#[element_id]_carousel" data-slide-to="1"></li>
            <li data-target="#[element_id]_carousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#[element_id]_carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#[element_id]_carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>';
$Options['CarouselCrossfade'] = '<div id="[element_id]_carousel" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
        <div class="carousel-item">
            <img src="divblox/assets/images/component_placeholder_carousel.svg" class="d-block w-100" alt="..."/>
        </div>
    </div>
    <a class="carousel-control-prev" href="#[element_id]_carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#[element_id]_carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>';

$OptionsWithJs = [];
//If all button js is the same thing
foreach($Options as $Key => $Value) {
    $OptionsWithJs[$Key] = '//Carousel Related JS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Show your carousel with this
$(".carousel").carousel();
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
';
}

if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options,"OptionsJs" => $OptionsWithJs)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>