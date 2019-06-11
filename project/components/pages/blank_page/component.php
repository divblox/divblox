<?php
require("../../../../divblox/divblox.php");
class BlankPageController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new BlankPageController("blank_page");
?>