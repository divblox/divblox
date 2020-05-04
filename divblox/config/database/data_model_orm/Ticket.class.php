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
        $TicketProgress = 0;
		if ($ExistingObj) {
			if ($TotalInt !== 0) {
				$TicketProgress = round(($CompletedInt / $TotalInt) * 100);
			} else if ($ExistingObj->TicketStatus == "Complete") {
				$TicketProgress = 100;
			}
		}
		////////////////////////////////////////////////////////////////////
        $this->intTicketProgress = $TicketProgress;

        $mixToReturn = parent::Save($blnForceInsert, $blnForceUpdate);

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