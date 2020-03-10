<?php
/**
 * The abstract ExpenseGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the Expense subclass which
 * extends this ExpenseGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the Expense class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property string $ExpenseName the value for strExpenseName 
 * @property string $ExpenseAmount the value for strExpenseAmount 
 * @property dxDateTime $ExpenseDate the value for dttExpenseDate 
 * @property string $ExpenseUniqueId the value for strExpenseUniqueId 
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $Account the value for intAccount 
 * @property string $SearchMetaInfo the value for strSearchMetaInfo 
 * @property integer $Category the value for intCategory 
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property Account $AccountObject the value for the Account object referenced by intAccount 
 * @property Category $CategoryObject the value for the Category object referenced by intCategory 
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class ExpenseGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column Expense.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.ExpenseName
     * @var string strExpenseName
     */
    protected $strExpenseName;
    const ExpenseNameMaxLength = 50;
    const ExpenseNameDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.ExpenseAmount
     * @var string strExpenseAmount
     */
    protected $strExpenseAmount;
    const ExpenseAmountDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.ExpenseDate
     * @var dxDateTime dttExpenseDate
     */
    protected $dttExpenseDate;
    const ExpenseDateDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.ExpenseUniqueId
     * @var string strExpenseUniqueId
     */
    protected $strExpenseUniqueId;
    const ExpenseUniqueIdMaxLength = 25;
    const ExpenseUniqueIdDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.Account
     * @var integer intAccount
     */
    protected $intAccount;
    const AccountDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.SearchMetaInfo
     * @var string strSearchMetaInfo
     */
    protected $strSearchMetaInfo;
    const SearchMetaInfoDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.Category
     * @var integer intCategory
     */
    protected $intCategory;
    const CategoryDefault = null;


    /**
     * Protected member variable that maps to the database column Expense.ObjectOwner
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
     * in the database column Expense.Account.
     *
     * NOTE: Always use the AccountObject property getter to correctly retrieve this Account object.
     * (Because this class implements late binding, this variable reference MAY be null.)
     * @var Account objAccountObject
     */
    protected $objAccountObject;

    /**
     * Protected member variable that contains the object pointed by the reference
     * in the database column Expense.Category.
     *
     * NOTE: Always use the CategoryObject property getter to correctly retrieve this Category object.
     * (Because this class implements late binding, this variable reference MAY be null.)
     * @var Category objCategoryObject
     */
    protected $objCategoryObject;


    /**
     * Initialize each property with default values from database definition
     */
    public function Initialize() {
        $this->intId = Expense::IdDefault;
        $this->strExpenseName = Expense::ExpenseNameDefault;
        $this->strExpenseAmount = Expense::ExpenseAmountDefault;
        $this->dttExpenseDate = (Expense::ExpenseDateDefault === null)?null:new dxDateTime(Expense::ExpenseDateDefault);
        $this->strExpenseUniqueId = Expense::ExpenseUniqueIdDefault;
        $this->strLastUpdated = Expense::LastUpdatedDefault;
        $this->intAccount = Expense::AccountDefault;
        $this->strSearchMetaInfo = Expense::SearchMetaInfoDefault;
        $this->intCategory = Expense::CategoryDefault;
        $this->intObjectOwner = Expense::ObjectOwnerDefault;
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
     * Load a Expense from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Expense
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'Expense', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = Expense::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::Expense()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all Expenses
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Expense[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call Expense::QueryArray to perform the LoadAll query
        try {
            return Expense::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all Expenses
     * @return int
     */
    public static function CountAll() {
        // Call Expense::QueryCount to perform the CountAll query
        return Expense::QueryCount(dxQuery::All());
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
        $objDatabase = Expense::GetDatabase();

        // Create/Build out the QueryBuilder object with Expense-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'Expense');

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
            Expense::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('Expense');

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
     * Static divblox Query method to query for a single Expense object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return Expense the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = Expense::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new Expense object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = Expense::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
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
            return Expense::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of Expense objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return Expense[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = Expense::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return Expense::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
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
            $strQuery = Expense::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
     * Static divblox Query method to query for a count of Expense objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = Expense::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
        $objDatabase = Expense::GetDatabase();

        $strQuery = Expense::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/expense', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = Expense::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this Expense
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'Expense';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'ExpenseName', $strAliasPrefix . 'ExpenseName');
            $objBuilder->AddSelectItem($strTableName, 'ExpenseAmount', $strAliasPrefix . 'ExpenseAmount');
            $objBuilder->AddSelectItem($strTableName, 'ExpenseDate', $strAliasPrefix . 'ExpenseDate');
            $objBuilder->AddSelectItem($strTableName, 'ExpenseUniqueId', $strAliasPrefix . 'ExpenseUniqueId');
            $objBuilder->AddSelectItem($strTableName, 'LastUpdated', $strAliasPrefix . 'LastUpdated');
            $objBuilder->AddSelectItem($strTableName, 'Account', $strAliasPrefix . 'Account');
            $objBuilder->AddSelectItem($strTableName, 'SearchMetaInfo', $strAliasPrefix . 'SearchMetaInfo');
            $objBuilder->AddSelectItem($strTableName, 'Category', $strAliasPrefix . 'Category');
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
     * Instantiate a Expense from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this Expense::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a Expense, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
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

            if (Expense::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray)) {
                return false; // db row was used but no new object was created
            }
        }

        // Create a new instance of the Expense object
        $objToReturn = new Expense();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'ExpenseName';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strExpenseName = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'ExpenseAmount';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strExpenseAmount = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'ExpenseDate';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttExpenseDate = $objDbRow->GetColumn($strAliasName, 'Date');
        $strAlias = $strAliasPrefix . 'ExpenseUniqueId';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strExpenseUniqueId = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'LastUpdated';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strLastUpdated = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'Account';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intAccount = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'SearchMetaInfo';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strSearchMetaInfo = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'Category';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intCategory = $objDbRow->GetColumn($strAliasName, 'Integer');
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
            $strAliasPrefix = 'Expense__';

        // Check for AccountObject Early Binding
        $strAlias = $strAliasPrefix . 'Account__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            $objExpansionNode = (empty($objExpansionAliasArray['Account']) ? null : $objExpansionAliasArray['Account']);
            $objToReturn->objAccountObject = Account::InstantiateDbRow($objDbRow, $strAliasPrefix . 'Account__', $objExpansionNode, null, $strColumnAliasArray);
        }
        // Check for CategoryObject Early Binding
        $strAlias = $strAliasPrefix . 'Category__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            $objExpansionNode = (empty($objExpansionAliasArray['Category']) ? null : $objExpansionAliasArray['Category']);
            $objToReturn->objCategoryObject = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'Category__', $objExpansionNode, null, $strColumnAliasArray);
        }



        return $objToReturn;
    }

    /**
     * Instantiate an array of Expenses from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return Expense[]
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
                $objItem = Expense::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = Expense::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single Expense object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return Expense next row resulting from the query
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
        return Expense::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single Expense object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Expense
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return Expense::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::Expense()->Id, $intId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load an array of Expense objects,
     * by Account Index(es)
     * @param integer $intAccount
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Expense[]
    */
    public static function LoadArrayByAccount($intAccount, $objOptionalClauses = null) {
        // Call Expense::QueryArray to perform the LoadArrayByAccount query
        try {
            return Expense::QueryArray(
                dxQuery::Equal(dxQueryN::Expense()->Account, $intAccount),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count Expenses
     * by Account Index(es)
     * @param integer $intAccount
     * @return int
    */
    public static function CountByAccount($intAccount) {
        // Call Expense::QueryCount to perform the CountByAccount query
        return Expense::QueryCount(
            dxQuery::Equal(dxQueryN::Expense()->Account, $intAccount)
        );
    }

    /**
     * Load an array of Expense objects,
     * by Category Index(es)
     * @param integer $intCategory
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Expense[]
    */
    public static function LoadArrayByCategory($intCategory, $objOptionalClauses = null) {
        // Call Expense::QueryArray to perform the LoadArrayByCategory query
        try {
            return Expense::QueryArray(
                dxQuery::Equal(dxQueryN::Expense()->Category, $intCategory),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count Expenses
     * by Category Index(es)
     * @param integer $intCategory
     * @return int
    */
    public static function CountByCategory($intCategory) {
        // Call Expense::QueryCount to perform the CountByCategory query
        return Expense::QueryCount(
            dxQuery::Equal(dxQueryN::Expense()->Category, $intCategory)
        );
    }
    ////////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Array via Many to Many)
    ////////////////////////////////////////////////////


    //////////////////////////
    // SAVE, DELETE AND RELOAD
    //////////////////////////

    /**
    * Save this Expense
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Expense",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = Expense::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = Expense::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'Expense';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("ExpenseName" => $this->strExpenseName));
            $ChangedArray = array_merge($ChangedArray,array("ExpenseAmount" => $this->strExpenseAmount));
            $ChangedArray = array_merge($ChangedArray,array("ExpenseDate" => $this->dttExpenseDate));
            $ChangedArray = array_merge($ChangedArray,array("ExpenseUniqueId" => $this->strExpenseUniqueId));
            $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
            $ChangedArray = array_merge($ChangedArray,array("Account" => $this->intAccount));
            $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
            $ChangedArray = array_merge($ChangedArray,array("Category" => $this->intCategory));
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
            if (!is_null($ExistingObj->ExpenseName)) {
                $ExistingValueStr = $ExistingObj->ExpenseName;
            }
            if ($ExistingObj->ExpenseName != $this->strExpenseName) {
                $ChangedArray = array_merge($ChangedArray,array("ExpenseName" => array("Before" => $ExistingValueStr,"After" => $this->strExpenseName)));
                //$ChangedArray = array_merge($ChangedArray,array("ExpenseName" => "From: ".$ExistingValueStr." to: ".$this->strExpenseName));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ExpenseAmount)) {
                $ExistingValueStr = $ExistingObj->ExpenseAmount;
            }
            if ($ExistingObj->ExpenseAmount != $this->strExpenseAmount) {
                $ChangedArray = array_merge($ChangedArray,array("ExpenseAmount" => array("Before" => $ExistingValueStr,"After" => $this->strExpenseAmount)));
                //$ChangedArray = array_merge($ChangedArray,array("ExpenseAmount" => "From: ".$ExistingValueStr." to: ".$this->strExpenseAmount));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ExpenseDate)) {
                $ExistingValueStr = $ExistingObj->ExpenseDate;
            }
            if ($ExistingObj->ExpenseDate != $this->dttExpenseDate) {
                $ChangedArray = array_merge($ChangedArray,array("ExpenseDate" => array("Before" => $ExistingValueStr,"After" => $this->dttExpenseDate)));
                //$ChangedArray = array_merge($ChangedArray,array("ExpenseDate" => "From: ".$ExistingValueStr." to: ".$this->dttExpenseDate));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ExpenseUniqueId)) {
                $ExistingValueStr = $ExistingObj->ExpenseUniqueId;
            }
            if ($ExistingObj->ExpenseUniqueId != $this->strExpenseUniqueId) {
                $ChangedArray = array_merge($ChangedArray,array("ExpenseUniqueId" => array("Before" => $ExistingValueStr,"After" => $this->strExpenseUniqueId)));
                //$ChangedArray = array_merge($ChangedArray,array("ExpenseUniqueId" => "From: ".$ExistingValueStr." to: ".$this->strExpenseUniqueId));
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
            if (!is_null($ExistingObj->Account)) {
                $ExistingValueStr = $ExistingObj->Account;
            }
            if ($ExistingObj->Account != $this->intAccount) {
                $ChangedArray = array_merge($ChangedArray,array("Account" => array("Before" => $ExistingValueStr,"After" => $this->intAccount)));
                //$ChangedArray = array_merge($ChangedArray,array("Account" => "From: ".$ExistingValueStr." to: ".$this->intAccount));
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
            if (!is_null($ExistingObj->Category)) {
                $ExistingValueStr = $ExistingObj->Category;
            }
            if ($ExistingObj->Category != $this->intCategory) {
                $ChangedArray = array_merge($ChangedArray,array("Category" => array("Before" => $ExistingValueStr,"After" => $this->intCategory)));
                //$ChangedArray = array_merge($ChangedArray,array("Category" => "From: ".$ExistingValueStr." to: ".$this->intCategory));
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
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'Expense'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `Expense` (
							`ExpenseName`,
							`ExpenseAmount`,
							`ExpenseDate`,
							`ExpenseUniqueId`,
							`Account`,
							`SearchMetaInfo`,
							`Category`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strExpenseName) . ',
							' . $objDatabase->SqlVariable($this->strExpenseAmount) . ',
							' . $objDatabase->SqlVariable($this->dttExpenseDate) . ',
							' . $objDatabase->SqlVariable($this->strExpenseUniqueId) . ',
							' . $objDatabase->SqlVariable($this->intAccount) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intCategory) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('Expense', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'Expense'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `Expense` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('Expense');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `Expense` SET
							`ExpenseName` = ' . $objDatabase->SqlVariable($this->strExpenseName) . ',
							`ExpenseAmount` = ' . $objDatabase->SqlVariable($this->strExpenseAmount) . ',
							`ExpenseDate` = ' . $objDatabase->SqlVariable($this->dttExpenseDate) . ',
							`ExpenseUniqueId` = ' . $objDatabase->SqlVariable($this->strExpenseUniqueId) . ',
							`Account` = ' . $objDatabase->SqlVariable($this->intAccount) . ',
							`SearchMetaInfo` = ' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							`Category` = ' . $objDatabase->SqlVariable($this->intCategory) . ',
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
            error_log('Could not save audit log while saving Expense. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `Expense` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this Expense
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this Expense with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Expense",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'Expense'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = Expense::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'Expense';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("ExpenseName" => $this->strExpenseName));
        $ChangedArray = array_merge($ChangedArray,array("ExpenseAmount" => $this->strExpenseAmount));
        $ChangedArray = array_merge($ChangedArray,array("ExpenseDate" => $this->dttExpenseDate));
        $ChangedArray = array_merge($ChangedArray,array("ExpenseUniqueId" => $this->strExpenseUniqueId));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("Account" => $this->intAccount));
        $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
        $ChangedArray = array_merge($ChangedArray,array("Category" => $this->intCategory));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting Expense. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `Expense`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this Expense ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'Expense', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all Expenses
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = Expense::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `Expense`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate Expense table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = Expense::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `Expense`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this Expense from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved Expense object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = Expense::Load($this->intId);

        // Update $this's local variables to match
        $this->strExpenseName = $objReloaded->strExpenseName;
        $this->strExpenseAmount = $objReloaded->strExpenseAmount;
        $this->dttExpenseDate = $objReloaded->dttExpenseDate;
        $this->strExpenseUniqueId = $objReloaded->strExpenseUniqueId;
        $this->strLastUpdated = $objReloaded->strLastUpdated;
        $this->Account = $objReloaded->Account;
        $this->strSearchMetaInfo = $objReloaded->strSearchMetaInfo;
        $this->Category = $objReloaded->Category;
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

            case 'ExpenseName':
                /**
                 * Gets the value for strExpenseName 
                 * @return string
                 */
                return $this->strExpenseName;

            case 'ExpenseAmount':
                /**
                 * Gets the value for strExpenseAmount 
                 * @return string
                 */
                return $this->strExpenseAmount;

            case 'ExpenseDate':
                /**
                 * Gets the value for dttExpenseDate 
                 * @return dxDateTime
                 */
                return $this->dttExpenseDate;

            case 'ExpenseUniqueId':
                /**
                 * Gets the value for strExpenseUniqueId 
                 * @return string
                 */
                return $this->strExpenseUniqueId;

            case 'LastUpdated':
                /**
                 * Gets the value for strLastUpdated (Read-Only Timestamp)
                 * @return string
                 */
                return $this->strLastUpdated;

            case 'Account':
                /**
                 * Gets the value for intAccount 
                 * @return integer
                 */
                return $this->intAccount;

            case 'SearchMetaInfo':
                /**
                 * Gets the value for strSearchMetaInfo 
                 * @return string
                 */
                return $this->strSearchMetaInfo;

            case 'Category':
                /**
                 * Gets the value for intCategory 
                 * @return integer
                 */
                return $this->intCategory;

            case 'ObjectOwner':
                /**
                 * Gets the value for intObjectOwner 
                 * @return integer
                 */
                return $this->intObjectOwner;


            ///////////////////
            // Member Objects
            ///////////////////
            case 'AccountObject':
                /**
                 * Gets the value for the Account object referenced by intAccount 
                 * @return Account
                 */
                try {
                    if ((!$this->objAccountObject) && (!is_null($this->intAccount)))
                        $this->objAccountObject = Account::Load($this->intAccount);
                    return $this->objAccountObject;
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'CategoryObject':
                /**
                 * Gets the value for the Category object referenced by intCategory 
                 * @return Category
                 */
                try {
                    if ((!$this->objCategoryObject) && (!is_null($this->intCategory)))
                        $this->objCategoryObject = Category::Load($this->intCategory);
                    return $this->objCategoryObject;
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
            case 'ExpenseName':
                /**
                 * Sets the value for strExpenseName 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strExpenseName = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ExpenseAmount':
                /**
                 * Sets the value for strExpenseAmount 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strExpenseAmount = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ExpenseDate':
                /**
                 * Sets the value for dttExpenseDate 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttExpenseDate = dxType::Cast($mixValue, dxType::DateTime));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ExpenseUniqueId':
                /**
                 * Sets the value for strExpenseUniqueId 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strExpenseUniqueId = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Account':
                /**
                 * Sets the value for intAccount 
                 * @param integer $mixValue
                 * @return integer
                 */
                try {
                    $this->objAccountObject = null;
                    return ($this->intAccount = dxType::Cast($mixValue, dxType::Integer));
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

            case 'Category':
                /**
                 * Sets the value for intCategory 
                 * @param integer $mixValue
                 * @return integer
                 */
                try {
                    $this->objCategoryObject = null;
                    return ($this->intCategory = dxType::Cast($mixValue, dxType::Integer));
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
            case 'AccountObject':
                /**
                 * Sets the value for the Account object referenced by intAccount 
                 * @param Account $mixValue
                 * @return Account
                 */
                if (is_null($mixValue)) {
                    $this->intAccount = null;
                    $this->objAccountObject = null;
                    return null;
                } else {
                    // Make sure $mixValue actually is a Account object
                    try {
                        $mixValue = dxType::Cast($mixValue, 'Account');
                    } catch (dxInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                    // Make sure $mixValue is a SAVED Account object
                    if (is_null($mixValue->Id))
                        throw new dxCallerException('Unable to set an unsaved AccountObject for this Expense');

                    // Update Local Member Variables
                    $this->objAccountObject = $mixValue;
                    $this->intAccount = $mixValue->Id;

                    // Return $mixValue
                    return $mixValue;
                }
                break;

            case 'CategoryObject':
                /**
                 * Sets the value for the Category object referenced by intCategory 
                 * @param Category $mixValue
                 * @return Category
                 */
                if (is_null($mixValue)) {
                    $this->intCategory = null;
                    $this->objCategoryObject = null;
                    return null;
                } else {
                    // Make sure $mixValue actually is a Category object
                    try {
                        $mixValue = dxType::Cast($mixValue, 'Category');
                    } catch (dxInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                    // Make sure $mixValue is a SAVED Category object
                    if (is_null($mixValue->Id))
                        throw new dxCallerException('Unable to set an unsaved CategoryObject for this Expense');

                    // Update Local Member Variables
                    $this->objCategoryObject = $mixValue;
                    $this->intCategory = $mixValue->Id;

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
        return "Expense";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[Expense::GetDatabaseIndex()]->Database;
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
        $strToReturn = '<complexType name="Expense"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="ExpenseName" type="xsd:string"/>';
        $strToReturn .= '<element name="ExpenseAmount" type="xsd:string"/>';
        $strToReturn .= '<element name="ExpenseDate" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="ExpenseUniqueId" type="xsd:string"/>';
        $strToReturn .= '<element name="LastUpdated" type="xsd:string"/>';
        $strToReturn .= '<element name="AccountObject" type="xsd1:Account"/>';
        $strToReturn .= '<element name="SearchMetaInfo" type="xsd:string"/>';
        $strToReturn .= '<element name="CategoryObject" type="xsd1:Category"/>';
        $strToReturn .= '<element name="ObjectOwner" type="xsd:int"/>';
        $strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
        $strToReturn .= '</sequence></complexType>';
        return $strToReturn;
    }

    public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
        if (!array_key_exists('Expense', $strComplexTypeArray)) {
            $strComplexTypeArray['Expense'] = Expense::GetSoapComplexTypeXml();
            Account::AlterSoapComplexTypeArray($strComplexTypeArray);
            Category::AlterSoapComplexTypeArray($strComplexTypeArray);
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, Expense::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new Expense();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'ExpenseName'))
            $objToReturn->strExpenseName = $objSoapObject->ExpenseName;
        if (property_exists($objSoapObject, 'ExpenseAmount'))
            $objToReturn->strExpenseAmount = $objSoapObject->ExpenseAmount;
        if (property_exists($objSoapObject, 'ExpenseDate'))
            $objToReturn->dttExpenseDate = new dxDateTime($objSoapObject->ExpenseDate);
        if (property_exists($objSoapObject, 'ExpenseUniqueId'))
            $objToReturn->strExpenseUniqueId = $objSoapObject->ExpenseUniqueId;
        if (property_exists($objSoapObject, 'LastUpdated'))
            $objToReturn->strLastUpdated = $objSoapObject->LastUpdated;
        if ((property_exists($objSoapObject, 'AccountObject')) &&
            ($objSoapObject->AccountObject))
            $objToReturn->AccountObject = Account::GetObjectFromSoapObject($objSoapObject->AccountObject);
        if (property_exists($objSoapObject, 'SearchMetaInfo'))
            $objToReturn->strSearchMetaInfo = $objSoapObject->SearchMetaInfo;
        if ((property_exists($objSoapObject, 'CategoryObject')) &&
            ($objSoapObject->CategoryObject))
            $objToReturn->CategoryObject = Category::GetObjectFromSoapObject($objSoapObject->CategoryObject);
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
            array_push($objArrayToReturn, Expense::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttExpenseDate)
            $objObject->dttExpenseDate = $objObject->dttExpenseDate->qFormat(dxDateTime::FormatSoap);
        if ($objObject->objAccountObject)
            $objObject->objAccountObject = Account::GetSoapObjectFromObject($objObject->objAccountObject, false);
        else if (!$blnBindRelatedObjects)
            $objObject->intAccount = null;
        if ($objObject->objCategoryObject)
            $objObject->objCategoryObject = Category::GetSoapObjectFromObject($objObject->objCategoryObject, false);
        else if (!$blnBindRelatedObjects)
            $objObject->intCategory = null;
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
        $iArray['ExpenseName'] = $this->strExpenseName;
        $iArray['ExpenseAmount'] = $this->strExpenseAmount;
        $iArray['ExpenseDate'] = $this->dttExpenseDate;
        $iArray['ExpenseUniqueId'] = $this->strExpenseUniqueId;
        $iArray['LastUpdated'] = $this->strLastUpdated;
        $iArray['Account'] = $this->intAccount;
        $iArray['SearchMetaInfo'] = $this->strSearchMetaInfo;
        $iArray['Category'] = $this->intCategory;
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
     * @property-read dxQueryNode $ExpenseName
     * @property-read dxQueryNode $ExpenseAmount
     * @property-read dxQueryNode $ExpenseDate
     * @property-read dxQueryNode $ExpenseUniqueId
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $Account
     * @property-read dxQueryNodeAccount $AccountObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $Category
     * @property-read dxQueryNodeCategory $CategoryObject
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodeExpense extends dxQueryNode {
		protected $strTableName = 'Expense';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'Expense';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'ExpenseName':
					return new dxQueryNode('ExpenseName', 'ExpenseName', 'VarChar', $this);
				case 'ExpenseAmount':
					return new dxQueryNode('ExpenseAmount', 'ExpenseAmount', 'VarChar', $this);
				case 'ExpenseDate':
					return new dxQueryNode('ExpenseDate', 'ExpenseDate', 'Date', $this);
				case 'ExpenseUniqueId':
					return new dxQueryNode('ExpenseUniqueId', 'ExpenseUniqueId', 'VarChar', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'VarChar', $this);
				case 'Account':
					return new dxQueryNode('Account', 'Account', 'Integer', $this);
				case 'AccountObject':
					return new dxQueryNodeAccount('Account', 'AccountObject', 'Integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'Blob', $this);
				case 'Category':
					return new dxQueryNode('Category', 'Category', 'Integer', $this);
				case 'CategoryObject':
					return new dxQueryNodeCategory('Category', 'CategoryObject', 'Integer', $this);
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
     * @property-read dxQueryNode $ExpenseName
     * @property-read dxQueryNode $ExpenseAmount
     * @property-read dxQueryNode $ExpenseDate
     * @property-read dxQueryNode $ExpenseUniqueId
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $Account
     * @property-read dxQueryNodeAccount $AccountObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $Category
     * @property-read dxQueryNodeCategory $CategoryObject
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodeExpense extends dxQueryReverseReferenceNode {
		protected $strTableName = 'Expense';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'Expense';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'ExpenseName':
					return new dxQueryNode('ExpenseName', 'ExpenseName', 'string', $this);
				case 'ExpenseAmount':
					return new dxQueryNode('ExpenseAmount', 'ExpenseAmount', 'string', $this);
				case 'ExpenseDate':
					return new dxQueryNode('ExpenseDate', 'ExpenseDate', 'dxDateTime', $this);
				case 'ExpenseUniqueId':
					return new dxQueryNode('ExpenseUniqueId', 'ExpenseUniqueId', 'string', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'string', $this);
				case 'Account':
					return new dxQueryNode('Account', 'Account', 'integer', $this);
				case 'AccountObject':
					return new dxQueryNodeAccount('Account', 'AccountObject', 'integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'string', $this);
				case 'Category':
					return new dxQueryNode('Category', 'Category', 'integer', $this);
				case 'CategoryObject':
					return new dxQueryNodeCategory('Category', 'CategoryObject', 'integer', $this);
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
