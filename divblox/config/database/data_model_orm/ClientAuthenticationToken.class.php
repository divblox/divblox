<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/ClientAuthenticationTokenGen.class.php');

/**
 * The ClientAuthenticationToken class defined here contains any
 * customized code for the ClientAuthenticationToken class in the
 * Object Relational Model.  It represents the "ClientAuthenticationToken" table
 * in the database, and extends from the code generated abstract ClientAuthenticationTokenGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class ClientAuthenticationToken extends ClientAuthenticationTokenGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objClientAuthenticationToken->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('ClientAuthenticationToken Object %s',  $this->intId);
    }
    /**
     * Save this ClientAuthenticationToken
     * @param bool $blnForceInsert
     * @param bool $blnForceUpdate
     * @return int
     */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        //JGL: No object access is required for this
        /*
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ClientAuthenticationToken",$this->intId);
        */
        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();
        $mixToReturn = null;
        //JGL: No object access is required for this
        /*
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }*/
        $ExistingObj = ClientAuthenticationToken::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'ClientAuthenticationToken';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("Token" => $this->strToken));
            $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => $this->dttUpdateDateTime));
            $ChangedArray = array_merge($ChangedArray,array("ClientConnection" => $this->intClientConnection));
            $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
            $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
            $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
            $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        } else {
            $newAuditLogEntry->ModificationType = 'Update';
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Id)) {
                $ExistingValueStr = $ExistingObj->Id;
            }
            if ($ExistingObj->Id != $this->intId) {
                $ChangedArray = array_merge($ChangedArray,array("Id" => array("Before" => $ExistingValueStr,"After" => $this->intId)));
                //$ChangedArray = array_merge($ChangedArray,array("Id" => "From: ".$ExistingValueStr." to: ".$this->intId));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Token)) {
                $ExistingValueStr = $ExistingObj->Token;
            }
            if ($ExistingObj->Token != $this->strToken) {
                $ChangedArray = array_merge($ChangedArray,array("Token" => array("Before" => $ExistingValueStr,"After" => $this->strToken)));
                //$ChangedArray = array_merge($ChangedArray,array("Token" => "From: ".$ExistingValueStr." to: ".$this->strToken));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->UpdateDateTime)) {
                $ExistingValueStr = $ExistingObj->UpdateDateTime;
            }
            if ($ExistingObj->UpdateDateTime != $this->dttUpdateDateTime) {
                $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => array("Before" => $ExistingValueStr,"After" => $this->dttUpdateDateTime)));
                //$ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => "From: ".$ExistingValueStr." to: ".$this->dttUpdateDateTime));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ClientConnection)) {
                $ExistingValueStr = $ExistingObj->ClientConnection;
            }
            if ($ExistingObj->ClientConnection != $this->intClientConnection) {
                $ChangedArray = array_merge($ChangedArray,array("ClientConnection" => array("Before" => $ExistingValueStr,"After" => $this->intClientConnection)));
                //$ChangedArray = array_merge($ChangedArray,array("ClientConnection" => "From: ".$ExistingValueStr." to: ".$this->intClientConnection));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->SearchMetaInfo)) {
                $ExistingValueStr = $ExistingObj->SearchMetaInfo;
            }
            if ($ExistingObj->SearchMetaInfo != $this->strSearchMetaInfo) {
                $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => array("Before" => $ExistingValueStr,"After" => $this->strSearchMetaInfo)));
                //$ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => "From: ".$ExistingValueStr." to: ".$this->strSearchMetaInfo));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->LastUpdated)) {
                $ExistingValueStr = $ExistingObj->LastUpdated;
            }
            if ($ExistingObj->LastUpdated != $this->strLastUpdated) {
                $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => array("Before" => $ExistingValueStr,"After" => $this->strLastUpdated)));
                //$ChangedArray = array_merge($ChangedArray,array("LastUpdated" => "From: ".$ExistingValueStr." to: ".$this->strLastUpdated));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ObjectOwner)) {
                $ExistingValueStr = $ExistingObj->ObjectOwner;
            }
            if ($ExistingObj->ObjectOwner != $this->intObjectOwner) {
                $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => array("Before" => $ExistingValueStr,"After" => $this->intObjectOwner)));
                //$ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => "From: ".$ExistingValueStr." to: ".$this->intObjectOwner));
            }
            $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        }
        try {
            if ((!$this->__blnRestored) || ($blnForceInsert)) {
                //JGL: No object access is required for this
                /*
                if (!in_array(AccessOperation::CREATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'ClientAuthenticationToken'. Allowed access is ".json_encode($ObjectAccessArray));
                }*/
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `ClientAuthenticationToken` (
							`Token`,
							`UpdateDateTime`,
							`ClientConnection`,
							`SearchMetaInfo`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strToken) . ',
							' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							' . $objDatabase->SqlVariable($this->intClientConnection) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
                // Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('ClientAuthenticationToken', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                //JGL: No object access is required for this
                /*
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'ClientAuthenticationToken'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                */
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `ClientAuthenticationToken` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                    $objRow = $objResult->FetchArray();
                    if ($objRow[0] != $this->strLastUpdated)
                        throw new dxOptimisticLockingException('ClientAuthenticationToken');
                }

                // Perform the UPDATE query
                $objDatabase->NonQuery('
            UPDATE `ClientAuthenticationToken` SET
							`Token` = ' . $objDatabase->SqlVariable($this->strToken) . ',
							`UpdateDateTime` = ' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							`ClientConnection` = ' . $objDatabase->SqlVariable($this->intClientConnection) . ',
							`SearchMetaInfo` = ' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							`ObjectOwner` = ' . $objDatabase->SqlVariable($this->intObjectOwner) . '
            WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');
            }

        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
        try {
            $newAuditLogEntry->ObjectId = $this->intId;
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while saving ClientAuthenticationToken. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `ClientAuthenticationToken` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this ClientAuthenticationToken
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this ClientAuthenticationToken with an unset primary key.');

        //JGL: No object access is required for this
        /*
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Account",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'ClientAuthenticationToken'. Allowed access is ".json_encode($ObjectAccessArray));
        }*/

        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'ClientAuthenticationToken';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("Token" => $this->strToken));
        $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => $this->dttUpdateDateTime));
        $ChangedArray = array_merge($ChangedArray,array("ClientConnection" => $this->intClientConnection));
        $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting ClientAuthenticationToken. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `ClientAuthenticationToken`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }
}
?>