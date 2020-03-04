<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/FileDocumentGen.class.php');

/**
 * The FileDocument class defined here contains any
 * customized code for the FileDocument class in the
 * Object Relational Model.  It represents the "FileDocument" table
 * in the database, and extends from the code generated abstract FileDocumentGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class FileDocument extends FileDocumentGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objFileDocument->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('FileDocument Object %s',  $this->intId);
    }
    /**
     * Save this FileDocument
     * @param bool $blnForceInsert
     * @param bool $blnForceUpdate
     * @return int
     */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"FileDocument",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = FileDocument::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = FileDocument::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'FileDocument';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("FileName" => $this->strFileName));
            $ChangedArray = array_merge($ChangedArray,array("Path" => $this->strPath));
            $ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => $this->strUploadedFileName));
            $ChangedArray = array_merge($ChangedArray,array("FileType" => $this->strFileType));
            $ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => $this->strSizeInKilobytes));
            $ChangedArray = array_merge($ChangedArray,array("CreatedDate" => $this->dttCreatedDate));
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
            if (!is_null($ExistingObj->FileName)) {
                $ExistingValueStr = $ExistingObj->FileName;
            }
            if ($ExistingObj->FileName != $this->strFileName) {
                $ChangedArray = array_merge($ChangedArray,array("FileName" => array("Before" => $ExistingValueStr,"After" => $this->strFileName)));
                //$ChangedArray = array_merge($ChangedArray,array("FileName" => "From: ".$ExistingValueStr." to: ".$this->strFileName));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Path)) {
                $ExistingValueStr = $ExistingObj->Path;
            }
            if ($ExistingObj->Path != $this->strPath) {
                $ChangedArray = array_merge($ChangedArray,array("Path" => array("Before" => $ExistingValueStr,"After" => $this->strPath)));
                //$ChangedArray = array_merge($ChangedArray,array("Path" => "From: ".$ExistingValueStr." to: ".$this->strPath));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->UploadedFileName)) {
                $ExistingValueStr = $ExistingObj->UploadedFileName;
            }
            if ($ExistingObj->UploadedFileName != $this->strUploadedFileName) {
                $ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => array("Before" => $ExistingValueStr,"After" => $this->strUploadedFileName)));
                //$ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => "From: ".$ExistingValueStr." to: ".$this->strUploadedFileName));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->FileType)) {
                $ExistingValueStr = $ExistingObj->FileType;
            }
            if ($ExistingObj->FileType != $this->strFileType) {
                $ChangedArray = array_merge($ChangedArray,array("FileType" => array("Before" => $ExistingValueStr,"After" => $this->strFileType)));
                //$ChangedArray = array_merge($ChangedArray,array("FileType" => "From: ".$ExistingValueStr." to: ".$this->strFileType));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->SizeInKilobytes)) {
                $ExistingValueStr = $ExistingObj->SizeInKilobytes;
            }
            if ($ExistingObj->SizeInKilobytes != $this->strSizeInKilobytes) {
                $ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => array("Before" => $ExistingValueStr,"After" => $this->strSizeInKilobytes)));
                //$ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => "From: ".$ExistingValueStr." to: ".$this->strSizeInKilobytes));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->CreatedDate)) {
                $ExistingValueStr = $ExistingObj->CreatedDate;
            }
            if ($ExistingObj->CreatedDate != $this->dttCreatedDate) {
                $ChangedArray = array_merge($ChangedArray,array("CreatedDate" => array("Before" => $ExistingValueStr,"After" => $this->dttCreatedDate)));
                //$ChangedArray = array_merge($ChangedArray,array("CreatedDate" => "From: ".$ExistingValueStr." to: ".$this->dttCreatedDate));
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
            $this->strPath = str_replace(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR, "", $this->strPath);
            if ((!$this->__blnRestored) || ($blnForceInsert)) {
                if (!in_array(AccessOperation::CREATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'FileDocument'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (is_null($this->dttCreatedDate)) {
                    $this->dttCreatedDate = dxDateTime::Now();
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `FileDocument` (
							`FileName`,
							`Path`,
							`UploadedFileName`,
							`FileType`,
							`SizeInKilobytes`,
							`CreatedDate`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strFileName) . ',
							' . $objDatabase->SqlVariable($this->strPath) . ',
							' . $objDatabase->SqlVariable($this->strUploadedFileName) . ',
							' . $objDatabase->SqlVariable($this->strFileType) . ',
							' . $objDatabase->SqlVariable($this->strSizeInKilobytes) . ',
							' . $objDatabase->SqlVariable($this->dttCreatedDate) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
                // Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('FileDocument', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'FileDocument'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `FileDocument` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                    $objRow = $objResult->FetchArray();
                    if ($objRow[0] != $this->strLastUpdated)
                        throw new dxOptimisticLockingException('FileDocument');
                }

                // Perform the UPDATE query
                $objDatabase->NonQuery('
            UPDATE `FileDocument` SET
							`FileName` = ' . $objDatabase->SqlVariable($this->strFileName) . ',
							`Path` = ' . $objDatabase->SqlVariable($this->strPath) . ',
							`UploadedFileName` = ' . $objDatabase->SqlVariable($this->strUploadedFileName) . ',
							`FileType` = ' . $objDatabase->SqlVariable($this->strFileType) . ',
							`SizeInKilobytes` = ' . $objDatabase->SqlVariable($this->strSizeInKilobytes) . ',
							`CreatedDate` = ' . $objDatabase->SqlVariable($this->dttCreatedDate) . ',
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
            error_log('Could not save audit log while saving FileDocument. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `FileDocument` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this FileDocument
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this FileDocument with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"FileDocument",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'FileDocument'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = FileDocument::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'FileDocument';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("FileName" => $this->strFileName));
        $ChangedArray = array_merge($ChangedArray,array("Path" => $this->strPath));
        $ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => $this->strUploadedFileName));
        $ChangedArray = array_merge($ChangedArray,array("FileType" => $this->strFileType));
        $ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => $this->strSizeInKilobytes));
        $ChangedArray = array_merge($ChangedArray,array("CreatedDate" => $this->dttCreatedDate));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting FileDocument. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        if (file_exists($this->strPath)) {
            unlink($this->strPath);
        } elseif (file_exists(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$this->strPath)) {
            unlink(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$this->strPath);
        }
        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `FileDocument`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }
}
?>