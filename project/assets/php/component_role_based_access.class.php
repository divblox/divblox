<?php
// The role "Administrator" by default has access to all components
abstract class ComponentRoleBasedAccess {
    public static $AccessArray = array(
        "Anonymous" => [
            "authentication",
            "account_registration"],
        "User" => [
            "my_profile",
            "current_user_profile_manager",
            "profile_picture_uploader",
            "account_additional_info_manager",
            "account_additional_info_manager_data_series",
            "account_additional_info_manager_create",
            "account_additional_info_manager_update",
            "ticket_crud",
            "ticket_crud_create",
            "ticket_crud_data_series",
            "ticket_crud_update",
            "category_crud",
            "category_crud_create",
            "category_crud_data_series",
            "category_crud_update",
            "category_update",
            "subcategory_list",
            "subtask_crud",
            "subtask_crud_create",
            "subtask_crud_update",
            "subtask_crud_data_series",
            "note_crud",
            "note_crud_create",
            "note_crud_update",
            "note_crud_data_series",
            "note_attachment_uploader"
        ],
        // Define an array for each additional user role in the system here.
    );
}
