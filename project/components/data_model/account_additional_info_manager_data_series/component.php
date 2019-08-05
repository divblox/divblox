<?php
require("../../../../divblox/divblox.php");
class AdditionalAccountInformationDataListController extends ProjectComponentController {
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
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"AdditionalAccountInformation");
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Read access denied");
            $this->presentOutput();
        }
        $Offset = $this->getInputValue("CurrentOffset");
        $QueryCondition = dxQ::All();
        $QueryCondition = dxQ::AndCondition($QueryCondition,dxQ::Equal(dxQN::AdditionalAccountInformation()->AccountObject->Id, $this->getInputValue("ConstrainingAccountId",true)));
            
        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $QueryCondition = dxQ::AndCondition(
                    $QueryCondition,
                    dxQ::OrCondition(dxQ::Like(dxQueryN::AdditionalAccountInformation()->Type, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::AdditionalAccountInformation()->Label, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::AdditionalAccountInformation()->Value, "%".$this->getInputValue("SearchText")."%")));
            }
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::AdditionalAccountInformation()->Type);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::AdditionalAccountInformation()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $AdditionalAccountInformationArray = AdditionalAccountInformation::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage"),$Offset)
            ));
        $AdditionalAccountInformationReturnArray = [];
        foreach($AdditionalAccountInformationArray as $AdditionalAccountInformationObj) {
            
            array_push($AdditionalAccountInformationReturnArray,
                array("Id" => $AdditionalAccountInformationObj->Id,
                    "Type" => is_null($AdditionalAccountInformationObj->Type)? 'N/A':$AdditionalAccountInformationObj->Type,
                    "Label" => is_null($AdditionalAccountInformationObj->Label)? 'N/A':$AdditionalAccountInformationObj->Label,
                    "Value" => is_null($AdditionalAccountInformationObj->Value)? 'N/A':$AdditionalAccountInformationObj->Value,
                    ));
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$AdditionalAccountInformationReturnArray);
        $this->setReturnValue("TotalCount",AdditionalAccountInformation::QueryCount($QueryCondition));
        $this->presentOutput();
    }
}

$ComponentObj = new AdditionalAccountInformationDataListController("account_additional_info_manager_data_series");
?>