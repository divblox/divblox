<?php
require("../../../../divblox/divblox.php");
class TicketUpdateController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new TicketUpdateController("ticket_update");
?>