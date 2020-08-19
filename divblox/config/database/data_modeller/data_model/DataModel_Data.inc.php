<?php
/* GENERATED FILE.
	- This file is created and maintained by the divblox Data Modeller. This file can be modified here, but if the Data
	  Modeller is used to update the project's data model, this file will be regenerated and all local changes might be lost
	- This file is used to provide the data model data to the DataModel class */
abstract class DataModelData {
	public static $ProjectEntityArray = array("Category","Ticket");
	public static $ProjectEntityAttributeArray = array(
        "Category"
            => array("CategoryLabel"),
        "Ticket"
            => array("TicketName","TicketDescription","TicketDueDate","TicketStatus","TicketUniqueId"));
	public static $ProjectEntityAttributeTypeArray = array(
        "Category"
            => array("VARCHAR(25)"),
        "Ticket"
            => array("VARCHAR(50)","TEXT","DATE","VARCHAR(50)","VARCHAR(25) UNIQUE"));
	public static $ProjectEntitySingleRelationshipArray = array(
        "Ticket"
            => array("Account","Category"));
	public static $UserRoleArray = array("Administrator","User");
    public static $ModuleArray = array(
    "Main" => array("Account","AdditionalAccountInformation","AllowedApiOperation","ApiKey","ApiOperation","AuditLogEntry","BackgroundProcess","BackgroundProcessUpdate","Category","ClientAuthenticationToken","ClientConnection","EmailMessage","FileDocument","PageView","PasswordReset","PushRegistration","Ticket","UserRole",));
}
?>