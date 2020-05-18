<?php
require("../../../../divblox/divblox.php");
class SideNavbarController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new SideNavbarController("side_navbar");
?>