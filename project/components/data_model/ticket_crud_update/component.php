<?php
require("../../../../divblox/divblox.php");

class TicketController extends EntityInstanceComponentController {
	protected $EntityNameStr = "Ticket";
	protected $IncludedAttributeArray = ["TicketName", "TicketDescription", "TicketDueDate", "TicketStatus", "TicketUniqueId",];
	protected $IncludedRelationshipArray = ["Category" => "HierarchyPath",];
	protected $ConstrainByArray = [];
	protected $RequiredAttributeArray = [];
	protected $NumberValidationAttributeArray = [];

	public function __construct($ComponentNameStr = 'Component') {
		parent::__construct($ComponentNameStr);
	}

	public function getNewTicketUniqueId() {
		$this->setReturnValue("Result", "Success");
		$this->setReturnValue("Message", "New unique ID created");
		$this->setReturnValue("TicketId", ProjectFunctions::getNewTicketUniqueId());
		$this->presentOutput();
	}
}

$ComponentObj = new TicketController("ticket_crud_update");
?>