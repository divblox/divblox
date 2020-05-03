<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/SubTaskGen.class.php');

/**
 * The SubTask class defined here contains any
 * customized code for the SubTask class in the
 * Object Relational Model.  It represents the "SubTask" table
 * in the database, and extends from the code generated abstract SubTaskGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class SubTask extends SubTaskGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objSubTask->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('SubTask Object %s',  $this->intId);
    }

	/**
	 * Save this SubTask
	 * @param bool $blnForceInsert
	 * @param bool $blnForceUpdate
	 * @return int
	 */
	public function Save($blnForceInsert = false, $blnForceUpdate = false) {
		$mixToReturn = parent::Save($blnForceInsert, $blnForceUpdate);

		$TicketObj = Ticket::Load($this->intTicket);
		$SubTaskObjArr = SubTask::QueryArray(
			dxQ::Equal(
				dxQN::SubTask()->TicketObject->Id,
				$this->intTicket
			)
		);

		$SubTaskStatusArr = [];
		$NotCompleteBool = false;
		foreach ($SubTaskObjArr as $SubTaskObj) {
			$SubTaskStatusArr[] = $SubTaskObj->SubTaskStatus;
		}

		$StatusCompleteCounter = 0;
		foreach ($SubTaskStatusArr as $Status) {
			if ($Status !== "Complete") {
				$NotCompleteBool = true;
				break;
			} else {
				$StatusCompleteCounter++;
			}
		}

		if ($NotCompleteBool == true) {
			if ($TicketObj->TicketStatus == "Complete") {
				$TicketObj->TicketStatus = "Urgent";
				$TicketObj->Save();
			}
		}

		if ($StatusCompleteCounter == count($SubTaskStatusArr)) {
			if ($TicketObj->TicketStatus !== "Complete") {
				$TicketObj->TicketStatus = "Complete";
				$TicketObj->Save();
			}
		}

		// Return
		return $mixToReturn;
	}
}
?>