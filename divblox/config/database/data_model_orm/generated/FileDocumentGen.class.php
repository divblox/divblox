<?php
/**
 * The abstract FileDocumentGen class defined here is
 * code-generated and contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * To use, you should use the FileDocument subclass which
 * extends this FileDocumentGen class.
 *
 * Because subsequent re-code generations will overwrite any changes to this
 * file, you should leave this file unaltered to prevent yourself from losing
 * any information or code changes.  All customizations should be done by
 * overriding existing or implementing new methods, properties and variables
 * in the FileDocument class.
 *
 * @package divblox_app
 * @subpackage GeneratedDataObjects
 * @property-read integer $Id the value for intId (Read-Only PK)
 * @property string $FileName the value for strFileName 
 * @property string $Path the value for strPath 
 * @property string $UploadedFileName the value for strUploadedFileName 
 * @property string $FileType the value for strFileType 
 * @property string $SizeInKilobytes the value for strSizeInKilobytes 
 * @property dxDateTime $CreatedDate the value for dttCreatedDate 
 * @property-read string $LastUpdated the value for strLastUpdated (Read-Only Timestamp)
 * @property integer $ObjectOwner the value for intObjectOwner 
 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
 */
class FileDocumentGen extends dxBaseClass implements IteratorAggregate {

    ///////////////////////////////////////////////////////////////////////
    // PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
    ///////////////////////////////////////////////////////////////////////

    /**
     * Protected member variable that maps to the database PK Identity column FileDocument.Id
     * @var integer intId
     */
    protected $intId;
    const IdDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.FileName
     * @var string strFileName
     */
    protected $strFileName;
    const FileNameMaxLength = 200;
    const FileNameDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.Path
     * @var string strPath
     */
    protected $strPath;
    const PathMaxLength = 300;
    const PathDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.UploadedFileName
     * @var string strUploadedFileName
     */
    protected $strUploadedFileName;
    const UploadedFileNameMaxLength = 300;
    const UploadedFileNameDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.FileType
     * @var string strFileType
     */
    protected $strFileType;
    const FileTypeMaxLength = 50;
    const FileTypeDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.SizeInKilobytes
     * @var string strSizeInKilobytes
     */
    protected $strSizeInKilobytes;
    const SizeInKilobytesDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.CreatedDate
     * @var dxDateTime dttCreatedDate
     */
    protected $dttCreatedDate;
    const CreatedDateDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.LastUpdated
     * @var string strLastUpdated
     */
    protected $strLastUpdated;
    const LastUpdatedDefault = null;


