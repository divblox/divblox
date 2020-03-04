<?php
/**
 * The abstract BackgroundProcessUpdateGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the BackgroundProcessUpdate subclass which
 * extends this BackgroundProcessUpdateGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the BackgroundProcessUpdate class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property dxDateTime $UpdateDateTime the value for dttUpdateDateTime 
 * @property string $UpdateMessage the value for strUpdateMessage 
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $BackgroundProcess the value for intBackgroundProcess 
 * @property string $SearchMetaInfo the value for strSearchMetaInfo 
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property BackgroundProcess $BackgroundProcessObject the value for the BackgroundProcess object referenced by intBackgroundProcess 
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class BackgroundProcessUpdateGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column BackgroundProcessUpdate.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column BackgroundProcessUpdate.UpdateDateTime
     * @var dxDateTime dttUpdateDateTime
     */
    protected $dttUpdateDateTime;
    const UpdateDateTimeDefault = null;


    /**
     * Protected member variable that maps to the database column BackgroundProcessUpdate.UpdateMessage
     * @var string strUpdateMessage
     */
    protected $strUpdateMessage;
    const UpdateMessageDefault = null;


    /**
     * Protected member variable that maps to the database column BackgroundProcessUpdate.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column BackgroundProcessUpdate.BackgroundProcess
     * @var integer intBackgroundProcess
     */
    protected $intBackgroundProcess;
    const BackgroundProcessDefault = null;


    /**
     * Protected member variable that maps to the database column BackgroundProcessUpdate.SearchMetaInfo
     * @var string strSearchMetaInfo
     */
    protected $strSearchMetaInfo;
    const SearchMetaInfoDefault = null;


    /**
     * Protected member variable that maps to the database column BackgroundProcessUpdate.ObjectOwner
     * @var integer intObjectOwner
     */
    protected $intObjectOwner;
    const ObjectOwnerDefault = null;


    /**
     * Protected array of virtual attributes for this object (e.g. extra/other calculated and/or non-object bound
     * columns from the run-time database query result for this object).  Used by InstantiateDbRow and
     * GetVirtualAttribute.
     * @var string[] $__strVirtualAttributeArray
     */
    protected $__strVirtualAttributeArray = array();

    /**
     * Protected internal member variable that specifies whether or not this object is Restored from the database.
     * Used by Save() to determine if Save() should perform a db UPDATE or INSERT.
     * @var bool __blnRestored;
     */
    protected $__blnRestored;

    ///////////////////////////////
    // PROTECTED MEMBER OBJECTS
    ///////////////////////////////

    /**
     * Protected member variable that contains the object pointed by the reference
     * in the database column BackgroundProcessUpdate.BackgroundProcess.
     *
     * NOTE: Always use the BackgroundProcessObject property getter to correctly retrieve this BackgroundProcess object.
     * (Because this class implements late binding, this variable reference MAY be null.)
     * @var BackgroundProcess objBackgroundProcessObject
     */
    protected $objBackgroundProcessObject;


    /**
     * Initialize each property with default values from database definition
     */
    public function Initialize() {
        $this->intId = BackgroundProcessUpdate::IdDefault;
        $this->dttUpdateDateTime = (BackgroundProcessUpdate::UpdateDateTimeDefault === null)?null:new dxDateTime(BackgroundProcessUpdate::UpdateDateTimeDefault);
        $this->strUpdateMessage = BackgroundProcessUpdate::UpdateMessageDefault;
        $this->strLastUpdated = BackgroundProcessUpdate::LastUpdatedDefault;
        $this->intBackgroundProcess = BackgroundProcessUpdate::BackgroundProcessDefault;
        $this->strSearchMetaInfo = BackgroundProcessUpdate::SearchMetaInfoDefault;
        $this->intObjectOwner = BackgroundProcessUpdate::ObjectOwnerDefault;
    }

    ///////////////////////////////
    // CLASS-WIDE LOAD AND COUNT METHODS
    ///////////////////////////////

    /**
     * Static method to retrieve the Database object that owns this class.
     * @return dxDatabaseBase reference to the Database object that can query this class
     */
    public static function GetDatabase() {
        return ProjectFunctions::$Database[1];
    }

    /**
     * Load a BackgroundProcessUpdate from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return BackgroundProcessUpdate
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'BackgroundProcessUpdate', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = BackgroundProcessUpdate::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::BackgroundProcessUpdate()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all BackgroundProcessUpdates
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return BackgroundProcessUpdate[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call BackgroundProcessUpdate::QueryArray to perform the LoadAll query
        try {
            return BackgroundProcessUpdate::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all BackgroundProcessUpdates
     * @return int
     */
    public static function CountAll() {
        // Call BackgroundProcessUpdate::QueryCount to perform the CountAll query
        return BackgroundProcessUpdate::QueryCount(dxQuery::All());
    }

    ///////////////////////////////
    // DIVBLOX QUERY-RELATED METHODS
    ///////////////////////////////

    /**
     * Internally called method to assist with calling divblox Query for this class
     * on load methods.
     * @param dxQueryBuilder &$objQueryBuilder the QueryBuilder object that will be created
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause object or array of dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with (sending in null will skip the PrepareStatement step)
     * @param boolean $blnCountOnly only select a rowcount
     * @return string the query statement
     */
    protected static function BuildQueryStatement(&$objQueryBuilder, dxQueryCondition $objConditions, $objOptionalClauses, $mixParameterArray, $blnCountOnly) {
        // Get the Database Object for this Class
        $objDatabase = BackgroundProcessUpdate::GetDatabase();

        // Create/Build out the QueryBuilder object with BackgroundProcessUpdate-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'BackgroundProcessUpdate');

        $blnAddAllFieldsToSelect = true;
        if ($objDatabase->OnlyFullGroupBy) {
            // see if we have any group by or aggregation clauses, if yes, don't add the fields to select clause
            if ($objOptionalClauses instanceof dxQueryClause) {
                if ($objOptionalClauses instanceof dxQueryAggregationClause || $objOptionalClauses instanceof dxQueryGroupBy) {
                    $blnAddAllFieldsToSelect = false;
                }
            } else if (is_array($objOptionalClauses)) {
                foreach ($objOptionalClauses as $objClause) {
                    if ($objClause instanceof dxQueryAggregationClause || $objClause instanceof dxQueryGroupBy) {
                        $blnAddAllFieldsToSelect = false;
                        break;
                    }
                }
            }
        }
        if ($blnAddAllFieldsToSelect) {
            BackgroundProcessUpdate::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('BackgroundProcessUpdate');

        // Set "CountOnly" option (if applicable)
        if ($blnCountOnly)
            $objQueryBuilder->SetCountOnlyFlag();

        // Apply Any Conditions
        if ($objConditions)
            try {
                $objConditions->UpdateQueryBuilder($objQueryBuilder);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                throw $objExc;
            }

        // Iterate through all the Optional Clauses (if any) and perform accordingly
        if ($objOptionalClauses) {
            if ($objOptionalClauses instanceof dxQueryClause)
                $objOptionalClauses->UpdateQueryBuilder($objQueryBuilder);
            else if (is_array($objOptionalClauses))
                foreach ($objOptionalClauses as $objClause)
                    $objClause->UpdateQueryBuilder($objQueryBuilder);
            else
                throw new dxCallerException('Optional Clauses must be a dxQueryClause object or an array of dxQueryClause objects');
        }

        // Get the SQL Statement
        $strQuery = $objQueryBuilder->GetStatement();

        // Prepare the Statement with the Query Parameters (if applicable)
        if ($mixParameterArray) {
            if (is_array($mixParameterArray)) {
                if (ProjectFunctions::getDataSetSize($mixParameterArray))
                    $strQuery = $objDatabase->PrepareStatement($strQuery, $mixParameterArray);

                // Ensure that there are no other Unresolved Named Parameters
                if (strpos($strQuery, chr(dxQueryNamedValue::DelimiterCode) . '{') !== false)
                    throw new dxCallerException('Unresolved named parameters in the query');
            } else
                throw new dxCallerException('Parameter Array must be an array of name-value parameter pairs');
        }

        // Return the Objects
        return $strQuery;
    }

    /**
     * Static divblox Query method to query for a single BackgroundProcessUpdate object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return BackgroundProcessUpdate the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = BackgroundProcessUpdate::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new BackgroundProcessUpdate object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = BackgroundProcessUpdate::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
            if (ProjectFunctions::getDataSetSize($objToReturn)) {
                // Since we only want the object to return, lets return the object and not the array.
                return $objToReturn[0];
            } else {
                return null;
            }
        } else {
            // No expands just return the first row
            $objDbRow = $objDbResult->GetNextRow();
            if(null === $objDbRow)
                return null;
            return BackgroundProcessUpdate::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of BackgroundProcessUpdate objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return BackgroundProcessUpdate[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = BackgroundProcessUpdate::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return BackgroundProcessUpdate::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
    }

    /**
     * Static divblox query method to issue a query and get a cursor to progressively fetch its results.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return dxDatabaseResultBase the cursor resource instance
     */
    public static function QueryCursor(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the query statement
        try {
            $strQuery = BackgroundProcessUpdate::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the query
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Return the results cursor
        $objDbResult->QueryBuilder = $objQueryBuilder;
        return $objDbResult;
    }

    /**
     * Static divblox Query method to query for a count of BackgroundProcessUpdate objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = BackgroundProcessUpdate::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and return the row_count
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Figure out if the query is using GroupBy
        $blnGrouped = false;

        if ($objOptionalClauses) {
            if ($objOptionalClauses instanceof dxQueryClause) {
                if ($objOptionalClauses instanceof dxQueryGroupBy) {
                    $blnGrouped = true;
                }
            } else if (is_array($objOptionalClauses)) {
                foreach ($objOptionalClauses as $objClause) {
                    if ($objClause instanceof dxQueryGroupBy) {
                        $blnGrouped = true;
                        break;
                    }
                }
            } else {
                throw new dxCallerException('Optional Clauses must be a dxQueryClause object or an array of dxQueryClause objects');
            }
        }

        if ($blnGrouped)
            // Groups in this query - return the count of Groups (which is the count of all rows)
            return $objDbResult->CountRows();
        else {
            // No Groups - return the sql-calculated count(*) value
            $strDbRow = $objDbResult->FetchRow();
            return dxType::Cast($strDbRow[0], dxType::Integer);
        }
    }

    public static function QueryArrayCached(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null, $blnForceUpdate = false) {
        // Get the Database Object for this Class
        $objDatabase = BackgroundProcessUpdate::GetDatabase();

        $strQuery = BackgroundProcessUpdate::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/backgroundprocessupdate', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = BackgroundProcessUpdate::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this BackgroundProcessUpdate
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'BackgroundProcessUpdate';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'UpdateDateTime', $strAliasPrefix . 'UpdateDateTime');
            $objBuilder->AddSelectItem($strTableName, 'UpdateMessage', $strAliasPrefix . 'UpdateMessage');
            $objBuilder->AddSelectItem($strTableName, 'LastUpdated', $strAliasPrefix . 'LastUpdated');
            $objBuilder->AddSelectItem($strTableName, 'BackgroundProcess', $strAliasPrefix . 'BackgroundProcess');
            $objBuilder->AddSelectItem($strTableName, 'SearchMetaInfo', $strAliasPrefix . 'SearchMetaInfo');
            $objBuilder->AddSelectItem($strTableName, 'ObjectOwner', $strAliasPrefix . 'ObjectOwner');
        }
    }
    ///////////////////////////////
    // INSTANTIATION-RELATED METHODS
    ///////////////////////////////

    /**
     * Do a possible array expansion on the given node. If the node is an ExpandAsArray node,
     * it will add to the corresponding array in the object. Otherwise, it will follow the node
     * so that any leaf expansions can be handled.
     *
     * @param DatabaseRowBase $objDbRow
     * @param dxQueryBaseNode $objChildNode
     * @param dxBaseClass $objPreviousItem
     * @param string[] $strColumnAliasArray
     */

    public static function ExpandArray ($objDbRow, $strAliasPrefix, $objNode, $objPreviousItemArray, $strColumnAliasArray) {
        if (!$objNode->ChildNodeArray) {
            return false;
        }

        $strAlias = $strAliasPrefix . 'Id';
        $strColumnAlias = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $blnExpanded = false;

        foreach ($objPreviousItemArray as $objPreviousItem) {
            if ($objPreviousItem->intId != $objDbRow->GetColumn($strColumnAlias, 'Integer')) {
                continue;
            }

            foreach ($objNode->ChildNodeArray as $objChildNode) {
                $strPropName = $objChildNode->_PropertyName;
                $strClassName = $objChildNode->_ClassName;
                $blnExpanded = false;
                $strLongAlias = $objChildNode->ExtendedAlias();

                if ($objChildNode->ExpandAsArray) {
                    $strVarName = '_obj' . $strPropName . 'Array';
                    if (null === $objPreviousItem->$strVarName) {
                        $objPreviousItem->$strVarName = array();
                    }
                    if ($intPreviousChildItemCount = ProjectFunctions::getDataSetSize($objPreviousItem->$strVarName)) {
                        $objPreviousChildItems = $objPreviousItem->$strVarName;
                        if ($objChildNode->_Type == "association") {
                            $objChildNode = $objChildNode->FirstChild();
                        }
                        $nextAlias = $objChildNode->ExtendedAlias() . '__';

                        $objChildItem = call_user_func(array ($strClassName, 'InstantiateDbRow'), $objDbRow, $nextAlias, $objChildNode, $objPreviousChildItems, $strColumnAliasArray);
                        if ($objChildItem) {
                            $objPreviousItem->{$strVarName}[] = $objChildItem;
                            $blnExpanded = true;
                        } elseif ($objChildItem === false) {
                            $blnExpanded = true;
                        }
                    }
                } else {

                    // Follow single node if keys match
                    $nodeType = $objChildNode->_Type;
                    if ($nodeType == 'reverse_reference' || $nodeType == 'association') {
                        $strVarName = '_obj' . $strPropName;
                    } else {
                        $strVarName = 'obj' . $strPropName;
                    }

                    if (null === $objPreviousItem->$strVarName) {
                        return false;
                    }

                    $objPreviousChildItems = array($objPreviousItem->$strVarName);
                    $blnResult = call_user_func(array ($strClassName, 'ExpandArray'), $objDbRow, $strLongAlias . '__', $objChildNode, $objPreviousChildItems, $strColumnAliasArray);

                    if ($blnResult) {
                        $blnExpanded = true;
                    }
                }
            }
        }
        return $blnExpanded;
    }

    /**
     * Instantiate a BackgroundProcessUpdate from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this BackgroundProcessUpdate::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a BackgroundProcessUpdate, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
    */
    public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $objExpandAsArrayNode = null, $objPreviousItemArray = null, $strColumnAliasArray = array()) {
        // If blank row, return null
        if (!$objDbRow) {
            return null;
        }

        if (empty ($strAliasPrefix) && $objPreviousItemArray) {
            $strColumnAlias = !empty($strColumnAliasArray['Id']) ? $strColumnAliasArray['Id'] : 'Id';
            $key = $objDbRow->GetColumn($strColumnAlias, 'Integer');
            $objPreviousItemArray = (!empty ($objPreviousItemArray[$key]) ? $objPreviousItemArray[$key] : null);
        }

        // See if we're doing an array expansion on the previous item
        if ($objExpandAsArrayNode &&
                is_array($objPreviousItemArray) &&
                ProjectFunctions::getDataSetSize($objPreviousItemArray)) {

            if (BackgroundProcessUpdate::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray)) {
                return false; // db row was used but no new object was created
            }
        }

        // Create a new instance of the BackgroundProcessUpdate object
        $objToReturn = new BackgroundProcessUpdate();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'UpdateDateTime';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttUpdateDateTime = $objDbRow->GetColumn($strAliasName, 'DateTime');
        $strAlias = $strAliasPrefix . 'UpdateMessage';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strUpdateMessage = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'LastUpdated';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strLastUpdated = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'BackgroundProcess';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intBackgroundProcess = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'SearchMetaInfo';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strSearchMetaInfo = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'ObjectOwner';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intObjectOwner = $objDbRow->GetColumn($strAliasName, 'Integer');

        if (isset($objPreviousItemArray) && is_array($objPreviousItemArray)) {
            foreach ($objPreviousItemArray as $objPreviousItem) {
                if ($objToReturn->Id != $objPreviousItem->Id) {
                    continue;
                }
                // this is a duplicate leaf in a complex join
                return null; // indicates no object created and the db row has not been used
            }
        }

        // Instantiate Virtual Attributes
        $strVirtualPrefix = $strAliasPrefix . '__';
        $strVirtualPrefixLength = strlen($strVirtualPrefix);
        foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
            if (strncmp($strColumnName, $strVirtualPrefix, $strVirtualPrefixLength) == 0)
                $objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
        }


        // Prepare to Check for Early/Virtual Binding

        $objExpansionAliasArray = array();
        if ($objExpandAsArrayNode) {
            $objExpansionAliasArray = $objExpandAsArrayNode->ChildNodeArray;
        }

        if (!$strAliasPrefix)
            $strAliasPrefix = 'BackgroundProcessUpdate__';

        // Check for BackgroundProcessObject Early Binding
        $strAlias = $strAliasPrefix . 'BackgroundProcess__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            $objExpansionNode = (empty($objExpansionAliasArray['BackgroundProcess']) ? null : $objExpansionAliasArray['BackgroundProcess']);
            $objToReturn->objBackgroundProcessObject = BackgroundProcess::InstantiateDbRow($objDbRow, $strAliasPrefix . 'BackgroundProcess__', $objExpansionNode, null, $strColumnAliasArray);
        }



        return $objToReturn;
    }

    /**
     * Instantiate an array of BackgroundProcessUpdates from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return BackgroundProcessUpdate[]
     */
    public static function InstantiateDbResult(dxDatabaseResultBase $objDbResult, $objExpandAsArrayNode = null, $strColumnAliasArray = null) {
        $objToReturn = array();

        if (!$strColumnAliasArray)
            $strColumnAliasArray = array();

        // If blank resultset, then return empty array
        if (!$objDbResult)
            return $objToReturn;

        // Load up the return array with each row
        if ($objExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = BackgroundProcessUpdate::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = BackgroundProcessUpdate::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single BackgroundProcessUpdate object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return BackgroundProcessUpdate next row resulting from the query
     */
    public static function InstantiateCursor(dxDatabaseResultBase $objDbResult) {
        // If blank resultset, then return empty result
        if (!$objDbResult) return null;

        // If empty resultset, then return empty result
        $objDbRow = $objDbResult->GetNextRow();
        if (!$objDbRow) return null;

        // We need the Column Aliases
        $strColumnAliasArray = $objDbResult->QueryBuilder->ColumnAliasArray;
        if (!$strColumnAliasArray) $strColumnAliasArray = array();

        // Pull Expansions
        $objExpandAsArrayNode = $objDbResult->QueryBuilder->ExpandAsArrayNode;
        if (!empty ($objExpandAsArrayNode)) {
            throw new dxCallerException ("Cannot use InstantiateCursor with ExpandAsArray");
        }

        // Load up the return result with a row and return it
        return BackgroundProcessUpdate::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single BackgroundProcessUpdate object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return BackgroundProcessUpdate
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return BackgroundProcessUpdate::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::BackgroundProcessUpdate()->Id, $intId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load an array of BackgroundProcessUpdate objects,
     * by BackgroundProcess Index(es)
     * @param integer $intBackgroundProcess
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return BackgroundProcessUpdate[]
    */
    public static function LoadArrayByBackgroundProcess($intBackgroundProcess, $objOptionalClauses = null) {
        // Call BackgroundProcessUpdate::QueryArray to perform the LoadArrayByBackgroundProcess query
        try {
            return BackgroundProcessUpdate::QueryArray(
                dxQuery::Equal(dxQueryN::BackgroundProcessUpdate()->BackgroundProcess, $intBackgroundProcess),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count BackgroundProcessUpdates
     * by BackgroundProcess Index(es)
     * @param integer $intBackgroundProcess
     * @return int
    */
    public static function CountByBackgroundProcess($intBackgroundProcess) {
        // Call BackgroundProcessUpdate::QueryCount to perform the CountByBackgroundProcess query
        return BackgroundProcessUpdate::QueryCount(
            dxQuery::Equal(dxQueryN::BackgroundProcessUpdate()->BackgroundProcess, $intBackgroundProcess)
        );
    }
    ////////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Array via Many to Many)
    ////////////////////////////////////////////////////


    //////////////////////////
    // SAVE, DELETE AND RELOAD
    //////////////////////////

    /**
    * Save this BackgroundProcessUpdate
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"BackgroundProcessUpdate",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = BackgroundProcessUpdate::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = BackgroundProcessUpdate::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'BackgroundProcessUpdate';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => $this->dttUpdateDateTime));
            $ChangedArray = array_merge($ChangedArray,array("UpdateMessage" => $this->strUpdateMessage));
            $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
            $ChangedArray = array_merge($ChangedArray,array("BackgroundProcess" => $this->intBackgroundProcess));
            $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
            $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
            $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        } else {
            $newAuditLogEntry->ModificationType = 'Update';
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Id)) {
                $ExistingValueStr = $ExistingObj->Id;
            }
            if ($ExistingObj->Id != $this->intId) {
                $ChangedArray = array_merge($ChangedArray,array("Id" => array("Before" => $ExistingValueStr,"After" => $this->intId)));
                //$ChangedArray = array_merge($ChangedArray,array("Id" => "From: ".$ExistingValueStr." to: ".$this->intId));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->UpdateDateTime)) {
                $ExistingValueStr = $ExistingObj->UpdateDateTime;
            }
            if ($ExistingObj->UpdateDateTime != $this->dttUpdateDateTime) {
                $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => array("Before" => $ExistingValueStr,"After" => $this->dttUpdateDateTime)));
                //$ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => "From: ".$ExistingValueStr." to: ".$this->dttUpdateDateTime));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->UpdateMessage)) {
                $ExistingValueStr = $ExistingObj->UpdateMessage;
            }
            if ($ExistingObj->UpdateMessage != $this->strUpdateMessage) {
                $ChangedArray = array_merge($ChangedArray,array("UpdateMessage" => array("Before" => $ExistingValueStr,"After" => $this->strUpdateMessage)));
                //$ChangedArray = array_merge($ChangedArray,array("UpdateMessage" => "From: ".$ExistingValueStr." to: ".$this->strUpdateMessage));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->LastUpdated)) {
                $ExistingValueStr = $ExistingObj->LastUpdated;
            }
            if ($ExistingObj->LastUpdated != $this->strLastUpdated) {
                $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => array("Before" => $ExistingValueStr,"After" => $this->strLastUpdated)));
                //$ChangedArray = array_merge($ChangedArray,array("LastUpdated" => "From: ".$ExistingValueStr." to: ".$this->strLastUpdated));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->BackgroundProcess)) {
                $ExistingValueStr = $ExistingObj->BackgroundProcess;
            }
            if ($ExistingObj->BackgroundProcess != $this->intBackgroundProcess) {
                $ChangedArray = array_merge($ChangedArray,array("BackgroundProcess" => array("Before" => $ExistingValueStr,"After" => $this->intBackgroundProcess)));
                //$ChangedArray = array_merge($ChangedArray,array("BackgroundProcess" => "From: ".$ExistingValueStr." to: ".$this->intBackgroundProcess));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->SearchMetaInfo)) {
                $ExistingValueStr = $ExistingObj->SearchMetaInfo;
            }
            if ($ExistingObj->SearchMetaInfo != $this->strSearchMetaInfo) {
                $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => array("Before" => $ExistingValueStr,"After" => $this->strSearchMetaInfo)));
                //$ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => "From: ".$ExistingValueStr." to: ".$this->strSearchMetaInfo));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ObjectOwner)) {
                $ExistingValueStr = $ExistingObj->ObjectOwner;
            }
            if ($ExistingObj->ObjectOwner != $this->intObjectOwner) {
                $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => array("Before" => $ExistingValueStr,"After" => $this->intObjectOwner)));
                //$ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => "From: ".$ExistingValueStr." to: ".$this->intObjectOwner));
            }
            $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        }
        try {
            if ((!$this->__blnRestored) || ($blnForceInsert)) {
                if (!in_array(AccessOperation::CREATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'BackgroundProcessUpdate'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `BackgroundProcessUpdate` (
							`UpdateDateTime`,
							`UpdateMessage`,
							`BackgroundProcess`,
							`SearchMetaInfo`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							' . $objDatabase->SqlVariable($this->strUpdateMessage) . ',
							' . $objDatabase->SqlVariable($this->intBackgroundProcess) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('BackgroundProcessUpdate', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'BackgroundProcessUpdate'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `BackgroundProcessUpdate` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('BackgroundProcessUpdate');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `BackgroundProcessUpdate` SET
							`UpdateDateTime` = ' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							`UpdateMessage` = ' . $objDatabase->SqlVariable($this->strUpdateMessage) . ',
							`BackgroundProcess` = ' . $objDatabase->SqlVariable($this->intBackgroundProcess) . ',
							`SearchMetaInfo` = ' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							`ObjectOwner` = ' . $objDatabase->SqlVariable($this->intObjectOwner) . '
            WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');
            }

        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
        try {
            $newAuditLogEntry->ObjectId = $this->intId;
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while saving BackgroundProcessUpdate. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `BackgroundProcessUpdate` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this BackgroundProcessUpdate
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this BackgroundProcessUpdate with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"BackgroundProcessUpdate",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'BackgroundProcessUpdate'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = BackgroundProcessUpdate::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'BackgroundProcessUpdate';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => $this->dttUpdateDateTime));
        $ChangedArray = array_merge($ChangedArray,array("UpdateMessage" => $this->strUpdateMessage));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("BackgroundProcess" => $this->intBackgroundProcess));
        $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting BackgroundProcessUpdate. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `BackgroundProcessUpdate`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this BackgroundProcessUpdate ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'BackgroundProcessUpdate', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all BackgroundProcessUpdates
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = BackgroundProcessUpdate::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `BackgroundProcessUpdate`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate BackgroundProcessUpdate table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = BackgroundProcessUpdate::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `BackgroundProcessUpdate`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this BackgroundProcessUpdate from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved BackgroundProcessUpdate object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = BackgroundProcessUpdate::Load($this->intId);

        // Update $this's local variables to match
        $this->dttUpdateDateTime = $objReloaded->dttUpdateDateTime;
        $this->strUpdateMessage = $objReloaded->strUpdateMessage;
        $this->strLastUpdated = $objReloaded->strLastUpdated;
        $this->BackgroundProcess = $objReloaded->BackgroundProcess;
        $this->strSearchMetaInfo = $objReloaded->strSearchMetaInfo;
        $this->intObjectOwner = $objReloaded->intObjectOwner;
    }
    ////////////////////
    // PUBLIC OVERRIDERS
    ////////////////////

        /**
     * Override method to perform a property "Get"
     * This will get the value of $strName
     *
     * @param string $strName Name of the property to get
     * @return mixed
     */
    public function __get($strName) {
        switch ($strName) {
            ///////////////////
            // Member Variables
            ///////////////////
            case 'Id':
                /**
                 * Gets the value for intId (Read-Only PK)
                 * @return integer
                 */
                return $this->intId;

            case 'UpdateDateTime':
                /**
                 * Gets the value for dttUpdateDateTime 
                 * @return dxDateTime
                 */
                return $this->dttUpdateDateTime;

            case 'UpdateMessage':
                /**
                 * Gets the value for strUpdateMessage 
                 * @return string
                 */
                return $this->strUpdateMessage;

            case 'LastUpdated':
                /**
                 * Gets the value for strLastUpdated (Read-Only Timestamp)
                 * @return string
                 */
                return $this->strLastUpdated;

            case 'BackgroundProcess':
                /**
                 * Gets the value for intBackgroundProcess 
                 * @return integer
                 */
                return $this->intBackgroundProcess;

            case 'SearchMetaInfo':
                /**
                 * Gets the value for strSearchMetaInfo 
                 * @return string
                 */
                return $this->strSearchMetaInfo;

            case 'ObjectOwner':
                /**
                 * Gets the value for intObjectOwner 
                 * @return integer
                 */
                return $this->intObjectOwner;


            ///////////////////
            // Member Objects
            ///////////////////
            case 'BackgroundProcessObject':
                /**
                 * Gets the value for the BackgroundProcess object referenced by intBackgroundProcess 
                 * @return BackgroundProcess
                 */
                try {
                    if ((!$this->objBackgroundProcessObject) && (!is_null($this->intBackgroundProcess)))
                        $this->objBackgroundProcessObject = BackgroundProcess::Load($this->intBackgroundProcess);
                    return $this->objBackgroundProcessObject;
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }


            ////////////////////////////
            // Virtual Object References (Many to Many and Reverse References)
            // (If restored via a "Many-to" expansion)
            ////////////////////////////


            case '__Restored':
                return $this->__blnRestored;

            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
        /**
     * Override method to perform a property "Set"
     * This will set the property $strName to be $mixValue
     *
     * @param string $strName Name of the property to set
     * @param string $mixValue New value of the property
     * @return mixed
     */
    public function __set($strName, $mixValue) {
        switch ($strName) {
            ///////////////////
            // Member Variables
            ///////////////////
            case 'UpdateDateTime':
                /**
                 * Sets the value for dttUpdateDateTime 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttUpdateDateTime = dxType::Cast($mixValue, dxType::DateTime));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'UpdateMessage':
                /**
                 * Sets the value for strUpdateMessage 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strUpdateMessage = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'BackgroundProcess':
                /**
                 * Sets the value for intBackgroundProcess 
                 * @param integer $mixValue
                 * @return integer
                 */
                try {
                    $this->objBackgroundProcessObject = null;
                    return ($this->intBackgroundProcess = dxType::Cast($mixValue, dxType::Integer));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'SearchMetaInfo':
                /**
                 * Sets the value for strSearchMetaInfo 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strSearchMetaInfo = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ObjectOwner':
                /**
                 * Sets the value for intObjectOwner 
                 * @param integer $mixValue
                 * @return integer
                 */
                try {
                    return ($this->intObjectOwner = dxType::Cast($mixValue, dxType::Integer));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }


            ///////////////////
            // Member Objects
            ///////////////////
            case 'BackgroundProcessObject':
                /**
                 * Sets the value for the BackgroundProcess object referenced by intBackgroundProcess 
                 * @param BackgroundProcess $mixValue
                 * @return BackgroundProcess
                 */
                if (is_null($mixValue)) {
                    $this->intBackgroundProcess = null;
                    $this->objBackgroundProcessObject = null;
                    return null;
                } else {
                    // Make sure $mixValue actually is a BackgroundProcess object
                    try {
                        $mixValue = dxType::Cast($mixValue, 'BackgroundProcess');
                    } catch (dxInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                    // Make sure $mixValue is a SAVED BackgroundProcess object
                    if (is_null($mixValue->Id))
                        throw new dxCallerException('Unable to set an unsaved BackgroundProcessObject for this BackgroundProcessUpdate');

                    // Update Local Member Variables
                    $this->objBackgroundProcessObject = $mixValue;
                    $this->intBackgroundProcess = $mixValue->Id;

                    // Return $mixValue
                    return $mixValue;
                }
                break;

            default:
                try {
                    return parent::__set($strName, $mixValue);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
    /**
     * Lookup a VirtualAttribute value (if applicable).  Returns NULL if none found.
     * @param string $strName
     * @return string
     */
    public function GetVirtualAttribute($strName) {
        if (array_key_exists($strName, $this->__strVirtualAttributeArray))
            return $this->__strVirtualAttributeArray[$strName];
        return null;
    }

    ///////////////////////////////
    // ASSOCIATED OBJECTS' METHODS
    ///////////////////////////////



    
///////////////////////////////
    // METHODS TO EXTRACT INFO ABOUT THE CLASS
    ///////////////////////////////

    /**
     * Static method to retrieve the Database object that owns this class.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetTableName() {
        return "BackgroundProcessUpdate";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[BackgroundProcessUpdate::GetDatabaseIndex()]->Database;
    }

    /**
     * Static method to retrieve the Database index in the configuration.inc.php file.
     * This can be useful when there are two databases of the same name which create
     * confusion for the developer. There are no internal uses of this function but are
     * here to help retrieve info if need be!
     * @return int position or index of the database in the config file.
     */
    public static function GetDatabaseIndex() {
        return 1;
    }

    ////////////////////////////////////////
    // METHODS for SOAP-BASED WEB SERVICES
    ////////////////////////////////////////

    public static function GetSoapComplexTypeXml() {
        $strToReturn = '<complexType name="BackgroundProcessUpdate"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="UpdateDateTime" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="UpdateMessage" type="xsd:string"/>';
        $strToReturn .= '<element name="LastUpdated" type="xsd:string"/>';
        $strToReturn .= '<element name="BackgroundProcessObject" type="xsd1:BackgroundProcess"/>';
        $strToReturn .= '<element name="SearchMetaInfo" type="xsd:string"/>';
        $strToReturn .= '<element name="ObjectOwner" type="xsd:int"/>';
        $strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
        $strToReturn .= '</sequence></complexType>';
        return $strToReturn;
    }

    public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
        if (!array_key_exists('BackgroundProcessUpdate', $strComplexTypeArray)) {
            $strComplexTypeArray['BackgroundProcessUpdate'] = BackgroundProcessUpdate::GetSoapComplexTypeXml();
            BackgroundProcess::AlterSoapComplexTypeArray($strComplexTypeArray);
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, BackgroundProcessUpdate::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new BackgroundProcessUpdate();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'UpdateDateTime'))
            $objToReturn->dttUpdateDateTime = new dxDateTime($objSoapObject->UpdateDateTime);
        if (property_exists($objSoapObject, 'UpdateMessage'))
            $objToReturn->strUpdateMessage = $objSoapObject->UpdateMessage;
        if (property_exists($objSoapObject, 'LastUpdated'))
            $objToReturn->strLastUpdated = $objSoapObject->LastUpdated;
        if ((property_exists($objSoapObject, 'BackgroundProcessObject')) &&
            ($objSoapObject->BackgroundProcessObject))
            $objToReturn->BackgroundProcessObject = BackgroundProcess::GetObjectFromSoapObject($objSoapObject->BackgroundProcessObject);
        if (property_exists($objSoapObject, 'SearchMetaInfo'))
            $objToReturn->strSearchMetaInfo = $objSoapObject->SearchMetaInfo;
        if (property_exists($objSoapObject, 'ObjectOwner'))
            $objToReturn->intObjectOwner = $objSoapObject->ObjectOwner;
        if (property_exists($objSoapObject, '__blnRestored'))
            $objToReturn->__blnRestored = $objSoapObject->__blnRestored;
        return $objToReturn;
    }

    public static function GetSoapArrayFromArray($objArray) {
        if (!$objArray)
            return null;

        $objArrayToReturn = array();

        foreach ($objArray as $objObject)
            array_push($objArrayToReturn, BackgroundProcessUpdate::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttUpdateDateTime)
            $objObject->dttUpdateDateTime = $objObject->dttUpdateDateTime->qFormat(dxDateTime::FormatSoap);
        if ($objObject->objBackgroundProcessObject)
            $objObject->objBackgroundProcessObject = BackgroundProcess::GetSoapObjectFromObject($objObject->objBackgroundProcessObject, false);
        else if (!$blnBindRelatedObjects)
            $objObject->intBackgroundProcess = null;
        return $objObject;
    }


    ////////////////////////////////////////
    // METHODS for JSON Object Translation
    ////////////////////////////////////////

    // this function is required for objects that implement the
    // IteratorAggregate interface
    public function getIterator() {
        ///////////////////
        // Member Variables
        ///////////////////
        $iArray['Id'] = $this->intId;
        $iArray['UpdateDateTime'] = $this->dttUpdateDateTime;
        $iArray['UpdateMessage'] = $this->strUpdateMessage;
        $iArray['LastUpdated'] = $this->strLastUpdated;
        $iArray['BackgroundProcess'] = $this->intBackgroundProcess;
        $iArray['SearchMetaInfo'] = $this->strSearchMetaInfo;
        $iArray['ObjectOwner'] = $this->intObjectOwner;
        return new ArrayIterator($iArray);
    }

    // this function returns a Json formatted string using the
    // IteratorAggregate interface
    public function getJson() {
        return json_encode($this->getIterator());
    }

    /**
     * Default "toJsObject" handler
     * Specifies how the object should be displayed in JQuery UI lists and menus. Note that these lists use
     * value and label differently.
     *
     * value 	= The short form of what to display in the list and selection.
     * label 	= [optional] If defined, is what is displayed in the menu
     * id 		= Primary key of object.
     *
     * @return an array that specifies how to display the object
     */
    public function toJsObject () {
        return JavaScriptHelper::toJsObject(array('value' => $this->__toString(), 'id' =>  $this->intId ));
    }


}

/////////////////////////////////////
	// ADDITIONAL CLASSES for DIVBLOX QUERY
	/////////////////////////////////////

    /**
     * @uses dxQueryNode
     *
     * @property-read dxQueryNode $Id
     * @property-read dxQueryNode $UpdateDateTime
     * @property-read dxQueryNode $UpdateMessage
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $BackgroundProcess
     * @property-read dxQueryNodeBackgroundProcess $BackgroundProcessObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodeBackgroundProcessUpdate extends dxQueryNode {
		protected $strTableName = 'BackgroundProcessUpdate';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'BackgroundProcessUpdate';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'UpdateDateTime':
					return new dxQueryNode('UpdateDateTime', 'UpdateDateTime', 'DateTime', $this);
				case 'UpdateMessage':
					return new dxQueryNode('UpdateMessage', 'UpdateMessage', 'Blob', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'VarChar', $this);
				case 'BackgroundProcess':
					return new dxQueryNode('BackgroundProcess', 'BackgroundProcess', 'Integer', $this);
				case 'BackgroundProcessObject':
					return new dxQueryNodeBackgroundProcess('BackgroundProcess', 'BackgroundProcessObject', 'Integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'Blob', $this);
				case 'ObjectOwner':
					return new dxQueryNode('ObjectOwner', 'ObjectOwner', 'Integer', $this);

				case '_PrimaryKeyNode':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (dxCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

    /**
     * @property-read dxQueryNode $Id
     * @property-read dxQueryNode $UpdateDateTime
     * @property-read dxQueryNode $UpdateMessage
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $BackgroundProcess
     * @property-read dxQueryNodeBackgroundProcess $BackgroundProcessObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodeBackgroundProcessUpdate extends dxQueryReverseReferenceNode {
		protected $strTableName = 'BackgroundProcessUpdate';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'BackgroundProcessUpdate';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'UpdateDateTime':
					return new dxQueryNode('UpdateDateTime', 'UpdateDateTime', 'dxDateTime', $this);
				case 'UpdateMessage':
					return new dxQueryNode('UpdateMessage', 'UpdateMessage', 'string', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'string', $this);
				case 'BackgroundProcess':
					return new dxQueryNode('BackgroundProcess', 'BackgroundProcess', 'integer', $this);
				case 'BackgroundProcessObject':
					return new dxQueryNodeBackgroundProcess('BackgroundProcess', 'BackgroundProcessObject', 'integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'string', $this);
				case 'ObjectOwner':
					return new dxQueryNode('ObjectOwner', 'ObjectOwner', 'integer', $this);

				case '_PrimaryKeyNode':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (dxCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>
