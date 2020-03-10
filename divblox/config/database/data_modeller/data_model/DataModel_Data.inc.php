<?php
/* GENERATED FILE.
	- This file is created and maintained by the divblox Data Modeller. This file can be modified here, but if the Data
	  Modeller is used to update the project's data model, this file will be regenerated and all local changes might be lost
	- This file is used to provide the data model data to the DataModel class */
abstract class DataModelData {
	public static $ProjectEntityArray = array("Expense","Category");
	public static $ProjectEntityAttributeArray = array(
        "Expense"
            => array("ExpenseName","ExpenseAmount","ExpenseDate","ExpenseUniqueId"),
        "Category"
            => array("CategoryName","CategoryTotal"));
	public static $ProjectEntityAttributeTypeArray = array(
        "Expense"
            => array("VARCHAR(50)","DOUBLE","DATE","VARCHAR(25)"),
        "Category"
            => array("VARCHAR(50)","DOUBLE"));
	public static $ProjectEntitySingleRelationshipArray = array(
        "Expense"
            => array("Account","Category"));
	public static $UserRoleArray = array("Administrator","User");
    public static $ModuleArray = array(
    "Main" => array("Account","AdditionalAccountInformation","AllowedApiOperation","ApiKey","ApiOperation","AuditLogEntry","BackgroundProcess","BackgroundProcessUpdate","Category","ClientAuthenticationToken","ClientConnection","EmailMessage","Expense","FileDocument","PageView","PasswordReset","PushRegistration","UserRole",));
}
?>