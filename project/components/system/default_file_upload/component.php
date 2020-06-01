<?php
require("../../../../divblox/divblox.php");
class DefaultFileUploadController extends ProjectComponentController {
    protected $UploadPath = PROJECT_ROOT_STR.'/uploads/';
    public function __construct($ComponentNameStr = 'Component') {
        if (!file_exists($this->UploadPath)) {
            mkdir($this->UploadPath,0755);
        }
        parent::__construct($ComponentNameStr);
    }
    public function checkComponentAccess() {
        $this->setResult(true);
        $this->setReturnValue("Message","Component ready");
        if (!ProjectAccessManager::getComponentAccess(ProjectFunctions::getCurrentAccountId(),$this->ComponentNameStr)) {
            $this->setResult(false);
            $this->setReturnValue("Message","ACCESS DENIED");
            $this->setReturnValue("ComponentFriendlyMessage","Access is denied for component '".$this->ComponentNameStr."'");
            $this->presentOutput();
        }
    }
    public function handleFilePost() {
        // initialize FileUploader
        $FileUploader = new FileUploader('files', array(
            'uploadDir' => $this->UploadPath,
            'title' => 'auto'
        ));

        $this->setResult(true);
        // call to upload the files
        $data = $FileUploader->upload();
        $this->setReturnValue("Message",$data);
        foreach($data["files"] as $file) {
            $FileDocumentObj = new FileDocument();
            $FileDocumentObj->FileName = $file["name"];
            $FileDocumentObj->Path = $file["file"];
            $FileDocumentObj->UploadedFileName = $file["old_name"];
            $FileDocumentObj->FileType = $file["type"];
            $FileDocumentObj->SizeInKilobytes = round(doubleval(preg_replace('/[^0-9.]+/', '', $file["size2"])),2);
            $FileDocumentObj->Save();
        }
        foreach($data as $key=>$value) {
            $this->setReturnValue($key,$value);
        }
        $this->presentOutput();
    }
    public function handleRemoveFile() {
        $FileDocumentObj = FileDocument::QuerySingle(dxQ::Equal(dxQueryN::FileDocument()->FileName, $this->getInputValue("file")));
        if (!is_null($FileDocumentObj)) {
            $FileDocumentObj->Delete();
        }
    }
}
$ComponentObj = new DefaultFileUploadController("default_file_upload");
?>