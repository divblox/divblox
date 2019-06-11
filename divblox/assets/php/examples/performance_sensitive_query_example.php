<?php
require('../../../divblox.php');
require(FRAMEWORK_ROOT_STR.'/assets/php/database_helper.class.php');
doPerformanceImprovedQuery(false);

function doPerformanceImprovedQuery($OutputTableData = false) {
    $ObjectArray = DatabaseHelper::getObjectArray('AuditLogEntry', array("Id"), null, DatabaseHelper::getOrderByClause(array("EntryTimeStamp" => "DESC","Id" =>"ASC")), 10000, null, $ErrorInfo);
    $MemoryPerformance = memory_get_usage();
    if ($OutputTableData) {
        if (FrameworkFunctions::getDataSetSize($ObjectArray) == 0) {
            echo "No items found...<br>Error info: ".json_encode($ErrorInfo);
        } else {
            foreach($ObjectArray as $item) {
                echo json_encode($item).'<br>';
            }
        }
    }

    $Duration = null;
    foreach($ErrorInfo as $Error) {
        if (array_key_exists("Query process duration",$Error))
            $Duration = $Error["Query process duration"] * 1000;
    }
    echo "Total items: ".AuditLogEntry::QueryCount(dxQ::All(),dxQ::Clause(dxQ::LimitInfo(10000)))."<br>";
    echo "Performance query duration: $Duration ms<br>";
    $QueryStartTime = microtime(true);
    $AllObjects = AuditLogEntry::QueryArray(dxQ::All(),dxQ::Clause(dxQ::LimitInfo(10000)));
    $MemoryStandard = memory_get_usage();
    $QueryEndTime = microtime(true);
    $QueryDuration = ($QueryEndTime - $QueryStartTime) * 1000;
    echo "Std divblox: $QueryDuration ms<br>";
    $PerformanceGain = $QueryDuration / $Duration;
    if ($PerformanceGain > 1) {
        echo "Gain: ".number_format($PerformanceGain,2)."x faster<br>";
    } else {
        echo "Gain: ".number_format($PerformanceGain,2)."% faster<br>";
    }
    echo "Memory Usage - Standard method: ".ProjectFunctions::getSizeSymbolByQuantity($MemoryStandard).'<br>';
    echo "Memory Usage - Performance method: ".ProjectFunctions::getSizeSymbolByQuantity($MemoryPerformance).'<br>';
    if ($MemoryStandard > $MemoryPerformance)
        echo "Memory Usage - Gain: ".ProjectFunctions::getSizeSymbolByQuantity($MemoryStandard - $MemoryPerformance).'<br>';
}

?>