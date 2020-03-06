<?php
require("../../../../divblox/divblox.php");
class ApiKeyController extends EntityInstanceComponentController {
    protected $EntityNameStr = "ApiKey";
    protected $IncludedAttributeArray = ["ApiKey","ValidFromDate","ValidToDate","Notes","CallingEntityInformation",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new ApiKeyController("api_key_administration_update");
?>