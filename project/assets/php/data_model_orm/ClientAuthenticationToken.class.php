<?php
require(DATA_MODEL_ORM_PATH_STR.'/generated/ClientAuthenticationTokenGen.class.php');

/**
 * The ClientAuthenticationToken class defined here contains any
 * customized code for the ClientAuthenticationToken class in the
 * Object Relational Model.  It represents the "ClientAuthenticationToken" table
 * in the database, and extends from the code generated abstract ClientAuthenticationTokenGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package divblox_app
 * @subpackage DataObjects
 *
 */
class ClientAuthenticationToken extends ClientAuthenticationTokenGen {
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * Can also be called directly via $objClientAuthenticationToken->__toString().
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString() {
        return sprintf('ClientAuthenticationToken Object %s',  $this->intId);
    }
}
?>