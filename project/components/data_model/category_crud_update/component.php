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
        // JGL: This function is intended to be overridden in the child class for additional functionality
        if (is_null($EntityToUpdateObj)) {
            return;
        }

        $SubCategoryArr = Category::QueryArray(
            dxQ::Equal(
                dxQN::Category()->CategoryParentIdId,
                $EntityToUpdateObj->Id
            )
        );
    }

    public function deleteCategoryAndSubCategories(Category $CategoryObj = null, $CategoryToDeleteIdArr = []) {
        $CategoryObj = Category::Load($this->getInputValue("Id"));
        $CategoryToDeleteIdArr[] = $CategoryObj->Id;
        $SubCategoryArr = Category::QueryArray(
            dxQ::AndCondition(
                dxQ::Equal(
                    dxQN::Category()->CategoryParentId,
                    $CategoryObj->Id
                )
            )
        );
        $SiblingCategoryArr = Category::QueryArray(
            dxQ::AndCondition(
                dxQ::NotEqual(
                    dxQN::Category()->Id,
                    $CategoryObj->Id
                ),
                dxQ::Equal(
                    dxQN::Category()->CategoryParentIdId.
                    $CategoryObj->CategoryParentId
                )
            )
        );

        foreach ($SubCategoryArr as $SubCategoryObj) {

        }
        return $CategoryObj;
    }
}
$ComponentObj = new CategoryController("category_crud_update");
?>