    /**
     * Protected member variable that maps to the database column FileDocument.ObjectOwner
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
        $this->intId = FileDocument::IdDefault;
        $this->strFileName = FileDocument::FileNameDefault;
        $this->strPath = FileDocument::PathDefault;
        $this->strUploadedFileName = FileDocument::UploadedFileNameDefault;
        $this->strFileType = FileDocument::FileTypeDefault;
        $this->strSizeInKilobytes = FileDocument::SizeInKilobytesDefault;
        $this->dttCreatedDate = (FileDocument::CreatedDateDefault === null)?null:new dxDateTime(FileDocument::CreatedDateDefault);
        $this->strLastUpdated = FileDocument::LastUpdatedDefault;
        $this->intObjectOwner = FileDocument::ObjectOwnerDefault;
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
     * Load a FileDocument from PK Info
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return FileDocument
     */
    public static function Load($intId, $objOptionalClauses = null) {
        $strCacheKey = false;
        if (ProjectFunctions::$objCacheProvider && !$objOptionalClauses && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'FileDocument', $intId);
            $objCachedObject = ProjectFunctions::$objCacheProvider->Get($strCacheKey);
            if ($objCachedObject !== false) {
                return $objCachedObject;
            }
        }
        // Use QuerySingle to Perform the Query
        $objToReturn = FileDocument::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::FileDocument()->Id, $intId)
            ),
            $objOptionalClauses
        );
        if ($strCacheKey !== false) {
            ProjectFunctions::$objCacheProvider->Set($strCacheKey, $objToReturn);
        }
        return $objToReturn;
    }

    /**
     * Load all FileDocuments
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return FileDocument[]
     */
    public static function LoadAll($objOptionalClauses = null) {
        if (func_num_args() > 1) {
            throw new dxCallerException("LoadAll must be called with an array of optional clauses as a single argument");
        }
        // Call FileDocument::QueryArray to perform the LoadAll query
        try {
            return FileDocument::QueryArray(dxQuery::All(), $objOptionalClauses);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * Count all FileDocuments
     * @return int
     */
    public static function CountAll() {
        // Call FileDocument::QueryCount to perform the CountAll query
        return FileDocument::QueryCount(dxQuery::All());
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
        $objDatabase = FileDocument::GetDatabase();

        // Create/Build out the QueryBuilder object with FileDocument-specific SELET and FROM fields
        $objQueryBuilder = new dxQueryBuilder($objDatabase, 'FileDocument');

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
            FileDocument::GetSelectFields($objQueryBuilder, null, dxQuery::extractSelectClause($objOptionalClauses));
        }
        $objQueryBuilder->AddFromItem('FileDocument');

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
     * Static divblox Query method to query for a single FileDocument object.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return FileDocument the queried object
     */
    public static function QuerySingle(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = FileDocument::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query, Get the First Row, and Instantiate a new FileDocument object
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);

        // Do we have to expand anything?
        if ($objQueryBuilder->ExpandAsArrayNode) {
            $objToReturn = array();
            $objPrevItemArray = array();
            while ($objDbRow = $objDbResult->GetNextRow()) {
                $objItem = FileDocument::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
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
            return FileDocument::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
        }
    }

    /**
     * Static divblox Query method to query for an array of FileDocument objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return FileDocument[] the queried objects as an array
     */
    public static function QueryArray(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = FileDocument::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        // Perform the Query and Instantiate the Array Result
        $objDbResult = $objQueryBuilder->Database->Query($strQuery);
        return FileDocument::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
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
            $strQuery = FileDocument::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
     * Static divblox Query method to query for a count of FileDocument objects.
     * Uses BuildQueryStatment to perform most of the work.
     * @param dxQueryCondition $objConditions any conditions on the query, itself
     * @param dxQueryClause[] $objOptionalClausees additional optional dxQueryClause objects for this query
     * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
     * @return integer the count of queried objects as an integer
     */
    public static function QueryCount(dxQueryCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
        // Get the Query Statement
        try {
            $strQuery = FileDocument::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
        $objDatabase = FileDocument::GetDatabase();

        $strQuery = FileDocument::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

        $objCache = new dxCache('dxquery/filedocument', $strQuery);
        $cacheData = $objCache->GetData();

        if (!$cacheData || $blnForceUpdate) {
            $objDbResult = $objQueryBuilder->Database->Query($strQuery);
            $arrResult = FileDocument::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
            $objCache->SaveData(serialize($arrResult));
        } else {
            $arrResult = unserialize($cacheData);
        }

        return $arrResult;
    }

    /**
     * Updates a dxQueryBuilder with the SELECT fields for this FileDocument
     * @param dxQueryBuilder $objBuilder the Query Builder object to update
     * @param string $strPrefix optional prefix to add to the SELECT fields
     */
    public static function GetSelectFields(dxQueryBuilder $objBuilder, $strPrefix = null, dxQuerySelect $objSelect = null) {
        if ($strPrefix) {
            $strTableName = $strPrefix;
            $strAliasPrefix = $strPrefix . '__';
        } else {
            $strTableName = 'FileDocument';
            $strAliasPrefix = '';
        }

        if ($objSelect) {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
        } else {
            $objBuilder->AddSelectItem($strTableName, 'Id', $strAliasPrefix . 'Id');
            $objBuilder->AddSelectItem($strTableName, 'FileName', $strAliasPrefix . 'FileName');
            $objBuilder->AddSelectItem($strTableName, 'Path', $strAliasPrefix . 'Path');
            $objBuilder->AddSelectItem($strTableName, 'UploadedFileName', $strAliasPrefix . 'UploadedFileName');
            $objBuilder->AddSelectItem($strTableName, 'FileType', $strAliasPrefix . 'FileType');
            $objBuilder->AddSelectItem($strTableName, 'SizeInKilobytes', $strAliasPrefix . 'SizeInKilobytes');
            $objBuilder->AddSelectItem($strTableName, 'CreatedDate', $strAliasPrefix . 'CreatedDate');
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
     * Instantiate a FileDocument from a Database Row.
     * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
     * is calling this FileDocument::InstantiateDbRow in order to perform
     * early binding on referenced objects.
     * @param DatabaseRowBase $objDbRow
     * @param string $strAliasPrefix
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param dxBaseClass $arrPreviousItem
     * @param string[] $strColumnAliasArray
     * @return mixed Either a FileDocument, or false to indicate the dbrow was used in an expansion, or null to indicate that this leaf is a duplicate.
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


        // Create a new instance of the FileDocument object
        $objToReturn = new FileDocument();
        $objToReturn->__blnRestored = true;

        $strAlias = $strAliasPrefix . 'Id';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
        $strAlias = $strAliasPrefix . 'FileName';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strFileName = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'Path';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strPath = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'UploadedFileName';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strUploadedFileName = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'FileType';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strFileType = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'SizeInKilobytes';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->strSizeInKilobytes = $objDbRow->GetColumn($strAliasName, 'VarChar');
        $strAlias = $strAliasPrefix . 'CreatedDate';
        $strAliasName = !empty($strColumnAliasArray[$strAlias]) ? $strColumnAliasArray[$strAlias] : $strAlias;
        $objToReturn->dttCreatedDate = $objDbRow->GetColumn($strAliasName, 'DateTime');
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
            $strAliasPrefix = 'FileDocument__';




        return $objToReturn;
    }

    /**
     * Instantiate an array of FileDocuments from a Database Result
     * @param DatabaseResultBase $objDbResult
     * @param dxQueryBaseNode $objExpandAsArrayNode
     * @param string[] $strColumnAliasArray
     * @return FileDocument[]
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
                $objItem = FileDocument::InstantiateDbRow($objDbRow, null, $objExpandAsArrayNode, $objPrevItemArray, $strColumnAliasArray);
                if ($objItem) {
                    $objToReturn[] = $objItem;
                    $objPrevItemArray[$objItem->intId][] = $objItem;
                }
            }
        } else {
            while ($objDbRow = $objDbResult->GetNextRow())
                $objToReturn[] = FileDocument::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
        }

        return $objToReturn;
    }


    /**
     * Instantiate a single FileDocument object from a query cursor (e.g. a DB ResultSet).
     * Cursor is automatically moved to the "next row" of the result set.
     * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
     * @param dxDatabaseResultBase $objDbResult cursor resource
     * @return FileDocument next row resulting from the query
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
        return FileDocument::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
    }

    ///////////////////////////////////////////////////
    // INDEX-BASED LOAD METHODS (Single Load and Array)
    ///////////////////////////////////////////////////

    /**
     * Load a single FileDocument object,
     * by Id Index(es)
     * @param integer $intId
     * @param dxQueryClause[] $objOptionalClauses additional optional dxQueryClause objects for this query
     * @return FileDocument
    */
    public static function LoadById($intId, $objOptionalClauses = null) {
        return FileDocument::QuerySingle(
            dxQuery::AndCondition(
                dxQuery::Equal(dxQueryN::FileDocument()->Id, $intId)
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
    * Save this FileDocument
    * @param bool $blnForceInsert
    * @param bool $blnForceUpdate
    * @return int
    */
    public function Save($blnForceInsert = false, $blnForceUpdate = false) {
        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"FileDocument",$this->intId);
        // Get the Database Object for this Class
        $objDatabase = FileDocument::GetDatabase();
        $mixToReturn = null;
        if (!is_numeric($this->intObjectOwner)) {
            $this->intObjectOwner = ProjectFunctions::getCurrentAccountId();
        }
        $ExistingObj = FileDocument::Load($this->intId);
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'FileDocument';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        if (!$ExistingObj) {
            $newAuditLogEntry->ModificationType = 'Create';
            $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
            $ChangedArray = array_merge($ChangedArray,array("FileName" => $this->strFileName));
            $ChangedArray = array_merge($ChangedArray,array("Path" => $this->strPath));
            $ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => $this->strUploadedFileName));
            $ChangedArray = array_merge($ChangedArray,array("FileType" => $this->strFileType));
            $ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => $this->strSizeInKilobytes));
            $ChangedArray = array_merge($ChangedArray,array("CreatedDate" => $this->dttCreatedDate));
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
            if (!is_null($ExistingObj->FileName)) {
                $ExistingValueStr = $ExistingObj->FileName;
            }
            if ($ExistingObj->FileName != $this->strFileName) {
                $ChangedArray = array_merge($ChangedArray,array("FileName" => array("Before" => $ExistingValueStr,"After" => $this->strFileName)));
                //$ChangedArray = array_merge($ChangedArray,array("FileName" => "From: ".$ExistingValueStr." to: ".$this->strFileName));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->Path)) {
                $ExistingValueStr = $ExistingObj->Path;
            }
            if ($ExistingObj->Path != $this->strPath) {
                $ChangedArray = array_merge($ChangedArray,array("Path" => array("Before" => $ExistingValueStr,"After" => $this->strPath)));
                //$ChangedArray = array_merge($ChangedArray,array("Path" => "From: ".$ExistingValueStr." to: ".$this->strPath));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->UploadedFileName)) {
                $ExistingValueStr = $ExistingObj->UploadedFileName;
            }
            if ($ExistingObj->UploadedFileName != $this->strUploadedFileName) {
                $ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => array("Before" => $ExistingValueStr,"After" => $this->strUploadedFileName)));
                //$ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => "From: ".$ExistingValueStr." to: ".$this->strUploadedFileName));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->FileType)) {
                $ExistingValueStr = $ExistingObj->FileType;
            }
            if ($ExistingObj->FileType != $this->strFileType) {
                $ChangedArray = array_merge($ChangedArray,array("FileType" => array("Before" => $ExistingValueStr,"After" => $this->strFileType)));
                //$ChangedArray = array_merge($ChangedArray,array("FileType" => "From: ".$ExistingValueStr." to: ".$this->strFileType));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->SizeInKilobytes)) {
                $ExistingValueStr = $ExistingObj->SizeInKilobytes;
            }
            if ($ExistingObj->SizeInKilobytes != $this->strSizeInKilobytes) {
                $ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => array("Before" => $ExistingValueStr,"After" => $this->strSizeInKilobytes)));
                //$ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => "From: ".$ExistingValueStr." to: ".$this->strSizeInKilobytes));
            }
            $ExistingValueStr = "NULL";
            if (!is_null($ExistingObj->CreatedDate)) {
                $ExistingValueStr = $ExistingObj->CreatedDate;
            }
            if ($ExistingObj->CreatedDate != $this->dttCreatedDate) {
                $ChangedArray = array_merge($ChangedArray,array("CreatedDate" => array("Before" => $ExistingValueStr,"After" => $this->dttCreatedDate)));
                //$ChangedArray = array_merge($ChangedArray,array("CreatedDate" => "From: ".$ExistingValueStr." to: ".$this->dttCreatedDate));
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
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::CREATE_STR." on entity of type 'FileDocument'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                // Perform an INSERT query
                $objDatabase->NonQuery('
                INSERT INTO `FileDocument` (
							`FileName`,
							`Path`,
							`UploadedFileName`,
							`FileType`,
							`SizeInKilobytes`,
							`CreatedDate`,
							`ObjectOwner`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strFileName) . ',
							' . $objDatabase->SqlVariable($this->strPath) . ',
							' . $objDatabase->SqlVariable($this->strUploadedFileName) . ',
							' . $objDatabase->SqlVariable($this->strFileType) . ',
							' . $objDatabase->SqlVariable($this->strSizeInKilobytes) . ',
							' . $objDatabase->SqlVariable($this->dttCreatedDate) . ',
							' . $objDatabase->SqlVariable($this->intObjectOwner) . '
						)
                ');
					// Update Identity column and return its value
                $mixToReturn = $this->intId = $objDatabase->InsertId('FileDocument', 'Id');
            } else {
                // Perform an UPDATE query
                // First checking for Optimistic Locking constraints (if applicable)
                if (!in_array(AccessOperation::UPDATE_STR,$ObjectAccessArray)) {
                    // This user is not allowed to create an object of this type
                    throw new Exception("User is not allowed to perform operation ".AccessOperation::UPDATE_STR." on entity of type 'FileDocument'. Allowed access is ".json_encode($ObjectAccessArray));
                }
                if (!$blnForceUpdate) {
                    // Perform the Optimistic Locking check
                    $objResult = $objDatabase->Query('
                    SELECT `LastUpdated` FROM `FileDocument` WHERE
							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

                $objRow = $objResult->FetchArray();
                if ($objRow[0] != $this->strLastUpdated)
                    throw new dxOptimisticLockingException('FileDocument');
            }

            // Perform the UPDATE query
            $objDatabase->NonQuery('
            UPDATE `FileDocument` SET
							`FileName` = ' . $objDatabase->SqlVariable($this->strFileName) . ',
							`Path` = ' . $objDatabase->SqlVariable($this->strPath) . ',
							`UploadedFileName` = ' . $objDatabase->SqlVariable($this->strUploadedFileName) . ',
							`FileType` = ' . $objDatabase->SqlVariable($this->strFileType) . ',
							`SizeInKilobytes` = ' . $objDatabase->SqlVariable($this->strSizeInKilobytes) . ',
							`CreatedDate` = ' . $objDatabase->SqlVariable($this->dttCreatedDate) . ',
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
            error_log('Could not save audit log while saving FileDocument. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }
        // Update __blnRestored and any Non-Identity PK Columns (if applicable)
        $this->__blnRestored = true;

        // Update Local Timestamp
        $objResult = $objDatabase->Query('SELECT `LastUpdated` FROM
                                            `FileDocument` WHERE
                							`Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $objRow = $objResult->FetchArray();
        $this->strLastUpdated = $objRow[0];

        $this->DeleteCache();

        // Return
        return $mixToReturn;
    }
    /**
     * Delete this FileDocument
     * @return void
     */
    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this FileDocument with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"FileDocument",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'FileDocument'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = FileDocument::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'FileDocument';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("FileName" => $this->strFileName));
        $ChangedArray = array_merge($ChangedArray,array("Path" => $this->strPath));
        $ChangedArray = array_merge($ChangedArray,array("UploadedFileName" => $this->strUploadedFileName));
        $ChangedArray = array_merge($ChangedArray,array("FileType" => $this->strFileType));
        $ChangedArray = array_merge($ChangedArray,array("SizeInKilobytes" => $this->strSizeInKilobytes));
        $ChangedArray = array_merge($ChangedArray,array("CreatedDate" => $this->dttCreatedDate));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting FileDocument. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            DELETE FROM
                `FileDocument`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }

    /**
     * Delete this FileDocument ONLY from the cache
     * @return void
     */
    public function DeleteCache() {
        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            $strCacheKey = ProjectFunctions::$objCacheProvider->CreateKey(ProjectFunctions::$Database[1]->Database, 'FileDocument', $this->intId);
            ProjectFunctions::$objCacheProvider->Delete($strCacheKey);
        }
    }

    /**
     * Delete all FileDocuments
     * @return void
     */
    public static function DeleteAll() {
        // Get the Database Object for this Class
        $objDatabase = FileDocument::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            DELETE FROM
                `FileDocument`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }

    /**
     * Truncate FileDocument table
     * @return void
     */
    public static function Truncate() {
        // Get the Database Object for this Class
        $objDatabase = FileDocument::GetDatabase();

        // Perform the Query
        $objDatabase->NonQuery('
            TRUNCATE `FileDocument`');

        if (ProjectFunctions::$objCacheProvider && ProjectFunctions::$Database[1]->Caching) {
            ProjectFunctions::$objCacheProvider->DeleteAll();
        }
    }
    /**
     * Reload this FileDocument from the database.
     * @return void
     */
    public function Reload() {
        // Make sure we are actually Restored from the database
        if (!$this->__blnRestored)
            throw new dxCallerException('Cannot call Reload() on a new, unsaved FileDocument object.');

        $this->DeleteCache();

        // Reload the Object
        $objReloaded = FileDocument::Load($this->intId);

        // Update $this's local variables to match
        $this->strFileName = $objReloaded->strFileName;
        $this->strPath = $objReloaded->strPath;
        $this->strUploadedFileName = $objReloaded->strUploadedFileName;
        $this->strFileType = $objReloaded->strFileType;
        $this->strSizeInKilobytes = $objReloaded->strSizeInKilobytes;
        $this->dttCreatedDate = $objReloaded->dttCreatedDate;
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

            case 'FileName':
                /**
                 * Gets the value for strFileName 
                 * @return string
                 */
                return $this->strFileName;

            case 'Path':
                /**
                 * Gets the value for strPath 
                 * @return string
                 */
                return $this->strPath;

            case 'UploadedFileName':
                /**
                 * Gets the value for strUploadedFileName 
                 * @return string
                 */
                return $this->strUploadedFileName;

            case 'FileType':
                /**
                 * Gets the value for strFileType 
                 * @return string
                 */
                return $this->strFileType;

            case 'SizeInKilobytes':
                /**
                 * Gets the value for strSizeInKilobytes 
                 * @return string
                 */
                return $this->strSizeInKilobytes;

            case 'CreatedDate':
                /**
                 * Gets the value for dttCreatedDate 
                 * @return dxDateTime
                 */
                return $this->dttCreatedDate;

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
            case 'FileName':
                /**
                 * Sets the value for strFileName 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strFileName = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Path':
                /**
                 * Sets the value for strPath 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strPath = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'UploadedFileName':
                /**
                 * Sets the value for strUploadedFileName 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strUploadedFileName = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'FileType':
                /**
                 * Sets the value for strFileType 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strFileType = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'SizeInKilobytes':
                /**
                 * Sets the value for strSizeInKilobytes 
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strSizeInKilobytes = dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'CreatedDate':
                /**
                 * Sets the value for dttCreatedDate 
                 * @param dxDateTime $mixValue
                 * @return dxDateTime
                 */
                try {
                    return ($this->dttCreatedDate = dxType::Cast($mixValue, dxType::DateTime));
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
        return "FileDocument";
    }

    /**
     * Static method to retrieve the Table name from which this class has been created.
     * @return string Name of the table from which this class has been created.
     */
    public static function GetDatabaseName() {
        return ProjectFunctions::$Database[FileDocument::GetDatabaseIndex()]->Database;
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
        $strToReturn = '<complexType name="FileDocument"><sequence>';
        $strToReturn .= '<element name="Id" type="xsd:int"/>';
        $strToReturn .= '<element name="FileName" type="xsd:string"/>';
        $strToReturn .= '<element name="Path" type="xsd:string"/>';
        $strToReturn .= '<element name="UploadedFileName" type="xsd:string"/>';
        $strToReturn .= '<element name="FileType" type="xsd:string"/>';
        $strToReturn .= '<element name="SizeInKilobytes" type="xsd:string"/>';
        $strToReturn .= '<element name="CreatedDate" type="xsd:dateTime"/>';
        $strToReturn .= '<element name="LastUpdated" type="xsd:string"/>';
        $strToReturn .= '<element name="ObjectOwner" type="xsd:int"/>';
        $strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
        $strToReturn .= '</sequence></complexType>';
        return $strToReturn;
    }

    public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
        if (!array_key_exists('FileDocument', $strComplexTypeArray)) {
            $strComplexTypeArray['FileDocument'] = FileDocument::GetSoapComplexTypeXml();
        }
    }

    public static function GetArrayFromSoapArray($objSoapArray) {
        $objArrayToReturn = array();

        foreach ($objSoapArray as $objSoapObject)
            array_push($objArrayToReturn, FileDocument::GetObjectFromSoapObject($objSoapObject));

        return $objArrayToReturn;
    }

    public static function GetObjectFromSoapObject($objSoapObject) {
        $objToReturn = new FileDocument();
        if (property_exists($objSoapObject, 'Id'))
            $objToReturn->intId = $objSoapObject->Id;
        if (property_exists($objSoapObject, 'FileName'))
            $objToReturn->strFileName = $objSoapObject->FileName;
        if (property_exists($objSoapObject, 'Path'))
            $objToReturn->strPath = $objSoapObject->Path;
        if (property_exists($objSoapObject, 'UploadedFileName'))
            $objToReturn->strUploadedFileName = $objSoapObject->UploadedFileName;
        if (property_exists($objSoapObject, 'FileType'))
            $objToReturn->strFileType = $objSoapObject->FileType;
        if (property_exists($objSoapObject, 'SizeInKilobytes'))
            $objToReturn->strSizeInKilobytes = $objSoapObject->SizeInKilobytes;
        if (property_exists($objSoapObject, 'CreatedDate'))
            $objToReturn->dttCreatedDate = new dxDateTime($objSoapObject->CreatedDate);
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
            array_push($objArrayToReturn, FileDocument::GetSoapObjectFromObject($objObject, true));

        return unserialize(serialize($objArrayToReturn));
    }

    public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
        if ($objObject->dttCreatedDate)
            $objObject->dttCreatedDate = $objObject->dttCreatedDate->qFormat(dxDateTime::FormatSoap);
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
        $iArray['FileName'] = $this->strFileName;
        $iArray['Path'] = $this->strPath;
        $iArray['UploadedFileName'] = $this->strUploadedFileName;
        $iArray['FileType'] = $this->strFileType;
        $iArray['SizeInKilobytes'] = $this->strSizeInKilobytes;
        $iArray['CreatedDate'] = $this->dttCreatedDate;
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
     * @property-read dxQueryNode $FileName
     * @property-read dxQueryNode $Path
     * @property-read dxQueryNode $UploadedFileName
     * @property-read dxQueryNode $FileType
     * @property-read dxQueryNode $SizeInKilobytes
     * @property-read dxQueryNode $CreatedDate
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryNodeFileDocument extends dxQueryNode {
		protected $strTableName = 'FileDocument';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'FileDocument';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'Integer', $this);
				case 'FileName':
					return new dxQueryNode('FileName', 'FileName', 'VarChar', $this);
				case 'Path':
					return new dxQueryNode('Path', 'Path', 'VarChar', $this);
				case 'UploadedFileName':
					return new dxQueryNode('UploadedFileName', 'UploadedFileName', 'VarChar', $this);
				case 'FileType':
					return new dxQueryNode('FileType', 'FileType', 'VarChar', $this);
				case 'SizeInKilobytes':
					return new dxQueryNode('SizeInKilobytes', 'SizeInKilobytes', 'VarChar', $this);
				case 'CreatedDate':
					return new dxQueryNode('CreatedDate', 'CreatedDate', 'DateTime', $this);
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
     * @property-read dxQueryNode $FileName
     * @property-read dxQueryNode $Path
     * @property-read dxQueryNode $UploadedFileName
     * @property-read dxQueryNode $FileType
     * @property-read dxQueryNode $SizeInKilobytes
     * @property-read dxQueryNode $CreatedDate
     * @property-read dxQueryNode $LastUpdated
     * @property-read dxQueryNode $ObjectOwner
     *
     *

     * @property-read dxQueryNode $_PrimaryKeyNode
     **/
	class dxQueryReverseReferenceNodeFileDocument extends dxQueryReverseReferenceNode {
		protected $strTableName = 'FileDocument';
		protected $strPrimaryKey = 'Id';
		protected $strClassName = 'FileDocument';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new dxQueryNode('Id', 'Id', 'integer', $this);
				case 'FileName':
					return new dxQueryNode('FileName', 'FileName', 'string', $this);
				case 'Path':
					return new dxQueryNode('Path', 'Path', 'string', $this);
				case 'UploadedFileName':
					return new dxQueryNode('UploadedFileName', 'UploadedFileName', 'string', $this);
				case 'FileType':
					return new dxQueryNode('FileType', 'FileType', 'string', $this);
				case 'SizeInKilobytes':
					return new dxQueryNode('SizeInKilobytes', 'SizeInKilobytes', 'string', $this);
				case 'CreatedDate':
					return new dxQueryNode('CreatedDate', 'CreatedDate', 'dxDateTime', $this);
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
