<?php
require("../../../../../divblox/divblox.php");

class AccountController extends EntityInstanceComponentController {
    protected $EntityNameStr = "Account";
    protected $IncludedAttributeArray = ["FirstName", "MiddleNames", "LastName", "EmailAddress", "Username", "Password", "MaidenName", "ProfilePicturePath", "MainContactNumber", "Title", "DateOfBirth", "PhysicalAddressLineOne", "PhysicalAddressLineTwo", "PhysicalAddressPostalCode", "PhysicalAddressCountry", "PostalAddressLineOne", "PostalAddressLineTwo", "PostalAddressPostalCode", "PostalAddressCountry", "IdentificationNumber", "Nickname", "Status", "Gender", "AccessBlocked", "BlockedReason",];
    protected $IncludedRelationshipArray = ["UserRole" => "Role",];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }

    public function getObjectData() {
        $EntityObj = $this->EntityNameStr::Load($this->getInputValue("Id", true));
        $EntityJsonDecoded = array();
        if (!is_null($EntityObj)) {
            $EntityObj->Password = '';
            $EntityJsonDecoded = json_decode($EntityObj->getJson());
        }
        $this->setReturnValue("Object", $EntityJsonDecoded);
        foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayValue) {
            $RelationshipList = $this->getRelationshipList($EntityObj, $Relationship);
            $this->setReturnValue($Relationship."List", $RelationshipList);
        }
        $this->setResult(true);
        $this->setReturnValue("Message", "");
        $this->presentOutput();

    }

    public function doBeforeSaveActions($EntityToUpdateObj = null) {
        $EntityToUpdateObj->FullName = trim($EntityToUpdateObj->FirstName).' '.trim($EntityToUpdateObj->MiddleNames).' '.trim($EntityToUpdateObj->LastName);
    }
}

$ComponentObj = new AccountController("account_administration_update");
?>