<?php
require("../../../../../divblox/divblox.php");

class ApiOperationController extends EntityInstanceComponentController {
    protected $EntityNameStr = "ApiOperation";
    protected $IncludedAttributeArray = ["OperationName", "CrudEntityName",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new ApiOperationController("api_operation_administration_update");
?>