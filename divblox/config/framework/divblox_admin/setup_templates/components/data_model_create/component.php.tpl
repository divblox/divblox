<?php
[require_divblox]
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
require_once(DATA_MODEL_CLASS_PATH_STR);
class [component_class_name]Controller extends ProjectComponentController {
    protected $DataModelObj;
    protected $RequiredAttributeArray = [Required-Attribute-Array];
    protected $NumberValidationAttributeArray = [Number-Validation-Attribute-Array];
    public function __construct($ComponentNameStr = 'Component') {
        $this->DataModelObj = new DataModel();
        parent::__construct($ComponentNameStr);
    }
    public function getObjectData() {
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        [Relationship-List-Return-Values]
        $this->presentOutput();
    }
    [Relationship-List-Functions]
    public function saveObjectData() {
        if (is_null($this->getInputValue("ObjectData"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No [component_class_name] object provided");
            $this->presentOutput();
        }
        $[component_class_name]Obj = json_decode($this->getInputValue("ObjectData"),true);
        $[component_class_name]ToCreateObj = new [component_class_name]();
        foreach($[component_class_name]ToCreateObj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            if (isset($[component_class_name]Obj[$Attribute])) {
                if (in_array($Attribute, $this->RequiredAttributeArray)) {
                    if (strlen($[component_class_name]Obj[$Attribute]) == 0) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute not provided");
                        $this->presentOutput();
                    }
                }
                if (in_array($Attribute, $this->NumberValidationAttributeArray)) {
                    if (!is_numeric($[component_class_name]Obj[$Attribute])) {
                        $this->setReturnValue("Result","Failed");
                        $this->setReturnValue("Message","$Attribute must be numeric");
                        $this->presentOutput();
                    }
                }
                if (in_array($this->DataModelObj->getEntityAttributeType("[component_class_name]", $Attribute),["DATE","DATETIME"])) {
                    if (is_string($[component_class_name]Obj[$Attribute]) && (strlen($[component_class_name]Obj[$Attribute]) > 0)) {
                        $DateObj = new dxDateTime($[component_class_name]Obj[$Attribute]);
                        $[component_class_name]ToCreateObj->$Attribute = $DateObj;
                    }
                } else {
                    $[component_class_name]ToCreateObj->$Attribute = $[component_class_name]Obj[$Attribute];
                }
            } elseif (in_array($Attribute, $this->RequiredAttributeArray)) {
                $this->setReturnValue("Result","Failed");
                $this->setReturnValue("Message","$Attribute not provided");
                $this->presentOutput();
            }
        }
        [AdditionalConstrainByValues]
        $[component_class_name]ToCreateObj->Save();
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Object created");
        $this->setReturnValue("Id",$[component_class_name]ToCreateObj->Id);
        $this->presentOutput();
    }
}
$ComponentObj = new [component_class_name]Controller("[component_name]");
?>