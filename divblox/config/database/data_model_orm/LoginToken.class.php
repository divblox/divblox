<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/LoginTokenGen.class.php');

/**
 * The LoginToken class defined here contains any
 * customized code for the LoginToken class in the
 * Object Relational Model.  It represents the "LoginToken" table
 * in the database, and extends from the code generated abstract LoginTokenGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class LoginToken extends LoginTokenGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objLoginToken->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('LoginToken Object %s',  $this->intId);
    }
}
?>