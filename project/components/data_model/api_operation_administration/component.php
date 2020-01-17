<?php
require("../../../../divblox/divblox.php");
class ApiOperationAdministrationController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new ApiOperationAdministrationController("api_operation_administration");
?>