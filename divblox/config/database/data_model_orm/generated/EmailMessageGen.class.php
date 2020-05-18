<?php
/**
 * The abstract EmailMessageGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the EmailMessage subclass which
 * extends this EmailMessageGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the EmailMessage class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property dxDateTime $SentDate the value for dttSentDate 
 * @property string $FromAddress the value for strFromAddress 
 * @property string $ReplyEmail the value for strReplyEmail 
 * @property string $Recipients the value for strRecipients 
 * @property string $Cc the value for strCc 
 * @property string $Bcc the value for strBcc 
 * @property string $Subject the value for strSubject 
 * @property string $EmailMessage the value for strEmailMessage 
 * @property string $Attachments the value for strAttachments 
 * @property string $ErrorInfo the value for strErrorInfo 
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class EmailMessageGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column EmailMessage.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.SentDate
     * @var dxDateTime dttSentDate
     */
    protected $dttSentDate;
    const SentDateDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.FromAddress
     * @var string strFromAddress
     */
    protected $strFromAddress;
    const FromAddressDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.ReplyEmail
     * @var string strReplyEmail
     */
    protected $strReplyEmail;
    const ReplyEmailDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.Recipients
     * @var string strRecipients
     */
    protected $strRecipients;
    const RecipientsDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.Cc
     * @var string strCc
     */
    protected $strCc;
    const CcDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.Bcc
     * @var string strBcc
     */
    protected $strBcc;
    const BccDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.Subject
     * @var string strSubject
     */
    protected $strSubject;
    const SubjectDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.EmailMessage
     * @var string strEmailMessage
     */
    protected $strEmailMessage;
    const EmailMessageDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.Attachments
     * @var string strAttachments
     */
    protected $strAttachments;
    const AttachmentsDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.ErrorInfo
     * @var string strErrorInfo
     */
    protected $strErrorInfo;
    const ErrorInfoDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column EmailMessage.ObjectOwner
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
     * Initialize each property with default values from database definition
     */
    public function Initialize() {
        $this->intId = EmailMessage::IdDefault;
        $this->dttSentDate = (EmailMessage::SentDateDefault === null)?null:new dxDateTime(EmailMessage::SentDateDefault);
        $this->strFromAddress = EmailMessage::FromAddressDefault;
        $this->strReplyEmail = EmailMessage::ReplyEmailDefault;
        $this->strRecipients = EmailMessage::RecipientsDefault;
        $this->strCc = EmailMessage::CcDefault;
        $this->strBcc = EmailMessage::BccDefault;
        $this->strSubject = EmailMessage::SubjectDefault;
        $this->strEmailMessage = EmailMessage::EmailMessageDefault;
        $this->strAttachments = EmailMessage::AttachmentsDefault;
        $this->strErrorInfo = EmailMessage::ErrorInfoDefault;
        $this->strLastUpdated = EmailMessage::LastUpdatedDefault;
        $this->intObjectOwner = EmailMessage::ObjectOwnerDefault;
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
     * Load a EmailMessage from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return EmailMessage
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'EmailMessage', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = EmailMessage::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::EmailMessage()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all EmailMessages
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return EmailMessage[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call EmailMessage::QueryArray to perform the LoadAll query
        try {
            return EmailMessage::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all EmailMessages
     * @return int
     */
    public static function CountAll() {
        // Call EmailMessage::QueryCount to perform the CountAll query
        return EmailMessage::QueryCount(dxQuery::All());
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
        $objDatabase = EmailMessage::GetDatabase();

        // Create/Build out the QueryBuilder object with EmailMessage-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'EmailMessage');

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
            EmailMessage::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('EmailMessage');

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
     * Static divblox Query method to query for a single EmailMessage object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return EmailMessage the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = EmailMessage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new EmailMessage object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = EmailMessage::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
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
            return EmailMessage::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of EmailMessage objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return EmailMessage[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = EmailMessage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return EmailMessage::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
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
            $strQuery = EmailMessage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
     * Static divblox Query method to query for a count of EmailMessage objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = EmailMessage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
        $objDatabase = EmailMessage::GetDatabase();

        $strQuery = EmailMessage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/emailmessage', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = EmailMessage::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this EmailMessage
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'EmailMessage';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'SentDate', $strAliasPrefix . 'SentDate');
            $objBuilder->AddSelectItem($strTableName, 'FromAddress', $strAliasPrefix . 'FromAddress');
            $objBuilder->AddSelectItem($strTableName, 'ReplyEmail', $strAliasPrefix . 'ReplyEmail');
            $objBuilder->AddSelectItem($strTableName, 'Recipients', $strAliasPrefix . 'Recipients');
            $objBuilder->AddSelectItem($strTableName, 'Cc', $strAliasPrefix . 'Cc');
            $objBuilder->AddSelectItem($strTableName, 'Bcc', $strAliasPrefix . 'Bcc');
            $objBuilder->AddSelectItem($strTableName, 'Subject', $strAliasPrefix . 'Subject');
            $objBuilder->AddSelectItem($strTableName, 'EmailMessage', $strAliasPrefix . 'EmailMessage');
            $objBuilder->AddSelectItem($strTableName, 'Attachments', $strAliasPrefix . 'Attachments');
            $objBuilder->AddSelectItem($strTableName, 'ErrorInfo', $strAliasPrefix . 'ErrorInfo');
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
     * Instantiate a EmailMessage from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this EmailMessage::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a EmailMessage, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
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


        // Create a new instance of the EmailMessage object
        $objToReturn = new EmailMessage();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'SentDate';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttSentDate = $objDbRow->GetColumn($strAliasName, 'DateTime');
        $strAlias = $strAliasPrefix . 'FromAddress';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strFromAddress = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'ReplyEmail';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strReplyEmail = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'Recipients';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strRecipients = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'Cc';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strCc = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'Bcc';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strBcc = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'Subject';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strSubject = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'EmailMessage';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strEmailMessage = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'Attachments';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strAttachments = $objDbRow->GetColumn($strAliasName, 'Blob');
        $strAlias = $strAliasPrefix . 'ErrorInfo';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strErrorInfo = $objDbRow->GetColumn($strAliasName, 'Blob');
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
            $strAliasPrefix = 'EmailMessage__';




        return $objToReturn;
    }

    /**
     * Instantiate an array of EmailMessages from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return EmailMessage[]
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
                $objItem = EmailMessage::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = EmailMessage::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single EmailMessage object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return EmailMessage next row resulting from the query
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
        return EmailMessage::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single EmailMessage object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return EmailMessage
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return EmailMessage::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::EmailMessage()->Id, $intId)
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
    * Save this EmailMessage
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"EmailMessage",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = EmailMessage::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = EmailMessage::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'EmailMessage';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("SentDate" => $this->dttSentDate));
            $ChangedArray = array_merge($ChangedArray,array("FromAddress" => $this->strFromAddress));
            $ChangedArray = array_merge($ChangedArray,array("ReplyEmail" => $this->strReplyEmail));
            $ChangedArray = array_merge($ChangedArray,array("Recipients" => $this->strRecipients));
            $ChangedArray = array_merge($ChangedArray,array("Cc" => $this->strCc));
            $ChangedArray = array_merge($ChangedArray,array("Bcc" => $this->strBcc));
            $ChangedArray = array_merge($ChangedArray,array("Subject" => $this->strSubject));
            $ChangedArray = array_merge($ChangedArray,array("EmailMessage" => $this->strEmailMessage));
            $ChangedArray = array_merge($ChangedArray,array("Attachments" => $this->strAttachments));
            $ChangedArray = array_merge($ChangedArray,array("ErrorInfo" => $this->strErrorInfo));
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
            if (!is_null($ExistingObj->SentDate)) {
                $ExistingValueStr = $ExistingObj->SentDate;
            }
            if ($ExistingObj->SentDate != $this->dttSentDate) {
                $ChangedArray = array_merge($ChangedArray,array("SentDate" => array("Before" => $ExistingValueStr,"After" => $this->dttSentDate)));
                //$ChangedArray = array_merge($ChangedArray,array("SentDate" => "From: ".$ExistingValueStr." to: ".$this->dttSentDate));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->FromAddress)) {
                $ExistingValueStr = $ExistingObj->FromAddress;
            }
            if ($ExistingObj->FromAddress != $this->strFromAddress) {
                $ChangedArray = array_merge($ChangedArray,array("FromAddress" => array("Before" => $ExistingValueStr,"After" => $this->strFromAddress)));
                //$ChangedArray = array_merge($ChangedArray,array("FromAddress" => "From: ".$ExistingValueStr." to: ".$this->strFromAddress));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ReplyEmail)) {
                $ExistingValueStr = $ExistingObj->ReplyEmail;
            }
            if ($ExistingObj->ReplyEmail != $this->strReplyEmail) {
                $ChangedArray = array_merge($ChangedArray,array("ReplyEmail" => array("Before" => $ExistingValueStr,"After" => $this->strReplyEmail)));
                //$ChangedArray = array_merge($ChangedArray,array("ReplyEmail" => "From: ".$ExistingValueStr." to: ".$this->strReplyEmail));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Recipients)) {
                $ExistingValueStr = $ExistingObj->Recipients;
            }
            if ($ExistingObj->Recipients != $this->strRecipients) {
                $ChangedArray = array_merge($ChangedArray,array("Recipients" => array("Before" => $ExistingValueStr,"After" => $this->strRecipients)));
                //$ChangedArray = array_merge($ChangedArray,array("Recipients" => "From: ".$ExistingValueStr." to: ".$this->strRecipients));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Cc)) {
                $ExistingValueStr = $ExistingObj->Cc;
            }
            if ($ExistingObj->Cc != $this->strCc) {
                $ChangedArray = array_merge($ChangedArray,array("Cc" => array("Before" => $ExistingValueStr,"After" => $this->strCc)));
                //$ChangedArray = array_merge($ChangedArray,array("Cc" => "From: ".$ExistingValueStr." to: ".$this->strCc));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Bcc)) {
                $ExistingValueStr = $ExistingObj->Bcc;
            }
            if ($ExistingObj->Bcc != $this->strBcc) {
                $ChangedArray = array_merge($ChangedArray,array("Bcc" => array("Before" => $ExistingValueStr,"After" => $this->strBcc)));
                //$ChangedArray = array_merge($ChangedArray,array("Bcc" => "From: ".$ExistingValueStr." to: ".$this->strBcc));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Subject)) {
                $ExistingValueStr = $ExistingObj->Subject;
            }
            if ($ExistingObj->Subject != $this->strSubject) {
                $ChangedArray = array_merge($ChangedArray,array("Subject" => array("Before" => $ExistingValueStr,"After" => $this->strSubject)));
                //$ChangedArray = array_merge($ChangedArray,array("Subject" => "From: ".$ExistingValueStr." to: ".$this->strSubject));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->EmailMessage)) {
                $ExistingValueStr = $ExistingObj->EmailMessage;
            }
            if ($ExistingObj->EmailMessage != $this->strEmailMessage) {
                $ChangedArray = array_merge($ChangedArray,array("EmailMessage" => array("Before" => $ExistingValueStr,"After" => $this->strEmailMessage)));
                //$ChangedArray = array_merge($ChangedArray,array("EmailMessage" => "From: ".$ExistingValueStr." to: ".$this->strEmailMessage));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Attachments)) {
                $ExistingValueStr = $ExistingObj->Attachments;
            }
            if ($ExistingObj->Attachments != $this->strAttachments) {
                $ChangedArray = array_merge($ChangedArray,array("Attachments" => array("Before" => $ExistingValueStr,"After" => $this->strAttachments)));
                //$ChangedArray = array_merge($ChangedArray,array("Attachments" => "From: ".$ExistingValueStr." to: ".$this->strAttachments));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->ErrorInfo)) {
                $ExistingValueStr = $ExistingObj->ErrorInfo;
            }
            if ($ExistingObj->ErrorInfo != $this->strErrorInfo) {
                $ChangedArray = array_merge($ChangedArray,array("ErrorInfo" => array("Before" => $ExistingValueStr,"After" => $this->strErrorInfo)));
                //$ChangedArray = array_merge($ChangedArray,array("ErrorInfo" => "From: ".$ExistingValueStr." to: ".$this->strErrorInfo));
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
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'EmailMessage'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `EmailMessage` (
							`SentDate`,
							`FromAddress`,
							`ReplyEmail`,
							`Recipients`,
							`Cc`,
							`Bcc`,
							`Subject`,
							`EmailMessage`,
							`Attachments`,
							`ErrorInfo`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->dttSentDate) . ',
							' . $objDatabase->SqlVariable($this->strFromAddress) . ',
							' . $objDatabase->SqlVariable($this->strReplyEmail) . ',
							' . $objDatabase->SqlVariable($this->strRecipients) . ',
							' . $objDatabase->SqlVariable($this->strCc) . ',
							' . $objDatabase->SqlVariable($this->strBcc) . ',
							' . $objDatabase->SqlVariable($this->strSubject) . ',
							' . $objDatabase->SqlVariable($this->strEmailMessage) . ',
							' . $objDatabase->SqlVariable($this->strAttachments) . ',
							' . $objDatabase->SqlVariable($this->strErrorInfo) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('EmailMessage', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'EmailMessage'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `EmailMessage` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('EmailMessage');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `EmailMessage` SET
							`SentDate` = ' . $objDatabase->SqlVariable($this->dttSentDate) . ',
							`FromAddress` = ' . $objDatabase->SqlVariable($this->strFromAddress) . ',
							`ReplyEmail` = ' . $objDatabase->SqlVariable($this->strReplyEmail) . ',
							`Recipients` = ' . $objDatabase->SqlVariable($this->strRecipients) . ',
							`Cc` = ' . $objDatabase->SqlVariable($this->strCc) . ',
							`Bcc` = ' . $objDatabase->SqlVariable($this->strBcc) . ',
							`Subject` = ' . $objDatabase->SqlVariable($this->strSubject) . ',
							`EmailMessage` = ' . $objDatabase->SqlVariable($this->strEmailMessage) . ',
							`Attachments` = ' . $objDatabase->SqlVariable($this->strAttachments) . ',
							`ErrorInfo` = ' . $objDatabase->SqlVariable($this->strErrorInfo) . ',
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
            error_log('Could not save audit log while saving EmailMessage. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `EmailMessage` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this EmailMessage
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this EmailMessage with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"EmailMessage",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'EmailMessage'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = EmailMessage::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'EmailMessage';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("SentDate" => $this->dttSentDate));
        $ChangedArray = array_merge($ChangedArray,array("FromAddress" => $this->strFromAddress));
        $ChangedArray = array_merge($ChangedArray,array("ReplyEmail" => $this->strReplyEmail));
        $ChangedArray = array_merge($ChangedArray,array("Recipients" => $this->strRecipients));
        $ChangedArray = array_merge($ChangedArray,array("Cc" => $this->strCc));
        $ChangedArray = array_merge($ChangedArray,array("Bcc" => $this->strBcc));
        $ChangedArray = array_merge($ChangedArray,array("Subject" => $this->strSubject));
        $ChangedArray = array_merge($ChangedArray,array("EmailMessage" => $this->strEmailMessage));
        $ChangedArray = array_merge($ChangedArray,array("Attachments" => $this->strAttachments));
        $ChangedArray = array_merge($ChangedArray,array("ErrorInfo" => $this->strErrorInfo));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting EmailMessage. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `EmailMessage`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this EmailMessage ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'EmailMessage', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all EmailMessages
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = EmailMessage::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `EmailMessage`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate EmailMessage table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = EmailMessage::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `EmailMessage`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this EmailMessage from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved EmailMessage object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = EmailMessage::Load($this->intId);

        // Update $this's local variables to match
        $this->dttSentDate = $objReloaded->dttSentDate;
        $this->strFromAddress = $objReloaded->strFromAddress;
        $this->strReplyEmail = $objReloaded->strReplyEmail;
        $this->strRecipients = $objReloaded->strRecipients;
        $this->strCc = $objReloaded->strCc;
        $this->strBcc = $objReloaded->strBcc;
        $this->strSubject = $objReloaded->strSubject;
        $this->strEmailMessage = $objReloaded->strEmailMessage;
        $this->strAttachments = $objReloaded->strAttachments;
        $this->strErrorInfo = $objReloaded->strErrorInfo;
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

            case 'SentDate':
                /**
                 * Gets the value for dttSentDate 
                 * @return dxDateTime
                 */
                return $this->dttSentDate;

            case 'FromAddress':
                /**
                 * Gets the value for strFromAddress 
                 * @return string
                 */
                return $this->strFromAddress;

            case 'ReplyEmail':
                /**
                 * Gets the value for strReplyEmail 
                 * @return string
                 */
                return $this->strReplyEmail;

            case 'Recipients':
                /**
                 * Gets the value for strRecipients 
                 * @return string
                 */
                return $this->strRecipients;

            case 'Cc':
                /**
                 * Gets the value for strCc 
                 * @return string
                 */
                return $this->strCc;

            case 'Bcc':
                /**
                 * Gets the value for strBcc 
                 * @return string
                 */
                return $this->strBcc;

            case 'Subject':
                /**
                 * Gets the value for strSubject 
                 * @return string
                 */
                return $this->strSubject;

            case 'EmailMessage':
                /**
                 * Gets the value for strEmailMessage 
                 * @return string
                 */
                return $this->strEmailMessage;

            case 'Attachments':
                /**
                 * Gets the value for strAttachments 
                 * @return string
                 */
                return $this->strAttachments;

            case 'ErrorInfo':
                /**
                 * Gets the value for strErrorInfo 
                 * @return string
                 */
                return $this->strErrorInfo;

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
            case 'SentDate':
                /**
                 * Sets the value for dttSentDate 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttSentDate = dxType::Cast($mixValue, dxType::DateTime));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'FromAddress':
                /**
                 * Sets the value for strFromAddress 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strFromAddress = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ReplyEmail':
                /**
                 * Sets the value for strReplyEmail 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strReplyEmail = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Recipients':
                /**
                 * Sets the value for strRecipients 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strRecipients = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Cc':
                /**
                 * Sets the value for strCc 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strCc = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Bcc':
                /**
                 * Sets the value for strBcc 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strBcc = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Subject':
                /**
                 * Sets the value for strSubject 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strSubject = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'EmailMessage':
                /**
                 * Sets the value for strEmailMessage 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strEmailMessage = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Attachments':
                /**
                 * Sets the value for strAttachments 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strAttachments = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ErrorInfo':
                /**
                 * Sets the value for strErrorInfo 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strErrorInfo = dxType::Cast($mixValue, dxType::String));
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



    
///////////////////////////////
    // METHODS TO EXTRACT INFO ABOUT THE CLASS
    ///////////////////////////////

    /**
     * Static method to retrieve the Database object that owns this class.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetTableName() {
        return "EmailMessage";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[EmailMessage::GetDatabaseIndex()]->Database;
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
        $strToReturn = '<complexType name="EmailMessage"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="SentDate" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="FromAddress" type="xsd:string"/>';
        $strToReturn .= '<element name="ReplyEmail" type="xsd:string"/>';
        $strToReturn .= '<element name="Recipients" type="xsd:string"/>';
        $strToReturn .= '<element name="Cc" type="xsd:string"/>';
        $strToReturn .= '<element name="Bcc" type="xsd:string"/>';
        $strToReturn .= '<element name="Subject" type="xsd:string"/>';
        $strToReturn .= '<element name="EmailMessage" type="xsd:string"/>';
        $strToReturn .= '<element name="Attachments" type="xsd:string"/>';
        $strToReturn .= '<element name="ErrorInfo" type="xsd:string"/>';
        $strToReturn .= '<element name="LastUpdated" type="xsd:string"/>';
        $strToReturn .= '<element name="ObjectOwner" type="xsd:int"/>';
        $strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
        $strToReturn .= '</sequence></complexType>';
        return $strToReturn;
    }

    public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
        if (!array_key_exists('EmailMessage', $strComplexTypeArray)) {
            $strComplexTypeArray['EmailMessage'] = EmailMessage::GetSoapComplexTypeXml();
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, EmailMessage::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new EmailMessage();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'SentDate'))
            $objToReturn->dttSentDate = new dxDateTime($objSoapObject->SentDate);
        if (property_exists($objSoapObject, 'FromAddress'))
            $objToReturn->strFromAddress = $objSoapObject->FromAddress;
        if (property_exists($objSoapObject, 'ReplyEmail'))
            $objToReturn->strReplyEmail = $objSoapObject->ReplyEmail;
        if (property_exists($objSoapObject, 'Recipients'))
            $objToReturn->strRecipients = $objSoapObject->Recipients;
        if (property_exists($objSoapObject, 'Cc'))
            $objToReturn->strCc = $objSoapObject->Cc;
        if (property_exists($objSoapObject, 'Bcc'))
            $objToReturn->strBcc = $objSoapObject->Bcc;
        if (property_exists($objSoapObject, 'Subject'))
            $objToReturn->strSubject = $objSoapObject->Subject;
        if (property_exists($objSoapObject, 'EmailMessage'))
            $objToReturn->strEmailMessage = $objSoapObject->EmailMessage;
        if (property_exists($objSoapObject, 'Attachments'))
            $objToReturn->strAttachments = $objSoapObject->Attachments;
        if (property_exists($objSoapObject, 'ErrorInfo'))
            $objToReturn->strErrorInfo = $objSoapObject->ErrorInfo;
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
            array_push($objArrayToReturn, EmailMessage::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttSentDate)
            $objObject->dttSentDate = $objObject->dttSentDate->qFormat(dxDateTime::FormatSoap);
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
        $iArray['SentDate'] = $this->dttSentDate;
        $iArray['FromAddress'] = $this->strFromAddress;
        $iArray['ReplyEmail'] = $this->strReplyEmail;
        $iArray['Recipients'] = $this->strRecipients;
        $iArray['Cc'] = $this->strCc;
        $iArray['Bcc'] = $this->strBcc;
        $iArray['Subject'] = $this->strSubject;
        $iArray['EmailMessage'] = $this->strEmailMessage;
        $iArray['Attachments'] = $this->strAttachments;
        $iArray['ErrorInfo'] = $this->strErrorInfo;
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
     * @property-read dxQueryNode $SentDate
     * @property-read dxQueryNode $FromAddress
     * @property-read dxQueryNode $ReplyEmail
     * @property-read dxQueryNode $Recipients
     * @property-read dxQueryNode $Cc
     * @property-read dxQueryNode $Bcc
     * @property-read dxQueryNode $Subject
     * @property-read dxQueryNode $EmailMessage
     * @property-read dxQueryNode $Attachments
     * @property-read dxQueryNode $ErrorInfo
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodeEmailMessage extends dxQueryNode {
		protected $strTableName = 'EmailMessage';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'EmailMessage';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'SentDate':
					return new dxQueryNode('SentDate', 'SentDate', 'DateTime', $this);
				case 'FromAddress':
					return new dxQueryNode('FromAddress', 'FromAddress', 'Blob', $this);
				case 'ReplyEmail':
					return new dxQueryNode('ReplyEmail', 'ReplyEmail', 'Blob', $this);
				case 'Recipients':
					return new dxQueryNode('Recipients', 'Recipients', 'Blob', $this);
				case 'Cc':
					return new dxQueryNode('Cc', 'Cc', 'Blob', $this);
				case 'Bcc':
					return new dxQueryNode('Bcc', 'Bcc', 'Blob', $this);
				case 'Subject':
					return new dxQueryNode('Subject', 'Subject', 'Blob', $this);
				case 'EmailMessage':
					return new dxQueryNode('EmailMessage', 'EmailMessage', 'Blob', $this);
				case 'Attachments':
					return new dxQueryNode('Attachments', 'Attachments', 'Blob', $this);
				case 'ErrorInfo':
					return new dxQueryNode('ErrorInfo', 'ErrorInfo', 'Blob', $this);
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
     * @property-read dxQueryNode $SentDate
     * @property-read dxQueryNode $FromAddress
     * @property-read dxQueryNode $ReplyEmail
     * @property-read dxQueryNode $Recipients
     * @property-read dxQueryNode $Cc
     * @property-read dxQueryNode $Bcc
     * @property-read dxQueryNode $Subject
     * @property-read dxQueryNode $EmailMessage
     * @property-read dxQueryNode $Attachments
     * @property-read dxQueryNode $ErrorInfo
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodeEmailMessage extends dxQueryReverseReferenceNode {
		protected $strTableName = 'EmailMessage';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'EmailMessage';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'SentDate':
					return new dxQueryNode('SentDate', 'SentDate', 'dxDateTime', $this);
				case 'FromAddress':
					return new dxQueryNode('FromAddress', 'FromAddress', 'string', $this);
				case 'ReplyEmail':
					return new dxQueryNode('ReplyEmail', 'ReplyEmail', 'string', $this);
				case 'Recipients':
					return new dxQueryNode('Recipients', 'Recipients', 'string', $this);
				case 'Cc':
					return new dxQueryNode('Cc', 'Cc', 'string', $this);
				case 'Bcc':
					return new dxQueryNode('Bcc', 'Bcc', 'string', $this);
				case 'Subject':
					return new dxQueryNode('Subject', 'Subject', 'string', $this);
				case 'EmailMessage':
					return new dxQueryNode('EmailMessage', 'EmailMessage', 'string', $this);
				case 'Attachments':
					return new dxQueryNode('Attachments', 'Attachments', 'string', $this);
				case 'ErrorInfo':
					return new dxQueryNode('ErrorInfo', 'ErrorInfo', 'string', $this);
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
