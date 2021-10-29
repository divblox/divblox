<?php
require("../../../../divblox/divblox.php");

class ProfilePictureUploaderController extends ProjectComponentController {
    protected $UploadPath = PROJECT_ROOT_STR.'/uploads/profile_pictures/';
    protected $CurrentAccountObj;

    public function __construct($ComponentNameStr = 'Component') {
        if (!file_exists($this->UploadPath)) {
            if (!mkdir($this->UploadPath, 0755, true)) {
                $this->setResult(false);
                $this->setReturnValue("Message", "Cannot create file upload folder");
                $this->setReturnValue("ComponentFriendlyMessage", "Cannot create file upload folder");
                $this->presentOutput();
            }
        }
        parent::__construct($ComponentNameStr);
    }

    public function checkComponentAccess() {
        $this->setResult(true);
        $this->setReturnValue("Message", "Component ready");
        if (!ProjectAccessManager::getComponentAccess(ProjectFunctions::getCurrentAccountId(), $this->ComponentNameStr)) {
            $this->setResult(false);
            $this->setReturnValue("Message", "ACCESS DENIED");
            $this->setReturnValue("ComponentFriendlyMessage", "Access is denied for component '".$this->ComponentNameStr."'");
            $this->presentOutput();
        }
    }

    public function handleFilePost() {
        // initialize FileUploader
        $FileUploader = new FileUploader('files', array(
            'uploadDir' => $this->UploadPath,
            'title' => 'auto',
        ));

        $this->setResult(true);
        // call to upload the files
        $data = $FileUploader->upload();
        $this->setReturnValue("Message", $data);
        $FilePath = "";
        foreach ($data["files"] as $file) {
            $FileDocumentObj = new FileDocument();
            $FileDocumentObj->FileName = $file["name"];
            $FileDocumentObj->Path = $file["file"];
            $FileDocumentObj->UploadedFileName = $file["old_name"];
            $FileDocumentObj->FileType = $file["type"];
            $FileDocumentObj->SizeInKilobytes = round(doubleval(preg_replace('/[^0-9.]+/', '', $file["size2"])), 2);
            $FileDocumentObj->Save();
            if (strlen($FilePath) == 0) {
                $FilePath = $FileDocumentObj->Path;
            }
        }
        error_log("File path: ".$FilePath);
        $this->CurrentAccountObj = Account::Load(ProjectFunctions::getCurrentAccountAttribute());
        if (!is_null($this->CurrentAccountObj)) {
            $this->CurrentAccountObj->ProfilePicturePath = $FilePath;
            $this->CurrentAccountObj->Save();
            error_log("Current Account updated: ".$this->CurrentAccountObj->getJson());
        } else {
            // TODO: Decide what to do...
            error_log("Account null");
        }
        foreach ($data as $key => $value) {
            $this->setReturnValue($key, $value);
        }
        $this->presentOutput();
    }

    public function handleRemoveFile() {
        $FileDocumentObj = FileDocument::QuerySingle(dxQ::Equal(dxQueryN::FileDocument()->FileName, $this->getInputValue("file")));
        if (!is_null($FileDocumentObj)) {
            $FileDocumentObj->Delete();
        }
        $this->CurrentAccountObj = Account::Load(ProjectFunctions::getCurrentAccountAttribute());
        if (!is_null($this->CurrentAccountObj)) {
            $this->CurrentAccountObj->ProfilePicturePath = "";
            $this->CurrentAccountObj->Save();
        } else {
            // TODO: Decide what to do...
        }
    }

    public function handleResizeFile() {
        $file = $this->getInputValue("_file");
        if (is_file($file)) {
            $editor = json_decode($this->getInputValue("_editor"), true);
            Fileuploader::resize($file, null, null, null, (isset($editor['crop']) ? $editor['crop'] : null), 100, (isset($editor['rotation']) ? $editor['rotation'] : null));
        }
    }

}

$ComponentObj = new ProfilePictureUploaderController("profile_picture_uploader");
?>