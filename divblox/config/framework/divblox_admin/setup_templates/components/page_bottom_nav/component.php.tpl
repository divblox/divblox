<?php
[require_divblox]
class [component_class_name]Controller extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new [component_class_name]Controller("[component_name]");
?>