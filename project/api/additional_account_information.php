<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudAdditionalAccountInformation.class.php');
ApiCrudAdditionalAccountInformation::initCrudApi("AdditionalAccountInformation");
ApiCrudAdditionalAccountInformation::doApiOperationDefinitions();
ApiCrudAdditionalAccountInformation::executeCrudApi();

// Operations
function createAdditionalAccountInformation() {
    ApiCrudAdditionalAccountInformation::create();
}
function retrieveAdditionalAccountInformation() {
    ApiCrudAdditionalAccountInformation::retrieve();
}
function updateAdditionalAccountInformation() {
    ApiCrudAdditionalAccountInformation::update();
}
function deleteAdditionalAccountInformation() {
    ApiCrudAdditionalAccountInformation::delete();
}
function listAdditionalAccountInformation() {
    ApiCrudAdditionalAccountInformation::retrieveList();
}
?>
