<?php
/* GENERATED FILE.
	- This file is created and maintained by the divblox Data Modeller. This file can be modified here, but if the Data
	  Modeller is used to update the project's data model, this file will be regenerated and all local changes might be lost
	- This file is used to provide the data model data to the DataModel class */
abstract class DataModelData {
	public static $ProjectEntityArray = array("Ticket","Category");
	public static $ProjectEntityAttributeArray = array(
        "Ticket"
            => array("TicketName","TicketDescription","DueDate","TicketUniqueId","TicketStatus"),
        "Category"
            => array("CategoryLabel"));
	public static $ProjectEntityAttributeTypeArray = array(
        "Ticket"
            => array("VARCHAR(50)","TEXT","DATE","VARCHAR(25) UNIQUE","VARCHAR(25)"),
        "Category"
            => array("VARCHAR(50)"));
	public static $ProjectEntitySingleRelationshipArray = array(
        "Ticket"
            => array("Account","Category"));
	public static $UserRoleArray = array("Administrator","User");
    public static $ModuleArray = array(
    "Main" => array("Account","AdditionalAccountInformation","AllowedApiOperation","ApiKey","ApiOperation","AuditLogEntry","BackgroundProcess","BackgroundProcessUpdate","Category","ClientAuthenticationToken","ClientConnection","EmailMessage","FileDocument","PageView","PasswordReset","PushRegistration","Ticket","UserRole",));
}
?>