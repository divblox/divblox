<?php
/**
 * The abstract ClientAuthenticationTokenGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the ClientAuthenticationToken subclass which
 * extends this ClientAuthenticationTokenGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the ClientAuthenticationToken class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property string $Token the value for strToken (Unique)
 * @property dxDateTime $UpdateDateTime the value for dttUpdateDateTime 
 * @property string $ExpiredToken the value for strExpiredToken (Unique)
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $ClientConnection the value for intClientConnection 
 * @property string $SearchMetaInfo the value for strSearchMetaInfo 
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property ClientConnection $ClientConnectionObject the value for the ClientConnection object referenced by intClientConnection 
 * @property-read PushRegistration $_PushRegistration the value for the private _objPushRegistration (Read-Only) if set due to an expansion on the PushRegistration.ClientAuthenticationToken reverse relationship
 * @property-read PushRegistration[] $_PushRegistrationArray the value for the private _objPushRegistrationArray (Read-Only) if set due to an ExpandAsArray on the PushRegistration.ClientAuthenticationToken reverse relationship
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class ClientAuthenticationTokenGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column ClientAuthenticationToken.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column ClientAuthenticationToken.Token
     * @var string strToken
     */
    protected $strToken;
    const TokenMaxLength = 50;
    const TokenDefault = null;


    /**
     * Protected member variable that maps to the database column ClientAuthenticationToken.UpdateDateTime
     * @var dxDateTime dttUpdateDateTime
     */
    protected $dttUpdateDateTime;
    const UpdateDateTimeDefault = null;


    /**
     * Protected member variable that maps to the database column ClientAuthenticationToken.ExpiredToken
     * @var string strExpiredToken
     */
    protected $strExpiredToken;
    const ExpiredTokenMaxLength = 50;
    const ExpiredTokenDefault = null;


    /**
     * Protected member variable that maps to the database column ClientAuthenticationToken.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column ClientAuthenticationToken.ClientConnection
     * @var integer intClientConnection
     */
    protected $intClientConnection;
    const ClientConnectionDefault = null;


    /**
     * Protected member variable that maps to the database column ClientAuthenticationToken.SearchMetaInfo
     * @var string strSearchMetaInfo
     */
    protected $strSearchMetaInfo;
    const SearchMetaInfoDefault = null;


    /**
     * Protected member variable that maps to the database column ClientAuthenticationToken.ObjectOwner
     * @var integer intObjectOwner
     */
    protected $intObjectOwner;
    const ObjectOwnerDefault = null;


    /**
     * Private member variable that stores a reference to a single PushRegistration object
     * (of type PushRegistration), if this ClientAuthenticationToken object was restored with
     * an expansion on the PushRegistration association table.
     * @var PushRegistration _objPushRegistration;
     */
    private $_objPushRegistration;

    /**
     * Private member variable that stores a reference to an array of PushRegistration objects
     * (of type PushRegistration[]), if this ClientAuthenticationToken object was restored with
     * an ExpandAsArray on the PushRegistration association table.
     * @var PushRegistration[] _objPushRegistrationArray;
     */
    private $_objPushRegistrationArray = null;

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
     * in the database column ClientAuthenticationToken.ClientConnection.
     *
     * NOTE: Always use the ClientConnectionObject property getter to correctly retrieve this ClientConnection object.
     * (Because this class implements late binding, this variable reference MAY be null.)
     * @var ClientConnection objClientConnectionObject
     */
    protected $objClientConnectionObject;


    /**
     * Initialize each property with default values from database definition
     */
    public function Initialize() {
        $this->intId = ClientAuthenticationToken::IdDefault;
        $this->strToken = ClientAuthenticationToken::TokenDefault;
        $this->dttUpdateDateTime = (ClientAuthenticationToken::UpdateDateTimeDefault === null)?null:new dxDateTime(ClientAuthenticationToken::UpdateDateTimeDefault);
        $this->strExpiredToken = ClientAuthenticationToken::ExpiredTokenDefault;
        $this->strLastUpdated = ClientAuthenticationToken::LastUpdatedDefault;
        $this->intClientConnection = ClientAuthenticationToken::ClientConnectionDefault;
        $this->strSearchMetaInfo = ClientAuthenticationToken::SearchMetaInfoDefault;
        $this->intObjectOwner = ClientAuthenticationToken::ObjectOwnerDefault;
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
     * Load a ClientAuthenticationToken from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ClientAuthenticationToken
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'ClientAuthenticationToken', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = ClientAuthenticationToken::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::ClientAuthenticationToken()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all ClientAuthenticationTokens
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ClientAuthenticationToken[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call ClientAuthenticationToken::QueryArray to perform the LoadAll query
        try {
            return ClientAuthenticationToken::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all ClientAuthenticationTokens
     * @return int
     */
    public static function CountAll() {
        // Call ClientAuthenticationToken::QueryCount to perform the CountAll query
        return ClientAuthenticationToken::QueryCount(dxQuery::All());
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
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Create/Build out the QueryBuilder object with ClientAuthenticationToken-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'ClientAuthenticationToken');

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
            ClientAuthenticationToken::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('ClientAuthenticationToken');

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
     * Static divblox Query method to query for a single ClientAuthenticationToken object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return ClientAuthenticationToken the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = ClientAuthenticationToken::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new ClientAuthenticationToken object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = ClientAuthenticationToken::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
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
            return ClientAuthenticationToken::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of ClientAuthenticationToken objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return ClientAuthenticationToken[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = ClientAuthenticationToken::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return ClientAuthenticationToken::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
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
            $strQuery = ClientAuthenticationToken::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
     * Static divblox Query method to query for a count of ClientAuthenticationToken objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = ClientAuthenticationToken::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        $strQuery = ClientAuthenticationToken::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/clientauthenticationtoken', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = ClientAuthenticationToken::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this ClientAuthenticationToken
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'ClientAuthenticationToken';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'Token', $strAliasPrefix . 'Token');
            $objBuilder->AddSelectItem($strTableName, 'UpdateDateTime', $strAliasPrefix . 'UpdateDateTime');
            $objBuilder->AddSelectItem($strTableName, 'ExpiredToken', $strAliasPrefix . 'ExpiredToken');
            $objBuilder->AddSelectItem($strTableName, 'LastUpdated', $strAliasPrefix . 'LastUpdated');
            $objBuilder->AddSelectItem($strTableName, 'ClientConnection', $strAliasPrefix . 'ClientConnection');
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
     * Instantiate a ClientAuthenticationToken from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this ClientAuthenticationToken::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a ClientAuthenticationToken, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
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

            if (ClientAuthenticationToken::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray)) {
                return false; // db row was used but no new object was created
            }
        }

        // Create a new instance of the ClientAuthenticationToken object
        $objToReturn = new ClientAuthenticationToken();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'Token';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strToken = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'UpdateDateTime';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttUpdateDateTime = $objDbRow->GetColumn($strAliasName, 'DateTime');
        $strAlias = $strAliasPrefix . 'ExpiredToken';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strExpiredToken = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'LastUpdated';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strLastUpdated = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'ClientConnection';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intClientConnection = $objDbRow->GetColumn($strAliasName, 'Integer');
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
            $strAliasPrefix = 'ClientAuthenticationToken__';

        // Check for ClientConnectionObject Early Binding
        $strAlias = $strAliasPrefix . 'ClientConnection__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            $objExpansionNode = (empty($objExpansionAliasArray['ClientConnection']) ? null : $objExpansionAliasArray['ClientConnection']);
            $objToReturn->objClientConnectionObject = ClientConnection::InstantiateDbRow($objDbRow, $strAliasPrefix . 'ClientConnection__', $objExpansionNode, null, $strColumnAliasArray);
        }



        // Check for PushRegistration Virtual Binding
        $strAlias = $strAliasPrefix . 'pushregistration__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objExpansionNode = (empty($objExpansionAliasArray['pushregistration']) ? null : $objExpansionAliasArray['pushregistration']);
        $blnExpanded = ($objExpansionNode && $objExpansionNode->ExpandAsArray);
        if ($blnExpanded && null === $objToReturn->_objPushRegistrationArray)
            $objToReturn->_objPushRegistrationArray = array();
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            if ($blnExpanded) {
                $objToReturn->_objPushRegistrationArray[] = PushRegistration::InstantiateDbRow($objDbRow, $strAliasPrefix . 'pushregistration__', $objExpansionNode, null, $strColumnAliasArray);
            } elseif (is_null($objToReturn->_objPushRegistration)) {
                $objToReturn->_objPushRegistration = PushRegistration::InstantiateDbRow($objDbRow, $strAliasPrefix . 'pushregistration__', $objExpansionNode, null, $strColumnAliasArray);
            }
        }

        return $objToReturn;
    }

    /**
     * Instantiate an array of ClientAuthenticationTokens from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return ClientAuthenticationToken[]
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
                $objItem = ClientAuthenticationToken::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = ClientAuthenticationToken::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single ClientAuthenticationToken object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return ClientAuthenticationToken next row resulting from the query
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
        return ClientAuthenticationToken::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single ClientAuthenticationToken object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ClientAuthenticationToken
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return ClientAuthenticationToken::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::ClientAuthenticationToken()->Id, $intId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load a single ClientAuthenticationToken object,
     * by Token Index(es)
     * @param string $strToken
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ClientAuthenticationToken
    */
    public static function LoadByToken($strToken, $objOptionalClauses = null) {
        return ClientAuthenticationToken::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::ClientAuthenticationToken()->Token, $strToken)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load a single ClientAuthenticationToken object,
     * by ExpiredToken Index(es)
     * @param string $strExpiredToken
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ClientAuthenticationToken
    */
    public static function LoadByExpiredToken($strExpiredToken, $objOptionalClauses = null) {
        return ClientAuthenticationToken::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::ClientAuthenticationToken()->ExpiredToken, $strExpiredToken)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load an array of ClientAuthenticationToken objects,
     * by ClientConnection Index(es)
     * @param integer $intClientConnection
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return ClientAuthenticationToken[]
    */
    public static function LoadArrayByClientConnection($intClientConnection, $objOptionalClauses = null) {
        // Call ClientAuthenticationToken::QueryArray to perform the LoadArrayByClientConnection query
        try {
            return ClientAuthenticationToken::QueryArray(
                dxQuery::Equal(dxQueryN::ClientAuthenticationToken()->ClientConnection, $intClientConnection),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count ClientAuthenticationTokens
     * by ClientConnection Index(es)
     * @param integer $intClientConnection
     * @return int
    */
    public static function CountByClientConnection($intClientConnection) {
        // Call ClientAuthenticationToken::QueryCount to perform the CountByClientConnection query
        return ClientAuthenticationToken::QueryCount(
            dxQuery::Equal(dxQueryN::ClientAuthenticationToken()->ClientConnection, $intClientConnection)
        );
    }
    ////////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Array via Many to Many)
    ////////////////////////////////////////////////////


    //////////////////////////
    // SAVE, DELETE AND RELOAD
    //////////////////////////

    /**
    * Save this ClientAuthenticationToken
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ClientAuthenticationToken",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = ClientAuthenticationToken::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'ClientAuthenticationToken';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("Token" => $this->strToken));
            $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => $this->dttUpdateDateTime));
            $ChangedArray = array_merge($ChangedArray,array("ExpiredToken" => $this->strExpiredToken));
            $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
            $ChangedArray = array_merge($ChangedArray,array("ClientConnection" => $this->intClientConnection));
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
            if (!is_null($ExistingObj->Token)) {
                $ExistingValueStr = $ExistingObj->Token;
            }
            if ($ExistingObj->Token != $this->strToken) {
                $ChangedArray = array_merge($ChangedArray,array("Token" => array("Before" => $ExistingValueStr,"After" => $this->strToken)));
                //$ChangedArray = array_merge($ChangedArray,array("Token" => "From: ".$ExistingValueStr." to: ".$this->strToken));
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
            if (!is_null($ExistingObj->ExpiredToken)) {
                $ExistingValueStr = $ExistingObj->ExpiredToken;
            }
            if ($ExistingObj->ExpiredToken != $this->strExpiredToken) {
                $ChangedArray = array_merge($ChangedArray,array("ExpiredToken" => array("Before" => $ExistingValueStr,"After" => $this->strExpiredToken)));
                //$ChangedArray = array_merge($ChangedArray,array("ExpiredToken" => "From: ".$ExistingValueStr." to: ".$this->strExpiredToken));
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
            if (!is_null($ExistingObj->ClientConnection)) {
                $ExistingValueStr = $ExistingObj->ClientConnection;
            }
            if ($ExistingObj->ClientConnection != $this->intClientConnection) {
                $ChangedArray = array_merge($ChangedArray,array("ClientConnection" => array("Before" => $ExistingValueStr,"After" => $this->intClientConnection)));
                //$ChangedArray = array_merge($ChangedArray,array("ClientConnection" => "From: ".$ExistingValueStr." to: ".$this->intClientConnection));
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
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'ClientAuthenticationToken'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `ClientAuthenticationToken` (
							`Token`,
							`UpdateDateTime`,
							`ExpiredToken`,
							`ClientConnection`,
							`SearchMetaInfo`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strToken) . ',
							' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							' . $objDatabase->SqlVariable($this->strExpiredToken) . ',
							' . $objDatabase->SqlVariable($this->intClientConnection) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('ClientAuthenticationToken', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'ClientAuthenticationToken'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `ClientAuthenticationToken` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('ClientAuthenticationToken');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `ClientAuthenticationToken` SET
							`Token` = ' . $objDatabase->SqlVariable($this->strToken) . ',
							`UpdateDateTime` = ' . $objDatabase->SqlVariable($this->dttUpdateDateTime) . ',
							`ExpiredToken` = ' . $objDatabase->SqlVariable($this->strExpiredToken) . ',
							`ClientConnection` = ' . $objDatabase->SqlVariable($this->intClientConnection) . ',
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
            error_log('Could not save audit log while saving ClientAuthenticationToken. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `ClientAuthenticationToken` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this ClientAuthenticationToken
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this ClientAuthenticationToken with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"ClientAuthenticationToken",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'ClientAuthenticationToken'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'ClientAuthenticationToken';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("Token" => $this->strToken));
        $ChangedArray = array_merge($ChangedArray,array("UpdateDateTime" => $this->dttUpdateDateTime));
        $ChangedArray = array_merge($ChangedArray,array("ExpiredToken" => $this->strExpiredToken));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ClientConnection" => $this->intClientConnection));
        $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting ClientAuthenticationToken. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `ClientAuthenticationToken`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this ClientAuthenticationToken ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'ClientAuthenticationToken', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all ClientAuthenticationTokens
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `ClientAuthenticationToken`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate ClientAuthenticationToken table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `ClientAuthenticationToken`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this ClientAuthenticationToken from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved ClientAuthenticationToken object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = ClientAuthenticationToken::Load($this->intId);

        // Update $this's local variables to match
        $this->strToken = $objReloaded->strToken;
        $this->dttUpdateDateTime = $objReloaded->dttUpdateDateTime;
        $this->strExpiredToken = $objReloaded->strExpiredToken;
        $this->strLastUpdated = $objReloaded->strLastUpdated;
        $this->ClientConnection = $objReloaded->ClientConnection;
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

            case 'Token':
                /**
                 * Gets the value for strToken (Unique)
                 * @return string
                 */
                return $this->strToken;

            case 'UpdateDateTime':
                /**
                 * Gets the value for dttUpdateDateTime 
                 * @return dxDateTime
                 */
                return $this->dttUpdateDateTime;

            case 'ExpiredToken':
                /**
                 * Gets the value for strExpiredToken (Unique)
                 * @return string
                 */
                return $this->strExpiredToken;

            case 'LastUpdated':
                /**
                 * Gets the value for strLastUpdated (Read-Only Timestamp)
                 * @return string
                 */
                return $this->strLastUpdated;

            case 'ClientConnection':
                /**
                 * Gets the value for intClientConnection 
                 * @return integer
                 */
                return $this->intClientConnection;

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
            case 'ClientConnectionObject':
                /**
                 * Gets the value for the ClientConnection object referenced by intClientConnection 
                 * @return ClientConnection
                 */
                try {
                    if ((!$this->objClientConnectionObject) && (!is_null($this->intClientConnection)))
                        $this->objClientConnectionObject = ClientConnection::Load($this->intClientConnection);
                    return $this->objClientConnectionObject;
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }


            ////////////////////////////
            // Virtual Object References (Many to Many and Reverse References)
            // (If restored via a "Many-to" expansion)
            ////////////////////////////

            case '_PushRegistration':
                /**
                 * Gets the value for the private _objPushRegistration (Read-Only)
                 * if set due to an expansion on the PushRegistration.ClientAuthenticationToken reverse relationship
                 * @return PushRegistration
                 */
                return $this->_objPushRegistration;

            case '_PushRegistrationArray':
                /**
                 * Gets the value for the private _objPushRegistrationArray (Read-Only)
                 * if set due to an ExpandAsArray on the PushRegistration.ClientAuthenticationToken reverse relationship
                 * @return PushRegistration[]
                 */
                return $this->_objPushRegistrationArray;


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
            case 'Token':
                /**
                 * Sets the value for strToken (Unique)
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strToken = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

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

            case 'ExpiredToken':
                /**
                 * Sets the value for strExpiredToken (Unique)
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strExpiredToken = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ClientConnection':
                /**
                 * Sets the value for intClientConnection 
                 * @param integer $mixValue
                 * @return integer
                 */
                try {
                    $this->objClientConnectionObject = null;
                    return ($this->intClientConnection = dxType::Cast($mixValue, dxType::Integer));
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
            case 'ClientConnectionObject':
                /**
                 * Sets the value for the ClientConnection object referenced by intClientConnection 
                 * @param ClientConnection $mixValue
                 * @return ClientConnection
                 */
                if (is_null($mixValue)) {
                    $this->intClientConnection = null;
                    $this->objClientConnectionObject = null;
                    return null;
                } else {
                    // Make sure $mixValue actually is a ClientConnection object
                    try {
                        $mixValue = dxType::Cast($mixValue, 'ClientConnection');
                    } catch (dxInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                    // Make sure $mixValue is a SAVED ClientConnection object
                    if (is_null($mixValue->Id))
                        throw new dxCallerException('Unable to set an unsaved ClientConnectionObject for this ClientAuthenticationToken');

                    // Update Local Member Variables
                    $this->objClientConnectionObject = $mixValue;
                    $this->intClientConnection = $mixValue->Id;

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



    // Related Objects' Methods for PushRegistration
    //-------------------------------------------------------------------

    /**
     * Gets all associated PushRegistrations as an array of PushRegistration objects
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return PushRegistration[]
    */
    public function GetPushRegistrationArray($objOptionalClauses = null) {
        if ((is_null($this->intId)))
            return array();

        try {
            return PushRegistration::LoadArrayByClientAuthenticationToken($this->intId, $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Counts all associated PushRegistrations
     * @return int
    */
    public function CountPushRegistrations() {
        if ((is_null($this->intId)))
            return 0;

        return PushRegistration::CountByClientAuthenticationToken($this->intId);
    }

    /**
     * Associates a PushRegistration
     * @param PushRegistration $objPushRegistration
     * @return void
    */
    public function AssociatePushRegistration(PushRegistration $objPushRegistration) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call AssociatePushRegistration on this unsaved ClientAuthenticationToken.');
        if ((is_null($objPushRegistration->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call AssociatePushRegistration on this ClientAuthenticationToken with an unsaved PushRegistration.');

        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `PushRegistration`
            SET
                `ClientAuthenticationToken` = ' . $objDatabase->SqlVariable($this->intId) . '
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objPushRegistration->Id) . '
        ');
    }

    /**
     * Unassociates a PushRegistration
     * @param PushRegistration $objPushRegistration
     * @return void
    */
    public function UnassociatePushRegistration(PushRegistration $objPushRegistration) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociatePushRegistration on this unsaved ClientAuthenticationToken.');
        if ((is_null($objPushRegistration->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociatePushRegistration on this ClientAuthenticationToken with an unsaved PushRegistration.');

        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `PushRegistration`
            SET
                `ClientAuthenticationToken` = null
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objPushRegistration->Id) . ' AND
                `ClientAuthenticationToken` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Unassociates all PushRegistrations
     * @return void
    */
    public function UnassociateAllPushRegistrations() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociatePushRegistration on this unsaved ClientAuthenticationToken.');

        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE
                `PushRegistration`
            SET
                `ClientAuthenticationToken` = null
            WHERE
                `ClientAuthenticationToken` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Deletes an associated PushRegistration
     * @param PushRegistration $objPushRegistration
     * @return void
    */
    public function DeleteAssociatedPushRegistration(PushRegistration $objPushRegistration) {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociatePushRegistration on this unsaved ClientAuthenticationToken.');
        if ((is_null($objPushRegistration->Id)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociatePushRegistration on this ClientAuthenticationToken with an unsaved PushRegistration.');

        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `PushRegistration`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($objPushRegistration->Id) . ' AND
                `ClientAuthenticationToken` = ' . $objDatabase->SqlVariable($this->intId) . '
        ');
    }

    /**
     * Deletes all associated PushRegistrations
     * @return void
    */
    public function DeleteAllPushRegistrations() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Unable to call UnassociatePushRegistration on this unsaved ClientAuthenticationToken.');

        // Get the Database Object for this Class
        $objDatabase = ClientAuthenticationToken::GetDatabase();

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `PushRegistration`
            WHERE
                `ClientAuthenticationToken` = ' . $objDatabase->SqlVariable($this->intId) . '
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
        return "ClientAuthenticationToken";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[ClientAuthenticationToken::GetDatabaseIndex()]->Database;
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
        $strToReturn = '<complexType name="ClientAuthenticationToken"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="Token" type="xsd:string"/>';
        $strToReturn .= '<element name="UpdateDateTime" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="ExpiredToken" type="xsd:string"/>';
        $strToReturn .= '<element name="LastUpdated" type="xsd:string"/>';
        $strToReturn .= '<element name="ClientConnectionObject" type="xsd1:ClientConnection"/>';
        $strToReturn .= '<element name="SearchMetaInfo" type="xsd:string"/>';
        $strToReturn .= '<element name="ObjectOwner" type="xsd:int"/>';
        $strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
        $strToReturn .= '</sequence></complexType>';
        return $strToReturn;
    }

    public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
        if (!array_key_exists('ClientAuthenticationToken', $strComplexTypeArray)) {
            $strComplexTypeArray['ClientAuthenticationToken'] = ClientAuthenticationToken::GetSoapComplexTypeXml();
            ClientConnection::AlterSoapComplexTypeArray($strComplexTypeArray);
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, ClientAuthenticationToken::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new ClientAuthenticationToken();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'Token'))
            $objToReturn->strToken = $objSoapObject->Token;
        if (property_exists($objSoapObject, 'UpdateDateTime'))
            $objToReturn->dttUpdateDateTime = new dxDateTime($objSoapObject->UpdateDateTime);
        if (property_exists($objSoapObject, 'ExpiredToken'))
            $objToReturn->strExpiredToken = $objSoapObject->ExpiredToken;
        if (property_exists($objSoapObject, 'LastUpdated'))
            $objToReturn->strLastUpdated = $objSoapObject->LastUpdated;
        if ((property_exists($objSoapObject, 'ClientConnectionObject')) &&
            ($objSoapObject->ClientConnectionObject))
            $objToReturn->ClientConnectionObject = ClientConnection::GetObjectFromSoapObject($objSoapObject->ClientConnectionObject);
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
            array_push($objArrayToReturn, ClientAuthenticationToken::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttUpdateDateTime)
            $objObject->dttUpdateDateTime = $objObject->dttUpdateDateTime->qFormat(dxDateTime::FormatSoap);
        if ($objObject->objClientConnectionObject)
            $objObject->objClientConnectionObject = ClientConnection::GetSoapObjectFromObject($objObject->objClientConnectionObject, false);
        else if (!$blnBindRelatedObjects)
            $objObject->intClientConnection = null;
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
        $iArray['Token'] = $this->strToken;
        $iArray['UpdateDateTime'] = $this->dttUpdateDateTime;
        $iArray['ExpiredToken'] = $this->strExpiredToken;
        $iArray['LastUpdated'] = $this->strLastUpdated;
        $iArray['ClientConnection'] = $this->intClientConnection;
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
     * @property-read dxQueryNode $Token
     * @property-read dxQueryNode $UpdateDateTime
     * @property-read dxQueryNode $ExpiredToken
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ClientConnection
     * @property-read dxQueryNodeClientConnection $ClientConnectionObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $ObjectOwner
     *
     *
     * @property-read dxQueryReverseReferenceNodePushRegistration $PushRegistration

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodeClientAuthenticationToken extends dxQueryNode {
		protected $strTableName = 'ClientAuthenticationToken';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'ClientAuthenticationToken';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'Token':
					return new dxQueryNode('Token', 'Token', 'VarChar', $this);
				case 'UpdateDateTime':
					return new dxQueryNode('UpdateDateTime', 'UpdateDateTime', 'DateTime', $this);
				case 'ExpiredToken':
					return new dxQueryNode('ExpiredToken', 'ExpiredToken', 'VarChar', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'VarChar', $this);
				case 'ClientConnection':
					return new dxQueryNode('ClientConnection', 'ClientConnection', 'Integer', $this);
				case 'ClientConnectionObject':
					return new dxQueryNodeClientConnection('ClientConnection', 'ClientConnectionObject', 'Integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'Blob', $this);
				case 'ObjectOwner':
					return new dxQueryNode('ObjectOwner', 'ObjectOwner', 'Integer', $this);
				case 'PushRegistration':
					return new dxQueryReverseReferenceNodePushRegistration($this, 'pushregistration', 'reverse_reference', 'ClientAuthenticationToken', 'PushRegistration');

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
     * @property-read dxQueryNode $Token
     * @property-read dxQueryNode $UpdateDateTime
     * @property-read dxQueryNode $ExpiredToken
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ClientConnection
     * @property-read dxQueryNodeClientConnection $ClientConnectionObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $ObjectOwner
     *
     *
     * @property-read dxQueryReverseReferenceNodePushRegistration $PushRegistration

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodeClientAuthenticationToken extends dxQueryReverseReferenceNode {
		protected $strTableName = 'ClientAuthenticationToken';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'ClientAuthenticationToken';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'Token':
					return new dxQueryNode('Token', 'Token', 'string', $this);
				case 'UpdateDateTime':
					return new dxQueryNode('UpdateDateTime', 'UpdateDateTime', 'dxDateTime', $this);
				case 'ExpiredToken':
					return new dxQueryNode('ExpiredToken', 'ExpiredToken', 'string', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'string', $this);
				case 'ClientConnection':
					return new dxQueryNode('ClientConnection', 'ClientConnection', 'integer', $this);
				case 'ClientConnectionObject':
					return new dxQueryNodeClientConnection('ClientConnection', 'ClientConnectionObject', 'integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'string', $this);
				case 'ObjectOwner':
					return new dxQueryNode('ObjectOwner', 'ObjectOwner', 'integer', $this);
				case 'PushRegistration':
					return new dxQueryReverseReferenceNodePushRegistration($this, 'pushregistration', 'reverse_reference', 'ClientAuthenticationToken', 'PushRegistration');

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
