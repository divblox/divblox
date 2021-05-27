<?php
require("../../../../divblox/divblox.php");
class MaintenanceController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new MaintenanceController("maintenance");
?>