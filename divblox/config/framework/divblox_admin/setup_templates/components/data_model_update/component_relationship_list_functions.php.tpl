public function get[Relationship-ObjectName]List([component_class_name] $[component_class_name]Obj = null) {
        if ([Relationship-ObjectName]::QueryCount(dxQ::All()) > 50) {
            if (!is_null($[component_class_name]Obj) && !is_null($[component_class_name]Obj->[Relationship-ObjectName]Object)) {
                // JGL: We only return the selected [Relationship-ObjectName], since the selectable list will be too big.
                // In this case, the developer will need to implement select functionality to link another object
                $ObjectArray = DatabaseHelper::getObjectArray('[Relationship-ObjectName]', array("Id","[Attribute-To-Display]"), "Id = '".$[component_class_name]Obj->[Relationship-ObjectName]Object->Id."'", null, 50, null, $ErrorInfo);
                array_push($ObjectArray, array("Id" => "DATASET TOO LARGE"));
                return $ObjectArray;
            }
        }
        $ObjectArray = DatabaseHelper::getObjectArray('[Relationship-ObjectName]', array("Id","[Attribute-To-Display]"), null, null, 50, null, $ErrorInfo);
        return $ObjectArray;
    }
