<?php
require(DATA_MODEL_ORM_PATH_STR . '/generated/TicketGen.class.php');

/**
 * The Ticket class defined here contains any
 * customized code for the Ticket class in the
 * Object Relational Model.  It represents the "Ticket" table
 * in the database, and extends from the code generated abstract TicketGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class Ticket extends TicketGen {
	/**
	 * Default "to string" handler
	 * Allows pages to _p()/echo()/print() this object, and to define the default
	 * way this object would be outputted.
	 *
	 * Can also be called directly via $objTicket->__toString().
	 *
	 * @return string a nicely formatted string representation of this object
	 */
	public function __toString() {
		return sprintf('Ticket Object %s', $this->intId);
	}

	/**
	 * Save this Ticket
	 * @param bool $blnForceInsert
	 * @param bool $blnForceUpdate
	 * @return int
	 */
	public function Save($blnForceInsert = false, $blnForceUpdate = false) {
		// $mixToReturn = parent::Save($blnForceInsert, $blnForceUpdate);

		$ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(), "Ticket", $this->intId);
		// Get the Database Object for this Class
		$objDatabase = Ticket::GetDatabase();
		$mixToReturn = null;
		if (!is_numeric($this->intObjectOwner)) {
			$this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
		}
		$ExistingObj = Ticket::Load($this->intId);

		// Calculating TicketProgression ///////////////////////////////////////
		$TotalInt = SubTask::QueryCount(
			dxQ::Equal(
				dxQN::SubTask()->TicketObject->Id,
				$this->intId
			)
		);
		$CompletedInt = SubTask::QueryCount(
			dxQ::AndCondition(
				dxQ::Equal(
					dxQN::SubTask()->TicketObject->Id,
					$this->intId
				), dxQ::Equal(
				dxQN::SubTask()->SubTaskStatus,
				"Complete"
			)
			)
		);
		if ($ExistingObj) {
			if ($TotalInt !== 0) {
				$TicketProgress = round(($CompletedInt / $TotalInt) * 100);
			} else if ($ExistingObj->TicketStatus == "Complete") {
				$TicketProgress = 100;
			}
		} else {
			$TicketProgress = 0;
		}
		////////////////////////////////////////////////////////////////////

		$newAuditLogEntry = new AuditLogEntry();
		$ChangedArray = array();
		$newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
		$newAuditLogEntry->ObjectId = $this->intId;
		$newAuditLogEntry->ObjectName = 'Ticket';
		$newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
		if (!$ExistingObj) {
			$newAuditLogEntry->ModificationType = 'Create';
			$ChangedArray = array_merge($ChangedArray, array("Id" => $this->intId));
			$ChangedArray = array_merge($ChangedArray, array("TicketName" => $this->strTicketName));
			$ChangedArray = array_merge($ChangedArray, array("TicketDescription" => $this->strTicketDescription));
			$ChangedArray = array_merge($ChangedArray, array("TicketDueDate" => $this->dttTicketDueDate));
			$ChangedArray = array_merge($ChangedArray, array("TicketStatus" => $this->strTicketStatus));
			$ChangedArray = array_merge($ChangedArray, array("TicketUniqueId" => $this->strTicketUniqueId));
			$ChangedArray = array_merge($ChangedArray, array("TicketProgress" => $TicketProgress));
			$ChangedArray = array_merge($ChangedArray, array("LastUpdated" => $this->strLastUpdated));
			$ChangedArray = array_merge($ChangedArray, array("Account" => $this->intAccount));
			$ChangedArray = array_merge($ChangedArray, array("SearchMetaInfo" => $this->strSearchMetaInfo));
			$ChangedArray = array_merge($ChangedArray, array("Category" => $this->intCategory));
			$ChangedArray = array_merge($ChangedArray, array("ObjectOwner" => $this->intObjectOwner));
			$newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
		} else {
			$newAuditLogEntry->ModificationType = 'Update';
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->Id)) {
				$ExistingValueStr = $ExistingObj->Id;
			}
			if ($ExistingObj->Id != $this->intId) {
				$ChangedArray = array_merge($ChangedArray, array("Id" => array("Before" => $ExistingValueStr, "After" => $this->intId)));
				//$ChangedArray = array_merge($ChangedArray,array("Id" => "From: ".$ExistingValueStr." to: ".$this->intId));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->TicketName)) {
				$ExistingValueStr = $ExistingObj->TicketName;
			}
			if ($ExistingObj->TicketName != $this->strTicketName) {
				$ChangedArray = array_merge($ChangedArray, array("TicketName" => array("Before" => $ExistingValueStr, "After" => $this->strTicketName)));
				//$ChangedArray = array_merge($ChangedArray,array("TicketName" => "From: ".$ExistingValueStr." to: ".$this->strTicketName));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->TicketDescription)) {
				$ExistingValueStr = $ExistingObj->TicketDescription;
			}
			if ($ExistingObj->TicketDescription != $this->strTicketDescription) {
				$ChangedArray = array_merge($ChangedArray, array("TicketDescription" => array("Before" => $ExistingValueStr, "After" => $this->strTicketDescription)));
				//$ChangedArray = array_merge($ChangedArray,array("TicketDescription" => "From: ".$ExistingValueStr." to: ".$this->strTicketDescription));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->TicketDueDate)) {
				$ExistingValueStr = $ExistingObj->TicketDueDate;
			}
			if ($ExistingObj->TicketDueDate != $this->dttTicketDueDate) {
				$ChangedArray = array_merge($ChangedArray, array("TicketDueDate" => array("Before" => $ExistingValueStr, "After" => $this->dttTicketDueDate)));
				//$ChangedArray = array_merge($ChangedArray,array("TicketDueDate" => "From: ".$ExistingValueStr." to: ".$this->dttTicketDueDate));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->TicketStatus)) {
				$ExistingValueStr = $ExistingObj->TicketStatus;
			}
			if ($ExistingObj->TicketStatus != $this->strTicketStatus) {
				$ChangedArray = array_merge($ChangedArray, array("TicketStatus" => array("Before" => $ExistingValueStr, "After" => $this->strTicketStatus)));
				//$ChangedArray = array_merge($ChangedArray,array("TicketStatus" => "From: ".$ExistingValueStr." to: ".$this->strTicketStatus));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->TicketUniqueId)) {
				$ExistingValueStr = $ExistingObj->TicketUniqueId;
			}
			if ($ExistingObj->TicketUniqueId != $this->strTicketUniqueId) {
				$ChangedArray = array_merge($ChangedArray, array("TicketUniqueId" => array("Before" => $ExistingValueStr, "After" => $this->strTicketUniqueId)));
				//$ChangedArray = array_merge($ChangedArray,array("TicketUniqueId" => "From: ".$ExistingValueStr." to: ".$this->strTicketUniqueId));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->TicketProgress)) {
				$ExistingValueStr = $ExistingObj->TicketProgress;
			}
			if ($ExistingObj->TicketProgress != $TicketProgress) {
				$ChangedArray = array_merge($ChangedArray, array("TicketProgress" => array("Before" => $ExistingValueStr, "After" => $TicketProgress)));
				//$ChangedArray = array_merge($ChangedArray,array("TicketProgress" => "From: ".$ExistingValueStr." to: ".$this->intTicketProgress));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->LastUpdated)) {
				$ExistingValueStr = $ExistingObj->LastUpdated;
			}
			if ($ExistingObj->LastUpdated != $this->strLastUpdated) {
				$ChangedArray = array_merge($ChangedArray, array("LastUpdated" => array("Before" => $ExistingValueStr, "After" => $this->strLastUpdated)));
				//$ChangedArray = array_merge($ChangedArray,array("LastUpdated" => "From: ".$ExistingValueStr." to: ".$this->strLastUpdated));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->Account)) {
				$ExistingValueStr = $ExistingObj->Account;
			}
			if ($ExistingObj->Account != $this->intAccount) {
				$ChangedArray = array_merge($ChangedArray, array("Account" => array("Before" => $ExistingValueStr, "After" => $this->intAccount)));
				//$ChangedArray = array_merge($ChangedArray,array("Account" => "From: ".$ExistingValueStr." to: ".$this->intAccount));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->SearchMetaInfo)) {
				$ExistingValueStr = $ExistingObj->SearchMetaInfo;
			}
			if ($ExistingObj->SearchMetaInfo != $this->strSearchMetaInfo) {
				$ChangedArray = array_merge($ChangedArray, array("SearchMetaInfo" => array("Before" => $ExistingValueStr, "After" => $this->strSearchMetaInfo)));
				//$ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => "From: ".$ExistingValueStr." to: ".$this->strSearchMetaInfo));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->Category)) {
				$ExistingValueStr = $ExistingObj->Category;
			}
			if ($ExistingObj->Category != $this->intCategory) {
				$ChangedArray = array_merge($ChangedArray, array("Category" => array("Before" => $ExistingValueStr, "After" => $this->intCategory)));
				//$ChangedArray = array_merge($ChangedArray,array("Category" => "From: ".$ExistingValueStr." to: ".$this->intCategory));
			}
			$ExistingValueStr = "NULL";
			if (!is_null($ExistingObj->ObjectOwner)) {
				$ExistingValueStr = $ExistingObj->ObjectOwner;
			}
			if ($ExistingObj->ObjectOwner != $this->intObjectOwner) {
				$ChangedArray = array_merge($ChangedArray, array("ObjectOwner" => array("Before" => $ExistingValueStr, "After" => $this->intObjectOwner)));
				//$ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => "From: ".$ExistingValueStr." to: ".$this->intObjectOwner));
			}
			$newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
		}

		try {
			if ((!$this->__blnRestored) || ($blnForceInsert)) {
				if (!in_array(AccessOperation::CREATE_STR, $ObjectAccessArray)) {
					// This user is not allowed to create an object of this type
					throw new Exception("User is not allowed to perform operation " . AccessOperation::CREATE_STR . " on entity of type 'Ticket'. Allowed access is " . json_encode($ObjectAccessArray));
				}


				// Perform an INSERT query
				$objDatabase->NonQuery('
                INSERT INTO `Ticket` (
							`TicketName`,
							`TicketDescription`,
							`TicketDueDate`,
							`TicketStatus`,
							`TicketUniqueId`,
							`TicketProgress`,
							`Account`,
							`SearchMetaInfo`,
							`Category`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strTicketName) . ',
							' . $objDatabase->SqlVariable($this->strTicketDescription) . ',
							' . $objDatabase->SqlVariable($this->dttTicketDueDate) . ',
							' . $objDatabase->SqlVariable($this->strTicketStatus) . ',
							' . $objDatabase->SqlVariable($this->strTicketUniqueId) . ',
							' . $objDatabase->SqlVariable($TicketProgress) . ',
							' . $objDatabase->SqlVariable($this->intAccount) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intCategory) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
				// Update Identity column and return its value
				$mixToReturn = $this->intId = $objDatabase->InsertId('Ticket', 'Id');
			} else {
				// Perform an UPDATE query
				// First checking for Optimistic Locking constraints (if applicable)
				if (!in_array(AccessOperation::UPDATE_STR, $ObjectAccessArray)) {
					// This user is not allowed to create an object of this type
					throw new Exception("User is not allowed to perform operation " . AccessOperation::UPDATE_STR . " on entity of type 'Ticket'. Allowed access is " . json_encode($ObjectAccessArray));
				}
				if (!$blnForceUpdate) {
					// Perform the Optimistic Locking check
					$objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `Ticket` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

					$objRow = $objResult->FetchArray();
					if ($objRow[0] != $this->strLastUpdated)
						throw new dxOptimisticLockingException('Ticket');
				}

				// Perform the UPDATE query
				$objDatabase->NonQuery('
            UPDATE `Ticket` SET
							`TicketName` = ' . $objDatabase->SqlVariable($this->strTicketName) . ',
							`TicketDescription` = ' . $objDatabase->SqlVariable($this->strTicketDescription) . ',
							`TicketDueDate` = ' . $objDatabase->SqlVariable($this->dttTicketDueDate) . ',
							`TicketStatus` = ' . $objDatabase->SqlVariable($this->strTicketStatus) . ',
							`TicketUniqueId` = ' . $objDatabase->SqlVariable($this->strTicketUniqueId) . ',
							`TicketProgress` = ' . $objDatabase->SqlVariable($TicketProgress) . ',
							`Account` = ' . $objDatabase->SqlVariable($this->intAccount) . ',
							`SearchMetaInfo` = ' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							`Category` = ' . $objDatabase->SqlVariable($this->intCategory) . ',
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
		} catch (dxCallerException $e) {
			error_log('Could not save audit log while saving Ticket. Details: ' . $newAuditLogEntry->getJson() . '<br>Error details: ' . $e->getMessage());
		}
		// Update __blnRestored and any Non-Identity PK Columns (if applicable)
		$this->__blnRestored = true;

		// Update Local Timestamp
		$objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `Ticket` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

		$objRow = $objResult->FetchArray();
		$this->strLastUpdated = $objRow[0];

		$this->DeleteCache();

		// Category Count logic /////////////////////////////////////
		$CategoryObj = Category::Load($this->intCategory);
		if (!is_null($CategoryObj)) {
			$TicketCount = Ticket::QueryCount(
				dxQ::Equal(
					dxQN::Ticket()->CategoryObject->Id,
					$CategoryObj->Id
				)
			);

			$CategoryObj->TicketCount = $TicketCount;
			$CategoryObj->Save();
		}
		///////////////////////////////////////////////////////////////

		// Return
		return $mixToReturn;
	}

	/**
	 * Delete this Ticket
	 * @return void
	 */
	public
	function Delete() {
		$CategoryObj = Category::Load($this->intCategory);
		parent::Delete();

		if (!is_null($CategoryObj)) {
			$TicketCount = Ticket::QueryCount(
				dxQ::Equal(
					dxQN::Ticket()->CategoryObject->Id,
					$CategoryObj->Id
				)
			);

			$CategoryObj->TicketCount = $TicketCount;
			$CategoryObj->Save();
		}
	}
}

?>