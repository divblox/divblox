<?php
require("../../.././../divblox/divblox.php");

class AllowedApiOperationCrudController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new AllowedApiOperationCrudController("allowed_api_operation_crud");
?>