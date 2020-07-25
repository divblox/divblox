<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/AllowedApiOperationGen.class.php');

/**
 * The AllowedApiOperation class defined here contains any
 * customized code for the AllowedApiOperation class in the
 * Object Relational Model.  It represents the "AllowedApiOperation" table
 * in the database, and extends from the code generated abstract AllowedApiOperationGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class AllowedApiOperation extends AllowedApiOperationGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objAllowedApiOperation->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('AllowedApiOperation Object %s',  $this->intId);
    }
}
?>