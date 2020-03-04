<?php
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudBase.class.php');
class ApiCrudCategory extends ApiCrudBase {
    public static function initCrudApi($EntityNameStr = null) {
        parent::initCrudApi($EntityNameStr);
    }
    public static function doApiOperationDefinitions() {
        parent::doApiOperationDefinitions();
    }
    public static function executeCrudApi() {
        parent::executeCrudApi();
    }

    public static function create() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::create();
    }
    public static function retrieve() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::retrieve();
    }
    public static function update() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::update();
    }
    public static function delete() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::delete();
    }
    public static function retrieveList() {
        // You are free to override this function to suit your needs. This file is only generated once.
        parent::retrieveList();
    }
}
?>