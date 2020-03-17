<?php
/**
 * The abstract TicketGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the Ticket subclass which
 * extends this TicketGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the Ticket class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property string $TicketName the value for strTicketName 
 * @property string $TicketDescription the value for strTicketDescription 
 * @property dxDateTime $TicketDueDate the value for dttTicketDueDate 
 * @property string $TicketStatus the value for strTicketStatus 
 * @property string $TicketUniqueId the value for strTicketUniqueId (Unique)
 * @property integer $TicketProgress the value for intTicketProgress 
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $Account the value for intAccount 
 * @property string $SearchMetaInfo the value for strSearchMetaInfo 
 * @property integer $Category the value for intCategory 
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property Account $AccountObject the value for the Account object referenced by intAccount 
 * @property Category $CategoryObject the value for the Category object referenced by intCategory 
 * @property-read Note $_Note the value for the private _objNote (Read-Only) if set due to an expansion on the Note.Ticket reverse relationship
 * @property-read Note[] $_NoteArray the value for the private _objNoteArray (Read-Only) if set due to an ExpandAsArray on the Note.Ticket reverse relationship
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class TicketGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column Ticket.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.TicketName
     * @var string strTicketName
     */
    protected $strTicketName;
    const TicketNameMaxLength = 25;
    const TicketNameDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.TicketDescription
     * @var string strTicketDescription
     */
    protected $strTicketDescription;
    const TicketDescriptionDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.TicketDueDate
     * @var dxDateTime dttTicketDueDate
     */
    protected $dttTicketDueDate;
    const TicketDueDateDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.TicketStatus
     * @var string strTicketStatus
     */
    protected $strTicketStatus;
    const TicketStatusMaxLength = 25;
    const TicketStatusDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.TicketUniqueId
     * @var string strTicketUniqueId
     */
    protected $strTicketUniqueId;
    const TicketUniqueIdMaxLength = 25;
    const TicketUniqueIdDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.TicketProgress
     * @var integer intTicketProgress
     */
    protected $intTicketProgress;
    const TicketProgressDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.Account
     * @var integer intAccount
     */
    protected $intAccount;
    const AccountDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.SearchMetaInfo
     * @var string strSearchMetaInfo
     */
    protected $strSearchMetaInfo;
    const SearchMetaInfoDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.Category
     * @var integer intCategory
     */
    protected $intCategory;
    const CategoryDefault = null;


    /**
     * Protected member variable that maps to the database column Ticket.ObjectOwner
     * @var integer intObjectOwner
     */
    protected $intObjectOwner;
    const ObjectOwnerDefault = null;


    /**
     * Private member variable that stores a reference to a single Note object
     * (of type Note), if this Ticket object was restored with
     * an expansion on the Note association table.
     * @var Note _objNote;
     */
    private $_objNote;

    /**
     * Private member variable that stores a reference to an array of Note objects
     * (of type Note[]), if this Ticket object was restored with
     * an ExpandAsArray on the Note association table.
     * @var Note[] _objNoteArray;
     */
    private $_objNoteArray = null;

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
     * in the database column Ticket.Account.
     *
     * NOTE: Always use the AccountObject property getter to correctly retrieve this Account object.
     * (Because this class implements late binding, this variable reference MAY be null.)
     * @var Account objAccountObject
     */
    protected $objAccountObject;

    /**
     * Protected member variable that contains the object pointed by the reference
     * in the database column Ticket.Category.
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
        $this->intId = Ticket::IdDefault;
        $this->strTicketName = Ticket::TicketNameDefault;
        $this->strTicketDescription = Ticket::TicketDescriptionDefault;
        $this->dttTicketDueDate = (Ticket::TicketDueDateDefault === null)?null:new dxDateTime(Ticket::TicketDueDateDefault);
        $this->strTicketStatus = Ticket::TicketStatusDefault;
        $this->strTicketUniqueId = Ticket::TicketUniqueIdDefault;
        $this->intTicketProgress = Ticket::TicketProgressDefault;
        $this->strLastUpdated = Ticket::LastUpdatedDefault;
        $this->intAccount = Ticket::AccountDefault;
        $this->strSearchMetaInfo = Ticket::SearchMetaInfoDefault;
        $this->intCategory = Ticket::CategoryDefault;
        $this->intObjectOwner = Ticket::ObjectOwnerDefault;
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
     * Load a Ticket from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Ticket
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'Ticket', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = Ticket::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::Ticket()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all Tickets
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Ticket[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call Ticket::QueryArray to perform the LoadAll query
        try {
            return Ticket::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all Tickets
     * @return int
     */
    public static function CountAll() {
        // Call Ticket::QueryCount to perform the CountAll query
        return Ticket::QueryCount(dxQuery::All());
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
        $objDatabase = Ticket::GetDatabase();

        // Create/Build out the QueryBuilder object with Ticket-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'Ticket');

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
            Ticket::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('Ticket');

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
     * Static divblox Query method to query for a single Ticket object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return Ticket the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = Ticket::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new Ticket object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = Ticket::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
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
            return Ticket::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of Ticket objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return Ticket[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = Ticket::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return Ticket::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
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
            $strQuery = Ticket::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
     * Static divblox Query method to query for a count of Ticket objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = Ticket::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
        $objDatabase = Ticket::GetDatabase();

        $strQuery = Ticket::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/ticket', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = Ticket::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this Ticket
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'Ticket';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'TicketName', $strAliasPrefix . 'TicketName');
            $objBuilder->AddSelectItem($strTableName, 'TicketDescription', $strAliasPrefix . 'TicketDescription');
            $objBuilder->AddSelectItem($strTableName, 'TicketDueDate', $strAliasPrefix . 'TicketDueDate');
            $objBuilder->AddSelectItem($strTableName, 'TicketStatus', $strAliasPrefix . 'TicketStatus');
            $objBuilder->AddSelectItem($strTableName, 'TicketUniqueId', $strAliasPrefix . 'TicketUniqueId');
            $objBuilder->AddSelectItem($strTableName, 'TicketProgress', $strAliasPrefix . 'TicketProgress');
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
     * Instantiate a Ticket from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this Ticket::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a Ticket, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
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

            if (Ticket::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray)) {
                return false; // db row was used but no new object was created
            }
        }

        // Create a new instance of the Ticket object
        $objToReturn = new Ticket();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'TicketName';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strTicketName = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'TicketDescription';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strTicketDescription = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'TicketDueDate';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttTicketDueDate = $objDbRow->GetColumn($strAliasName, 'Date');
        $strAlias = $strAliasPrefix . 'TicketStatus';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strTicketStatus = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'TicketUniqueId';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strTicketUniqueId = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'TicketProgress';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intTicketProgress = $objDbRow->GetColumn($strAliasName, 'Integer');
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
            $strAliasPrefix = 'Ticket__';

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



        // Check for Note Virtual Binding
        $strAlias = $strAliasPrefix . 'note__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objExpansionNode = (empty($objExpansionAliasArray['note']) ? null : $objExpansionAliasArray['note']);
        $blnExpanded = ($objExpansionNode && $objExpansionNode->ExpandAsArray);
        if ($blnExpanded && null === $objToReturn->_objNoteArray)
            $objToReturn->_objNoteArray = array();
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            if ($blnExpanded) {
                $objToReturn->_objNoteArray[] = Note::InstantiateDbRow($objDbRow, $strAliasPrefix . 'note__', $objExpansionNode, null, $strColumnAliasArray);
            } elseif (is_null($objToReturn->_objNote)) {
                $objToReturn->_objNote = Note::InstantiateDbRow($objDbRow, $strAliasPrefix . 'note__', $objExpansionNode, null, $strColumnAliasArray);
            }
        }

        return $objToReturn;
    }

    /**
     * Instantiate an array of Tickets from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return Ticket[]
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
                $objItem = Ticket::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = Ticket::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single Ticket object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return Ticket next row resulting from the query
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
        return Ticket::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single Ticket object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Ticket
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return Ticket::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::Ticket()->Id, $intId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load a single Ticket object,
     * by TicketUniqueId Index(es)
     * @param string $strTicketUniqueId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Ticket
    */
    public static function LoadByTicketUniqueId($strTicketUniqueId, $objOptionalClauses = null) {
        return Ticket::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::Ticket()->TicketUniqueId, $strTicketUniqueId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load an array of Ticket objects,
     * by Account Index(es)
     * @param integer $intAccount
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Ticket[]
    */
    public static function LoadArrayByAccount($intAccount, $objOptionalClauses = null) {
        // Call Ticket::QueryArray to perform the LoadArrayByAccount query
        try {
            return Ticket::QueryArray(
                dxQuery::Equal(dxQueryN::Ticket()->Account, $intAccount),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count Tickets
     * by Account Index(es)
     * @param integer $intAccount
     * @return int
    */
    public static function CountByAccount($intAccount) {
        // Call Ticket::QueryCount to perform the CountByAccount query
        return Ticket::QueryCount(
            dxQuery::Equal(dxQueryN::Ticket()->Account, $intAccount)
        );
    }

    /**
     * Load an array of Ticket objects,
     * by Category Index(es)
     * @param integer $intCategory
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Ticket[]
    */
    public static function LoadArrayByCategory($intCategory, $objOptionalClauses = null) {
        // Call Ticket::QueryArray to perform the LoadArrayByCategory query
        try {
            return Ticket::QueryArray(
                dxQuery::Equal(dxQueryN::Ticket()->Category, $intCategory),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count Tickets
     * by Category Index(es)
     * @param integer $intCategory
     * @return int
    */
    public static function CountByCategory($intCategory) {
        // Call Ticket::QueryCount to perform the CountByCategory query
        return Ticket::QueryCount(
            dxQuery::Equal(dxQueryN::Ticket()->Category, $intCategory)
        );
    }
    ////////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Array via Many to Many)
    ////////////////////////////////////////////////////


    //////////////////////////
    // SAVE, DELETE AND RELOAD
    //////////////////////////

    /**
    * Save this Ticket
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Ticket",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = Ticket::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'Ticket';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("TicketName" => $this->strTicketName));
            $ChangedArray = array_merge($ChangedArray,array("TicketDescription" => $this->strTicketDescription));
            $ChangedArray = array_merge($ChangedArray,array("TicketDueDate" => $this->dttTicketDueDate));
            $ChangedArray = array_merge($ChangedArray,array("TicketStatus" => $this->strTicketStatus));
            $ChangedArray = array_merge($ChangedArray,array("TicketUniqueId" => $this->strTicketUniqueId));
            $ChangedArray = array_merge($ChangedArray,array("TicketProgress" => $this->intTicketProgress));
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
            if (!is_null($ExistingObj->TicketName)) {
                $ExistingValueStr = $ExistingObj->TicketName;
            }
            if ($ExistingObj->TicketName != $this->strTicketName) {
                $ChangedArray = array_merge($ChangedArray,array("TicketName" => array("Before" => $ExistingValueStr,"After" => $this->strTicketName)));
                //$ChangedArray = array_merge($ChangedArray,array("TicketName" => "From: ".$ExistingValueStr." to: ".$this->strTicketName));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->TicketDescription)) {
                $ExistingValueStr = $ExistingObj->TicketDescription;
            }
            if ($ExistingObj->TicketDescription != $this->strTicketDescription) {
                $ChangedArray = array_merge($ChangedArray,array("TicketDescription" => array("Before" => $ExistingValueStr,"After" => $this->strTicketDescription)));
                //$ChangedArray = array_merge($ChangedArray,array("TicketDescription" => "From: ".$ExistingValueStr." to: ".$this->strTicketDescription));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->TicketDueDate)) {
                $ExistingValueStr = $ExistingObj->TicketDueDate;
            }
            if ($ExistingObj->TicketDueDate != $this->dttTicketDueDate) {
                $ChangedArray = array_merge($ChangedArray,array("TicketDueDate" => array("Before" => $ExistingValueStr,"After" => $this->dttTicketDueDate)));
                //$ChangedArray = array_merge($ChangedArray,array("TicketDueDate" => "From: ".$ExistingValueStr." to: ".$this->dttTicketDueDate));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->TicketStatus)) {
                $ExistingValueStr = $ExistingObj->TicketStatus;
            }
            if ($ExistingObj->TicketStatus != $this->strTicketStatus) {
                $ChangedArray = array_merge($ChangedArray,array("TicketStatus" => array("Before" => $ExistingValueStr,"After" => $this->strTicketStatus)));
                //$ChangedArray = array_merge($ChangedArray,array("TicketStatus" => "From: ".$ExistingValueStr." to: ".$this->strTicketStatus));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->TicketUniqueId)) {
                $ExistingValueStr = $ExistingObj->TicketUniqueId;
            }
            if ($ExistingObj->TicketUniqueId != $this->strTicketUniqueId) {
                $ChangedArray = array_merge($ChangedArray,array("TicketUniqueId" => array("Before" => $ExistingValueStr,"After" => $this->strTicketUniqueId)));
                //$ChangedArray = array_merge($ChangedArray,array("TicketUniqueId" => "From: ".$ExistingValueStr." to: ".$this->strTicketUniqueId));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->TicketProgress)) {
                $ExistingValueStr = $ExistingObj->TicketProgress;
            }
            if ($ExistingObj->TicketProgress != $this->intTicketProgress) {
                $ChangedArray = array_merge($ChangedArray,array("TicketProgress" => array("Before" => $ExistingValueStr,"After" => $this->intTicketProgress)));
                //$ChangedArray = array_merge($ChangedArray,array("TicketProgress" => "From: ".$ExistingValueStr." to: ".$this->intTicketProgress));
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
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'Ticket'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `Ticket` (
							`TicketName`,
							`TicketDescription`,
							`TicketDueDate`,
							`TicketStatus`,
							`TicketUniqueId`,
							`TicketProgress`,
							`Account`,
							`SearchMetaInfo`,
							`Category`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strTicketName) . ',
							' . $objDatabase->SqlVariable($this->strTicketDescription) . ',
							' . $objDatabase->SqlVariable($this->dttTicketDueDate) . ',
							' . $objDatabase->SqlVariable($this->strTicketStatus) . ',
							' . $objDatabase->SqlVariable($this->strTicketUniqueId) . ',
							' . $objDatabase->SqlVariable($this->intTicketProgress) . ',
							' . $objDatabase->SqlVariable($this->intAccount) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intCategory) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('Ticket', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'Ticket'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `Ticket` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('Ticket');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `Ticket` SET
							`TicketName` = ' . $objDatabase->SqlVariable($this->strTicketName) . ',
							`TicketDescription` = ' . $objDatabase->SqlVariable($this->strTicketDescription) . ',
							`TicketDueDate` = ' . $objDatabase->SqlVariable($this->dttTicketDueDate) . ',
							`TicketStatus` = ' . $objDatabase->SqlVariable($this->strTicketStatus) . ',
							`TicketUniqueId` = ' . $objDatabase->SqlVariable($this->strTicketUniqueId) . ',
							`TicketProgress` = ' . $objDatabase->SqlVariable($this->intTicketProgress) . ',
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
            error_log('Could not save audit log while saving Ticket. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `Ticket` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this Ticket
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this Ticket with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Ticket",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'Ticket'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'Ticket';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("TicketName" => $this->strTicketName));
        $ChangedArray = array_merge($ChangedArray,array("TicketDescription" => $this->strTicketDescription));
        $ChangedArray = array_merge($ChangedArray,array("TicketDueDate" => $this->dttTicketDueDate));
        $ChangedArray = array_merge($ChangedArray,array("TicketStatus" => $this->strTicketStatus));
        $ChangedArray = array_merge($ChangedArray,array("TicketUniqueId" => $this->strTicketUniqueId));
        $ChangedArray = array_merge($ChangedArray,array("TicketProgress" => $this->intTicketProgress));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("Account" => $this->intAccount));
        $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
        $ChangedArray = array_merge($ChangedArray,array("Category" => $this->intCategory));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting Ticket. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `Ticket`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this Ticket ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'Ticket', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all Tickets
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `Ticket`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate Ticket table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `Ticket`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this Ticket from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved Ticket object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = Ticket::Load($this->intId);

        // Update $this's local variables to match
        $this->strTicketName = $objReloaded->strTicketName;
        $this->strTicketDescription = $objReloaded->strTicketDescription;
        $this->dttTicketDueDate = $objReloaded->dttTicketDueDate;
        $this->strTicketStatus = $objReloaded->strTicketStatus;
        $this->strTicketUniqueId = $objReloaded->strTicketUniqueId;
        $this->intTicketProgress = $objReloaded->intTicketProgress;
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

            case 'TicketName':
                /**
                 * Gets the value for strTicketName 
                 * @return string
                 */
                return $this->strTicketName;

            case 'TicketDescription':
                /**
                 * Gets the value for strTicketDescription 
                 * @return string
                 */
                return $this->strTicketDescription;

            case 'TicketDueDate':
                /**
                 * Gets the value for dttTicketDueDate 
                 * @return dxDateTime
                 */
                return $this->dttTicketDueDate;

            case 'TicketStatus':
                /**
                 * Gets the value for strTicketStatus 
                 * @return string
                 */
                return $this->strTicketStatus;

            case 'TicketUniqueId':
                /**
                 * Gets the value for strTicketUniqueId (Unique)
                 * @return string
                 */
                return $this->strTicketUniqueId;

            case 'TicketProgress':
                /**
                 * Gets the value for intTicketProgress 
                 * @return integer
                 */
                return $this->intTicketProgress;

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

            case '_Note':
                /**
                 * Gets the value for the private _objNote (Read-Only)
                 * if set due to an expansion on the Note.Ticket reverse relationship
                 * @return Note
                 */
                return $this->_objNote;

            case '_NoteArray':
                /**
                 * Gets the value for the private _objNoteArray (Read-Only)
                 * if set due to an ExpandAsArray on the Note.Ticket reverse relationship
                 * @return Note[]
                 */
                return $this->_objNoteArray;


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
            case 'TicketName':
                /**
                 * Sets the value for strTicketName 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strTicketName = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'TicketDescription':
                /**
                 * Sets the value for strTicketDescription 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strTicketDescription = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'TicketDueDate':
                /**
                 * Sets the value for dttTicketDueDate 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttTicketDueDate = dxType::Cast($mixValue, dxType::DateTime));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'TicketStatus':
                /**
                 * Sets the value for strTicketStatus 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strTicketStatus = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'TicketUniqueId':
                /**
                 * Sets the value for strTicketUniqueId (Unique)
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strTicketUniqueId = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'TicketProgress':
                /**
                 * Sets the value for intTicketProgress 
                 * @param integer $mixValue
                 * @return integer
                 */
                try {
                    return ($this->intTicketProgress = dxType::Cast($mixValue, dxType::Integer));
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
                        throw new dxCallerException('Unable to set an unsaved AccountObject for this Ticket');

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
                        throw new dxCallerException('Unable to set an unsaved CategoryObject for this Ticket');

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



    // Related Objects' Methods for Note
    //-------------------------------------------------------------------

    /**
     * Gets all associated Notes as an array of Note objects
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return Note[]
    */
    public function GetNoteArray($objOptionalClauses = null) {
        if ((is_null($this->intId)))
            return array();

        try {
            return Note::LoadArrayByTicket($this->intId, $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Counts all associated Notes
     * @return int
    */
    public function CountNotes() {
        if ((is_null($this->intId)))
            return 0;

        return Note::CountByTicket($this->intId);
    }

    /**
     * Associates a Note
     * @param Note $objNote
     * @return void
    */
    public function AssociateNote(Note $objNote) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call AssociateNote on this unsaved Ticket.');
        if ((is_null($objNote->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call AssociateNote on this Ticket with an unsaved Note.');

        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `Note`
            SET
                `Ticket` = ' . $objDatabase->SqlVariable($this->intId) . '
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objNote->Id) . '
        ');
    }

    /**
     * Unassociates a Note
     * @param Note $objNote
     * @return void
    */
    public function UnassociateNote(Note $objNote) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateNote on this unsaved Ticket.');
        if ((is_null($objNote->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateNote on this Ticket with an unsaved Note.');

        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `Note`
            SET
                `Ticket` = null
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objNote->Id) . ' AND
                `Ticket` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Unassociates all Notes
     * @return void
    */
    public function UnassociateAllNotes() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateNote on this unsaved Ticket.');

        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `Note`
            SET
                `Ticket` = null
            WHERE
                `Ticket` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Deletes an associated Note
     * @param Note $objNote
     * @return void
    */
    public function DeleteAssociatedNote(Note $objNote) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateNote on this unsaved Ticket.');
        if ((is_null($objNote->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateNote on this Ticket with an unsaved Note.');

        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `Note`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objNote->Id) . ' AND
                `Ticket` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Deletes all associated Notes
     * @return void
    */
    public function DeleteAllNotes() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociateNote on this unsaved Ticket.');

        // Get the Database Object for this Class
        $objDatabase = Ticket::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `Note`
            WHERE
                `Ticket` = ' . $objDatabase->SqlVariable($this->intId) . '
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
        return "Ticket";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[Ticket::GetDatabaseIndex()]->Database;
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
        $strToReturn = '<complexType name="Ticket"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="TicketName" type="xsd:string"/>';
        $strToReturn .= '<element name="TicketDescription" type="xsd:string"/>';
        $strToReturn .= '<element name="TicketDueDate" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="TicketStatus" type="xsd:string"/>';
        $strToReturn .= '<element name="TicketUniqueId" type="xsd:string"/>';
        $strToReturn .= '<element name="TicketProgress" type="xsd:int"/>';
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
        if (!array_key_exists('Ticket', $strComplexTypeArray)) {
            $strComplexTypeArray['Ticket'] = Ticket::GetSoapComplexTypeXml();
            Account::AlterSoapComplexTypeArray($strComplexTypeArray);
            Category::AlterSoapComplexTypeArray($strComplexTypeArray);
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, Ticket::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new Ticket();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'TicketName'))
            $objToReturn->strTicketName = $objSoapObject->TicketName;
        if (property_exists($objSoapObject, 'TicketDescription'))
            $objToReturn->strTicketDescription = $objSoapObject->TicketDescription;
        if (property_exists($objSoapObject, 'TicketDueDate'))
            $objToReturn->dttTicketDueDate = new dxDateTime($objSoapObject->TicketDueDate);
        if (property_exists($objSoapObject, 'TicketStatus'))
            $objToReturn->strTicketStatus = $objSoapObject->TicketStatus;
        if (property_exists($objSoapObject, 'TicketUniqueId'))
            $objToReturn->strTicketUniqueId = $objSoapObject->TicketUniqueId;
        if (property_exists($objSoapObject, 'TicketProgress'))
            $objToReturn->intTicketProgress = $objSoapObject->TicketProgress;
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
            array_push($objArrayToReturn, Ticket::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttTicketDueDate)
            $objObject->dttTicketDueDate = $objObject->dttTicketDueDate->qFormat(dxDateTime::FormatSoap);
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
        $iArray['TicketName'] = $this->strTicketName;
        $iArray['TicketDescription'] = $this->strTicketDescription;
        $iArray['TicketDueDate'] = $this->dttTicketDueDate;
        $iArray['TicketStatus'] = $this->strTicketStatus;
        $iArray['TicketUniqueId'] = $this->strTicketUniqueId;
        $iArray['TicketProgress'] = $this->intTicketProgress;
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
     * @property-read dxQueryNode $TicketName
     * @property-read dxQueryNode $TicketDescription
     * @property-read dxQueryNode $TicketDueDate
     * @property-read dxQueryNode $TicketStatus
     * @property-read dxQueryNode $TicketUniqueId
     * @property-read dxQueryNode $TicketProgress
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $Account
     * @property-read dxQueryNodeAccount $AccountObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $Category
     * @property-read dxQueryNodeCategory $CategoryObject
     * @property-read dxQueryNode $ObjectOwner
     *
     *
     * @property-read dxQueryReverseReferenceNodeNote $Note

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodeTicket extends dxQueryNode {
		protected $strTableName = 'Ticket';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'Ticket';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'TicketName':
					return new dxQueryNode('TicketName', 'TicketName', 'VarChar', $this);
				case 'TicketDescription':
					return new dxQueryNode('TicketDescription', 'TicketDescription', 'Blob', $this);
				case 'TicketDueDate':
					return new dxQueryNode('TicketDueDate', 'TicketDueDate', 'Date', $this);
				case 'TicketStatus':
					return new dxQueryNode('TicketStatus', 'TicketStatus', 'VarChar', $this);
				case 'TicketUniqueId':
					return new dxQueryNode('TicketUniqueId', 'TicketUniqueId', 'VarChar', $this);
				case 'TicketProgress':
					return new dxQueryNode('TicketProgress', 'TicketProgress', 'Integer', $this);
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
				case 'Note':
					return new dxQueryReverseReferenceNodeNote($this, 'note', 'reverse_reference', 'Ticket', 'Note');

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
     * @property-read dxQueryNode $TicketName
     * @property-read dxQueryNode $TicketDescription
     * @property-read dxQueryNode $TicketDueDate
     * @property-read dxQueryNode $TicketStatus
     * @property-read dxQueryNode $TicketUniqueId
     * @property-read dxQueryNode $TicketProgress
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $Account
     * @property-read dxQueryNodeAccount $AccountObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $Category
     * @property-read dxQueryNodeCategory $CategoryObject
     * @property-read dxQueryNode $ObjectOwner
     *
     *
     * @property-read dxQueryReverseReferenceNodeNote $Note

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodeTicket extends dxQueryReverseReferenceNode {
		protected $strTableName = 'Ticket';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'Ticket';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'TicketName':
					return new dxQueryNode('TicketName', 'TicketName', 'string', $this);
				case 'TicketDescription':
					return new dxQueryNode('TicketDescription', 'TicketDescription', 'string', $this);
				case 'TicketDueDate':
					return new dxQueryNode('TicketDueDate', 'TicketDueDate', 'dxDateTime', $this);
				case 'TicketStatus':
					return new dxQueryNode('TicketStatus', 'TicketStatus', 'string', $this);
				case 'TicketUniqueId':
					return new dxQueryNode('TicketUniqueId', 'TicketUniqueId', 'string', $this);
				case 'TicketProgress':
					return new dxQueryNode('TicketProgress', 'TicketProgress', 'integer', $this);
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
				case 'Note':
					return new dxQueryReverseReferenceNodeNote($this, 'note', 'reverse_reference', 'Ticket', 'Note');

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
