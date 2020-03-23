<?php
require("../../../../divblox/divblox.php");
class SubTaskController extends EntityDataSeriesComponentController {
    protected $EntityNameStr = "SubTask";
    protected $IncludedAttributeArray = ["Description","SubTaskStatus","SubTaskDueDate",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = ["Ticket",];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new SubTaskController("subtask_crud_data_series");
?>