<?php
require("../../../../divblox/divblox.php");
class LoginController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new LoginController("login");
?>