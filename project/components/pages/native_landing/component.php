<?php
require("../../../../divblox/divblox.php");
class NativeLandingController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new NativeLandingController("native_landing");
?>