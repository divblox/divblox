<?php
require("../../../../divblox/divblox.php");
class AccountController extends EntityInstanceComponentController {
    protected $EntityNameStr = "Account";
    protected $IncludedAttributeArray = ["FirstName","MiddleNames","LastName","EmailAddress","Username","Password","MaidenName","ProfilePicturePath","MainContactNumber","Title","DateOfBirth","PhysicalAddressLineOne","PhysicalAddressLineTwo","PhysicalAddressPostalCode","PhysicalAddressCountry","PostalAddressLineOne","PostalAddressLineTwo","PostalAddressPostalCode","PostalAddressCountry","IdentificationNumber","Nickname","Status","Gender","AccessBlocked","BlockedReason",];
    protected $IncludedRelationshipArray = ["UserRole" => "Role",];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function doBeforeSaveActions($EntityToUpdateObj = null)  {
        $EntityToUpdateObj->FullName = trim($EntityToUpdateObj->FirstName).' '.trim($EntityToUpdateObj->MiddleNames).' '.trim($EntityToUpdateObj->LastName);
    }
}
$ComponentObj = new AccountController("account_administration_update");
?>