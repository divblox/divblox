<?php
/**
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */
require_once(DATA_MODELLER_PATH_STR.'/data_model/DataModel.class.php');
abstract class DatabaseHelper {
	/**
	 * @param null $EntityName: The name of the entity to be queried. For example "Account"
	 * @param null $AttributeArray: An array of the attributes of the relevant entity that we would like to have returned
	 *                              This will be a key=>value array if more than 1 attribute is specified or simply a
	 *                              1-dimensional array with the values for each retrieved entity of only 1 attribute is specified
	 * @param null $Conditions: This is the WHERE clause of our query. Should be anything that comes after the "WHERE"
	 *                          keyword in a normal query. For example: "Id = 1 OR(FullName = 'John Doe')"
	 * @param null $OrderBy: The order by clause for our query. The DatabaseHelper class has a helper class than builds
	 *                       this part of the query. To use this, provide the input to this parameter in the following way:
	 *                          e.g: DatabaseHelper::getOrderByClause(array("FirstName" =>"ASC","LastName" => "ASC"))
	 * @param int $Limit: If no limit is specified, we will only query for 1 result as a safety measure
	 * @param int $Offset: Self explanatory
	 * @param $ErrorInfo: Will be an array populated with information about the process
	 * @return array: See explanation of return array from $AttributeArray above
	 */
	public static function getObjectArray($EntityName = null, $AttributeArray = null, $Conditions = null, $OrderBy = null, $Limit = 1, $Offset = 0, &$ErrorInfo) {
		$ErrorInfo = array();
		$ReturnArray = array();
		// First, let's check that we have valid input information
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (is_null($EntityName)) {
			array_push($ErrorInfo,"No entity name provided");
			return $ReturnArray;
		}
		if (is_null($AttributeArray)) {
			array_push($ErrorInfo,"No attribute(s) provided");
			return $ReturnArray;
		}

		foreach($AttributeArray as $attr) {
			if (!self::isValidAttribute($EntityName,$attr)) {
				array_push($ErrorInfo,"Invalid entity / attribute(s) provided");
				return $ReturnArray;
			}
		}
		// If no limit is specified, we will only query for 1 result as a safety measure
		if (!is_numeric($Limit))
			$Limit = 1;
		if (!is_numeric($Offset))
			$Offset = 0;
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// At this point we have valid input information. Now let's connect to the database
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$Database = $EntityName::GetDatabase();
		$Connection = mysqli_connect($Database->Server,$Database->Username,$Database->Password,$Database->Database);
		if (mysqli_connect_errno()) {
			array_push($ErrorInfo,"Failed to connect to MySQL: " . mysqli_connect_error());
			return $ReturnArray;
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// Here we build the select query
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$Sql = "SELECT ";
		foreach($AttributeArray as $attr) {
			$Sql .= $attr.",";
		}
		$Sql = substr($Sql, 0,strlen($Sql)-1)." ";
		$Sql .= "FROM $EntityName ";
		if (!is_null($Conditions)) {
			$Sql .= "WHERE $Conditions ";
		}
		$Sql .= $OrderBy;
		$Sql .= "LIMIT $Limit OFFSET $Offset;";
		array_push($ErrorInfo,array("Query" => $Sql));
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// Now, let's process the query
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$QueryStartTime = microtime(true);
		array_push($ErrorInfo,array("Query start" => $QueryStartTime));
		$Result = $Connection->query($Sql);
		$QueryEndTime = microtime(true);
		array_push($ErrorInfo,array("Query end" => $QueryEndTime));
		$QueryDuration = $QueryEndTime - $QueryStartTime;
		array_push($ErrorInfo,array("Query duration" => $QueryDuration));
		if ($Result) {
			while($row = $Result->fetch_assoc()) {
				if (FrameworkFunctions::getDataSetSize($AttributeArray) == 1) {
					// In this case, we want to return the simplest possible result: An array containing only the values
					// of the required attribute per object
					array_push($ReturnArray,$row[$AttributeArray[0]]);
				} else {
					// In this case, we need a slightly more complex return type. Let's use a key value pair
					$KeyValueArray = array();
					foreach($AttributeArray as $attr) {
						$KeyValueArray = array_merge($KeyValueArray,array($attr => $row[$attr]));
					}
					array_push($ReturnArray,$KeyValueArray);
				}
			}
			$QueryProcessEndTime = microtime(true);
			array_push($ErrorInfo,array("Query process end" => $QueryProcessEndTime));
			$QueryProcessDuration = $QueryProcessEndTime - $QueryStartTime;
			array_push($ErrorInfo,array("Query process duration" => $QueryProcessDuration));
		} else {
			array_push($ErrorInfo,array("Mysqli Error" => mysqli_error($Connection)));
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$Connection->close();
		return $ReturnArray;
	}

	/**
	 * @param null $AttributeArray: Input can be multiple order by attributes in the form of array("FirstName" =>"ASC","LastName" => "ASC")
	 * @return string: The part of the query that defines the order by clause
	 */
	public static function getOrderByClause($AttributeArray = null) {
		if (is_null($AttributeArray)) {
			return "";
		}
		$SqlClause = "ORDER BY ";
		foreach($AttributeArray as $Attr=>$Direction) {
			$SqlClause .= "$Attr $Direction,";
		}
		$SqlClause = substr($SqlClause, 0,strlen($SqlClause)-1);
		return $SqlClause." ";
	}

	/**
	 * @param null $EntityName: An entity specified in the data model. If it does not exist in the data model, this function returns false
	 * @return bool
	 */
	public static function isValidEntity($EntityName = null) {
		if (is_null($EntityName))
			return false;
		if (class_exists($EntityName)) {
		    return true;
        }
		$DataModel = new DataModel();
		return in_array($EntityName,$DataModel->objects);
	}

	/**
	 * @param null $EntityName: An entity specified in the data model. If it does not exist in the data model, this function returns false
	 * @param null $AttributeName: An attribute specified in the data model for the provided EntityName. If it does not exist in the data model, this function returns false
	 * @return bool
	 */
	public static function isValidAttribute($EntityName = null, $AttributeName = null) {
		if (is_null($EntityName))
			return false;
		if (is_null($AttributeName))
			return false;
		if (!self::isValidEntity($EntityName))
			return false;
		$HoldEntity = new $EntityName();
		$AttrArray = array();
		foreach($HoldEntity->getIterator() as $Key=>$Value) {
			array_push($AttrArray,$Key);
		}
		return in_array($AttributeName,$AttrArray);
	}
}