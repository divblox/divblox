<?php
[require_divblox]
class [component_class_name]DataListController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function getPage() {
        if (is_null($this->getInputValue("CurrentOffset"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Page not provided");
            $this->presentOutput();
        }
        if (is_null($this->getInputValue("ItemsPerPage"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No items per page provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"[component_class_name]");
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Read access denied");
            $this->presentOutput();
        }
        $Offset = $this->getInputValue("CurrentOffset");
        $QueryCondition = dxQ::All();
        [AdditionalConstrainByValues]
        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $QueryCondition = dxQ::AndCondition(
                    $QueryCondition,
                    dxQ::OrCondition([Query-Or-Condition]));
            }
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::[component_class_name]()->[First-Included-Attribute]);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::[component_class_name]()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $[component_class_name]Array = [component_class_name]::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage"),$Offset)
            ));
        $[component_class_name]ReturnArray = [];
        foreach($[component_class_name]Array as $[component_class_name]Obj) {
            [Relationship-Return-Values]
            array_push($[component_class_name]ReturnArray,
                array("Id" => $[component_class_name]Obj->Id,
                    [Return-Component-Object]));
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$[component_class_name]ReturnArray);
        $this->setReturnValue("TotalCount",[component_class_name]::QueryCount($QueryCondition));
        $this->presentOutput();
    }
}

$ComponentObj = new [component_class_name]DataListController("[component_name]");
?>