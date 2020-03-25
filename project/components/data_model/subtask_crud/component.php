<?php
require("../../../../divblox/divblox.php");
class SubtaskCrudController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new SubtaskCrudController("subtask_crud");
?>