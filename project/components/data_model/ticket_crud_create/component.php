<?php
require("../../../../divblox/divblox.php");
class TicketController extends EntityInstanceComponentController {
    protected $EntityNameStr = "Ticket";
    protected $IncludedAttributeArray = ["TicketName","TicketDescription","TicketDueDate","TicketStatus","TicketUniqueId",];
    protected $IncludedRelationshipArray = ["Category" => "HierarchyPath",];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = ["TicketName", "TicketDescription"];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }

}
$ComponentObj = new TicketController("ticket_crud_create");
?>