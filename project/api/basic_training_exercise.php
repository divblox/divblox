<?php
require("../../divblox/divblox.php");
// Start by declaring your operations and then calling the initApi function
PublicApi::addApiOperation("mergeTickets",
    ["input_ids"],
    ["merged_ticket" => "[JSON object representing merged ticket]"],
    "Merge Tickets",
    "This operation will merge an array of tickets into a combined ticket with the unique
    ID of the first ticket. input_ids should be a JSON encoded array of unique ticket IDs");
PublicApi::initApi("API endpoint to demonstrate our basic training exercise functionality",
"Basic Training Exercise");

// Operations
function mergeTickets() {
     // More information on functions available in the public API class
    // is provided in the API documentation section
    $InputIdArrayStr = PublicApi::getInputParameter("input_ids");
    if (!ProjectFunctions::isJson($InputIdArrayStr)) {
        PublicApi::setApiResult(false);
        PublicApi::addApiOutput("Message","Invalid value for input_ids provided.");
        PublicApi::printApiResult();
    }

    $InputIdArray = json_decode($InputIdArrayStr);
    if (!isset($InputIdArray[0])) {
        PublicApi::setApiResult(false);
        PublicApi::addApiOutput("Message","Invalid value for input_ids provided.");
        PublicApi::printApiResult();
    }

    $MasterTicketObj = Ticket::LoadByTicketUniqueId($InputIdArray[0]);
    if (is_null($MasterTicketObj)) {
        PublicApi::setApiResult(false);
        PublicApi::addApiOutput("Message","Invalid input ID for master ticket");
        PublicApi::printApiResult();
    }

    $InputIdArraySizeInt = ProjectFunctions::getDataSetSize($InputIdArray);
    if ($InputIdArraySizeInt < 2) {
        PublicApi::setApiResult(true);
        PublicApi::addApiOutput("merged_ticket", json_decode($MasterTicketObj->getJson()));
        PublicApi::printApiResult();
    }

    for ($i = 1; $i < $InputIdArraySizeInt; $i++) {
        $TicketObj = Ticket::LoadByTicketUniqueId($InputIdArray[$i]);
        if (is_null($TicketObj)) {
            continue;
        }
        $MasterTicketObj->TicketDescription .= $TicketObj->TicketDescription;
        $TicketObj->Delete();
    }

    $MasterTicketObj->Save();
    PublicApi::setApiResult(true);
    PublicApi::addApiOutput("merged_ticket", json_decode($MasterTicketObj->getJson()));
    PublicApi::printApiResult();
}