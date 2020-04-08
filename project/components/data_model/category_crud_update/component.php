<?php
require("../../../../divblox/divblox.php");
class CategoryController extends EntityInstanceComponentController {
    protected $EntityNameStr = "Category";
    protected $IncludedAttributeArray = ["CategoryLabel",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }

    public function doBeforeDeleteActions($EntityToUpdateObj = null) {
        if (is_null($EntityToUpdateObj)) {
            return;
        }
        self::deleteCategoryAndSubCategories();
    }
    public function deleteObjectData() {
        if (is_null($this->getInputValue("Id"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No ".$this->EntityNameStr." Id provided");
            $this->presentOutput();
        }
        $EntityNodeNameStr = $this->EntityNameStr;
        $EntityObj = $EntityNodeNameStr::Load($this->getInputValue("Id",true));
        if (is_null($EntityObj)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message",$this->EntityNameStr." not found");
            $this->presentOutput();
        } else {
            $this->doBeforeDeleteActions($EntityObj);
            $EntityObj->Delete();
            $this->doAfterDeleteActions();
            $this->setReturnValue("Result","Success");
            $this->setReturnValue("Message",$this->EntityNameStr." deleted");
            $this->presentOutput();
        }
    }
    public function deleteCategoryAndSubCategories($CategoryToDeleteIdArr = []) {
        foreach ($CategoryToDeleteIdArr as $CategoryToDeleteId) {
            $CategoryObj = Category::Load($this->getInputValue("Id"));
        }
        $ToDeleteArr = ProjectFunctions::getSubCategoriesRecursive($CategoryObj, $SubCategoryArr = []);

        foreach($ToDeleteArr as $ToDeleteId) {
            $CategoryObjToDelete = Category::QuerySingle(
                dxQN::Category()->Id,
                $ToDeleteId
            );
            $CategoryObjToDelete->Delete();
        }

    }
}
$ComponentObj = new CategoryController("category_crud_update");
?>