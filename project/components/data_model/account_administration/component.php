<?php
require("../../../../divblox/divblox.php");
class AccountAdministrationController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new AccountAdministrationController("account_administration");
?>