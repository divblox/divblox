<?php
// The role "Administrator" by default has access to all components
abstract class ComponentRoleBasedAccess {
    public static $AccessArray = array(
        "Anonymous" => [
            "authentication",
            "account_registration",
            "anonymous_landing_page"],
        "User" => [
            "my_profile",
            "current_user_profile_manager",
            "profile_picture_uploader",
            "account_additional_info_manager",
            "account_additional_info_manager_data_series",
            "account_additional_info_manager_create",
            "account_additional_info_manager_update"],
        // Define an array for each additional user role in the system here.
    );
}
