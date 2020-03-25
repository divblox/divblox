<?php
require("../../../../divblox/divblox.php");
class NoteCrudController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new NoteCrudController("note_crud");
?>