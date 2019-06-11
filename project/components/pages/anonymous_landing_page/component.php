<?php
require("../../../../divblox/divblox.php");
class AnonymousLandingPageController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new AnonymousLandingPageController("anonymous_landing_page");
?>