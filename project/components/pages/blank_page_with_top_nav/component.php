<?php
require("../../../../divblox/divblox.php");
class BlankPageWithTopNavController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new BlankPageWithTopNavController("blank_page_with_top_nav");
?>