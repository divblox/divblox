<?php
require("../../../../divblox/divblox.php");
class NativeCameraController extends ProjectComponentController {
    protected $UploadPath = PROJECT_ROOT_STR.'/uploads/';
    public function __construct($ComponentNameStr = 'Component') {
        if (!file_exists($this->UploadPath)) {
            mkdir($this->UploadPath,0755);
        }
        parent::__construct($ComponentNameStr);
    }
    public function handleFilePost() {
        $ServerPath = str_replace(PROJECT_ROOT_STR."/", "", $this->UploadPath);
        $ServerPath = ProjectFunctions::getBaseUrl()."/project/".$ServerPath;
        if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
            $file = $_FILES["file"]["tmp_name"];
            $UniqueFileName = md5(dxDateTime::Now()->getTimestamp().ProjectFunctions::generateRandomString(5));
            $InputFileName = $_FILES["file"]["name"];
            $LastDot = strrpos($InputFileName,".");
            $FileNameWithoutExtension = substr($InputFileName, 0,$LastDot);
            $Extension = str_replace($FileNameWithoutExtension, "", $InputFileName);
            $UniqueFileName .= $Extension;
            if (!move_uploaded_file($file, $this->UploadPath.$UniqueFileName)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","Error uploading [1]");
                $this->presentOutput();
            } else {
                $FileDocumentObj = new FileDocument();
                $FileDocumentObj->FileName = $UniqueFileName;
                $FileDocumentObj->Path = $this->UploadPath.$UniqueFileName;
                $FileDocumentObj->UploadedFileName = $InputFileName;
                $FileDocumentObj->FileType = $_FILES["file"]["type"];
                $FileDocumentObj->SizeInKilobytes = round($_FILES["file"]["size"]/1024,2);
                $FileDocumentObj->Save();
            }
            $this->setReturnValue("Result","Success");
            $this->setReturnValue("Message","Uploaded");
            $this->setReturnValue("Path",$this->UploadPath.$UniqueFileName);
            $this->setReturnValue("ServerPath",$ServerPath.$UniqueFileName);
            $this->presentOutput();
        } else {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Error uploading [2]");
            $this->presentOutput();
        }
    }
}
$ComponentObj = new NativeCameraController("native_camera");
?>