<?php
/**
 * The abstract ApiKeyGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the ApiKey subclass which
 * extends this ApiKeyGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the ApiKey class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property string $ApiKey the value for strApiKey (Unique)
 * @property dxDateTime $ValidFromDate the value for dttValidFromDate 
 * @property dxDateTime $ValidToDate the value for dttValidToDate 
 * @property string $Notes the value for strNotes 
 * @property string $CallingEntityInformation the value for strCallingEntityInformation 
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property-read AllowedApiOperation $_AllowedApiOperation the value for the private _objAllowedApiOperation (Read-Only) if set due to an expansion on the AllowedApiOperation.ApiKey reverse relationship
 * @property-read AllowedApiOperation[] $_AllowedApiOperationArray the value for the private _objAllowedApiOperationArray (Read-Only) if set due to an ExpandAsArray on the AllowedApiOperation.ApiKey reverse relationship
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class ApiKeyGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column ApiKey.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column ApiKey.ApiKey
     * @var string strApiKey
     */
    protected $strApiKey;
    const ApiKeyMaxLength = 50;
    const ApiKeyDefault = null;


    /**
     * Protected member variable that maps to the database column ApiKey.ValidFromDate
     * @var dxDateTime dttValidFromDate
     */
    protected $dttValidFromDate;
    const ValidFromDateDefault = null;


    /**
     * Protected member variable that maps to the database column ApiKey.ValidToDate
     * @var dxDateTime dttValidToDate
     */
    protected $dttValidToDate;
    const ValidToDateDefault = null;


    /**
     * Protected member variable that maps to the database column ApiKey.Notes
     * @var string strNotes
     */
    protected $strNotes;
    const NotesDefault = null;


    /**
     * Protected member variable that maps to the database column ApiKey.CallingEntityInformation
     * @var string strCallingEntityInformation
     */
    protected $strCallingEntityInformation;
    const CallingEntityInformationDefault = null;


    /**
     * Protected member variable that maps to the database column ApiKey.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column ApiKey.ObjectOwner
     * @var integer intObjectOwner
     */
    protected $intObjectOwner;
    const ObjectOwnerDefault = null;


    /**
     * Private member variable that stores a reference to a single AllowedApiOperation object
     * (of type AllowedApiOperation), if this ApiKey object was restored with
     * an expansion on the AllowedApiOperation association table.
     * @var AllowedApiOperation _objAllowedApiOperation;
     */
    private $_objAllowedApiOperation;

    /**
     * Private member variable that stores a reference to an array of AllowedApiOperation objects
     * (of type AllowedApiOperation[]), if this ApiKey object was restored with
     * an ExpandAsArray on the AllowedApiOperation association table.
     * @var AllowedApiOperation[] _objAllowedApiOperationArray;
     */
    private $_objAllowedApiOperationArray = null;

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
     * Initialize each property with default values from database definition
     */
    public function Initialize() {
        $this->intId = ApiKey::IdDefault;
        $this->strApiKey = ApiKey::ApiKeyDefault;
        $this->dttValidFromDate = (ApiKey::ValidFromDateDefault === null)?null:new dxDateTime(ApiKey::ValidFromDateDefault);
        $this->dttValidToDate = (ApiKey::ValidToDateDefault === null)?null:new dxDateTime(ApiKey::ValidToDateDefault);
        $this->strNotes = ApiKey::NotesDefault;
        $this->strCallingEntityInformation = ApiKey::CallingEntityInformationDefault;
        $this->strLastUpdated = ApiKey::LastUpdatedDefault;
        $this->intObjectOwner = ApiKey::ObjectOwnerDefault;
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
     * Load a ApiKey from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ApiKey
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'ApiKey', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = ApiKey::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::ApiKey()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all ApiKeies
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ApiKey[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call ApiKey::QueryArray to perform the LoadAll query
        try {
            return ApiKey::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all ApiKeies
     * @return int
     */
    public static function CountAll() {
        // Call ApiKey::QueryCount to perform the CountAll query
        return ApiKey::QueryCount(dxQuery::All());
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
        $objDatabase = ApiKey::GetDatabase();

        // Create/Build out the QueryBuilder object with ApiKey-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'ApiKey');

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
            ApiKey::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('ApiKey');

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
     * Static divblox Query method to query for a single ApiKey object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return ApiKey the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = ApiKey::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new ApiKey object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = ApiKey::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
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
            return ApiKey::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of ApiKey objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return ApiKey[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = ApiKey::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return ApiKey::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
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
            $strQuery = ApiKey::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
     * Static divblox Query method to query for a count of ApiKey objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = ApiKey::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
        $objDatabase = ApiKey::GetDatabase();

        $strQuery = ApiKey::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/apikey', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = ApiKey::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this ApiKey
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'ApiKey';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'ApiKey', $strAliasPrefix . 'ApiKey');
            $objBuilder->AddSelectItem($strTableName, 'ValidFromDate', $strAliasPrefix . 'ValidFromDate');
            $objBuilder->AddSelectItem($strTableName, 'ValidToDate', $strAliasPrefix . 'ValidToDate');
            $objBuilder->AddSelectItem($strTableName, 'Notes', $strAliasPrefix . 'Notes');
            $objBuilder->AddSelectItem($strTableName, 'CallingEntityInformation', $strAliasPrefix . 'CallingEntityInformation');
            $objBuilder->AddSelectItem($strTableName, 'LastUpdated', $strAliasPrefix . 'LastUpdated');
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
     * Instantiate a ApiKey from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this ApiKey::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a ApiKey, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
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

            if (ApiKey::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray)) {
                return false; // db row was used but no new object was created
            }
        }

        // Create a new instance of the ApiKey object
        $objToReturn = new ApiKey();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'ApiKey';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strApiKey = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'ValidFromDate';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttValidFromDate = $objDbRow->GetColumn($strAliasName, 'Date');
        $strAlias = $strAliasPrefix . 'ValidToDate';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttValidToDate = $objDbRow->GetColumn($strAliasName, 'Date');
        $strAlias = $strAliasPrefix . 'Notes';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strNotes = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'CallingEntityInformation';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strCallingEntityInformation = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'LastUpdated';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strLastUpdated = $objDbRow->GetColumn($strAliasName, 'VarChar');
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
            $strAliasPrefix = 'ApiKey__';




        // Check for AllowedApiOperation Virtual Binding
        $strAlias = $strAliasPrefix . 'allowedapioperation__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objExpansionNode = (empty($objExpansionAliasArray['allowedapioperation']) ? null : $objExpansionAliasArray['allowedapioperation']);
        $blnExpanded = ($objExpansionNode && $objExpansionNode->ExpandAsArray);
        if ($blnExpanded && null === $objToReturn->_objAllowedApiOperationArray)
            $objToReturn->_objAllowedApiOperationArray = array();
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            if ($blnExpanded) {
                $objToReturn->_objAllowedApiOperationArray[] = AllowedApiOperation::InstantiateDbRow($objDbRow, $strAliasPrefix . 'allowedapioperation__', $objExpansionNode, null, $strColumnAliasArray);
            } elseif (is_null($objToReturn->_objAllowedApiOperation)) {
                $objToReturn->_objAllowedApiOperation = AllowedApiOperation::InstantiateDbRow($objDbRow, $strAliasPrefix . 'allowedapioperation__', $objExpansionNode, null, $strColumnAliasArray);
            }
        }

        return $objToReturn;
    }

    /**
     * Instantiate an array of ApiKeies from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return ApiKey[]
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
                $objItem = ApiKey::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = ApiKey::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single ApiKey object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return ApiKey next row resulting from the query
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
        return ApiKey::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single ApiKey object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ApiKey
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return ApiKey::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::ApiKey()->Id, $intId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load a single ApiKey object,
     * by ApiKey Index(es)
     * @param string $strApiKey
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ApiKey
    */
    public static function LoadByApiKey($strApiKey, $objOptionalClauses = null) {
        return ApiKey::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::ApiKey()->ApiKey, $strApiKey)
            ),
            $objOptionalClauses
        );
    }
    ////////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Array via Many to Many)
    ////////////////////////////////////////////////////


    //////////////////////////
    // SAVE, DELETE AND RELOAD
    //////////////////////////

    /**
    * Save this ApiKey
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ApiKey",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = ApiKey::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'ApiKey';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("ApiKey" => $this->strApiKey));
            $ChangedArray = array_merge($ChangedArray,array("ValidFromDate" => $this->dttValidFromDate));
            $ChangedArray = array_merge($ChangedArray,array("ValidToDate" => $this->dttValidToDate));
            $ChangedArray = array_merge($ChangedArray,array("Notes" => $this->strNotes));
            $ChangedArray = array_merge($ChangedArray,array("CallingEntityInformation" => $this->strCallingEntityInformation));
            $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
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
            if (!is_null($ExistingObj->ApiKey)) {
                $ExistingValueStr = $ExistingObj->ApiKey;
            }
            if ($ExistingObj->ApiKey != $this->strApiKey) {
                $ChangedArray = array_merge($ChangedArray,array("ApiKey" => array("Before" => $ExistingValueStr,"After" => $this->strApiKey)));
                //$ChangedArray = array_merge($ChangedArray,array("ApiKey" => "From: ".$ExistingValueStr." to: ".$this->strApiKey));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ValidFromDate)) {
                $ExistingValueStr = $ExistingObj->ValidFromDate;
            }
            if ($ExistingObj->ValidFromDate != $this->dttValidFromDate) {
                $ChangedArray = array_merge($ChangedArray,array("ValidFromDate" => array("Before" => $ExistingValueStr,"After" => $this->dttValidFromDate)));
                //$ChangedArray = array_merge($ChangedArray,array("ValidFromDate" => "From: ".$ExistingValueStr." to: ".$this->dttValidFromDate));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ValidToDate)) {
                $ExistingValueStr = $ExistingObj->ValidToDate;
            }
            if ($ExistingObj->ValidToDate != $this->dttValidToDate) {
                $ChangedArray = array_merge($ChangedArray,array("ValidToDate" => array("Before" => $ExistingValueStr,"After" => $this->dttValidToDate)));
                //$ChangedArray = array_merge($ChangedArray,array("ValidToDate" => "From: ".$ExistingValueStr." to: ".$this->dttValidToDate));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Notes)) {
                $ExistingValueStr = $ExistingObj->Notes;
            }
            if ($ExistingObj->Notes != $this->strNotes) {
                $ChangedArray = array_merge($ChangedArray,array("Notes" => array("Before" => $ExistingValueStr,"After" => $this->strNotes)));
                //$ChangedArray = array_merge($ChangedArray,array("Notes" => "From: ".$ExistingValueStr." to: ".$this->strNotes));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->CallingEntityInformation)) {
                $ExistingValueStr = $ExistingObj->CallingEntityInformation;
            }
            if ($ExistingObj->CallingEntityInformation != $this->strCallingEntityInformation) {
                $ChangedArray = array_merge($ChangedArray,array("CallingEntityInformation" => array("Before" => $ExistingValueStr,"After" => $this->strCallingEntityInformation)));
                //$ChangedArray = array_merge($ChangedArray,array("CallingEntityInformation" => "From: ".$ExistingValueStr." to: ".$this->strCallingEntityInformation));
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
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'ApiKey'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `ApiKey` (
							`ApiKey`,
							`ValidFromDate`,
							`ValidToDate`,
							`Notes`,
							`CallingEntityInformation`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strApiKey) . ',
							' . $objDatabase->SqlVariable($this->dttValidFromDate) . ',
							' . $objDatabase->SqlVariable($this->dttValidToDate) . ',
							' . $objDatabase->SqlVariable($this->strNotes) . ',
							' . $objDatabase->SqlVariable($this->strCallingEntityInformation) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('ApiKey', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'ApiKey'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `ApiKey` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('ApiKey');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `ApiKey` SET
							`ApiKey` = ' . $objDatabase->SqlVariable($this->strApiKey) . ',
							`ValidFromDate` = ' . $objDatabase->SqlVariable($this->dttValidFromDate) . ',
							`ValidToDate` = ' . $objDatabase->SqlVariable($this->dttValidToDate) . ',
							`Notes` = ' . $objDatabase->SqlVariable($this->strNotes) . ',
							`CallingEntityInformation` = ' . $objDatabase->SqlVariable($this->strCallingEntityInformation) . ',
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
            error_log('Could not save audit log while saving ApiKey. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `ApiKey` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this ApiKey
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this ApiKey with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ApiKey",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'ApiKey'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'ApiKey';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("ApiKey" => $this->strApiKey));
        $ChangedArray = array_merge($ChangedArray,array("ValidFromDate" => $this->dttValidFromDate));
        $ChangedArray = array_merge($ChangedArray,array("ValidToDate" => $this->dttValidToDate));
        $ChangedArray = array_merge($ChangedArray,array("Notes" => $this->strNotes));
        $ChangedArray = array_merge($ChangedArray,array("CallingEntityInformation" => $this->strCallingEntityInformation));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting ApiKey. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `ApiKey`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this ApiKey ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'ApiKey', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all ApiKeies
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `ApiKey`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate ApiKey table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `ApiKey`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this ApiKey from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved ApiKey object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = ApiKey::Load($this->intId);

        // Update $this's local variables to match
        $this->strApiKey = $objReloaded->strApiKey;
        $this->dttValidFromDate = $objReloaded->dttValidFromDate;
        $this->dttValidToDate = $objReloaded->dttValidToDate;
        $this->strNotes = $objReloaded->strNotes;
        $this->strCallingEntityInformation = $objReloaded->strCallingEntityInformation;
        $this->strLastUpdated = $objReloaded->strLastUpdated;
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

            case 'ApiKey':
                /**
                 * Gets the value for strApiKey (Unique)
                 * @return string
                 */
                return $this->strApiKey;

            case 'ValidFromDate':
                /**
                 * Gets the value for dttValidFromDate 
                 * @return dxDateTime
                 */
                return $this->dttValidFromDate;

            case 'ValidToDate':
                /**
                 * Gets the value for dttValidToDate 
                 * @return dxDateTime
                 */
                return $this->dttValidToDate;

            case 'Notes':
                /**
                 * Gets the value for strNotes 
                 * @return string
                 */
                return $this->strNotes;

            case 'CallingEntityInformation':
                /**
                 * Gets the value for strCallingEntityInformation 
                 * @return string
                 */
                return $this->strCallingEntityInformation;

            case 'LastUpdated':
                /**
                 * Gets the value for strLastUpdated (Read-Only Timestamp)
                 * @return string
                 */
                return $this->strLastUpdated;

            case 'ObjectOwner':
                /**
                 * Gets the value for intObjectOwner 
                 * @return integer
                 */
                return $this->intObjectOwner;


            ///////////////////
            // Member Objects
            ///////////////////

            ////////////////////////////
            // Virtual Object References (Many to Many and Reverse References)
            // (If restored via a "Many-to" expansion)
            ////////////////////////////

            case '_AllowedApiOperation':
                /**
                 * Gets the value for the private _objAllowedApiOperation (Read-Only)
                 * if set due to an expansion on the AllowedApiOperation.ApiKey reverse relationship
                 * @return AllowedApiOperation
                 */
                return $this->_objAllowedApiOperation;

            case '_AllowedApiOperationArray':
                /**
                 * Gets the value for the private _objAllowedApiOperationArray (Read-Only)
                 * if set due to an ExpandAsArray on the AllowedApiOperation.ApiKey reverse relationship
                 * @return AllowedApiOperation[]
                 */
                return $this->_objAllowedApiOperationArray;


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
            case 'ApiKey':
                /**
                 * Sets the value for strApiKey (Unique)
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strApiKey = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ValidFromDate':
                /**
                 * Sets the value for dttValidFromDate 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttValidFromDate = dxType::Cast($mixValue, dxType::DateTime));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ValidToDate':
                /**
                 * Sets the value for dttValidToDate 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttValidToDate = dxType::Cast($mixValue, dxType::DateTime));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Notes':
                /**
                 * Sets the value for strNotes 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strNotes = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'CallingEntityInformation':
                /**
                 * Sets the value for strCallingEntityInformation 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strCallingEntityInformation = dxType::Cast($mixValue, dxType::String));
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



    // Related Objects' Methods for AllowedApiOperation
    //-------------------------------------------------------------------

    /**
     * Gets all associated AllowedApiOperations as an array of AllowedApiOperation objects
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return AllowedApiOperation[]
    */
    public function GetAllowedApiOperationArray($objOptionalClauses = null) {
        if ((is_null($this->intId)))
            return array();

        try {
            return AllowedApiOperation::LoadArrayByApiKey($this->intId, $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Counts all associated AllowedApiOperations
     * @return int
    */
    public function CountAllowedApiOperations() {
        if ((is_null($this->intId)))
            return 0;

        return AllowedApiOperation::CountByApiKey($this->intId);
    }

    /**
     * Associates a AllowedApiOperation
     * @param AllowedApiOperation $objAllowedApiOperation
     * @return void
    */
    public function AssociateAllowedApiOperation(AllowedApiOperation $objAllowedApiOperation) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call AssociateAllowedApiOperation on this unsaved ApiKey.');
        if ((is_null($objAllowedApiOperation->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call AssociateAllowedApiOperation on this ApiKey with an unsaved AllowedApiOperation.');

        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `AllowedApiOperation`
            SET
                `ApiKey` = ' . $objDatabase->SqlVariable($this->intId) . '
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objAllowedApiOperation->Id) . '
        ');
    }

    /**
     * Unassociates a AllowedApiOperation
     * @param AllowedApiOperation $objAllowedApiOperation
     * @return void
    */
    public function UnassociateAllowedApiOperation(AllowedApiOperation $objAllowedApiOperation) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateAllowedApiOperation on this unsaved ApiKey.');
        if ((is_null($objAllowedApiOperation->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateAllowedApiOperation on this ApiKey with an unsaved AllowedApiOperation.');

        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `AllowedApiOperation`
            SET
                `ApiKey` = null
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objAllowedApiOperation->Id) . ' AND
                `ApiKey` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Unassociates all AllowedApiOperations
     * @return void
    */
    public function UnassociateAllAllowedApiOperations() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateAllowedApiOperation on this unsaved ApiKey.');

        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `AllowedApiOperation`
            SET
                `ApiKey` = null
            WHERE
                `ApiKey` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Deletes an associated AllowedApiOperation
     * @param AllowedApiOperation $objAllowedApiOperation
     * @return void
    */
    public function DeleteAssociatedAllowedApiOperation(AllowedApiOperation $objAllowedApiOperation) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateAllowedApiOperation on this unsaved ApiKey.');
        if ((is_null($objAllowedApiOperation->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateAllowedApiOperation on this ApiKey with an unsaved AllowedApiOperation.');

        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `AllowedApiOperation`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objAllowedApiOperation->Id) . ' AND
                `ApiKey` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Deletes all associated AllowedApiOperations
     * @return void
    */
    public function DeleteAllAllowedApiOperations() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateAllowedApiOperation on this unsaved ApiKey.');

        // Get the Database Object for this Class
        $objDatabase = ApiKey::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `AllowedApiOperation`
            WHERE
                `ApiKey` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }


    
///////////////////////////////
    // METHODS TO EXTRACT INFO ABOUT THE CLASS
    ///////////////////////////////

    /**
     * Static method to retrieve the Database object that owns this class.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetTableName() {
        return "ApiKey";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[ApiKey::GetDatabaseIndex()]->Database;
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
        $strToReturn = '<complexType name="ApiKey"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="ApiKey" type="xsd:string"/>';
        $strToReturn .= '<element name="ValidFromDate" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="ValidToDate" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="Notes" type="xsd:string"/>';
        $strToReturn .= '<element name="CallingEntityInformation" type="xsd:string"/>';
        $strToReturn .= '<element name="LastUpdated" type="xsd:string"/>';
        $strToReturn .= '<element name="ObjectOwner" type="xsd:int"/>';
        $strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
        $strToReturn .= '</sequence></complexType>';
        return $strToReturn;
    }

    public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
        if (!array_key_exists('ApiKey', $strComplexTypeArray)) {
            $strComplexTypeArray['ApiKey'] = ApiKey::GetSoapComplexTypeXml();
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, ApiKey::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new ApiKey();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'ApiKey'))
            $objToReturn->strApiKey = $objSoapObject->ApiKey;
        if (property_exists($objSoapObject, 'ValidFromDate'))
            $objToReturn->dttValidFromDate = new dxDateTime($objSoapObject->ValidFromDate);
        if (property_exists($objSoapObject, 'ValidToDate'))
            $objToReturn->dttValidToDate = new dxDateTime($objSoapObject->ValidToDate);
        if (property_exists($objSoapObject, 'Notes'))
            $objToReturn->strNotes = $objSoapObject->Notes;
        if (property_exists($objSoapObject, 'CallingEntityInformation'))
            $objToReturn->strCallingEntityInformation = $objSoapObject->CallingEntityInformation;
        if (property_exists($objSoapObject, 'LastUpdated'))
            $objToReturn->strLastUpdated = $objSoapObject->LastUpdated;
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
            array_push($objArrayToReturn, ApiKey::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttValidFromDate)
            $objObject->dttValidFromDate = $objObject->dttValidFromDate->qFormat(dxDateTime::FormatSoap);
        if ($objObject->dttValidToDate)
            $objObject->dttValidToDate = $objObject->dttValidToDate->qFormat(dxDateTime::FormatSoap);
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
        $iArray['ApiKey'] = $this->strApiKey;
        $iArray['ValidFromDate'] = $this->dttValidFromDate;
        $iArray['ValidToDate'] = $this->dttValidToDate;
        $iArray['Notes'] = $this->strNotes;
        $iArray['CallingEntityInformation'] = $this->strCallingEntityInformation;
        $iArray['LastUpdated'] = $this->strLastUpdated;
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
     * @property-read dxQueryNode $ApiKey
     * @property-read dxQueryNode $ValidFromDate
     * @property-read dxQueryNode $ValidToDate
     * @property-read dxQueryNode $Notes
     * @property-read dxQueryNode $CallingEntityInformation
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *
     * @property-read dxQueryReverseReferenceNodeAllowedApiOperation $AllowedApiOperation

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodeApiKey extends dxQueryNode {
		protected $strTableName = 'ApiKey';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'ApiKey';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'ApiKey':
					return new dxQueryNode('ApiKey', 'ApiKey', 'VarChar', $this);
				case 'ValidFromDate':
					return new dxQueryNode('ValidFromDate', 'ValidFromDate', 'Date', $this);
				case 'ValidToDate':
					return new dxQueryNode('ValidToDate', 'ValidToDate', 'Date', $this);
				case 'Notes':
					return new dxQueryNode('Notes', 'Notes', 'Blob', $this);
				case 'CallingEntityInformation':
					return new dxQueryNode('CallingEntityInformation', 'CallingEntityInformation', 'Blob', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'VarChar', $this);
				case 'ObjectOwner':
					return new dxQueryNode('ObjectOwner', 'ObjectOwner', 'Integer', $this);
				case 'AllowedApiOperation':
					return new dxQueryReverseReferenceNodeAllowedApiOperation($this, 'allowedapioperation', 'reverse_reference', 'ApiKey', 'AllowedApiOperation');

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
     * @property-read dxQueryNode $ApiKey
     * @property-read dxQueryNode $ValidFromDate
     * @property-read dxQueryNode $ValidToDate
     * @property-read dxQueryNode $Notes
     * @property-read dxQueryNode $CallingEntityInformation
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *
     * @property-read dxQueryReverseReferenceNodeAllowedApiOperation $AllowedApiOperation

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodeApiKey extends dxQueryReverseReferenceNode {
		protected $strTableName = 'ApiKey';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'ApiKey';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'ApiKey':
					return new dxQueryNode('ApiKey', 'ApiKey', 'string', $this);
				case 'ValidFromDate':
					return new dxQueryNode('ValidFromDate', 'ValidFromDate', 'dxDateTime', $this);
				case 'ValidToDate':
					return new dxQueryNode('ValidToDate', 'ValidToDate', 'dxDateTime', $this);
				case 'Notes':
					return new dxQueryNode('Notes', 'Notes', 'string', $this);
				case 'CallingEntityInformation':
					return new dxQueryNode('CallingEntityInformation', 'CallingEntityInformation', 'string', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'string', $this);
				case 'ObjectOwner':
					return new dxQueryNode('ObjectOwner', 'ObjectOwner', 'integer', $this);
				case 'AllowedApiOperation':
					return new dxQueryReverseReferenceNodeAllowedApiOperation($this, 'allowedapioperation', 'reverse_reference', 'ApiKey', 'AllowedApiOperation');

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
