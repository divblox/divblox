<?php
require("../../../../divblox/divblox.php");
class DashboardController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new DashboardController("dashboard");
?>