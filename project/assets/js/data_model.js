/**
 * data_model.js is responsible for loading the data model definitions and making them available to the rest of the
 * project
 */
if (debug_mode) {
    no_cache_force_str = getRandomFilePostFix();
}
let data_model = {
    data_lists: {},
    entity_definitions_base: {},
    entity_definitions: {},
    loadEntityDefinitions(success_callback,failed_callback) {
        loadJsonFromFile(getRootPath()+'project/assets/data_model/generated/data_lists.json'+no_cache_force_str,function(json) {
            this.data_lists = json;
            loadJsonFromFile(getRootPath()+'project/assets/data_model/generated/entity_definitions_base.json'+no_cache_force_str,function(json) {
                this.entity_definitions_base = json;
                loadJsonFromFile(getRootPath()+'project/assets/data_model/entity_definitions.json'+no_cache_force_str,function(json) {
                    this.entity_definitions = json;
                    if ((this.entity_definitions === {}) || (this.entity_definitions_base === {})) {
                        failed_callback();
                    } else {
                        success_callback();
                    }
                }.bind(this));
            }.bind(this));
        }.bind(this));
    },
    getEntityAttributeList(entity_name) {
        if (!this.entity_definitions_base.hasOwnProperty(entity_name)) {
            return [];
        }
        return Object.keys(this.entity_definitions_base[entity_name]["Attributes"]);
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
        if (!this.entity_definitions_base.hasOwnProperty(entity_name)) {
            return return_obj;
        }
        if (typeof this.entity_definitions_base[entity_name]["Attributes"][attribute] !== "undefined") {
            let properties = Object.keys(return_obj);
            properties.forEach(function(propery_name) {
                if ((typeof this.entity_definitions[entity_name] !== "undefined") &&
                    (typeof this.entity_definitions[entity_name]["Attributes"] !== "undefined") &&
                    (typeof this.entity_definitions[entity_name]["Attributes"][attribute] !== "undefined") &&
                    (typeof this.entity_definitions[entity_name]["Attributes"][attribute][propery_name] !== "undefined")) {
                    return_obj[propery_name] = this.entity_definitions[entity_name]["Attributes"][attribute][propery_name];
                } else {
                    return_obj[propery_name] = this.entity_definitions_base[entity_name]["Attributes"][attribute][propery_name];
                }
            }.bind(this))
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
        if (!this.entity_definitions_base.hasOwnProperty(entity_name)) {
            return return_obj;
        }
        if (typeof this.entity_definitions_base[entity_name]["Relationships"] !== "object") {
            return return_obj;
        }
        if (typeof this.entity_definitions_base[entity_name]["Relationships"][relationship] !== "undefined") {
            let properties = Object.keys(return_obj);
            properties.forEach(function(propery_name) {
                if ((typeof this.entity_definitions[entity_name] !== "undefined") &&
                    (typeof this.entity_definitions[entity_name]["Relationships"] !== "undefined") &&
                    (typeof this.entity_definitions[entity_name]["Relationships"][relationship] !== "undefined") &&
                    (typeof this.entity_definitions[entity_name]["Relationships"][relationship][propery_name] !== "undefined")) {
                    return_obj[propery_name] = this.entity_definitions[entity_name]["Relationships"][relationship][propery_name];
                } else {
                    return_obj[propery_name] = this.entity_definitions_base[entity_name]["Relationships"][relationship][propery_name];
                }
            }.bind(this))
        }
        return return_obj;
    },
    getDataList(reference) {
        if (typeof this.data_lists[reference] !== "undefined") {
            return this.data_lists[reference];
        }
        return [];
    }
};
data_model.loadEntityDefinitions(function() {return;},
    function(){dxLog("Failed to load entity definitions")});