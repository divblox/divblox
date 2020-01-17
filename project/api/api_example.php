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
require("../../divblox/divblox.php");
// Start by declaring your operations and then calling the initApi function
PublicApi::addApiOperation("dummyOperation",
    ["first_name","last_name"],
    ["Message" => "You called the example operation","FullName" => "[The resulting full name]"],
    "Example Operation",
    "This is simply an example api operation that takes a first name and a last name and concatenates them");
PublicApi::addApiOperation("dummyOperation2",
    [],
    ["Message" => "Current system time is [system_time]"],
    "Example Operation 2",
    "A simple operation that returns the current system time");
PublicApi::initApi("Example API endpoint to illustrate the basics of divblox APIs","Example Endpoint");

// Operations
function dummyOperation() {
    $Name = PublicApi::getInputParameter("first_name");
    $Surname = PublicApi::getInputParameter("last_name");
    PublicApi::addApiOutput("Message","You called the example operation.");
    PublicApi::addApiOutput("FullName","$Name $Surname");
    PublicApi::setApiResult(true); // You need to set this to true to indicate that the API executed successfully
    PublicApi::printApiResult();
}
function dummyOperation2() {
    PublicApi::addApiOutput("Message","Current system time is ".dxDateTime::Now()->format(DATE_TIME_FORMAT_PHP_STR.' H:i:s'));
    PublicApi::setApiResult(true); // You need to set this to true to indicate that the API executed successfully
    PublicApi::printApiResult();
}