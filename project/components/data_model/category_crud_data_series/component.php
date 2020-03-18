<?php
require("../../../../divblox/divblox.php");
class CategoryController extends EntityDataSeriesComponentController {
    protected $EntityNameStr = "Category";
    protected $IncludedAttributeArray = ["CategoryLabel",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }

    public function getPage() {
        error_log("Constrain by values: ".json_encode($this->ConstrainByArray));
        $EntityNodeNameStr = $this->EntityNameStr;
        $DefaultSortAttribute = $this->IncludedAttributeArray[0];

        if (is_null($this->getInputValue("ItemsPerPage"))) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","No items per page provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),$this->EntityNameStr);
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Read access denied");
            $this->presentOutput();
        }
        $Offset = $this->getInputValue("CurrentOffset",true);
        if ($Offset < 0) {
            $Offset = ($this->getInputValue("CurrentPage",true) - 1) * $this->getInputValue("ItemsPerPage",true);
        }
        if ($Offset < 0) {
            $Offset = 0;
        }
        $QueryCondition = dxQ::OrCondition(
            dxQ::IsNull(
                dxQN::Category()->CategoryParentId
            ),
            dxQ::Equal(
                dxQN::Category()->CategoryParentId,
                -1
            )
        );

        foreach ($this->ConstrainByArray as $Relationship) {
            $RelationshipNodeStr = $Relationship.'Object';
            $QueryCondition = dxQ::AndCondition(
                $QueryCondition,
                dxQ::Equal(
                    dxQN::$EntityNodeNameStr()->$RelationshipNodeStr->Id, $this->getInputValue('Constraining'.$Relationship.'Id',true)
                )
            );
        }

        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $SearchInputStr = "%".$this->getInputValue("SearchText")."%";
                $QueryOrConditions = null;
                foreach ($this->IncludedAttributeArray as $Attribute) {
                    if (is_null($QueryOrConditions)) {
                        $QueryOrConditions = dxQ::Like(dxQueryN::$EntityNodeNameStr()->$Attribute,$SearchInputStr);
                    } else {
                        $QueryOrConditions = dxQ::OrCondition($QueryOrConditions,
                            dxQ::Like(dxQueryN::$EntityNodeNameStr()->$Attribute,$SearchInputStr));
                    }
                };
                foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayAttribute) {
                    $RelationshipNodeStr = $Relationship.'Object';
                    if (is_null($QueryOrConditions)) {
                        $QueryOrConditions = dxQ::Like(dxQueryN::$EntityNodeNameStr()->$RelationshipNodeStr->$DisplayAttribute,$SearchInputStr);
                    } else {
                        $QueryOrConditions = dxQ::OrCondition($QueryOrConditions,
                            dxQ::Like(dxQueryN::$EntityNodeNameStr()->$RelationshipNodeStr->$DisplayAttribute,$SearchInputStr));
                    }
                };
            }
        }
        $OrderByClause = dxQ::OrderBy(dxQueryN::$EntityNodeNameStr()->$DefaultSortAttribute);
        if (!is_null($this->getInputValue("SortOptions"))) {
            if (ProjectFunctions::isJson($this->getInputValue("SortOptions"))) {
                $SortOptionsArray = json_decode($this->getInputValue("SortOptions"));
                if (is_array($SortOptionsArray)) {
                    if (ProjectFunctions::getDataSetSize($SortOptionsArray) == 2) {
                        $AttributeStr = $SortOptionsArray[0];
                        $OrderByClause = dxQ::OrderBy(dxQueryN::$EntityNodeNameStr()->$AttributeStr,$SortOptionsArray[1]);
                    }
                }
            }
        }
        $EntityArray = $EntityNodeNameStr::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage",true),$Offset)
            ));
        $EntityReturnArray = [];
        foreach($EntityArray as $EntityObj) {
            $CompleteReturnArray = ["Id" => $EntityObj->Id];
            foreach ($this->IncludedAttributeArray as $Attribute) {
                if (in_array($this->DataModelObj->getEntityAttributeType($this->EntityNameStr, $Attribute),["DATE","DATETIME"])) {
                    $CompleteReturnArray[$Attribute] = is_null($EntityObj->$Attribute)? 'N/A':$EntityObj->$Attribute->format(DATE_TIME_FORMAT_PHP_STR." H:i:s");
                } else {
                    $CompleteReturnArray[$Attribute] = is_null($EntityObj->$Attribute)? 'N/A':$EntityObj->$Attribute;
                }
            }

            foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayAttribute) {
                $RelationshipReturnStr = "N/A";
                $RelationshipNodeStr = $this->DataModelObj->getEntityRelationshipPathAsNode($EntityObj,$this->EntityNameStr,$Relationship,[]);
                if (!is_null($RelationshipNodeStr)) {
                    if (!is_null($RelationshipNodeStr->$DisplayAttribute)) {
                        if (in_array($this->DataModelObj->getEntityAttributeType($Relationship, $DisplayAttribute),["DATE","DATETIME"])) {
                            $RelationshipReturnStr = $RelationshipNodeStr->$DisplayAttribute->format(DATE_TIME_FORMAT_PHP_STR." H:i:s");
                        } else {
                            $RelationshipReturnStr = is_null($RelationshipNodeStr->$DisplayAttribute)? 'N/A':$RelationshipNodeStr->$DisplayAttribute;
                        }
                    }
                }
                $CompleteReturnArray[$Relationship] = $RelationshipReturnStr;
            }
            array_push($EntityReturnArray,$CompleteReturnArray);
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","");
        $this->setReturnValue("Page",$EntityReturnArray);
        $this->setReturnValue("TotalCount",$EntityNodeNameStr::QueryCount($QueryCondition));
        $this->presentOutput();
    }
}
$ComponentObj = new CategoryController("category_crud_data_series");
?>