<?php
require("../../../../divblox/divblox.php");

class AccountAdditionalInfoManagerController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new AccountAdditionalInfoManagerController("account_additional_info_manager");
?>