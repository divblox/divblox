<?php
require("../../../../divblox/divblox.php");
class ApiKeyDataTableController extends ProjectComponentController {
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
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ApiKey");
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
                    dxQ::OrCondition(dxQ::Like(dxQueryN::ApiKey()->ApiKey, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::ApiKey()->ValidFromDate, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::ApiKey()->ValidToDate, "%".$this->getInputValue("SearchText")."%")));
            }
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::ApiKey()->ApiKey);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::ApiKey()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $ApiKeyArray = ApiKey::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage"),$Offset)
            ));
        $ApiKeyReturnArray = [];
        foreach($ApiKeyArray as $ApiKeyObj) {
            
            array_push($ApiKeyReturnArray,
                array("Id" => $ApiKeyObj->Id,
                    "ApiKey" => is_null($ApiKeyObj->ApiKey)? 'N/A':$ApiKeyObj->ApiKey,
                    "ValidFromDate" => is_null($ApiKeyObj->ValidFromDate)? 'N/A':$ApiKeyObj->ValidFromDate->format(DATE_TIME_FORMAT_PHP_STR),
                    "ValidToDate" => is_null($ApiKeyObj->ValidToDate)? 'N/A':$ApiKeyObj->ValidToDate->format(DATE_TIME_FORMAT_PHP_STR),
                    ));
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$ApiKeyReturnArray);
        $this->setReturnValue("TotalCount",ApiKey::QueryCount($QueryCondition));
        $this->presentOutput();
    }
    public function deleteSelection() {
        if (is_null($this->getInputValue("SelectedItemArray"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No items provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ApiKey");
        if (!in_array(AccessOperation::DELETE_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Delete permission denied");
            $this->presentOutput();
        }
        $DeleteItemsArray = json_decode($this->getInputValue("SelectedItemArray"));
        $DeleteCount = 0;
        foreach($DeleteItemsArray as $item) {
            $ApiKeyToDeleteObj = ApiKey::Load($item);
            if (is_null($ApiKeyToDeleteObj)) {
                continue;
            }
            $ApiKeyToDeleteObj->Delete();
            $DeleteCount++;
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","$DeleteCount items deleted");
        $this->presentOutput();
    }
}
$ComponentObj = new ApiKeyDataTableController("api_key_administration_data_series");
?>