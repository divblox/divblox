<?php
require("../../../../divblox/divblox.php");
class MockPageController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new MockPageController("mock_page");
?>