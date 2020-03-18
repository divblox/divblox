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

    public function doAfterSaveActions($EntityToUpdateObj = null) {
        // JGL: This function is intended to be overridden in the child class for additional functionality
        if (is_null($EntityToUpdateObj)) {
            return;
        }

        $ParentCategoryObj = Category::Load(
            $this->getInputValue("ConstrainingCategoryId", true)
        );

        if (!is_null($ParentCategoryObj)) {
            $EntityToUpdateObj->CategoryParentId = $ParentCategoryObj->Id;
            $EntityToUpdateObj->Save();
            $ReturnArr = ProjectFunctions::getBreadCrumbsRecursive($EntityToUpdateObj);
            $ReturnArr = array_reverse($ReturnArr);
            $HierarchyPathStr = "";
            foreach($ReturnArr as $CategoryLabel => $CategoryId) {
                if (strlen($HierarchyPathStr) > 0) {
                    $HierarchyPathStr .= ' / ';
                }
                $HierarchyPathStr .= $CategoryLabel;
            }
            $EntityToUpdateObj->HierarchyPath = $HierarchyPathStr;
        } else {
            $EntityToUpdateObj->HierachryPath = $EntityToUpdateObj->CategoryLabel;
        }
        $EntityToUpdateObj->Save();
    }

}
$ComponentObj = new CategoryController("category_crud_create");
?>