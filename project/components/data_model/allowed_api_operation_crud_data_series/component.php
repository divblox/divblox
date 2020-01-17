<?php
require("../../../../divblox/divblox.php");
class AllowedApiOperationDataListController extends ProjectComponentController {
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
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"AllowedApiOperation");
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Read access denied");
            $this->presentOutput();
        }
        $Offset = $this->getInputValue("CurrentOffset");
        $QueryCondition = dxQ::All();
        $QueryCondition = dxQ::AndCondition($QueryCondition,dxQ::Equal(dxQN::AllowedApiOperation()->ApiKeyObject->Id, $this->getInputValue("ConstrainingApiKeyId",true)));
            
        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $QueryCondition = dxQ::AndCondition(
                    $QueryCondition,
                    dxQ::OrCondition(dxQ::Like(dxQueryN::AllowedApiOperation()->IsActive, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::AllowedApiOperation()->ApiOperationObject->OperationName, "%".$this->getInputValue("SearchText")."%")));
            }
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::AllowedApiOperation()->IsActive);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::AllowedApiOperation()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $AllowedApiOperationArray = AllowedApiOperation::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage"),$Offset)
            ));
        $AllowedApiOperationReturnArray = [];
        foreach($AllowedApiOperationArray as $AllowedApiOperationObj) {
            $ApiOperationStr = "Not Defined";
            if (!is_null($AllowedApiOperationObj->ApiOperationObject) &&
                !is_null($AllowedApiOperationObj->ApiOperationObject->OperationName)) {
                $ApiOperationStr = $AllowedApiOperationObj->ApiOperationObject->OperationName;
            }
            
            array_push($AllowedApiOperationReturnArray,
                array("Id" => $AllowedApiOperationObj->Id,
                    "IsActive" => is_null($AllowedApiOperationObj->IsActive)? 'N/A':$AllowedApiOperationObj->IsActive,
                    "ApiOperation" => $ApiOperationStr,
                    ));
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$AllowedApiOperationReturnArray);
        $this->setReturnValue("TotalCount",AllowedApiOperation::QueryCount($QueryCondition));
        $this->presentOutput();
    }
}

$ComponentObj = new AllowedApiOperationDataListController("allowed_api_operation_crud_data_series");
?>