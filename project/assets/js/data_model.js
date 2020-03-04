/**
 * data_model.js is responsible for loading the data model definitions and making them available to the rest of the
 * project
 */
let data_lists = {};
let entity_definitions_base = {};
let entity_definitions = {};
let no_cache_force_str = '';
if (debug_mode) {
    no_cache_force_str = getRandomFilePostFix();
}
let data_model = {
    loadEntityDefinitions(success_callback,failed_callback) {
        loadJsonFromFile(getRootPath()+'project/assets/data_model/generated/data_lists.json'+no_cache_force_str,function(json) {
            data_lists = json;
            loadJsonFromFile(getRootPath()+'project/assets/data_model/generated/entity_definitions_base.json'+no_cache_force_str,function(json) {
                entity_definitions_base = json;
                loadJsonFromFile(getRootPath()+'project/assets/data_model/entity_definitions.json'+no_cache_force_str,function(json) {
                    entity_definitions = json;
                    if ((entity_definitions === {}) || (entity_definitions_base === {})) {
                        failed_callback();
                    } else {
                        success_callback();
                    }
                });
            });
        });
    },
    getEntityAttributeList(entity_name) {
        if (!entity_definitions_base.hasOwnProperty(entity_name)) {
            return [];
        }
        return Object.keys(entity_definitions_base[entity_name]["Attributes"]);
    },
    getEntityAttributeProperties(entity_name,attribute) {
        let return_obj = {
            "DisplayType": "",
            "InputLabel": "",
            "DefaultValue": "",
            "Placeholder": "",
            "Data": null,
            "ValidationMessage": ""
        };
        if (!entity_definitions_base.hasOwnProperty(entity_name)) {
            return return_obj;
        }
        if (typeof entity_definitions_base[entity_name]["Attributes"][attribute] !== "undefined") {
            let properties = Object.keys(return_obj);
            properties.forEach(function(propery_name) {
                if ((typeof entity_definitions[entity_name] !== "undefined") &&
                    (typeof entity_definitions[entity_name]["Attributes"] !== "undefined") &&
                    (typeof entity_definitions[entity_name]["Attributes"][attribute] !== "undefined") &&
                    (typeof entity_definitions[entity_name]["Attributes"][attribute][propery_name] !== "undefined")) {
                    return_obj[propery_name] = entity_definitions[entity_name]["Attributes"][attribute][propery_name];
                } else {
                    return_obj[propery_name] = entity_definitions_base[entity_name]["Attributes"][attribute][propery_name];
                }
            })
        }
        return return_obj;
    },
    getEntityRelationshipProperties(entity_name,relationship) {
        let return_obj = {
            "DisplayType": "",
            "InputLabel": "",
            "DefaultValue": "",
            "Placeholder": "",
            "Data": null,
            "ValidationMessage": ""
        };
        if (!entity_definitions_base.hasOwnProperty(entity_name)) {
            return return_obj;
        }
        if (typeof entity_definitions_base[entity_name]["Relationships"] !== "object") {
            return return_obj;
        }
        if (typeof entity_definitions_base[entity_name]["Relationships"][relationship] !== "undefined") {
            let properties = Object.keys(return_obj);
            properties.forEach(function(propery_name) {
                if ((typeof entity_definitions[entity_name] !== "undefined") &&
                    (typeof entity_definitions[entity_name]["Relationships"] !== "undefined") &&
                    (typeof entity_definitions[entity_name]["Relationships"][relationship] !== "undefined") &&
                    (typeof entity_definitions[entity_name]["Relationships"][relationship][propery_name] !== "undefined")) {
                    return_obj[propery_name] = entity_definitions[entity_name]["Relationships"][relationship][propery_name];
                } else {
                    return_obj[propery_name] = entity_definitions_base[entity_name]["Relationships"][relationship][propery_name];
                }
            })
        }
        return return_obj;
    },
    getDataList(reference) {
        if (typeof data_lists[reference] !== "undefined") {
            return data_lists[reference];
        }
        return [];
    }
};
data_model.loadEntityDefinitions(function(){return;},
    function(){dxLog("Failed to load entity definitions")});