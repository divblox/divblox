<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/ApiKeyGen.class.php');

/**
 * The ApiKey class defined here contains any
 * customized code for the ApiKey class in the
 * Object Relational Model.  It represents the "ApiKey" table
 * in the database, and extends from the code generated abstract ApiKeyGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class ApiKey extends ApiKeyGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objApiKey->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('ApiKey Object %s',  $this->intId);
    }
}
?>