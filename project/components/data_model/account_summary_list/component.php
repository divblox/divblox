<?php
require("../../../../divblox/divblox.php");

class AccountController extends EntityDataSeriesComponentController
{
    protected $EntityNameStr = "Account";
    protected $IncludedAttributeArray = ["FullName", "FirstName", "LastName", "ProfilePicturePath", "Title",];
    protected $IncludedRelationshipArray = [];
    protected $ConstrainByArray = [];
    protected $RequiredAttributeArray = [];
    protected $NumberValidationAttributeArray = [];

    public function __construct($ComponentNameStr = 'Component')
    {
        parent::__construct($ComponentNameStr);
    }

    public function getPage()
    {
        error_log("Constrain by values: " . json_encode($this->ConstrainByArray));
        $EntityNodeNameStr = $this->EntityNameStr;
        $DefaultSortAttribute = $this->IncludedAttributeArray[0];

        if (is_null($this->getInputValue("ItemsPerPage"))) {
            $this->setReturnValue("Result", "Failed");
            $this->setReturnValue("Message", "No items per page provided");
            $this->presentOutput();
        }
        $AccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(), $this->EntityNameStr);
        if (!in_array(AccessOperation::READ_STR, $AccessArray)) {
            $this->setReturnValue("Result", "Failed");
            $this->setReturnValue("Message", "Read access denied");
            $this->presentOutput();
        }
        $Offset = $this->getInputValue("CurrentOffset", true);
        if ($Offset < 0) {
            $Offset = ($this->getInputValue("CurrentPage", true) - 1) * $this->getInputValue("ItemsPerPage", true);
        }
        if ($Offset < 0) {
            $Offset = 0;
        }

        $QueryCondition = dxQ::All();

