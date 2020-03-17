<?php
require("../../../../divblox/divblox.php");
class CategoryManagementController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new CategoryManagementController("category_management");
?>