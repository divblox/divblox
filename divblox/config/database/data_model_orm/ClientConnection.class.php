<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/ClientConnectionGen.class.php');

/**
 * The ClientConnection class defined here contains any
 * customized code for the ClientConnection class in the
 * Object Relational Model.  It represents the "ClientConnection" table
 * in the database, and extends from the code generated abstract ClientConnectionGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class ClientConnection extends ClientConnectionGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objClientConnection->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('ClientConnection Object %s',  $this->intId);
    }
    /**
     * Save this ClientConnection
     * @param bool $blnForceInsert
     * @param bool $blnForceUpdate
     * @return int
     */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        //JGL: No object access is required for this
        //$ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ClientConnection",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = ClientConnection::GetDatabase();
        $mixToReturn = null;
        //JGL: No object access is required for this
        /*
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }*/
        try {
            if ((!$this->__blnRestored) || ($blnForceInsert)) {
                //JGL: No object access is required for this
                /*
                if (!in_array(AccessOperation::CREATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'ClientConnection'. Allowed access is ".json_encode($ObjectAccessArray));
                }*/
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `ClientConnection` (
							`ClientIpAddress`,
							`ClientUserAgent`,
							`UpdateDateTime`,
							`Account`,
							`SearchMetaInfo`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strClientIpAddress) . ',
							' . $objDatabase->SqlVariable($this->strClientUserAgent) . ',
							' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							' . $objDatabase->SqlVariable($this->intAccount) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
                // Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('ClientConnection', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                //JGL: No object access is required for this
                /*
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'ClientConnection'. Allowed access is ".json_encode($ObjectAccessArray));
                }*/
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `ClientConnection` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                    $objRow = $objResult->FetchArray();
                    if ($objRow[0] != $this->strLastUpdated)
                        throw new dxOptimisticLockingException('ClientConnection');
                }

                // Perform the UPDATE query
                $objDatabase->NonQuery('
            UPDATE `ClientConnection` SET
							`ClientIpAddress` = ' . $objDatabase->SqlVariable($this->strClientIpAddress) . ',
							`ClientUserAgent` = ' . $objDatabase->SqlVariable($this->strClientUserAgent) . ',
							`UpdateDateTime` = ' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							`Account` = ' . $objDatabase->SqlVariable($this->intAccount) . ',
							`SearchMetaInfo` = ' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							`ObjectOwner` = ' . $objDatabase->SqlVariable($this->intObjectOwner) . '
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
                                            `ClientConnection` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this ClientConnection
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this ClientConnection with an unset primary key.');

        //JGL: No object access is required for this
        /*
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Account",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'ClientConnection'. Allowed access is ".json_encode($ObjectAccessArray));
        }*/

        // Get the Database Object for this Class
        $objDatabase = ClientConnection::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `ClientConnection`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }
}
?>