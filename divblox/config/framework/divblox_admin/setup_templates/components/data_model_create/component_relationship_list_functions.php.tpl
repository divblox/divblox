public function get[Relationship-ObjectName]List() {
        if ([Relationship-ObjectName]::QueryCount(dxQ::All()) > 50) {
            return [array("Id" => "DATASET TOO LARGE")];
        }
        $ObjectArray = DatabaseHelper::getObjectArray('[Relationship-ObjectName]', array("Id","[Attribute-To-Display]"), null, null, 50, null, $ErrorInfo);
        return $ObjectArray;
    }
