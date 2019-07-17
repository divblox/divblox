<?php
/**
 * The abstract PushRegistrationGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the PushRegistration subclass which
 * extends this PushRegistrationGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the PushRegistration class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property string $RegistrationId the value for strRegistrationId 
 * @property string $DeviceUuid the value for strDeviceUuid 
 * @property string $DevicePlatform the value for strDevicePlatform 
 * @property string $DeviceOs the value for strDeviceOs 
 * @property dxDateTime $RegistrationDateTime the value for dttRegistrationDateTime 
 * @property string $RegistrationStatus the value for strRegistrationStatus 
 * @property string $InternalUniqueId the value for strInternalUniqueId (Unique)
 * @property integer $ClientAuthenticationToken the value for intClientAuthenticationToken 
 * @property string $SearchMetaInfo the value for strSearchMetaInfo 
 * @property integer $Account the value for intAccount 
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property ClientAuthenticationToken $ClientAuthenticationTokenObject the value for the ClientAuthenticationToken object referenced by intClientAuthenticationToken 
 * @property Account $AccountObject the value for the Account object referenced by intAccount 
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class PushRegistrationGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column PushRegistration.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.RegistrationId
     * @var string strRegistrationId
     */
    protected $strRegistrationId;
    const RegistrationIdDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.DeviceUuid
     * @var string strDeviceUuid
     */
    protected $strDeviceUuid;
    const DeviceUuidMaxLength = 150;
    const DeviceUuidDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.DevicePlatform
     * @var string strDevicePlatform
     */
    protected $strDevicePlatform;
    const DevicePlatformMaxLength = 150;
    const DevicePlatformDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.DeviceOs
     * @var string strDeviceOs
     */
    protected $strDeviceOs;
    const DeviceOsMaxLength = 50;
    const DeviceOsDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.RegistrationDateTime
     * @var dxDateTime dttRegistrationDateTime
     */
    protected $dttRegistrationDateTime;
    const RegistrationDateTimeDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.RegistrationStatus
     * @var string strRegistrationStatus
     */
    protected $strRegistrationStatus;
    const RegistrationStatusMaxLength = 50;
    const RegistrationStatusDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.InternalUniqueId
     * @var string strInternalUniqueId
     */
    protected $strInternalUniqueId;
    const InternalUniqueIdMaxLength = 50;
    const InternalUniqueIdDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.ClientAuthenticationToken
     * @var integer intClientAuthenticationToken
     */
    protected $intClientAuthenticationToken;
    const ClientAuthenticationTokenDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.SearchMetaInfo
     * @var string strSearchMetaInfo
     */
    protected $strSearchMetaInfo;
    const SearchMetaInfoDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.Account
     * @var integer intAccount
     */
    protected $intAccount;
    const AccountDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column PushRegistration.ObjectOwner
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
     * in the database column PushRegistration.ClientAuthenticationToken.
     *
     * NOTE: Always use the ClientAuthenticationTokenObject property getter to correctly retrieve this ClientAuthenticationToken object.
     * (Because this class implements late binding, this variable reference MAY be null.)
     * @var ClientAuthenticationToken objClientAuthenticationTokenObject
     */
    protected $objClientAuthenticationTokenObject;

    /**
     * Protected member variable that contains the object pointed by the reference
     * in the database column PushRegistration.Account.
     *
     * NOTE: Always use the AccountObject property getter to correctly retrieve this Account object.
     * (Because this class implements late binding, this variable reference MAY be null.)
     * @var Account objAccountObject
     */
    protected $objAccountObject;


    /**
     * Initialize each property with default values from database definition
     */
    public function Initialize() {
        $this->intId = PushRegistration::IdDefault;
        $this->strRegistrationId = PushRegistration::RegistrationIdDefault;
        $this->strDeviceUuid = PushRegistration::DeviceUuidDefault;
        $this->strDevicePlatform = PushRegistration::DevicePlatformDefault;
        $this->strDeviceOs = PushRegistration::DeviceOsDefault;
        $this->dttRegistrationDateTime = (PushRegistration::RegistrationDateTimeDefault === null)?null:new dxDateTime(PushRegistration::RegistrationDateTimeDefault);
        $this->strRegistrationStatus = PushRegistration::RegistrationStatusDefault;
        $this->strInternalUniqueId = PushRegistration::InternalUniqueIdDefault;
        $this->intClientAuthenticationToken = PushRegistration::ClientAuthenticationTokenDefault;
        $this->strSearchMetaInfo = PushRegistration::SearchMetaInfoDefault;
        $this->intAccount = PushRegistration::AccountDefault;
        $this->strLastUpdated = PushRegistration::LastUpdatedDefault;
        $this->intObjectOwner = PushRegistration::ObjectOwnerDefault;
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
     * Load a PushRegistration from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return PushRegistration
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'PushRegistration', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = PushRegistration::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::PushRegistration()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all PushRegistrations
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return PushRegistration[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call PushRegistration::QueryArray to perform the LoadAll query
        try {
            return PushRegistration::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all PushRegistrations
     * @return int
     */
    public static function CountAll() {
        // Call PushRegistration::QueryCount to perform the CountAll query
        return PushRegistration::QueryCount(dxQuery::All());
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
        $objDatabase = PushRegistration::GetDatabase();

        // Create/Build out the QueryBuilder object with PushRegistration-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'PushRegistration');

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
            PushRegistration::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('PushRegistration');

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
     * Static divblox Query method to query for a single PushRegistration object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return PushRegistration the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = PushRegistration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new PushRegistration object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = PushRegistration::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
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
            return PushRegistration::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of PushRegistration objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return PushRegistration[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = PushRegistration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return PushRegistration::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
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
            $strQuery = PushRegistration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
     * Static divblox Query method to query for a count of PushRegistration objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = PushRegistration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
        $objDatabase = PushRegistration::GetDatabase();

        $strQuery = PushRegistration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/pushregistration', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = PushRegistration::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this PushRegistration
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'PushRegistration';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'RegistrationId', $strAliasPrefix . 'RegistrationId');
            $objBuilder->AddSelectItem($strTableName, 'DeviceUuid', $strAliasPrefix . 'DeviceUuid');
            $objBuilder->AddSelectItem($strTableName, 'DevicePlatform', $strAliasPrefix . 'DevicePlatform');
            $objBuilder->AddSelectItem($strTableName, 'DeviceOs', $strAliasPrefix . 'DeviceOs');
            $objBuilder->AddSelectItem($strTableName, 'RegistrationDateTime', $strAliasPrefix . 'RegistrationDateTime');
            $objBuilder->AddSelectItem($strTableName, 'RegistrationStatus', $strAliasPrefix . 'RegistrationStatus');
            $objBuilder->AddSelectItem($strTableName, 'InternalUniqueId', $strAliasPrefix . 'InternalUniqueId');
            $objBuilder->AddSelectItem($strTableName, 'ClientAuthenticationToken', $strAliasPrefix . 'ClientAuthenticationToken');
            $objBuilder->AddSelectItem($strTableName, 'SearchMetaInfo', $strAliasPrefix . 'SearchMetaInfo');
            $objBuilder->AddSelectItem($strTableName, 'Account', $strAliasPrefix . 'Account');
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
     * Instantiate a PushRegistration from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this PushRegistration::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a PushRegistration, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
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

            if (PushRegistration::ExpandArray ($objDbRow, $strAliasPrefix, $objExpandAsArrayNode, $objPreviousItemArray, $strColumnAliasArray)) {
                return false; // db row was used but no new object was created
            }
        }

        // Create a new instance of the PushRegistration object
        $objToReturn = new PushRegistration();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'RegistrationId';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strRegistrationId = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'DeviceUuid';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strDeviceUuid = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'DevicePlatform';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strDevicePlatform = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'DeviceOs';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strDeviceOs = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'RegistrationDateTime';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttRegistrationDateTime = $objDbRow->GetColumn($strAliasName, 'DateTime');
        $strAlias = $strAliasPrefix . 'RegistrationStatus';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strRegistrationStatus = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'InternalUniqueId';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strInternalUniqueId = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'ClientAuthenticationToken';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intClientAuthenticationToken = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'SearchMetaInfo';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strSearchMetaInfo = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'Account';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intAccount = $objDbRow->GetColumn($strAliasName, 'Integer');
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
            $strAliasPrefix = 'PushRegistration__';

        // Check for ClientAuthenticationTokenObject Early Binding
        $strAlias = $strAliasPrefix . 'ClientAuthenticationToken__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            $objExpansionNode = (empty($objExpansionAliasArray['ClientAuthenticationToken']) ? null : $objExpansionAliasArray['ClientAuthenticationToken']);
            $objToReturn->objClientAuthenticationTokenObject = ClientAuthenticationToken::InstantiateDbRow($objDbRow, $strAliasPrefix . 'ClientAuthenticationToken__', $objExpansionNode, null, $strColumnAliasArray);
        }
        // Check for AccountObject Early Binding
        $strAlias = $strAliasPrefix . 'Account__Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        if (!is_null($objDbRow->GetColumn($strAliasName))) {
            $objExpansionNode = (empty($objExpansionAliasArray['Account']) ? null : $objExpansionAliasArray['Account']);
            $objToReturn->objAccountObject = Account::InstantiateDbRow($objDbRow, $strAliasPrefix . 'Account__', $objExpansionNode, null, $strColumnAliasArray);
        }



        return $objToReturn;
    }

    /**
     * Instantiate an array of PushRegistrations from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return PushRegistration[]
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
                $objItem = PushRegistration::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = PushRegistration::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single PushRegistration object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return PushRegistration next row resulting from the query
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
        return PushRegistration::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single PushRegistration object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return PushRegistration
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return PushRegistration::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::PushRegistration()->Id, $intId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load a single PushRegistration object,
     * by InternalUniqueId Index(es)
     * @param string $strInternalUniqueId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return PushRegistration
    */
    public static function LoadByInternalUniqueId($strInternalUniqueId, $objOptionalClauses = null) {
        return PushRegistration::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::PushRegistration()->InternalUniqueId, $strInternalUniqueId)
            ),
            $objOptionalClauses
        );
    }

    /**
     * Load an array of PushRegistration objects,
     * by ClientAuthenticationToken Index(es)
     * @param integer $intClientAuthenticationToken
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return PushRegistration[]
    */
    public static function LoadArrayByClientAuthenticationToken($intClientAuthenticationToken, $objOptionalClauses = null) {
        // Call PushRegistration::QueryArray to perform the LoadArrayByClientAuthenticationToken query
        try {
            return PushRegistration::QueryArray(
                dxQuery::Equal(dxQueryN::PushRegistration()->ClientAuthenticationToken, $intClientAuthenticationToken),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count PushRegistrations
     * by ClientAuthenticationToken Index(es)
     * @param integer $intClientAuthenticationToken
     * @return int
    */
    public static function CountByClientAuthenticationToken($intClientAuthenticationToken) {
        // Call PushRegistration::QueryCount to perform the CountByClientAuthenticationToken query
        return PushRegistration::QueryCount(
            dxQuery::Equal(dxQueryN::PushRegistration()->ClientAuthenticationToken, $intClientAuthenticationToken)
        );
    }

    /**
     * Load an array of PushRegistration objects,
     * by Account Index(es)
     * @param integer $intAccount
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return PushRegistration[]
    */
    public static function LoadArrayByAccount($intAccount, $objOptionalClauses = null) {
        // Call PushRegistration::QueryArray to perform the LoadArrayByAccount query
        try {
            return PushRegistration::QueryArray(
                dxQuery::Equal(dxQueryN::PushRegistration()->Account, $intAccount),
                $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count PushRegistrations
     * by Account Index(es)
     * @param integer $intAccount
     * @return int
    */
    public static function CountByAccount($intAccount) {
        // Call PushRegistration::QueryCount to perform the CountByAccount query
        return PushRegistration::QueryCount(
            dxQuery::Equal(dxQueryN::PushRegistration()->Account, $intAccount)
        );
    }
    ////////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Array via Many to Many)
    ////////////////////////////////////////////////////


    //////////////////////////
    // SAVE, DELETE AND RELOAD
    //////////////////////////

    /**
    * Save this PushRegistration
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"PushRegistration",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = PushRegistration::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = PushRegistration::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'PushRegistration';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("RegistrationId" => $this->strRegistrationId));
            $ChangedArray = array_merge($ChangedArray,array("DeviceUuid" => $this->strDeviceUuid));
            $ChangedArray = array_merge($ChangedArray,array("DevicePlatform" => $this->strDevicePlatform));
            $ChangedArray = array_merge($ChangedArray,array("DeviceOs" => $this->strDeviceOs));
            $ChangedArray = array_merge($ChangedArray,array("RegistrationDateTime" => $this->dttRegistrationDateTime));
            $ChangedArray = array_merge($ChangedArray,array("RegistrationStatus" => $this->strRegistrationStatus));
            $ChangedArray = array_merge($ChangedArray,array("InternalUniqueId" => $this->strInternalUniqueId));
            $ChangedArray = array_merge($ChangedArray,array("ClientAuthenticationToken" => $this->intClientAuthenticationToken));
            $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
            $ChangedArray = array_merge($ChangedArray,array("Account" => $this->intAccount));
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
            if (!is_null($ExistingObj->RegistrationId)) {
                $ExistingValueStr = $ExistingObj->RegistrationId;
            }
            if ($ExistingObj->RegistrationId != $this->strRegistrationId) {
                $ChangedArray = array_merge($ChangedArray,array("RegistrationId" => array("Before" => $ExistingValueStr,"After" => $this->strRegistrationId)));
                //$ChangedArray = array_merge($ChangedArray,array("RegistrationId" => "From: ".$ExistingValueStr." to: ".$this->strRegistrationId));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->DeviceUuid)) {
                $ExistingValueStr = $ExistingObj->DeviceUuid;
            }
            if ($ExistingObj->DeviceUuid != $this->strDeviceUuid) {
                $ChangedArray = array_merge($ChangedArray,array("DeviceUuid" => array("Before" => $ExistingValueStr,"After" => $this->strDeviceUuid)));
                //$ChangedArray = array_merge($ChangedArray,array("DeviceUuid" => "From: ".$ExistingValueStr." to: ".$this->strDeviceUuid));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->DevicePlatform)) {
                $ExistingValueStr = $ExistingObj->DevicePlatform;
            }
            if ($ExistingObj->DevicePlatform != $this->strDevicePlatform) {
                $ChangedArray = array_merge($ChangedArray,array("DevicePlatform" => array("Before" => $ExistingValueStr,"After" => $this->strDevicePlatform)));
                //$ChangedArray = array_merge($ChangedArray,array("DevicePlatform" => "From: ".$ExistingValueStr." to: ".$this->strDevicePlatform));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->DeviceOs)) {
                $ExistingValueStr = $ExistingObj->DeviceOs;
            }
            if ($ExistingObj->DeviceOs != $this->strDeviceOs) {
                $ChangedArray = array_merge($ChangedArray,array("DeviceOs" => array("Before" => $ExistingValueStr,"After" => $this->strDeviceOs)));
                //$ChangedArray = array_merge($ChangedArray,array("DeviceOs" => "From: ".$ExistingValueStr." to: ".$this->strDeviceOs));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->RegistrationDateTime)) {
                $ExistingValueStr = $ExistingObj->RegistrationDateTime;
            }
            if ($ExistingObj->RegistrationDateTime != $this->dttRegistrationDateTime) {
                $ChangedArray = array_merge($ChangedArray,array("RegistrationDateTime" => array("Before" => $ExistingValueStr,"After" => $this->dttRegistrationDateTime)));
                //$ChangedArray = array_merge($ChangedArray,array("RegistrationDateTime" => "From: ".$ExistingValueStr." to: ".$this->dttRegistrationDateTime));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->RegistrationStatus)) {
                $ExistingValueStr = $ExistingObj->RegistrationStatus;
            }
            if ($ExistingObj->RegistrationStatus != $this->strRegistrationStatus) {
                $ChangedArray = array_merge($ChangedArray,array("RegistrationStatus" => array("Before" => $ExistingValueStr,"After" => $this->strRegistrationStatus)));
                //$ChangedArray = array_merge($ChangedArray,array("RegistrationStatus" => "From: ".$ExistingValueStr." to: ".$this->strRegistrationStatus));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->InternalUniqueId)) {
                $ExistingValueStr = $ExistingObj->InternalUniqueId;
            }
            if ($ExistingObj->InternalUniqueId != $this->strInternalUniqueId) {
                $ChangedArray = array_merge($ChangedArray,array("InternalUniqueId" => array("Before" => $ExistingValueStr,"After" => $this->strInternalUniqueId)));
                //$ChangedArray = array_merge($ChangedArray,array("InternalUniqueId" => "From: ".$ExistingValueStr." to: ".$this->strInternalUniqueId));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ClientAuthenticationToken)) {
                $ExistingValueStr = $ExistingObj->ClientAuthenticationToken;
            }
            if ($ExistingObj->ClientAuthenticationToken != $this->intClientAuthenticationToken) {
                $ChangedArray = array_merge($ChangedArray,array("ClientAuthenticationToken" => array("Before" => $ExistingValueStr,"After" => $this->intClientAuthenticationToken)));
                //$ChangedArray = array_merge($ChangedArray,array("ClientAuthenticationToken" => "From: ".$ExistingValueStr." to: ".$this->intClientAuthenticationToken));
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
            if (!is_null($ExistingObj->Account)) {
                $ExistingValueStr = $ExistingObj->Account;
            }
            if ($ExistingObj->Account != $this->intAccount) {
                $ChangedArray = array_merge($ChangedArray,array("Account" => array("Before" => $ExistingValueStr,"After" => $this->intAccount)));
                //$ChangedArray = array_merge($ChangedArray,array("Account" => "From: ".$ExistingValueStr." to: ".$this->intAccount));
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
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'PushRegistration'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `PushRegistration` (
							`RegistrationId`,
							`DeviceUuid`,
							`DevicePlatform`,
							`DeviceOs`,
							`RegistrationDateTime`,
							`RegistrationStatus`,
							`InternalUniqueId`,
							`ClientAuthenticationToken`,
							`SearchMetaInfo`,
							`Account`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strRegistrationId) . ',
							' . $objDatabase->SqlVariable($this->strDeviceUuid) . ',
							' . $objDatabase->SqlVariable($this->strDevicePlatform) . ',
							' . $objDatabase->SqlVariable($this->strDeviceOs) . ',
							' . $objDatabase->SqlVariable($this->dttRegistrationDateTime) . ',
							' . $objDatabase->SqlVariable($this->strRegistrationStatus) . ',
							' . $objDatabase->SqlVariable($this->strInternalUniqueId) . ',
							' . $objDatabase->SqlVariable($this->intClientAuthenticationToken) . ',
							' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							' . $objDatabase->SqlVariable($this->intAccount) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('PushRegistration', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'PushRegistration'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `PushRegistration` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('PushRegistration');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `PushRegistration` SET
							`RegistrationId` = ' . $objDatabase->SqlVariable($this->strRegistrationId) . ',
							`DeviceUuid` = ' . $objDatabase->SqlVariable($this->strDeviceUuid) . ',
							`DevicePlatform` = ' . $objDatabase->SqlVariable($this->strDevicePlatform) . ',
							`DeviceOs` = ' . $objDatabase->SqlVariable($this->strDeviceOs) . ',
							`RegistrationDateTime` = ' . $objDatabase->SqlVariable($this->dttRegistrationDateTime) . ',
							`RegistrationStatus` = ' . $objDatabase->SqlVariable($this->strRegistrationStatus) . ',
							`InternalUniqueId` = ' . $objDatabase->SqlVariable($this->strInternalUniqueId) . ',
							`ClientAuthenticationToken` = ' . $objDatabase->SqlVariable($this->intClientAuthenticationToken) . ',
							`SearchMetaInfo` = ' . $objDatabase->SqlVariable($this->strSearchMetaInfo) . ',
							`Account` = ' . $objDatabase->SqlVariable($this->intAccount) . ',
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
            error_log('Could not save audit log while saving PushRegistration. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `PushRegistration` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this PushRegistration
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this PushRegistration with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"PushRegistration",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'PushRegistration'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = PushRegistration::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'PushRegistration';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("RegistrationId" => $this->strRegistrationId));
        $ChangedArray = array_merge($ChangedArray,array("DeviceUuid" => $this->strDeviceUuid));
        $ChangedArray = array_merge($ChangedArray,array("DevicePlatform" => $this->strDevicePlatform));
        $ChangedArray = array_merge($ChangedArray,array("DeviceOs" => $this->strDeviceOs));
        $ChangedArray = array_merge($ChangedArray,array("RegistrationDateTime" => $this->dttRegistrationDateTime));
        $ChangedArray = array_merge($ChangedArray,array("RegistrationStatus" => $this->strRegistrationStatus));
        $ChangedArray = array_merge($ChangedArray,array("InternalUniqueId" => $this->strInternalUniqueId));
        $ChangedArray = array_merge($ChangedArray,array("ClientAuthenticationToken" => $this->intClientAuthenticationToken));
        $ChangedArray = array_merge($ChangedArray,array("SearchMetaInfo" => $this->strSearchMetaInfo));
        $ChangedArray = array_merge($ChangedArray,array("Account" => $this->intAccount));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting PushRegistration. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `PushRegistration`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this PushRegistration ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'PushRegistration', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all PushRegistrations
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = PushRegistration::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `PushRegistration`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate PushRegistration table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = PushRegistration::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `PushRegistration`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this PushRegistration from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved PushRegistration object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = PushRegistration::Load($this->intId);

        // Update $this's local variables to match
        $this->strRegistrationId = $objReloaded->strRegistrationId;
        $this->strDeviceUuid = $objReloaded->strDeviceUuid;
        $this->strDevicePlatform = $objReloaded->strDevicePlatform;
        $this->strDeviceOs = $objReloaded->strDeviceOs;
        $this->dttRegistrationDateTime = $objReloaded->dttRegistrationDateTime;
        $this->strRegistrationStatus = $objReloaded->strRegistrationStatus;
        $this->strInternalUniqueId = $objReloaded->strInternalUniqueId;
        $this->ClientAuthenticationToken = $objReloaded->ClientAuthenticationToken;
        $this->strSearchMetaInfo = $objReloaded->strSearchMetaInfo;
        $this->Account = $objReloaded->Account;
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

            case 'RegistrationId':
                /**
                 * Gets the value for strRegistrationId 
                 * @return string
                 */
                return $this->strRegistrationId;

            case 'DeviceUuid':
                /**
                 * Gets the value for strDeviceUuid 
                 * @return string
                 */
                return $this->strDeviceUuid;

            case 'DevicePlatform':
                /**
                 * Gets the value for strDevicePlatform 
                 * @return string
                 */
                return $this->strDevicePlatform;

            case 'DeviceOs':
                /**
                 * Gets the value for strDeviceOs 
                 * @return string
                 */
                return $this->strDeviceOs;

            case 'RegistrationDateTime':
                /**
                 * Gets the value for dttRegistrationDateTime 
                 * @return dxDateTime
                 */
                return $this->dttRegistrationDateTime;

            case 'RegistrationStatus':
                /**
                 * Gets the value for strRegistrationStatus 
                 * @return string
                 */
                return $this->strRegistrationStatus;

            case 'InternalUniqueId':
                /**
                 * Gets the value for strInternalUniqueId (Unique)
                 * @return string
                 */
                return $this->strInternalUniqueId;

            case 'ClientAuthenticationToken':
                /**
                 * Gets the value for intClientAuthenticationToken 
                 * @return integer
                 */
                return $this->intClientAuthenticationToken;

            case 'SearchMetaInfo':
                /**
                 * Gets the value for strSearchMetaInfo 
                 * @return string
                 */
                return $this->strSearchMetaInfo;

            case 'Account':
                /**
                 * Gets the value for intAccount 
                 * @return integer
                 */
                return $this->intAccount;

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
            case 'ClientAuthenticationTokenObject':
                /**
                 * Gets the value for the ClientAuthenticationToken object referenced by intClientAuthenticationToken 
                 * @return ClientAuthenticationToken
                 */
                try {
                    if ((!$this->objClientAuthenticationTokenObject) && (!is_null($this->intClientAuthenticationToken)))
                        $this->objClientAuthenticationTokenObject = ClientAuthenticationToken::Load($this->intClientAuthenticationToken);
                    return $this->objClientAuthenticationTokenObject;
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

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
            case 'RegistrationId':
                /**
                 * Sets the value for strRegistrationId 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strRegistrationId = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'DeviceUuid':
                /**
                 * Sets the value for strDeviceUuid 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strDeviceUuid = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'DevicePlatform':
                /**
                 * Sets the value for strDevicePlatform 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strDevicePlatform = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'DeviceOs':
                /**
                 * Sets the value for strDeviceOs 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strDeviceOs = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'RegistrationDateTime':
                /**
                 * Sets the value for dttRegistrationDateTime 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttRegistrationDateTime = dxType::Cast($mixValue, dxType::DateTime));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'RegistrationStatus':
                /**
                 * Sets the value for strRegistrationStatus 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strRegistrationStatus = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'InternalUniqueId':
                /**
                 * Sets the value for strInternalUniqueId (Unique)
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strInternalUniqueId = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ClientAuthenticationToken':
                /**
                 * Sets the value for intClientAuthenticationToken 
                 * @param integer $mixValue
                 * @return integer
                 */
                try {
                    $this->objClientAuthenticationTokenObject = null;
                    return ($this->intClientAuthenticationToken = dxType::Cast($mixValue, dxType::Integer));
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
            case 'ClientAuthenticationTokenObject':
                /**
                 * Sets the value for the ClientAuthenticationToken object referenced by intClientAuthenticationToken 
                 * @param ClientAuthenticationToken $mixValue
                 * @return ClientAuthenticationToken
                 */
                if (is_null($mixValue)) {
                    $this->intClientAuthenticationToken = null;
                    $this->objClientAuthenticationTokenObject = null;
                    return null;
                } else {
                    // Make sure $mixValue actually is a ClientAuthenticationToken object
                    try {
                        $mixValue = dxType::Cast($mixValue, 'ClientAuthenticationToken');
                    } catch (dxInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                    // Make sure $mixValue is a SAVED ClientAuthenticationToken object
                    if (is_null($mixValue->Id))
                        throw new dxCallerException('Unable to set an unsaved ClientAuthenticationTokenObject for this PushRegistration');

                    // Update Local Member Variables
                    $this->objClientAuthenticationTokenObject = $mixValue;
                    $this->intClientAuthenticationToken = $mixValue->Id;

                    // Return $mixValue
                    return $mixValue;
                }
                break;

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
                        throw new dxCallerException('Unable to set an unsaved AccountObject for this PushRegistration');

                    // Update Local Member Variables
                    $this->objAccountObject = $mixValue;
                    $this->intAccount = $mixValue->Id;

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
        return "PushRegistration";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[PushRegistration::GetDatabaseIndex()]->Database;
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
        $strToReturn = '<complexType name="PushRegistration"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="RegistrationId" type="xsd:string"/>';
        $strToReturn .= '<element name="DeviceUuid" type="xsd:string"/>';
        $strToReturn .= '<element name="DevicePlatform" type="xsd:string"/>';
        $strToReturn .= '<element name="DeviceOs" type="xsd:string"/>';
        $strToReturn .= '<element name="RegistrationDateTime" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="RegistrationStatus" type="xsd:string"/>';
        $strToReturn .= '<element name="InternalUniqueId" type="xsd:string"/>';
        $strToReturn .= '<element name="ClientAuthenticationTokenObject" type="xsd1:ClientAuthenticationToken"/>';
        $strToReturn .= '<element name="SearchMetaInfo" type="xsd:string"/>';
        $strToReturn .= '<element name="AccountObject" type="xsd1:Account"/>';
        $strToReturn .= '<element name="LastUpdated" type="xsd:string"/>';
        $strToReturn .= '<element name="ObjectOwner" type="xsd:int"/>';
        $strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
        $strToReturn .= '</sequence></complexType>';
        return $strToReturn;
    }

    public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
        if (!array_key_exists('PushRegistration', $strComplexTypeArray)) {
            $strComplexTypeArray['PushRegistration'] = PushRegistration::GetSoapComplexTypeXml();
            ClientAuthenticationToken::AlterSoapComplexTypeArray($strComplexTypeArray);
            Account::AlterSoapComplexTypeArray($strComplexTypeArray);
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, PushRegistration::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new PushRegistration();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'RegistrationId'))
            $objToReturn->strRegistrationId = $objSoapObject->RegistrationId;
        if (property_exists($objSoapObject, 'DeviceUuid'))
            $objToReturn->strDeviceUuid = $objSoapObject->DeviceUuid;
        if (property_exists($objSoapObject, 'DevicePlatform'))
            $objToReturn->strDevicePlatform = $objSoapObject->DevicePlatform;
        if (property_exists($objSoapObject, 'DeviceOs'))
            $objToReturn->strDeviceOs = $objSoapObject->DeviceOs;
        if (property_exists($objSoapObject, 'RegistrationDateTime'))
            $objToReturn->dttRegistrationDateTime = new dxDateTime($objSoapObject->RegistrationDateTime);
        if (property_exists($objSoapObject, 'RegistrationStatus'))
            $objToReturn->strRegistrationStatus = $objSoapObject->RegistrationStatus;
        if (property_exists($objSoapObject, 'InternalUniqueId'))
            $objToReturn->strInternalUniqueId = $objSoapObject->InternalUniqueId;
        if ((property_exists($objSoapObject, 'ClientAuthenticationTokenObject')) &&
            ($objSoapObject->ClientAuthenticationTokenObject))
            $objToReturn->ClientAuthenticationTokenObject = ClientAuthenticationToken::GetObjectFromSoapObject($objSoapObject->ClientAuthenticationTokenObject);
        if (property_exists($objSoapObject, 'SearchMetaInfo'))
            $objToReturn->strSearchMetaInfo = $objSoapObject->SearchMetaInfo;
        if ((property_exists($objSoapObject, 'AccountObject')) &&
            ($objSoapObject->AccountObject))
            $objToReturn->AccountObject = Account::GetObjectFromSoapObject($objSoapObject->AccountObject);
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
            array_push($objArrayToReturn, PushRegistration::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttRegistrationDateTime)
            $objObject->dttRegistrationDateTime = $objObject->dttRegistrationDateTime->qFormat(dxDateTime::FormatSoap);
        if ($objObject->objClientAuthenticationTokenObject)
            $objObject->objClientAuthenticationTokenObject = ClientAuthenticationToken::GetSoapObjectFromObject($objObject->objClientAuthenticationTokenObject, false);
        else if (!$blnBindRelatedObjects)
            $objObject->intClientAuthenticationToken = null;
        if ($objObject->objAccountObject)
            $objObject->objAccountObject = Account::GetSoapObjectFromObject($objObject->objAccountObject, false);
        else if (!$blnBindRelatedObjects)
            $objObject->intAccount = null;
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
        $iArray['RegistrationId'] = $this->strRegistrationId;
        $iArray['DeviceUuid'] = $this->strDeviceUuid;
        $iArray['DevicePlatform'] = $this->strDevicePlatform;
        $iArray['DeviceOs'] = $this->strDeviceOs;
        $iArray['RegistrationDateTime'] = $this->dttRegistrationDateTime;
        $iArray['RegistrationStatus'] = $this->strRegistrationStatus;
        $iArray['InternalUniqueId'] = $this->strInternalUniqueId;
        $iArray['ClientAuthenticationToken'] = $this->intClientAuthenticationToken;
        $iArray['SearchMetaInfo'] = $this->strSearchMetaInfo;
        $iArray['Account'] = $this->intAccount;
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
     * @property-read dxQueryNode $RegistrationId
     * @property-read dxQueryNode $DeviceUuid
     * @property-read dxQueryNode $DevicePlatform
     * @property-read dxQueryNode $DeviceOs
     * @property-read dxQueryNode $RegistrationDateTime
     * @property-read dxQueryNode $RegistrationStatus
     * @property-read dxQueryNode $InternalUniqueId
     * @property-read dxQueryNode $ClientAuthenticationToken
     * @property-read dxQueryNodeClientAuthenticationToken $ClientAuthenticationTokenObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $Account
     * @property-read dxQueryNodeAccount $AccountObject
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodePushRegistration extends dxQueryNode {
		protected $strTableName = 'PushRegistration';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'PushRegistration';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'RegistrationId':
					return new dxQueryNode('RegistrationId', 'RegistrationId', 'Blob', $this);
				case 'DeviceUuid':
					return new dxQueryNode('DeviceUuid', 'DeviceUuid', 'VarChar', $this);
				case 'DevicePlatform':
					return new dxQueryNode('DevicePlatform', 'DevicePlatform', 'VarChar', $this);
				case 'DeviceOs':
					return new dxQueryNode('DeviceOs', 'DeviceOs', 'VarChar', $this);
				case 'RegistrationDateTime':
					return new dxQueryNode('RegistrationDateTime', 'RegistrationDateTime', 'DateTime', $this);
				case 'RegistrationStatus':
					return new dxQueryNode('RegistrationStatus', 'RegistrationStatus', 'VarChar', $this);
				case 'InternalUniqueId':
					return new dxQueryNode('InternalUniqueId', 'InternalUniqueId', 'VarChar', $this);
				case 'ClientAuthenticationToken':
					return new dxQueryNode('ClientAuthenticationToken', 'ClientAuthenticationToken', 'Integer', $this);
				case 'ClientAuthenticationTokenObject':
					return new dxQueryNodeClientAuthenticationToken('ClientAuthenticationToken', 'ClientAuthenticationTokenObject', 'Integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'Blob', $this);
				case 'Account':
					return new dxQueryNode('Account', 'Account', 'Integer', $this);
				case 'AccountObject':
					return new dxQueryNodeAccount('Account', 'AccountObject', 'Integer', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'VarChar', $this);
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
     * @property-read dxQueryNode $RegistrationId
     * @property-read dxQueryNode $DeviceUuid
     * @property-read dxQueryNode $DevicePlatform
     * @property-read dxQueryNode $DeviceOs
     * @property-read dxQueryNode $RegistrationDateTime
     * @property-read dxQueryNode $RegistrationStatus
     * @property-read dxQueryNode $InternalUniqueId
     * @property-read dxQueryNode $ClientAuthenticationToken
     * @property-read dxQueryNodeClientAuthenticationToken $ClientAuthenticationTokenObject
     * @property-read dxQueryNode $SearchMetaInfo
     * @property-read dxQueryNode $Account
     * @property-read dxQueryNodeAccount $AccountObject
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodePushRegistration extends dxQueryReverseReferenceNode {
		protected $strTableName = 'PushRegistration';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'PushRegistration';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'RegistrationId':
					return new dxQueryNode('RegistrationId', 'RegistrationId', 'string', $this);
				case 'DeviceUuid':
					return new dxQueryNode('DeviceUuid', 'DeviceUuid', 'string', $this);
				case 'DevicePlatform':
					return new dxQueryNode('DevicePlatform', 'DevicePlatform', 'string', $this);
				case 'DeviceOs':
					return new dxQueryNode('DeviceOs', 'DeviceOs', 'string', $this);
				case 'RegistrationDateTime':
					return new dxQueryNode('RegistrationDateTime', 'RegistrationDateTime', 'dxDateTime', $this);
				case 'RegistrationStatus':
					return new dxQueryNode('RegistrationStatus', 'RegistrationStatus', 'string', $this);
				case 'InternalUniqueId':
					return new dxQueryNode('InternalUniqueId', 'InternalUniqueId', 'string', $this);
				case 'ClientAuthenticationToken':
					return new dxQueryNode('ClientAuthenticationToken', 'ClientAuthenticationToken', 'integer', $this);
				case 'ClientAuthenticationTokenObject':
					return new dxQueryNodeClientAuthenticationToken('ClientAuthenticationToken', 'ClientAuthenticationTokenObject', 'integer', $this);
				case 'SearchMetaInfo':
					return new dxQueryNode('SearchMetaInfo', 'SearchMetaInfo', 'string', $this);
				case 'Account':
					return new dxQueryNode('Account', 'Account', 'integer', $this);
				case 'AccountObject':
					return new dxQueryNodeAccount('Account', 'AccountObject', 'integer', $this);
				case 'LastUpdated':
					return new dxQueryNode('LastUpdated', 'LastUpdated', 'string', $this);
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
