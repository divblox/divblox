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

    public function deleteCategoryAndSubCategories(Category $CategoryObj = null, $CategoryToDeleteIdArr = []) {
        $CategoryObj = Category::Load($this->getInputValue("Id"));
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