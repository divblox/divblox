<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/AuditLogEntryGen.class.php');

/**
 * The AuditLogEntry class defined here contains any
 * customized code for the AuditLogEntry class in the
 * Object Relational Model.  It represents the "AuditLogEntry" table
 * in the database, and extends from the code generated abstract AuditLogEntryGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class AuditLogEntry extends AuditLogEntryGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objAuditLogEntry->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('AuditLogEntry Object %s',  $this->intId);
    }
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
        // Get the Database Object for this Class
        $objDatabase = AuditLogEntry::GetDatabase();
        $mixToReturn = null;
        if (isset($_SESSION["API_CALL_ACTIVE"])) {
            if (isset($_SESSION["API_CALL_KEY"])) {
                $this->strApiKey = $_SESSION["API_CALL_KEY"];
            }
        }
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        try {
            if ((!$this->__blnRestored) || ($blnForceInsert)) {
                if (!in_array(AccessOperation::CREATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'AuditLogEntry'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `AuditLogEntry` (
							`EntryTimeStamp`,
							`ObjectName`,
							`ModificationType`,
							`UserEmail`,
							`ObjectId`,
							`AuditLogEntryDetail`,
							`ObjectOwner`,
							`ApiKey`
						) VALUES (
							' . $objDatabase->SqlVariable($this->dttEntryTimeStamp) . ',
							' . $objDatabase->SqlVariable($this->strObjectName) . ',
							' . $objDatabase->SqlVariable($this->strModificationType) . ',
							' . $objDatabase->SqlVariable($this->strUserEmail) . ',
							' . $objDatabase->SqlVariable($this->strObjectId) . ',
							' . $objDatabase->SqlVariable($this->strAuditLogEntryDetail) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . ',
							' . $objDatabase->SqlVariable($this->strApiKey) . '
						)
                ');
                // Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('AuditLogEntry', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'AuditLogEntry'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `AuditLogEntry` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                    $objRow = $objResult->FetchArray();
                    if ($objRow[0] != $this->strLastUpdated)
                        throw new dxOptimisticLockingException('AuditLogEntry');
                }

                // Perform the UPDATE query
                $objDatabase->NonQuery('
                UPDATE `AuditLogEntry` SET
                                `EntryTimeStamp` = ' . $objDatabase->SqlVariable($this->dttEntryTimeStamp) . ',
                                `ObjectName` = ' . $objDatabase->SqlVariable($this->strObjectName) . ',
                                `ModificationType` = ' . $objDatabase->SqlVariable($this->strModificationType) . ',
                                `UserEmail` = ' . $objDatabase->SqlVariable($this->strUserEmail) . ',
                                `ObjectId` = ' . $objDatabase->SqlVariable($this->strObjectId) . ',
                                `AuditLogEntryDetail` = ' . $objDatabase->SqlVariable($this->strAuditLogEntryDetail) . ',
                                `ObjectOwner` = ' . $objDatabase->SqlVariable($this->intObjectOwner) . ',
                                `ApiKey` = ' . $objDatabase->SqlVariable($this->strApiKey) . '
                WHERE
                                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');
                }

        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `AuditLogEntry` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
}
?>