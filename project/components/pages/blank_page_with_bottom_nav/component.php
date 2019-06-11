<?php
require("../../../../divblox/divblox.php");
class BlankPageWithBottomNavController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new BlankPageWithBottomNavController("blank_page_with_bottom_nav");
?>