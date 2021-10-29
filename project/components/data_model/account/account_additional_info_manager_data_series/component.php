<?php
require("../../../../../divblox/divblox.php");

class AdditionalAccountInformationController extends EntityDataSeriesComponentController {
    protected $EntityNameStr = "AdditionalAccountInformation";
    protected $IncludedAttributeArray = ["Type", "Label", "Value",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = ["Account",];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new AdditionalAccountInformationController("account_additional_info_manager_data_series");
?>