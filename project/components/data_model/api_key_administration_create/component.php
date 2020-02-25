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
    public function assignAdditionalValuesAfterSave($EntityToUpdateObj = null) {
        parent::assignAdditionalValuesAfterSave($EntityToUpdateObj);
        $Done = false;
        while (!$Done) {
            $ApiKeyCandidate = ProjectFunctions::generateRandomString(48);
            $ExistingApiKeyObj = ApiKey::LoadByApiKey($ApiKeyCandidate);
            if (is_null($ExistingApiKeyObj)) {
                $Done = true;
            }
        }
        $EntityToUpdateObj->ApiKey = $ApiKeyCandidate;
        $EntityToUpdateObj->Save();
    }
}
$ComponentObj = new ApiKeyController("api_key_administration_create");
?>