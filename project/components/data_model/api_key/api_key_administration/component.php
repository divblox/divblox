<?php
require("../../../../../divblox/divblox.php");

class ApiKeyAdministrationController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new ApiKeyAdministrationController("api_key_administration");
?>