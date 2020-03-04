<?php
require("../../../../divblox/divblox.php");
class AccountController extends EntityDataSeriesComponentController {
    protected $EntityNameStr = "Account";
    protected $IncludedAttributeArray = ["FullName","EmailAddress","Username",];
    protected $IncludedRelationshipArray = ["UserRole" => "Role",];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new AccountController("account_administration_data_series");
?>