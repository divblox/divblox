<?php
[require_divblox]
class [component_class_name]Controller extends EntityInstanceComponentController {
    protected $EntityNameStr = "[EntityName]";
    protected $IncludedAttributeArray = [Included-Attribute-Array];
    protected $IncludedRelationshipArray = [Included-Relationship-Array];
    protected $ConstrainByArray = [ConstrainBy-Array];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}
$ComponentObj = new [component_class_name]Controller("[component_name]");
?>