<?php
abstract class UserRoleHierarchy {
    public static $PermissionInheritanceArray = array(
        "User" => [
            "Anonymous"
        ],
        "[UserRole]" => [
            "[SubUserRole1]",
            "[SubUserRole2]",
            "[SubUserRole3]",
        ],
        // Define an array for each additional user role in the system here.
    );
}
