<?php
require("../../../../divblox/divblox.php");
class AnonymousLandingPageController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function loadAnonymous() {
        $this->setResult(true);
        $this->setReturnValue("Message", "Loaded server call to ensure correct system security check outcome");
        $this->presentOutput();
    }
}
$ComponentObj = new AnonymousLandingPageController("anonymous_landing_page");
?>