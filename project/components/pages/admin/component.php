<?php
require("../../../../divblox/divblox.php");
class AdminController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new AdminController("admin");
?>