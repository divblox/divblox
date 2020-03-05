<?php
require("../../../../divblox/divblox.php");
class TicketCrudController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new TicketCrudController("ticket_crud");
?>