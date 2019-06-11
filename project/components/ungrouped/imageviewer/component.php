<?php
require("../../../../divblox/divblox.php");
class ImageViewerController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new ImageViewerController("imageviewer");
?>