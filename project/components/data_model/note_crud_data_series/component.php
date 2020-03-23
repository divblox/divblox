<?php
require("../../../../divblox/divblox.php");
class NoteController extends EntityDataSeriesComponentController {
    protected $EntityNameStr = "Note";
    protected $IncludedAttributeArray = ["NoteDescription","NoteCreatedDate",];
    protected $IncludedRelationshipArray = ["FileDocument" => "Path",];
    protected $ConstrainByArray = ["Ticket",];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new NoteController("note_crud_data_series");
?>