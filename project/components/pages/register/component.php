<?php
require("../../../../divblox/divblox.php");
class RegisterController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new RegisterController("register");
?>