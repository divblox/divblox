<?php
require("../../../../divblox/divblox.php");
class BlankPageWithSideNavController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new BlankPageWithSideNavController("blank_page_with_side_nav");
?>