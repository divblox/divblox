<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudAuditLogEntry.class.php');
ApiCrudAuditLogEntry::initCrudApi("AuditLogEntry");
ApiCrudAuditLogEntry::doApiOperationDefinitions();
ApiCrudAuditLogEntry::executeCrudApi();

// Operations
function createAuditLogEntry() {
    ApiCrudAuditLogEntry::create();
}
function retrieveAuditLogEntry() {
    ApiCrudAuditLogEntry::retrieve();
}
function updateAuditLogEntry() {
    ApiCrudAuditLogEntry::update();
}
function deleteAuditLogEntry() {
    ApiCrudAuditLogEntry::delete();
}
function listAuditLogEntry() {
    ApiCrudAuditLogEntry::retrieveList();
}
?>
