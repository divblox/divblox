<?php
require("../../../../divblox/divblox.php");
class BlankPageWithInstanceTopNavController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new BlankPageWithInstanceTopNavController("blank_page_with_instance_top_nav");
?>