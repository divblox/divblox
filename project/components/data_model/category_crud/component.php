<?php
require("../../../../divblox/divblox.php");
class CategoryCrudController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new CategoryCrudController("category_crud");
?>