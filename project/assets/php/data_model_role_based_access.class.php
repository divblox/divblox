<?php
// The role "Administrator" by default has access to all components
abstract class DataModelRoleBasedAccess {
    public static $AccessArray = array(
        "Any" => [
            "PushRegistration" => [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR],
        ],
        "Anonymous" => [
            "[EntityName]" => [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR],
        ],
        "User" => [
            "Account" => [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR],
            "AdditionalAccountInformation" => [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR],
            "Ticket" => [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR],
            "TicketStatus" => [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR],
        ],
        // Define an array for each additional user role in the system here.
    );
}
