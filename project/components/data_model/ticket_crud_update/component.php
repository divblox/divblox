<?php
require("../../../../divblox/divblox.php");
class TicketController extends EntityInstanceComponentController {
    protected $EntityNameStr = "Ticket";
    protected $IncludedAttributeArray = ["TicketName","TicketDescription","DueDate","TicketUniqueId",];
    protected $IncludedRelationshipArray = ["TicketStatus" => "StatusLabel",];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new TicketController("ticket_crud_update");
?>