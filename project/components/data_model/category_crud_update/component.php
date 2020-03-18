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

    public function deleteCategoryAndSubCategories() {
        $CategoryToDeleteIdArr = [];
        $CategoryObj = Category::Load($this->getInputValue("Id"));
        $CategoryToDeleteIdArr[] = $CategoryObj->Id;
        $SubCategoryArr = Category::QueryArray(
            dxQ::Equal(
                dxQN::Category()->CategoryParentId,
                $CategoryObj->Id
            )
        );

        foreach ($SubCategoryArr as $SubCategoryObj) {

        }
        return $CategoryObj;
    }
}
$ComponentObj = new CategoryController("category_crud_update");
?>