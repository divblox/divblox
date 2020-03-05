<?php
require("../../../../divblox/divblox.php");
class NewTicketController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new NewTicketController("new_ticket");
?>