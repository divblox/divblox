<?php
/* GENERATED FILE.
	- This file is created and maintained by the divblox Data Modeller. This file can be modified here, but if the Data
	  Modeller is used to update the project's data model, this file will be regenerated and all local changes might be lost
	- This file is used to provide the data model data to the DataModel class */
abstract class DataModelData {
	public static $ProjectEntityArray = array("Ticket","TicketStatus");
	public static $ProjectEntityAttributeArray = array(
        "Ticket"
            => array("TicketName","TicketDescription","DueDate","TicketUniqueId"),
        "TicketStatus"
            => array("StatusLabel"));
	public static $ProjectEntityAttributeTypeArray = array(
        "Ticket"
            => array("VARCHAR(50)","TEXT","DATE","VARCHAR(25) UNIQUE"),
        "TicketStatus"
            => array("VARCHAR(25)"));
	public static $ProjectEntitySingleRelationshipArray = array(
        "Ticket"
            => array("Account","TicketStatus"));
	public static $UserRoleArray = array("Administrator","User");
    public static $ModuleArray = array(
    "Main" => array("Account","AdditionalAccountInformation","AllowedApiOperation","ApiKey","ApiOperation","AuditLogEntry","BackgroundProcess","BackgroundProcessUpdate","ClientAuthenticationToken","ClientConnection","EmailMessage","FileDocument","PageView","PasswordReset","PushRegistration","Ticket","TicketStatus","UserRole",));
}
?>