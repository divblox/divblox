<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/ApiOperationGen.class.php');

/**
 * The ApiOperation class defined here contains any
 * customized code for the ApiOperation class in the
 * Object Relational Model.  It represents the "ApiOperation" table
 * in the database, and extends from the code generated abstract ApiOperationGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class ApiOperation extends ApiOperationGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objApiOperation->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('ApiOperation Object %s',  $this->intId);
    }
}
?>