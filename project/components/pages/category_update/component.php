<?php
require("../../../../divblox/divblox.php");

class CategoryUpdateController extends ProjectComponentController
{
    public function __construct($ComponentNameStr = 'Component')
    {
        parent::__construct($ComponentNameStr);
    }

    public function getBreadCrumbs() {
        $InputCategoryInt = $this->getInputValue("category_id", true);

        $CategoryObj = Category::Load($InputCategoryInt);
        $ReturnArr = ProjectFunctions::getBreadCrumbsRecursive($CategoryObj);
        $ReturnArr = array_reverse($ReturnArr);

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", $ReturnArr);
        $this->presentOutput();
    }

}

$ComponentObj = new CategoryUpdateController("category_update");
?>