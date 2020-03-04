<?php
require("../../../../divblox/divblox.php");
class TicketStatusController extends EntityInstanceComponentController {
    protected $EntityNameStr = "TicketStatus";
    protected $IncludedAttributeArray = ["StatusLabel",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new TicketStatusController("ticket_status_crud_update");
?>