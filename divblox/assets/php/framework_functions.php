<?php
/**
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */
include(FRAMEWORK_ROOT_STR.'/assets/php/framework_functions_base.php');
abstract class FrameworkFunctions extends FrameworkFunctions_base {
    public static function getNewTaskUniqueId() {
        $CandidateStr = self::generateRandomString(24);
        $DoneBool = false;
        while(!$DoneBool) {
            // Divblox query language to load a ticket from the database,
            // based on the UniqueId field
            $ExistingTicketCount = Ticket::LoadByTicketUniqueId($CandidateStr);
            if ($ExistingTicketCount == 0) {
                $DoneBool = true;
            } else {
                $CandidateStr = self::generateRandomString(24);
            }
        }
        return $CandidateStr;
    }

}