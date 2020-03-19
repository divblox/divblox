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

    // The function on our component controller that will return a new unique ticket ID for us.
    // This function is executed when we pass "getNewTicketUniqueId" as
    // the value for "f" from our component JavaScript
    public function getNewTicketUniqueId() {
        // setReturnValue() sets the values in an array that will be returned as JSON
        //when the script completes. We always need to set the value for "Result" to either
        // "Success" or "Failed" in order for the component JavaScript to know
        // how to treat the response
        $this->setReturnValue("Result","Success");
        // It is always a good idea to populate a "Message" for the front-end
        $this->setReturnValue("Message", "New unique ID created");
        // Here we set the value of any additional parameters to return
        $this->setReturnValue("TicketId", ProjectFunctions::getNewTicketUniqueId());
        // "presentOutput()" returns our array as JSON and stops any
        // further execution of the current php script
        $this->presentOutput();
    }
}
$ComponentObj = new TicketController("ticket_crud_create");
?>