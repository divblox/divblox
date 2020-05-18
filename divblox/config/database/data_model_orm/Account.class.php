<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/AccountGen.class.php');

/**
 * The Account class defined here contains any
 * customized code for the Account class in the
 * Object Relational Model.  It represents the "Account" table
 * in the database, and extends from the code generated abstract AccountGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class Account extends AccountGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objAccount->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('Account Object %s',  $this->intId);
    }
    public function getIterator() {
        ///////////////////
        // Member Variables
        ///////////////////
        $iArray['Id'] = $this->intId;
        $iArray['FullName'] = $this->strFullName;
        $iArray['FirstName'] = $this->strFirstName;
        $iArray['LastName'] = $this->strLastName;
        $iArray['EmailAddress'] = $this->strEmailAddress;
        $iArray['Username'] = $this->strUsername;
        $iArray['Password'] = '';
        $iArray['UserRole'] = $this->intUserRole;
        $iArray['SearchMetaInfo'] = $this->strSearchMetaInfo;
        $iArray['LastUpdated'] = $this->strLastUpdated;
        $iArray['ObjectOwner'] = $this->intObjectOwner;
        $iArray['MiddleNames'] = $this->strMiddleNames;
        $iArray['MaidenName'] = $this->strMaidenName;
        $iArray['ProfilePicturePath'] = $this->strProfilePicturePath;
        $iArray['MainContactNumber'] = $this->strMainContactNumber;
        $iArray['Title'] = $this->strTitle;
        $iArray['DateOfBirth'] = $this->dttDateOfBirth;
        $iArray['PhysicalAddressLineOne'] = $this->strPhysicalAddressLineOne;
        $iArray['PhysicalAddressLineTwo'] = $this->strPhysicalAddressLineTwo;
        $iArray['PhysicalAddressPostalCode'] = $this->strPhysicalAddressPostalCode;
        $iArray['PhysicalAddressCountry'] = $this->strPhysicalAddressCountry;
        $iArray['PostalAddressLineOne'] = $this->strPostalAddressLineOne;
        $iArray['PostalAddressLineTwo'] = $this->strPostalAddressLineTwo;
        $iArray['PostalAddressPostalCode'] = $this->strPostalAddressPostalCode;
        $iArray['PostalAddressCountry'] = $this->strPostalAddressCountry;
        $iArray['IdentificationNumber'] = $this->strIdentificationNumber;
        $iArray['Nickname'] = $this->strNickname;
        $iArray['Status'] = $this->strStatus;
        $iArray['Gender'] = $this->strGender;
        $iArray['AccessBlocked'] = $this->blnAccessBlocked;
        $iArray['BlockedReason'] = $this->strBlockedReason;
        return new ArrayIterator($iArray);
    }
}
?>