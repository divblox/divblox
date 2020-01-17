<?php
/**
 * GENERATED FILE. DO NOT MODIFY.
 * This file contains the date of last update for each server instance that has ever been synchronized by the divblox Data Modeller
 * and is used to determine if a database synchronization for a specific instance is required.
 */
abstract class DataModelConfig {
	public static $Config = array("a" => 1540205030,"acceptance" => 1540242170,"Env_Two" => 1540563600,"Johan_Local" => 1540724422,"Local" => 1579250697,"Env_One" => 1542225512,"Env_One123" => 1542909510,"Local_AAA" => 1557738197);
	
	public static function getLastModifiedDate() {
        $CurrentDirectory = dirname(__FILE__);
        chdir(__DIR__);
        $LastUpdated = time();
        if (file_exists("DataModel_Data.inc.php")) {
            $LastUpdated = filemtime("DataModel_Data.inc.php");
        }
        chdir($CurrentDirectory);
        return $LastUpdated;
    }
}
?>