<?php
require("../../../../divblox/divblox.php");
class ApiOperationDataTableController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function getPage() {
        if (is_null($this->getInputValue("CurrentPage"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Page not provided");
            $this->presentOutput();
        }
        if (is_null($this->getInputValue("ItemsPerPage"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No items per page provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ApiOperation");
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Read access denied");
            $this->presentOutput();
        }
        $Offset = ($this->getInputValue("CurrentPage") - 1) * $this->getInputValue("ItemsPerPage");
        $QueryCondition = dxQ::All();
        
        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $QueryCondition = dxQ::AndCondition(
                    $QueryCondition,
                    dxQ::OrCondition(dxQ::Like(dxQueryN::ApiOperation()->OperationName, "%".$this->getInputValue("SearchText")."%")));
            }
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::ApiOperation()->OperationName);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::ApiOperation()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $ApiOperationArray = ApiOperation::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage"),$Offset)
            ));
        $ApiOperationReturnArray = [];
        foreach($ApiOperationArray as $ApiOperationObj) {
            
            array_push($ApiOperationReturnArray,
                array("Id" => $ApiOperationObj->Id,
                    "OperationName" => is_null($ApiOperationObj->OperationName)? 'N/A':$ApiOperationObj->OperationName,
                    ));
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$ApiOperationReturnArray);
        $this->setReturnValue("TotalCount",ApiOperation::QueryCount($QueryCondition));
        $this->presentOutput();
    }
    public function deleteSelection() {
        if (is_null($this->getInputValue("SelectedItemArray"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No items provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ApiOperation");
        if (!in_array(AccessOperation::DELETE_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Delete permission denied");
            $this->presentOutput();
        }
        $DeleteItemsArray = json_decode($this->getInputValue("SelectedItemArray"));
        $DeleteCount = 0;
        foreach($DeleteItemsArray as $item) {
            $ApiOperationToDeleteObj = ApiOperation::Load($item);
            if (is_null($ApiOperationToDeleteObj)) {
                continue;
            }
            $ApiOperationToDeleteObj->Delete();
            $DeleteCount++;
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","$DeleteCount items deleted");
        $this->presentOutput();
    }
}
$ComponentObj = new ApiOperationDataTableController("api_operation_administration_data_series");
?>