<?php
require("../../../../divblox/divblox.php");
class TicketController extends EntityDataSeriesComponentController {
    protected $EntityNameStr = "Ticket";
    protected $IncludedAttributeArray = ["TicketName","TicketDescription","DueDate","TicketStatus",];
    protected $IncludedRelationshipArray = ["Account" => "FullName","Category" => "CategoryLabel",];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new TicketController("ticket_crud_data_series");
?>