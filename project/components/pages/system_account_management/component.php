<?php
require("../../../../divblox/divblox.php");

class SystemAccountManagementController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new SystemAccountManagementController("system_account_management");
?>