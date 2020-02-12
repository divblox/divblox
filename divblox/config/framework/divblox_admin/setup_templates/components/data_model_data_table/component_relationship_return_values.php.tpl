$[Relationship-ObjectName]Str = "Not Defined";
            if ([Relationship-Is-Null-Check]) {
                $[Relationship-ObjectName]Str = $[component_class_name]Obj->[Attribute-To-Display];
            }
