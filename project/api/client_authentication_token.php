<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudClientAuthenticationToken.class.php');
ApiCrudClientAuthenticationToken::initCrudApi("ClientAuthenticationToken");
ApiCrudClientAuthenticationToken::doApiOperationDefinitions();
ApiCrudClientAuthenticationToken::executeCrudApi();

// Operations
function createClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::create();
}
function retrieveClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::retrieve();
}
function updateClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::update();
}
function deleteClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::delete();
}
function listClientAuthenticationToken() {
    ApiCrudClientAuthenticationToken::retrieveList();
}
?>
