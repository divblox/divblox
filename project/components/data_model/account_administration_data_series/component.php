<?php
require("../../../../divblox/divblox.php");
class AccountDataTableController extends ProjectComponentController {
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
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Account");
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Read access denied");
            $this->presentOutput();
        }
        $Offset = ($this->getInputValue("CurrentPage") - 1) * $this->getInputValue("ItemsPerPage");
        $QueryCondition = dxQ::All();
        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $QueryCondition = dxQ::OrCondition(
                    dxQ::Like(dxQueryN::Account()->FullName, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::Account()->EmailAddress, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::Account()->AccessBlocked, "%".$this->getInputValue("SearchText")."%"),
                    dxQ::Like(dxQueryN::Account()->UserRoleObject->Role, "%".$this->getInputValue("SearchText")."%"));
            }
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::Account()->FullName);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::Account()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $AccountArray = Account::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage"),$Offset)
            ));
        $AccountReturnArray = [];
        foreach($AccountArray as $AccountObj) {
            $UserRoleStr = "Not Defined";
            if (!is_null($AccountObj->UserRoleObject) &&
                !is_null($AccountObj->UserRoleObject->Role)) {
                $UserRoleStr = $AccountObj->UserRoleObject->Role;
            }

            array_push($AccountReturnArray,
                array("Id" => $AccountObj->Id,
                    "FullName" => $AccountObj->FullName,
                    "EmailAddress" => $AccountObj->EmailAddress,
                    "AccessBlocked" => $AccountObj->AccessBlocked,
                    "UserRole" => $UserRoleStr,
                ));
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$AccountReturnArray);
        $this->setReturnValue("TotalCount",Account::QueryCount($QueryCondition));
        $this->presentOutput();
    }
    public function deleteSelection() {
        if (is_null($this->getInputValue("SelectedItemArray"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No items provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Account");
        if (!in_array(AccessOperation::DELETE_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Delete permission denied");
            $this->presentOutput();
        }
        $DeleteItemsArray = json_decode($this->getInputValue("SelectedItemArray"));
        $DeleteCount = 0;
        foreach($DeleteItemsArray as $item) {
            $AccountToDeleteObj = Account::Load($item);
            if (is_null($AccountToDeleteObj)) {
                continue;
            }
            $AccountToDeleteObj->Delete();
            $DeleteCount++;
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","$DeleteCount items deleted");
        $this->presentOutput();
    }
}
$ComponentObj = new AccountDataTableController("account_administration_data_series");
?>