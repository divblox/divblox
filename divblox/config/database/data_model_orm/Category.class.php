<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/CategoryGen.class.php');

/**
 * The Category class defined here contains any
 * customized code for the Category class in the
 * Object Relational Model.  It represents the "Category" table
 * in the database, and extends from the code generated abstract CategoryGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class Category extends CategoryGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objCategory->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('Category Object %s',  $this->intId);
    }

    public function Delete() {
        if ((is_null($this->intId)))
            throw new dxUndefinedPrimaryKeyException('Cannot delete this Category with an unset primary key.');

        $ObjectAccessArray = ProjectAccessManager::getObjectAccess(ProjectFunctions::getCurrentAccountId(),"Category",$this->intId);
        if (!in_array(AccessOperation::DELETE_STR,$ObjectAccessArray)) {
            // This user is not allowed to delete an object of this type
            throw new Exception("User is not allowed to perform operation ".AccessOperation::DELETE_STR." on entity of type 'Category'. Allowed access is ".json_encode($ObjectAccessArray));
        }

        // Get the Database Object for this Class
        $objDatabase = Category::GetDatabase();
        $newAuditLogEntry = new AuditLogEntry();
        $ChangedArray = array();
        $newAuditLogEntry->EntryTimeStamp = dxDateTime::Now();
        $newAuditLogEntry->ObjectId = $this->intId;
        $newAuditLogEntry->ObjectName = 'Category';
        $newAuditLogEntry->UserEmail = ProjectFunctions::getCurrentUserEmailForAudit();
        $newAuditLogEntry->ModificationType = 'Delete';
        $ChangedArray = array_merge($ChangedArray,array("Id" => $this->intId));
        $ChangedArray = array_merge($ChangedArray,array("CategoryLabel" => $this->strCategoryLabel));
        $ChangedArray = array_merge($ChangedArray,array("TicketCount" => $this->intTicketCount));
        $ChangedArray = array_merge($ChangedArray,array("LastUpdated" => $this->strLastUpdated));
        $ChangedArray = array_merge($ChangedArray,array("ObjectOwner" => $this->intObjectOwner));
        $ChangedArray = array_merge($ChangedArray,array("CategoryParentId" => $this->intCategoryParentId));
        $ChangedArray = array_merge($ChangedArray,array("HierarchyPath" => $this->strHierarchyPath));
        $newAuditLogEntry->AuditLogEntryDetail = json_encode($ChangedArray);
        try {
            $newAuditLogEntry->Save();
        } catch(dxCallerException $e) {
            error_log('Could not save audit log while deleting Category. Details: '.$newAuditLogEntry->getJson().'<br>Error details: '.$e->getMessage());
        }

        // Perform the SQL Query
        $objDatabase->NonQuery('
            UPDATE 
                `Category` 
            SET `CategoryParentId` = ' . $objDatabase->SqlVariable($this->CategoryParentId) . '
            WHERE `CategoryParentId` = ' . $objDatabase->SqlVariable($this->Id) . '');

        $objDatabase->NonQuery('
            DELETE FROM
                `Category`
            WHERE
                `Id` = ' . $objDatabase->SqlVariable($this->intId) . '');

        $this->DeleteCache();
    }
}
?>