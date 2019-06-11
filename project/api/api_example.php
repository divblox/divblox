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
PublicApi::addApiOperation("dummyOperation",["Name","Surname"],"A string containing your full name");
PublicApi::initApi("Example API to illustrate the basics of divblox APIs. Call the api with /dummyOperation/Name=ACoolName/Surname=ACoolSurname to see results");

if (function_exists(PublicApi::getOperation())) {
    call_user_func(PublicApi::getOperation());
} else {
    PublicApi::addApiOutput("Result","Failed");
    PublicApi::addApiOutput("Message","Invalid operation provided. Try providing swapping '".PublicApi::getOperation()."' for 'doc' at the end of the url to see documentation");
    PublicApi::printApiResult();
}


// Operations
function dummyOperation() {
    $Name = PublicApi::getInputParameter("Name");
    $Surname = PublicApi::getInputParameter("Surname");
    PublicApi::addApiOutput("Additional Info","You called the dummy operation.");
    PublicApi::addApiOutput("Message","This file shows how to create a divblox api and how to handle the api inputs and outputs. Result is $Name $Surname");
    PublicApi::setApiResult(true); // You need to set this to true to indicate that the API executed successfully
    PublicApi::printApiResult();
}