        foreach ($this->ConstrainByArray as $Relationship) {
            $RelationshipNodeStr = $Relationship . 'Object';
            $QueryCondition = dxQ::AndCondition(
                $QueryCondition,
                dxQ::Equal(
                    dxQN::$EntityNodeNameStr()->$RelationshipNodeStr->Id, $this->getInputValue('Constraining' . $Relationship . 'Id', true)
                )
            );
        }
        $this->setReturnValue("This1", $this->getInputValue("SearchText"));
        if (!is_null($this->getInputValue("SearchText"))) {
            if (strlen($this->getInputValue("SearchText")) > 0) {
                $SearchInputStr = "%" . $this->getInputValue("SearchText") . "%";
                $this->setReturnValue("This", $SearchInputStr);
                $QueryOrConditions = null;
                foreach ($this->IncludedAttributeArray as $Attribute) {
                    if (is_null($QueryOrConditions)) {
                        $QueryOrConditions = dxQ::Like(dxQueryN::$EntityNodeNameStr()->$Attribute, $SearchInputStr);
                    } else {
                        $QueryOrConditions = dxQ::OrCondition($QueryOrConditions,
                            dxQ::Like(dxQueryN::$EntityNodeNameStr()->$Attribute, $SearchInputStr));
                    }
                };
                foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayAttribute) {
                    $RelationshipNodeStr = $Relationship . 'Object';
                    if (is_null($QueryOrConditions)) {
                        $QueryOrConditions = dxQ::Like(dxQueryN::$EntityNodeNameStr()->$RelationshipNodeStr->$DisplayAttribute, $SearchInputStr);
                    } else {
                        $QueryOrConditions = dxQ::OrCondition($QueryOrConditions,
                            dxQ::Like(dxQueryN::$EntityNodeNameStr()->$RelationshipNodeStr->$DisplayAttribute, $SearchInputStr));
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
                        $OrderByClause = dxQ::OrderBy(dxQueryN::$EntityNodeNameStr()->$AttributeStr, $SortOptionsArray[1]);
                    }
                }
            }
        }

        $EntityArray = $EntityNodeNameStr::QueryArray(
            $QueryCondition,
            dxQ::Clause(
                $OrderByClause,
                dxQ::LimitInfo($this->getInputValue("ItemsPerPage", true), $Offset)
            )
        );

        $EntityReturnArray = [];

        // Filling up $EntityReturnArray with FullName,ProfilePicturePath,Title,UserRole
        foreach ($EntityArray as $EntityObj) {
            $CompleteReturnArray = ["Id" => $EntityObj->Id];
            foreach ($this->IncludedAttributeArray as $Attribute) {
//                $AttributeArr = ['FullName,ProfilePicturePath,Title'] keys for the values
                if (in_array($this->DataModelObj->getEntityAttributeType($this->EntityNameStr, $Attribute), ["DATE", "DATETIME"])) {
                    $CompleteReturnArray[$Attribute] = is_null($EntityObj->$Attribute) ? 'N/A' : $EntityObj->$Attribute->format(DATE_TIME_FORMAT_PHP_STR . " H:i:s");
                } else if ($Attribute == 'ProfilePicturePath') {
                    $AttachmentPathStr = ProjectFunctions::getBaseUrl(). "/project/assets/images/divblox_profile_picture_placeholder.svg";
                    if (!is_null($EntityObj->ProfilePicturePath)) {
                        if (file_exists(DOCUMENT_ROOT_STR . SUBDIRECTORY_STR . $EntityObj->ProfilePicturePath)) {
                            $AttachmentPathStr = ProjectFunctions::getBaseUrl() . $EntityObj->ProfilePicturePath;
                        }
                    }
                    $CompleteReturnArray[$Attribute] = $AttachmentPathStr;
                } else {
                    $CompleteReturnArray[$Attribute] = is_null($EntityObj->$Attribute) ? 'N/A' : $EntityObj->$Attribute;
                }
            }
            foreach ($this->IncludedRelationshipArray as $Relationship => $DisplayAttribute) {
//                $Relationship/*Role* => $DisplayAttribute/*Userrole*
                $RelationshipReturnStr = "N/A";
                $RelationshipNodeStr = $this->DataModelObj->getEntityRelationshipPathAsNode($EntityObj, $this->EntityNameStr, $Relationship, []);
                if (!is_null($RelationshipNodeStr)) {
                    if (!is_null($RelationshipNodeStr->$DisplayAttribute)) {
                        if (in_array($this->DataModelObj->getEntityAttributeType($Relationship, $DisplayAttribute), ["DATE", "DATETIME"])) {
                            $RelationshipReturnStr = $RelationshipNodeStr->$DisplayAttribute->format(DATE_TIME_FORMAT_PHP_STR . " H:i:s");
                        } else {
                            $RelationshipReturnStr = is_null($RelationshipNodeStr->$DisplayAttribute) ? 'N/A' : $RelationshipNodeStr->$DisplayAttribute;
                        }
                    }
                }
                $CompleteReturnArray[$Relationship] = $RelationshipReturnStr;
            }

            $StatusCountArray = [];
            $StatusKeys = ["New", "In Progress", "Due Soon", "Urgent", "Complete",  "Overdue"];
            foreach ($StatusKeys as $Key) {
                $StatusCountInt = Ticket::QueryCount(
                    dxQ::AndCondition(
                        dxQ::Equals(
                            dxQN::Ticket()->AccountObject->Id,
                            $EntityObj->Id
                        ),
                        dxQ::Equal(
                            dxQN::Ticket()->TicketStatus,
                            $Key
                        )
                    )
                );
                $StatusCountArray[$Key] = $StatusCountInt;
            }
            $CompleteReturnArray["StatusCounts"] = $StatusCountArray;
            array_push($EntityReturnArray, $CompleteReturnArray);
        }

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("Message", "");
        $this->setReturnValue("Page", $EntityReturnArray);
        $this->setReturnValue("TotalCount", $EntityNodeNameStr::QueryCount($QueryCondition));
        $this->presentOutput();
    }
}

$ComponentObj = new AccountController("account_summary_list");
?>