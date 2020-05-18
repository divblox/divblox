<?php
require("../../../../divblox/divblox.php");
class MainNavbarController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new MainNavbarController("main_navbar");
?>