<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudTicketStatus.class.php');
ApiCrudTicketStatus::initCrudApi("TicketStatus");
ApiCrudTicketStatus::doApiOperationDefinitions();
ApiCrudTicketStatus::executeCrudApi();

// Operations
function createTicketStatus() {
    ApiCrudTicketStatus::create();
}
function retrieveTicketStatus() {
    ApiCrudTicketStatus::retrieve();
}
function updateTicketStatus() {
    ApiCrudTicketStatus::update();
}
function deleteTicketStatus() {
    ApiCrudTicketStatus::delete();
}
function listTicketStatus() {
    ApiCrudTicketStatus::retrieveList();
}
?>
