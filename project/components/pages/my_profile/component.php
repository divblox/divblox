<?php
require("../../../../divblox/divblox.php");

class MyProfileController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new MyProfileController("my_profile");
?>