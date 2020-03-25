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

    public function getObjectData() {
        $EntityObj = $this->EntityNameStr::Load(
            $this->getInputValue("Id",true)
        );
        $EntityJsonDecoded = array();
        $AttachmentPathStr = "";
        if (!is_null($EntityObj)) {
            $EntityJsonDecoded = json_decode($EntityObj->getJson());
            if (!is_null($EntityObj->FileDocumentObject)) {
                if (file_exists(DOCUMENT_ROOT_STR.SUBDIRECTORY_STR.$EntityObj->FileDocumentObject->Path)) {
                    $AttachmentPathStr = ProjectFunctions::getBaseUrl().$EntityObj->FileDocumentObject->Path;
                }
            }
        }
        $this->setReturnValue("Object",$EntityJsonDecoded);
        foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayValue) {
            $RelationshipList = $this->getRelationshipList($EntityObj,$Relationship);
            $this->setReturnValue($Relationship."List",$RelationshipList);
        }

        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("AttachmentPath", $AttachmentPathStr);
        $this->presentOutput();
    }

    public function doBeforeDeleteActions($EntityToUpdateObj = null) {
        // JGL: This function is intended to be overridden in the child class for additional functionality
        if (is_null($EntityToUpdateObj)) {
            return;
        }
        if (!is_null($EntityToUpdateObj->FileDocumentObject)) {
            $EntityToUpdateObj->FileDocumentObject->Delete();
        }

    }
}
$ComponentObj = new NoteController("note_crud_update");
?>