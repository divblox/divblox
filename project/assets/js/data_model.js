/**
 * data_model.js is responsible for loading the data model definitions and making them available to the rest of the
 * project
 */
if (isDebugMode) {
    noCacheForceText = getRandomFilePostFix();
}
let dataModel = {
    dataLists: {},
    entityDefinitionsBase: {},
    entityDefinitions: {},
    loadEntityDefinitions(successCallback, failedCallback) {
        loadJsonFromFile(getRootPath() + 'project/assets/configurations/data_model/generated/data_lists.json' + noCacheForceText, function (json) {
            this.dataLists = json;
            loadJsonFromFile(getRootPath() + 'project/assets/configurations/data_model/generated/entity_definitions_base.json' + noCacheForceText, function (json) {
                this.entityDefinitionsBase = json;
                loadJsonFromFile(getRootPath() + 'project/assets/configurations/data_model/entity_definitions.json' + noCacheForceText, function (json) {
                    this.entityDefinitions = json;
                    if ((this.entityDefinitions === {}) || (this.entityDefinitionsBase === {})) {
                        failedCallback();
                    } else {
                        successCallback();
                    }
                }.bind(this));
            }.bind(this));
        }.bind(this));
    },
    getEntityAttributeList(entityName) {
        if (!this.entityDefinitionsBase.hasOwnProperty(entityName)) {
            return [];
        }
        return Object.keys(this.entityDefinitionsBase[entityName]["Attributes"]);
    },
    getEntityAttributeProperties(entityName, attribute) {
        let returnObj = {
            "DisplayType": "",
            "InputLabel": "",
            "DefaultValue": "",
            "Placeholder": "",
            "Data": null,
            "ValidationMessage": ""
        };
        if (!this.entityDefinitionsBase.hasOwnProperty(entityName)) {
            return returnObj;
        }
        if (typeof this.entityDefinitionsBase[entityName]["Attributes"][attribute] !== "undefined") {
            let properties = Object.keys(returnObj);
            properties.forEach(function (propertyName) {
                if ((typeof this.entityDefinitions[entityName] !== "undefined") &&
                    (typeof this.entityDefinitions[entityName]["Attributes"] !== "undefined") &&
                    (typeof this.entityDefinitions[entityName]["Attributes"][attribute] !== "undefined") &&
                    (typeof this.entityDefinitions[entityName]["Attributes"][attribute][propertyName] !== "undefined")) {
                    returnObj[propertyName] = this.entityDefinitions[entityName]["Attributes"][attribute][propertyName];
                } else {
                    returnObj[propertyName] = this.entityDefinitionsBase[entityName]["Attributes"][attribute][propertyName];
                }
            }.bind(this));
        }
        return returnObj;
    },
    getEntityRelationshipProperties(entityName, relationship) {
        let returnObj = {
            "DisplayType": "",
            "InputLabel": "",
            "DefaultValue": "",
            "Placeholder": "",
            "Data": null,
            "ValidationMessage": ""
        };
        if (!this.entityDefinitionsBase.hasOwnProperty(entityName)) {
            return returnObj;
        }
        if (typeof this.entityDefinitionsBase[entityName]["Relationships"] !== "object") {
            return returnObj;
        }
        if (typeof this.entityDefinitionsBase[entityName]["Relationships"][relationship] !== "undefined") {
            let properties = Object.keys(returnObj);
            properties.forEach(function (propertyName) {
                if ((typeof this.entityDefinitions[entityName] !== "undefined") &&
                    (typeof this.entityDefinitions[entityName]["Relationships"] !== "undefined") &&
                    (typeof this.entityDefinitions[entityName]["Relationships"][relationship] !== "undefined") &&
                    (typeof this.entityDefinitions[entityName]["Relationships"][relationship][propertyName] !== "undefined")) {
                    returnObj[propertyName] = this.entityDefinitions[entityName]["Relationships"][relationship][propertyName];
                } else {
                    returnObj[propertyName] = this.entityDefinitionsBase[entityName]["Relationships"][relationship][propertyName];
                }
            }.bind(this));
        }
        return returnObj;
    },
    getDataList(reference) {
        if (typeof this.dataLists[reference] !== "undefined") {
            return this.dataLists[reference];
        }
        return [];
    }
};
dataModel.loadEntityDefinitions(function () {
        return;
    },
    function () {
        dxLog("Failed to load entity definitions");
    });