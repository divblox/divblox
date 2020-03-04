<?php
require("../../../../divblox/divblox.php");
class TicketStatusCrudController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new TicketStatusCrudController("ticket_status_crud");
?>