<?php
require("../../../../divblox/divblox.php");
class AllowedApiOperationController extends EntityInstanceComponentController {
    protected $EntityNameStr = "AllowedApiOperation";
    protected $IncludedAttributeArray = ["IsActive",];
    protected $IncludedRelationshipArray = ["ApiOperation" => "OperationName",];
    protected $ConstrainByArray = ["ApiKey",];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new AllowedApiOperationController("allowed_api_operation_crud_update");
?>