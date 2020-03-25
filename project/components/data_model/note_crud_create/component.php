<?php
require("../../../../divblox/divblox.php");
class NoteController extends EntityInstanceComponentController {
    protected $EntityNameStr = "Note";
    protected $IncludedAttributeArray = ["NoteDescription",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = ["Ticket",];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }

    public function doBeforeSaveActions($EntityToUpdateObj = null) {
        // JGL: This function is intended to be overridden in the child class for additional functionality
        if (is_null($EntityToUpdateObj)) {
            return;
        }
        $EntityToUpdateObj->NoteCreatedDate = dxDateTime::Now();
    }
}
$ComponentObj = new NoteController("note_crud_create");
?>