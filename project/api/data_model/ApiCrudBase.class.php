<?php
/**
 * Created by PhpStorm.
 * User: Johan Griesel (Stratusolve (Pty) Ltd)
 * Date: 2017/02/18
 * Time: 10:07 AM
 */
class ApiCrudBase {
    protected static $EntityNameStr = '';
    protected static $EntityAttributeArray = [];
    protected static $EntityRelationshipArray = [];
    protected static $EntityFullAttributeArray = [];
    protected static $DataModelObj;
    public static function initCrudApi($EntityNameStr = null) {
        if (is_null($EntityNameStr)) {return;}
        self::$EntityNameStr = $EntityNameStr;
        self::$DataModelObj = new DataModel();
        self::$EntityAttributeArray = self::$DataModelObj->getEntityAttributes(self::$EntityNameStr);
        self::$EntityRelationshipArray = self::$DataModelObj->getEntitySingleRelationships(self::$EntityNameStr);
        if (!is_null(self::$EntityRelationshipArray)) {
            self::$EntityFullAttributeArray = array_merge(self::$EntityAttributeArray,self::$EntityRelationshipArray);
        } else {
            self::$EntityFullAttributeArray = self::$EntityAttributeArray;
        }
    }
    public static function doApiOperationDefinitions() {
        PublicApi::addApiOperation("create".self::$EntityNameStr,
            self::$EntityFullAttributeArray,
            ["Message" => "Populated if an error occurred",
                "Id" => "[Resulting ".self::$EntityNameStr." Id]"],
            "Create ".self::$EntityNameStr,
            "Creates a new ".self::$EntityNameStr." with the specified inputs");
        PublicApi::addApiOperation("retrieve".self::$EntityNameStr,
            [self::$EntityNameStr."_Id"],
            ["Message" => "Populated if an error occurred",
                self::$EntityNameStr."Json" => "The relevant ".self::$EntityNameStr." as a JSON string"],
            "Retrieve ".self::$EntityNameStr,
            "Retrieves the ".self::$EntityNameStr." matching the specified Id");
        PublicApi::addApiOperation("update".self::$EntityNameStr,
            array_merge([self::$EntityNameStr."_Id"],self::$EntityFullAttributeArray),
            ["Message" => "Populated if an error occurred"],
            "Update ".self::$EntityNameStr,
            "Updates the ".self::$EntityNameStr." matching the specified Id with the specified inputs");
        PublicApi::addApiOperation("delete".self::$EntityNameStr,
            [self::$EntityNameStr."_Id"],
            ["Message" => "Populated if an error occurred"],
            "Delete ".self::$EntityNameStr,
            "Deletes the ".self::$EntityNameStr." matching the specified Id");
        PublicApi::addApiOperation("list".self::$EntityNameStr,
            ["Constraints","OrderBy","Ascending","Offset"],
            ["Message" => "Populated if an error occurred",
                self::$EntityNameStr."List" => "JSON string representing a list of ".self::$EntityNameStr.", each represented as a JSON string"],
            "List ".self::$EntityNameStr,
            "Returns a list of ".self::$EntityNameStr." based on the specified constraints.<br>Result sets are limited to 100 items per request, offset by the input parameter \"Offset\".
<br>All input fields are optional. If no inputs are provided, a list of all ".self::$EntityNameStr."s will be returned, ordered by Id, ascending. To order results, set the input parameter \"Ascending\" to either \"true\" or \"false\"<hr>Available constraints:
                <ul>
                    <li>Equal</li>
                    <li>NotEqual</li>
                    <li>GreaterThan</li>
                    <li>LessThan</li>
                    <li>GreaterOrEqual</li>
                    <li>LessOrEqual</li>
                    <li>IsNull</li>
                    <li>IsNotNull</li>
                    <li>In</li>
                    <li>Like</li>
                    <li>AndCondition</li>
                    <li>OrCondition</li>
                </ul>Constraints can be used individually, for example:<br><pre>{\"Equal\":{\"".self::$EntityAttributeArray[0]."\":\"Some Value\"}}</pre>
                They can also be combined:<br><pre>{\"AndCondition\":[{\"Equal\":{\"".self::$EntityAttributeArray[0]."\":\"Some Value\"}},{\"NotEqual\":{\"".self::$EntityAttributeArray[ProjectFunctions::getDataSetSize(self::$EntityAttributeArray)-1]."\":\"Some Value\"}]}</pre>");
    }
    public static function executeCrudApi() {
        PublicApi::initApi("API endpoint to allow CRUD for the entity \"".self::$EntityNameStr."\"",self::$EntityNameStr." CRUD");
    }
    
    public static function create() {
        $Obj = new self::$EntityNameStr();
        foreach($Obj->getIterator() as $Attribute => $Value) {
            if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                continue;
            }
            $Value = PublicApi::getInputParameter($Attribute);
            if (is_null($Value)) {continue;}
            
            
            if (in_array(self::$DataModelObj->getEntityAttributeType(self::$EntityNameStr, $Attribute),["DATE","DATETIME"])) {
                if (is_string($Value) && strlen($Value)) {
                    $DateObj = new dxDateTime($Value);
                    $Obj->$Attribute = $DateObj;
                }
            } elseif ($Attribute == "Password") {
                $Obj->$Attribute = password_hash($Value,PASSWORD_BCRYPT);
            } else {
                $Obj->$Attribute = $Value;
            }
        }
        $Obj->Save();
        PublicApi::setApiResult(true);
        PublicApi::addApiOutput("Id",$Obj->Id);
        PublicApi::printApiResult();
    }
    public static function retrieve() {
        $IdInt = PublicApi::getInputParameter(self::$EntityNameStr."_Id");
        $Obj = null;
        $EntityNameStr = self::$EntityNameStr;
        if (is_numeric($IdInt)) {
            $Obj = $EntityNameStr::Load($IdInt);
        }
        if (is_null($Obj)) {
            PublicApi::setApiResult(false);
            PublicApi::addApiOutput("Message","Not found");
        } else {
            PublicApi::setApiResult(true);
            PublicApi::addApiOutput(self::$EntityNameStr."Json",json_decode($Obj->getJson()));
        }
        PublicApi::printApiResult();
    }
    public static function update() {
        $IdInt = PublicApi::getInputParameter(self::$EntityNameStr."_Id");
        $Obj = null;
        if (is_numeric($IdInt)) {
            $Obj = Account::Load($IdInt);
        }
        if (is_null($Obj)) {
            PublicApi::setApiResult(false);
            PublicApi::addApiOutput("Message","Not found");
        } else {
            foreach($Obj->getIterator() as $Attribute => $Value) {
                if (in_array($Attribute, ProjectFunctions::get_divblox_Attributes())) {
                    continue;
                }
                $Value = PublicApi::getInputParameter($Attribute);
                if (is_null($Value)) {continue;}
                
                if (in_array(self::$DataModelObj->getEntityAttributeType(self::$EntityNameStr, $Attribute),["DATE","DATETIME"])) {
                    if (is_string($Value) && strlen($Value)) {
                        $DateObj = new dxDateTime($Value);
                        $Obj->$Attribute = $DateObj;
                    }
                } elseif ($Attribute == "Password") {
                    $Obj->$Attribute = password_hash($Value,PASSWORD_BCRYPT);
                } else {
                    $Obj->$Attribute = $Value;
                }
            }
        }
        $Obj->Save();
        PublicApi::setApiResult(true);
        PublicApi::printApiResult();
    }
    public static function delete() {
        $IdInt = PublicApi::getInputParameter(self::$EntityNameStr."_Id");
        $Obj = null;
        if (is_numeric($IdInt)) {
            $Obj = Account::Load($IdInt);
        }
        if (is_null($Obj)) {
            PublicApi::setApiResult(false);
            PublicApi::addApiOutput("Message","Not found");
        } else {
            $Obj->Delete();;
            PublicApi::setApiResult(true);
        }
        PublicApi::printApiResult();
    }
    public static function retrieveList() {
        $EntityNameStr = self::$EntityNameStr;
        $Conditions = dxQ::All();
        //TODO: We need to build up the $Conditions variable here, based on the constraints provided
        
        
        $OrderByStr = PublicApi::getInputParameter('OrderBy');
        if (is_null($OrderByStr)) {
            $OrderByStr = "Id";
        }
        $Ascending = PublicApi::getInputParameter("Ascending");
        if (is_null($Ascending)) {
            $Ascending = true;
        } else {
            if ($Ascending != "false") {
                $Ascending = true;
            } else {
                $Ascending = false;
            }
        }
        $Offset = PublicApi::getInputParameter('Offset');
        if (!is_numeric($Offset)) {
            $Offset = 0;
        }
        $ObjArray = $EntityNameStr::QueryArray(
            $Conditions,
            dxQ::Clause(
                dxQ::OrderBy(
                    dxQN::$EntityNameStr()->$OrderByStr,$Ascending
                ),
                dxQ::LimitInfo(
                    100,$Offset
                )
            )
        );
        $ReturnArray = [];
        if (ProjectFunctions::getDataSetSize($ObjArray) > 0) {
            foreach ($ObjArray as $item) {
                $ReturnArray[] = json_decode($item->getJson());
            }
        }
        PublicApi::setApiResult(true);
        PublicApi::addApiOutput("Message","list".self::$EntityNameStr." currently does not honor constraints. This functionality is coming soon.");
        PublicApi::addApiOutput(self::$EntityNameStr."List",$ReturnArray);
        PublicApi::printApiResult();
    }
}