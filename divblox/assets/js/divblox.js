/*
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the Divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the Divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. Divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future Divblox update
 * For queries please send an email to support@divblox.com
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Divblox initialization
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let dxVersion = "4.4.0";
let bootstrapVersion = "4.5.0";
let jqueryVersion = "3.5.1";
let minimumRequiredPhpVersion = "7.2.0";
let isSpaMode = false;
let isDebugMode = true;
let isFeedbackAllowed = false;
let allowableDivbloxPaths = ["divblox", "project"];
let allowableDivbloxSubPaths = ["assets", "config", "components"];
let documentRoot = "";
let pageUid = "main_page";
let authenticationToken = "";
let dxQueue = [];
let dxProcessingQueue = false;
let dxHasUploadsWaiting = false;
let isDxOffline = false;
let serviceWorker;
let installPromptEvent;
let elementLoadingTexts = {};
let elementLoadingStates = {};
let registeredToasts = [];
let isUpdatingToasts = false;
let globalVars = {};
let appState = {};
let rootHistories = [];
let rootHistoryIndex = -1;
let isRootHistoryProcessed = false;
let requestedCacheScripts = [];
let loadedCacheScripts = [];
let urlInputParameters = null;
let is_native = false;
let registeredEventHandlers = [];
let forceLogoutOccurred = false;
let maintenanceModeTriggered = false;
let noCacheForceText = '';
if (window.jQuery === undefined) {
    // JGL: We assume that we have jquery available here...
    throw new Error("jQuery has not been loaded. Please ensure that jQuery is loaded before divblox");
} else {
    //JGL : This is a temporary fix for jquery to work with the component builder for jquery v3.5+
    let rxhtmlTag = /<(?!area|br|hr|img|input|col|embed|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi;
    jQuery.htmlPrefilter = function (html) {
        html = html.replace(rxhtmlTag, "<$1></$2>");
        return html;
    };
}
let componentClasses = {};
let dxAdminRoles = ["dxadmin", "administrator"];
let currentUserRole = null;
let currentUserProfilePicturePath = getRootPath() + "project/assets/images/divblox_profile_picture_placeholder.svg";
let domComponentIndexMap = {};
// JGL: Let's initialize the object that will contain relevant DOM info for our components that are rendered on the page
let registeredComponents = {};
let divbloxDependencies = [
    "divblox/assets/js/bootstrap/4.5.0/bootstrap.bundle.min.js",
    "divblox/assets/js/sweetalert/sweetalert.min.js",
    "project/assets/js/project.js",
    "project/assets/js/momentjs/moment.js",
    "project/assets/js/data_model.js",
    "project/assets/js/menus.js",
];
/**
 * Responsible for rendering input fields consistently throughout the applications
 * @type {{renderInputField: dxRenderer.renderInputField}}
 */
let dxRenderer = {
    /**
     * Renders an input field as the content of a div specified in config_obj
     * @param config {
     *          "WrapperId": "The dom id of the div that will wrap this field",
     *          "FieldId": "The dom id of the field that will be rendered",
     *          "MustValidate": [true|false],
                "DisplayType": "[text|textarea|date|datetime-local|number|checkbox]",
                "InputLabel": "The label to display with the input field",
                "DefaultValue": "",
                "Placeholder": "e.g Your input here...",
                "Data": [null|reference to a list defined in data_lists.json],
                "ValidationMessage": "Message to be displayed if the input field is validated",
                "Rows": "Optional for when rendering a textarea: Defaults to 3",
            }
     */
    renderInputField: function (config) {
        let wrapperNode = $("#" + config.WrapperId);
        if (!wrapperNode.length) {
            return;
        }
        if (typeof config.DisplayType === "undefined") {
            throw new Error("Invalid display type provided for dx_renderer.renderInputField");
        }
        if (typeof config.FieldId === "undefined") {
            throw new Error("Invalid FieldId provided for dx_renderer.renderInputField");
        }
        let completeHtml = '';
        let inputFieldHtml = '';
        let placeholderText = '';
        let defaultValueText = '';
        let validationMessageText = '';
        let labelText = '';
        if (typeof config.InputLabel !== "undefined") {
            labelText = '<label>' + config.InputLabel + '</label>';
        }
        if (typeof config.Placeholder !== "undefined") {
            placeholderText = ' placeholder="' + config.Placeholder + '"';
        }
        if (typeof config.DefaultValue !== "undefined") {
            defaultValueText = config.DefaultValue;
            if (config.DisplayType === 'checkbox') {
                defaultValueText = ' checked="';
                if ((config.DefaultValue === 'checked')
                    || (config.DefaultValue === true)
                    || (config.DefaultValue === 'true')) {
                    defaultValueText += 'true"';
                } else {
                    defaultValueText += 'false"';
                }
            }
        }
        if (typeof config.ValidationMessage !== "undefined") {
            validationMessageText = config.ValidationMessage;
        }
        //ValidationMessage
        switch (config.DisplayType) {
            case 'text':
            case 'email':
            case 'number':
            case 'password':
            case 'date':
            case 'datetime-local':
            case 'month':
            case 'week':
            case 'tel':
            case 'url':
            case 'time':
            case 'color':
                inputFieldHtml = '<input id="' + config.FieldId + '" type="' + config.DisplayType + '" class="form-control"' +
                    '' + placeholderText + ' value="' + defaultValueText + '"/>';
                if (typeof config.MustValidate !== "undefined") {
                    if (config.MustValidate) {
                        inputFieldHtml += '<div id="' + config.FieldId + 'InvalidFeedback" class="invalid-feedback">\n' +
                            '' + validationMessageText + '</div>';
                    }
                }
                completeHtml = labelText + inputFieldHtml;
                break;
            case 'textarea':
                let rows = 3;
                if (typeof config.Rows !== "undefined") {
                    rows = config.Rows;
                }
                inputFieldHtml = '<textarea class="form-control" id="' + config.FieldId + '" rows="'
                    + rows + '" value="' + defaultValueText + '"' + placeholderText + '></textarea>';
                if (typeof config.MustValidate !== "undefined") {
                    if (config.MustValidate) {
                        inputFieldHtml += '<div id="' + config.FieldId + 'InvalidFeedback" class="invalid-feedback">\n' +
                            '' + validationMessageText + '</div>';
                    }
                }
                completeHtml = labelText + inputFieldHtml;
                break;
            case 'list':
                inputFieldHtml = ' <select name="' + config.FieldId + '" id="' + config.FieldId + '" class="custom-select">\n' +
                    '                        <option value="">' + defaultValueText + '</option>';
                if (typeof config.Data !== "undefined") {
                    let listValues = dataModel.getDataList(config.Data);
                    if (listValues.length > 0) {
                        listValues.forEach(function (item) {
                            inputFieldHtml += '<option value="' + item + '">' + item + '</option>';
                        });
                    }
                }
                inputFieldHtml += '</select>';
                if (typeof config.MustValidate !== "undefined") {
                    if (config.MustValidate) {
                        inputFieldHtml += '<div id="' + config.FieldId + 'InvalidFeedback" class="invalid-feedback">\n' +
                            '' + validationMessageText + '</div>';
                    }
                }
                completeHtml = labelText + inputFieldHtml;
                break;
            case 'checkbox':
                inputFieldHtml = '<input class="form-check-input" type="checkbox" name="' + config.FieldId + '" ' +
                    'id="' + config.FieldId + '"' + defaultValueText + '>';
                if (typeof config.MustValidate !== "undefined") {
                    if (config.MustValidate) {
                        inputFieldHtml += '<div id="' + config.FieldId + 'InvalidFeedback" class="invalid-feedback">\n' +
                            '' + validationMessageText + '</div>';
                    }
                }
                completeHtml = '<div class="form-check mt-3">' + inputFieldHtml + labelText + '</div>';
                break;
            default:
                inputFieldHtml = '<input id="' + config.FieldId + '" type="' + config.DisplayType + '" class="form-control"' +
                    '' + placeholderText + ' value="' + defaultValueText + '"/>';
                if (typeof config.MustValidate !== "undefined") {
                    if (config.MustValidate) {
                        inputFieldHtml += '<div id="' + config.FieldId + 'InvalidFeedback" class="invalid-feedback">\n' +
                            '' + validationMessageText + '</div>';
                    }
                }
                completeHtml = labelText + inputFieldHtml;
        }

        wrapperNode.html(completeHtml);
    }
};
/**
 * Responsible for determining whether the current page to be loaded has a mobile alternative
 * @type {{loadMobilePageAlternatives(*): void, mobilePageAlternates: {}, getMobilePageAlternate(*): (*)}}
 */
let dxPageManager = {
    mobilePageAlternates: {},
    /**
     * Loads the page alternate definitions from json
     * @param callback
     */
    loadMobilePageAlternatives(callback) {
        if (isDebugMode) {
            noCacheForceText = getRandomFilePostFix();
        }
        loadJsonFromFile(getRootPath() + 'project/assets/configurations/page_mobile_alternates.json' + noCacheForceText, function (json) {
            this.mobilePageAlternates = json;
            callback();
        }.bind(this));
    },
    /**
     * Returns the mobile alternate page for the given page_name if it is defined, otherwise the given page_name is
     * returned
     * @param pageName
     * @return {*}
     */
    getMobilePageAlternate(pageName) {
        if (!isScreenWidthMobile()) {
            return pageName;
        }
        if (typeof this.mobilePageAlternates[pageName] !== "undefined") {
            return this.mobilePageAlternates[pageName];
        }
        return pageName;
    }
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Divblox component and DOM related classes
 */

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * DivbloxDomBaseComponent is the base class that manages the component javascript for every component
 */
class DivbloxDomBaseComponent {
    /**
     * Initializes all the base variables for a Divblox dom component
     * @param {Object} inputs The arguments to pass to the component
     * @param {Boolean} supportsNative Indicate whether this component works on native projects
     * @param {Boolean} requiresNative Indicate whether this component works ONLY on native projects
     */
    constructor(inputs, supportsNative, requiresNative) {
        this.arguments = inputs;
        if (typeof supportsNative === "undefined") {
            supportsNative = true;
        }
        if (typeof requiresNative === "undefined") {
            requiresNative = false;
        }
        this.supportsNative = supportsNative;
        this.requiresNative = requiresNative;
        if (typeof (this.arguments["uid"]) !== "undefined") {
            this.uid = this.arguments["uid"];
        } else {
            this.uid = this.arguments["component_name"] + "_" + this.arguments["dom_index"];
        }
        this.componentSuccess = false;
        this.subComponentDefinitions = {};
        this.subComponentObjects = [];
        this.readySubComponents = {};
        this.subComponentLoadedCount = 0;
        this.allowedUserroles = [];
        this.usesLoadingState = false;
        this.isLoading = false;
        this.showingLoadingOverlay = false;
        this.isShowingLoadingOverlay = false;
        this.prerequisites = []; // See DivbloxDomEntityDataTableComponent for example of how to use
        this.prerequisiteLoadedIndex = 0;
        this.doConnectionCheck = false;
    }

    /**
     * Gets the current component's parent component obj
     * @return {null} | The parent component
     */
    getParentComponent() {
        let parentComponentUid = this.getLoadArgument('parent_uid');
        if ((parentComponentUid == null) || (parentComponentUid === '')) {
            return null;
        }
        return getRegisteredComponent(parentComponentUid);
    }

    /**
     * Shows a loading overlay (only for page components)
     */
    showLoadingOverlay() {
        if (this.getParentComponent() != null) {
            return;/*We cannot show a loading overlay for a sub component*/
        }
        this.isShowingLoadingOverlay = true;
        let overlayHtml = '<div id="' + this.getUid() + '_LoadingOverlay" class="loading-overlay"><div' +
            ' class="loading-overlay-animation"><div class="spinner-border text-dark" role="status">' +
            '<span class="sr-only">Loading...</span></div></div></div>';
        if (!getComponentElementById(this, 'LoadingOverlay').length) { // Only if not already appended before
            getComponentElementById(this, 'ComponentWrapper').append(overlayHtml);
        } else {
            getComponentElementById(this, 'LoadingOverlay').fadeIn();
        }
        getComponentElementById(this, 'LoadingOverlay').css('z-index', getHighestZIndex() + 100);
    }

    /**
     * Removes loading overlay (only for page components)
     */
    removeLoadingOverlay() {
        if (this.isShowingLoadingOverlay) {
            getComponentElementById(this, 'LoadingOverlay').fadeOut();
            this.isShowingLoadingOverlay = false;
        }
    }

    /**
     * Reports the current component's ready state to the parent once all sub components are ready
     */
    reportComponentReadiness() {
        if (this.isLoading) {
            return;
        }
        let parentComponent = this.getParentComponent();
        if (this.subComponentDefinitions.length === 0) {
            if (parentComponent != null) {
                parentComponent.reportSubComponentReady(this.getUid());
            } else {
                if (this.getUid() === pageUid) {
                    this.postPageLoadActions();
                }
                this.removeLoadingOverlay();
            }
        } else {
            let subComponentReadyUids = Object.keys(this.readySubComponents);
            let allComponentsReady = true;
            if (subComponentReadyUids.length < this.subComponentDefinitions.length) {
                allComponentsReady = false;
            }
            subComponentReadyUids.forEach(function (uid) {
                allComponentsReady &= this.readySubComponents[uid];
            }.bind(this));
            if (allComponentsReady) {
                if (parentComponent != null) {
                    parentComponent.reportSubComponentReady(this.getUid());
                } else {
                    if (this.getUid() === pageUid) {
                        this.postPageLoadActions();
                    }
                    this.removeLoadingOverlay();
                }
            }
        }
    }

    /**
     * Allows a sub component to inform this component that it is ready
     * @param subComponentUid: The uid of the calling sub component
     */
    reportSubComponentReady(subComponentUid) {
        this.readySubComponents[subComponentUid] = true;
        this.reportComponentReadiness();
    }

    /**
     * Used to load any prerequisites that a component may require before continuing to load the component
     * @param {Function} successCallback The function to call when prerequisites were loaded successfully
     * @param {Function} failCallback The function to call when something went wrong during load
     */
    loadPrerequisites(successCallback, failCallback) {
        if (typeof successCallback !== "function") {
            successCallback = function () {
            };
        }
        if (typeof failCallback !== "function") {
            failCallback = function () {
            };
        }
        if (this.prerequisiteLoadedIndex < this.prerequisites.length) {
            dxGetScript(getRootPath() + this.prerequisites[this.prerequisiteLoadedIndex], function () {
                this.prerequisiteLoadedIndex++;
                this.loadPrerequisites(successCallback, failCallback);
            }.bind(this));
        } else {
            successCallback();
        }
    }

    /**
     * This is the very first method that is called when a component is loaded. This triggers additional default
     * behaviour for the component
     * @param {Boolean} confirmSuccess Indicate whether the component should confirm a successful load or not
     * @param {Function} callback The function to call once the component is fully loaded and ready to go
     */
    onComponentLoaded(confirmSuccess, callback) {
        if (isNative()) {
            if (!this.supportsNative) {
                this.handleComponentError("Component " + this.uid + " does not support native.");
                return;
            }
        } else {
            if (this.requiresNative) {
                this.handleComponentError("Component " + this.uid + " requires a native implementation.");
                return;
            }
        }
        if (typeof confirmSuccess === "undefined") {
            confirmSuccess = true;
        }
        if (typeof callback !== "function") {
            callback = function () {
            };
        }
        if (this.showingLoadingOverlay) {
            this.showLoadingOverlay();
        }
        this.loadPrerequisites(function () {
            callback();
            dxCheckCurrentUserRole(this.allowedUserroles, function () {
                this.handleComponentAccessError("Access denied");
            }.bind(this), function () {
                if (confirmSuccess) {
                    this.handleComponentSuccess();
                }
                this.registerDomEvents();
                this.initCustomFunctions();
                // Load additional components here
                this.loadSubComponent();
                if (checkComponentBuilderActive()) {
                    setTimeout(function () {
                        //JGL: Some components might not remove their loading state if they do not receive
                        // initialization inputs. When we are in the component builder, we want to override this
                        if (this.isLoading) {
                            dxLog("Removing loading state if " + this.getComponentName() + " for component builder");
                            this.removeLoadingState();
                        }
                    }.bind(this), 1000);
                }
            }.bind(this));
        }.bind(this), function () {
            this.handleComponentError("Error loading component dependencies");
        }.bind(this));
    }

    /**
     * A useful function to call to reset the component state. Also the last function that is called once all sub
     * components are loaded
     * @param {Object} inputs The arguments to pass to the component
     * @param {boolean} propagate If true, will also reset all sub components
     */
    reset(inputs, propagate = false) {
        if (!this.usesLoadingState) {
            this.isLoading = false;
            this.reportComponentReadiness();
        }

        if (propagate) {
            this.resetSubComponents(inputs, true);
        }
        if (this.doConnectionCheck) {
            checkConnectionPerformance();
        }

        toggleUserRoleVisibility();
    }

    /**
     * Toggles the variable isLoading to true and displays the component loading state
     */
    setLoadingState() {
        this.usesLoadingState = true;
        this.isLoading = true;
        $("#" + this.uid + "_ComponentContent").hide();
        $("#" + this.uid + "_ComponentPlaceholder").show();
        $("#" + this.uid + "_ComponentFeedback").html('');
    }

    /**
     * Toggles the variable isLoading to false and removes the component loading state
     */
    removeLoadingState() {
        if (this.isLoading) {
            this.isLoading = false;
            this.reportComponentReadiness();
        }
        $("#" + this.uid + "_ComponentContent").show();
        $("#" + this.uid + "_ComponentPlaceholder").hide();
    }

    /**
     * Calls the reset function for all of this component's sub components
     * @param inputs
     * @param propagate
     */
    resetSubComponents(inputs, propagate) {
        this.subComponentObjects.forEach(function (component) {
            component.reset(inputs, propagate);
        }.bind(this));
    }

    /**
     * Checks whether this component is ready
     * @return {boolean} true if ready, false if not
     */
    getReadyState() {
        return this.componentSuccess && !this.isLoading;
    }

    /**
     * Used to remove the loading or error state when a component loads successfully
     * @param additionalInput Unused
     */
    handleComponentSuccess(additionalInput) {
        if (this.componentSuccess === true) {
            return;
        }
        this.componentSuccess = true;
        $("#" + this.uid + "_ComponentContent").show();
        $("#" + this.uid + "_ComponentPlaceholder").hide();
        if (typeof isComponentBuilderActive !== "undefined") {
            if (isComponentBuilderActive) {
                addComponentOverlay(this);
            }
        }
    }

    /**
     * Used to display the error state with a relevant message for this component
     * @param {String} errorMessage The error message to display
     */
    handleComponentError(errorMessage) {
        this.componentSuccess = false;
        this.removeLoadingOverlay();
        $("#" + this.uid + "_ComponentContent").hide();
        $("#" + this.uid + "_ComponentPlaceholder").show();
        $("#" + this.uid + "_ComponentFeedback").html('<div class="alert alert-danger alert-danger-component"><strong><i' +
            ' class="fa fa-exclamation-triangle ComponentErrorExclamation" aria-hidden="true"></i>' +
            ' </strong><br>' + errorMessage + '</div>');
        if (typeof isComponentBuilderActive !== "undefined") {
            if (isComponentBuilderActive) {
                addComponentOverlay(this);
            }
        }
    }

    /**
     * Used to display an access error for the current component
     * @param {String} errorMessage The error message to display
     */
    handleComponentAccessError(errorMessage) {
        this.handleComponentError(errorMessage);
    }

    /**
     * When registering DOM events it is useful to keep track of them per component if we want to offload them
     * later. This method is a wrapper for that functionality
     */
    registerDomEvents() {/*To be overridden in sub class as needed*/
    }

    /**
     * Called by onComponentLoaded to allow us to initiate additional local functions for this component
     */
    initCustomFunctions() {/*To be overridden in sub class as needed*/
    }

    /**
     * A default callback method that is called whenever a sub component is successfully loaded
     * @param {Object} component The component that was loaded
     */
    subComponentLoadedCallBack(component) {
        this.registerComponentAsSubComponent(component);
        this.loadSubComponent();
        // JGL: Override as needed
    }

    /**
     * Registers the given component as a sub component for the current component
     * @param {Object} component The component that was loaded
     */
    registerComponentAsSubComponent(component) {
        if (!this.subComponentObjects.includes(component)) {
            this.subComponentObjects.push(component);
            this.subComponentLoadedCount++;
            this.readySubComponents[component.getUid()] = false;
        }
        // JGL: Override as needed
    }

    /**
     * Loads the next sub component as defined in subComponentDefinitions
     */
    loadSubComponent() {
        if (typeof this.subComponentDefinitions[this.subComponentLoadedCount] !== "undefined") {
            let subComponentDefinition = this.subComponentDefinitions[this.subComponentLoadedCount];
            loadComponent(subComponentDefinition.component_load_path, this.uid, subComponentDefinition.parent_element, subComponentDefinition.arguments, false, false, this.subComponentLoadedCallBack.bind(this));
        } else {
            this.reset({}, false);
        }
    }

    /**
     * Gets all the sub components for the current component
     * @return {Array} The array of sub component objects
     */
    getSubComponents() {
        return this.subComponentObjects;
    }

    /**
     * Gets all the sub component definitions for the current component
     * @return {Array} The array of sub component definitions
     */
    getSubComponentDefinitions() {
        return this.subComponentDefinitions;
    }

    /**
     * Gets the current component's UID
     * @return {String} The current component's UID
     */
    getUid() {
        return this.uid;
    }

    /**
     * Gets the current component's name
     * @return {String} The current component's name
     */
    getComponentName() {
        return this.arguments['component_name'];
    }

    /**
     * Gets an argument defined for the current component
     * @param {String} argument The argument to return
     * @return {*|Null} The value of the argument if it exists, null if not
     */
    getLoadArgument(argument) {
        if (typeof this.arguments[argument] !== "undefined") {
            return this.arguments[argument];
        }
        if (typeof this.arguments["url_parameters"] !== "undefined") {
            if (typeof this.arguments["url_parameters"][argument] !== "undefined") {
                return this.arguments["url_parameters"][argument];
            }
        }
        return null;
    }

    /**
     * Handles a propagated event on the current component and continues the propagation to this component's sub
     * components
     * @param {String} eventName The name of the event that was received
     * @param {Object} parameters An object with inputs passed with the event
     */
    eventTriggered(eventName, parameters) {
        switch (eventName) {
            case '[event_name]':
            default:
                dxLog("Event triggered: " + eventName + ": " + JSON.stringify(parameters));
        }
        // Let's pass the event to all sub components
        this.propagateEventTriggered(eventName, parameters);
    }

    /**
     * Propagates an event to this component's sub components
     * @param {String} eventName The name of the event that was received
     * @param {Object} parameters An object with inputs passed with the event
     */
    propagateEventTriggered(eventName, parameters) {
        this.subComponentObjects.forEach(function (component) {
            component.eventTriggered(eventName, parameters);
        });
    }

    /**
     * Processes the post page load actions if this component is the main page component
     */
    postPageLoadActions() {
        menuManager.renderAllMenus();
        initFeedbackCapture();
        loadCurrentUserProfilePicture();
        renderAppLogo();
        toggleUserRoleVisibility();
        doAfterPageLoadActions();
    }

    /**
     * Fires when the native app is paused
     */
    onNativePause() {
        //TODO: Implement this if required
    }

    /**
     * Fires when the native app is resumed
     */
    onNativeResume() {
        //TODO: Implement this if required
    }

    /**
     * Just a helper function to reference on cancel of confirmation
     */
    doNothing() {
    };

    /**
     * Registers an event handler for the provided event on the specified class and executes a specified function within
     * this component while passing the trigger element's filtered id
     * @param event The event to listen for, e.g "click", "hover", etc
     * @param eventTriggerClass The class to listen on
     * @param idMatchText A string that is contained within the html element's id that should be stripped in order to
     * find the id to pass to the relevant function. This string is expected to be right before the id that we want to use
     * @param eventFunction The name of the function inside this component to execute
     */
    handleOnClassEvent(event = "click", eventTriggerClass = null, idMatchText = null, eventFunction = null) {
        if (eventTriggerClass === null) {
            return;
        }
        if (idMatchText === null) {
            return;
        }
        if (eventFunction === null) {
            return;
        }
        let thisComponent = getRegisteredComponent(this.getUid());
        $(document).on(event, "." + eventTriggerClass, function (event) {
            let idStart = $(this).attr("id").indexOf(idMatchText);
            let clickedId = $(this).attr("id").substring(idStart + (idMatchText.length));
            thisComponent[eventFunction](clickedId, event);
            return false;
        });
        registerEventHandler(document, event, undefined, "." + eventTriggerClass);
    }
}

/**
 * DivbloxDomEntityInstanceComponent is the base class that manages the component javascript for every entity
 * instance (CREATE/UPDATE) component
 */
class DivbloxDomEntityInstanceComponent extends DivbloxDomBaseComponent {
    /**
     * Initializes all the further variables needed for a Divblox DOM entity instance component
     * @param {Object} inputs The arguments to pass to the component
     * @param {Boolean} supportsNative Indicate whether this component works on native projects
     * @param {Boolean} requiresNative Indicate whether this component works ONLY on native projects
     */
    constructor(inputs, supportsNative, requiresNative) {
        super(inputs, supportsNative, requiresNative);
        this.componentObj = {};
        this.elementMapping = {};
        this.includedAttributes = [];
        this.includedRelationships = [];
        this.dataValidations = [];
        this.customValidations = [];
        this.requiredValidations = [];
        this.relationships = {};
        this.constrainedByEntities = [];
        this.entityName = undefined;
        this.lowerCaseEntityName = undefined;
        // Call this.initCrudVariables("YourEntityName") in the implementing class
    }

    /**
     * Initializes the necessary CRUD variables, setting validation requirements as well as rendering input fields
     * @param {String} entityName The entity in question
     */
    initCrudVariables(entityName) {
        this.requiredValidations = this.requiredValidations.concat(this.dataValidations).concat(this.customValidations);
        this.entityName = entityName;
        this.lowerCaseEntityName = entityName.replace(/([a-z0-9])([A-Z0-9])/g, '$1_$2').toLowerCase();
        this.renderInputFields();
    }

    /**
     * Renders the input fields in the DOM for specified entity
     */
    renderInputFields() {
        getComponentElementById(this, 'AdditionalInputFieldsWrapper').html("");
        this.includedAttributes.forEach(function (attribute) {
            let wrapperId = attribute + "Wrapper";
            let wrapperNode = getComponentElementById(this, wrapperId);
            if (!wrapperNode.length) {
                wrapperNode = this.addDynamicIncludedField(attribute);
            }
            wrapperNode.show();
            let entityAttributeProperties = dataModel.getEntityAttributeProperties(this.entityName, attribute);
            let renderConfig = {
                ...{
                    WrapperId: this.getUid() + "_" + wrapperId,
                    FieldId: this.getUid() + "_" + attribute,
                    MustValidate: (this.requiredValidations.indexOf(attribute) > -1)
                },
                ...entityAttributeProperties
            };
            dxRenderer.renderInputField(renderConfig);

        }.bind(this));

        this.includedRelationships.forEach(function (relationship) {
            let wrapperId = relationship + "Wrapper";
            let wrapperNode = getComponentElementById(this, wrapperId);
            if (!wrapperNode.length) {
                wrapperNode = this.addDynamicIncludedField(relationship);
            }
            wrapperNode.show();
            let entityRelationshipProperties = dataModel.getEntityRelationshipProperties(this.entityName, relationship);
            let renderConfig = {
                ...{
                    WrapperId: this.getUid() + "_" + wrapperId,
                    FieldId: this.getUid() + "_" + relationship,
                    MustValidate: (this.requiredValidations.indexOf(relationship) > -1)
                },
                ...entityRelationshipProperties
            };
            dxRenderer.renderInputField(renderConfig);
        }.bind(this));
    }

    /**
     * Includes input fields for attributes added dynamically after creation of the component
     * @param {String} fieldName Attribute for which input field is being created
     */
    addDynamicIncludedField(fieldName) {
        let wrapperId = fieldName + "Wrapper";
        let cbClass = '';
        if (checkComponentBuilderActive()) {
            cbClass = ' component-builder-column';
        }
        getComponentElementById(this, 'AdditionalInputFieldsWrapper').append(
            '<div id="' + this.getUid() + '_' + wrapperId + '" class="col-sm-6' +
            ' col-md-4 col-xl-3 entity-instance-input-field' + cbClass + '"> {' + fieldName + '}</div>');
        return getComponentElementById(this, wrapperId);
    }

    /**
     * Overriding default Divblox functionality to also set loading state and load the entity
     * @param {Object} inputs The arguments to pass to the component
     * @param {boolean} propagate If true, will also reset all sub components
     */
    reset(inputs, propagate) {
        this.setLoadingState();
        this.loadEntity();
        super.reset(inputs, propagate);
    }

    /**
     * Sets the local entity Id
     * @param {String} id The Id of entity instance to set
     */
    setEntityId(id) {
        this.arguments["entity_id"] = id;
    }

    /**
     * Gets the local entity Id
     * @return {int} The Entity Id
     */
    getEntityId() {
        return this.getLoadArgument("entity_id");
    }

    /**
     * When registering DOM events it is useful to keep track of them per component if we want to offload them later.
     */
    registerDomEvents() {
        getComponentElementById(this, "btnSave").on("click", function () {
            this.saveEntity();
        }.bind(this));

        getComponentElementById(this, "btnDelete").on("click", function () {
            showAlert("Are you sure?", "warning", ["Cancel", "Delete"], false, 0, this.deleteEntity.bind(this), this.doNothing);
        }.bind(this));

    }

    /**
     * Returns the input parameters object for the dxRequestInternal function call that loads the entity
     * @returns {Object} The object to pass to dxRequestInternal
     */
    getLoadFunctionParameters() {
        return {f: "getObjectData", Id: this.getEntityId()};
    }

    /**
     * Called before the server request fires in the loadEntity() function, giving room for functionality that should be
     * executed immediately before load. Can be used to stop the loadEntity() function by returning false
     */
    onBeforeLoadEntity() {
        //TODO: Override this as needed;
        return true;
    }

    /**
     * Loads the entity into the DOM. Handles communication with the backend as well as display on the front end.
     */
    loadEntity() {
        if (!this.onBeforeLoadEntity()) {
            return;
        }
        dxRequestInternal(
            getComponentControllerPath(this),
            this.getLoadFunctionParameters(),
            function (data) {
                this.removeLoadingState();
                let entity = {};
                if (typeof data.Object !== "undefined") {
                    entity = data.Object;
                }
                this.componentObj = {};
                this.elementMapping = {};
                if (Object.keys(entity).length > 0) {
                    this.componentObj = entity;
                }
                this.includedAttributes.forEach(function (attribute) {
                    if (Object.keys(entity).length === 0) {
                        this.componentObj[attribute] = "";
                    }
                    this.elementMapping[attribute] = "#" + this.getUid() + "_" + attribute;
                }.bind(this));
                this.includedRelationships.forEach(function (relationship) {
                    if (Object.keys(entity).length === 0) {
                        this.componentObj[relationship] = "";
                    }
                    this.elementMapping[relationship] = "#" + this.getUid() + "_" + relationship;
                    this.relationships[relationship] = data[relationship + "List"];
                }.bind(this));
                this.setValues();
                this.onAfterLoadEntity(data);
            }.bind(this), function (data) {
                this.removeLoadingState();
                this.handleComponentError(data.Message);
            }.bind(this));
    }

    /**
     * Called last in the success callback of the loadEntity() function, giving room for functionality that should be
     * executed immediately after load
     * @param data Input data object
     */
    onAfterLoadEntity(data) {
        //TODO: Override this as needed;
    }

    /**
     * Called in the loadEntity() function, sets the values of each attribute
     */
    setValues() {
        this.includedAttributes.forEach(function (attribute) {
            let entityAttributeProperties = dataModel.getEntityAttributeProperties(this.entityName, attribute);
            if (entityAttributeProperties.DisplayType === 'checkbox') {
                getComponentElementById(this, attribute).prop("checked", entityAttributeProperties.DefaultValue);
                if (typeof this.componentObj[attribute] !== "undefined") {
                    getComponentElementById(this, attribute).prop("checked", this.componentObj[attribute]);
                }
            } else {
                getComponentElementById(this, attribute).val(getDataModelAttributeValue(entityAttributeProperties.DefaultValue, entityAttributeProperties.DisplayType));
                if (typeof this.componentObj[attribute] !== "undefined") {
                    getComponentElementById(this, attribute).val(getDataModelAttributeValue(this.componentObj[attribute], entityAttributeProperties.DisplayType));
                }
            }
        }.bind(this));

        this.includedRelationships.forEach(function (relationship) {
            getComponentElementById(this, relationship).html('<option value="">-Please Select-</option>');
            if (typeof this.relationships[relationship] === "object") {
                let relationships = Object.keys(this.relationships[relationship]);
                if (relationships.length > 0) {
                    this.relationships[relationship].forEach(function (relationshipItem) {
                        if (relationshipItem['Id'] == "DATASET TOO LARGE") {
                            dxLog("Data set too large for " + relationship + ". Consider using another option to link the object");
                        } else {
                            getComponentElementById(this, relationship).append('<option value="' + relationshipItem['Id'] + '">' + relationshipItem['DisplayValue'] + '</option>');
                        }
                    }.bind(this));
                    if (typeof this.componentObj[relationship] !== "undefined") {
                        getComponentElementById(this, relationship).val(getDataModelAttributeValue(this.componentObj[relationship]));
                    }
                }
            }
        }.bind(this));
    }

    /**
     * Called in the saveEntity() function, updates the values of each attribute
     * @return {object} component_obj All the updated values of current entity
     */
    updateValues() {
        let keys = Object.keys(this.elementMapping);
        keys.forEach(function (item) {
            if ($(this.elementMapping[item]).attr("type") === "checkbox") {
                this.componentObj[item] = $(this.elementMapping[item]).is(':checked') ? 1 : 0;
            } else {
                this.componentObj[item] = $(this.elementMapping[item]).val();
            }
        }.bind(this));
        return this.componentObj;
    }

    /**
     * Returns the input parameters object for the dxRequestInternal function call that saves the entity
     * @returns {Object} The object to pass to dxRequestInternal
     */
    getSaveFunctionParameters() {
        let currentObjectData = this.updateValues();
        let parameters = {
            f: "saveObjectData",
            ObjectData: JSON.stringify(currentObjectData),
            Id: this.getEntityId()
        };
        if (this.constrainedByEntities.length > 0) {
            this.constrainedByEntities.forEach(function (relationship) {
                parameters['Constraining' + relationship + 'Id'] = getGlobalConstrainById(relationship);
            });
        }
        return parameters;
    }

    /**
     * Called before the server request fires in the saveEntity() function, giving room for functionality that should be
     * executed immediately before save. Can be used to stop the saveEntity() function by returning false
     */
    onBeforeSaveEntity() {
        this.resetValidation();
        return this.validateEntity();
    }

    /**
     * Sends updated entity object data to the backend to be saved
     */
    saveEntity() {
        if (!this.onBeforeSaveEntity()) {
            return;
        }
        dxRequestInternal(
            getComponentControllerPath(this),
            this.getSaveFunctionParameters(),
            function (data) {
                if (this.getLoadArgument("entity_id") != null) {
                    setGlobalConstrainById(this.entityName, data.Id);
                    pageEventTriggered(this.lowerCaseEntityName + "_updated", {"id": data.Id});
                } else {
                    setGlobalConstrainById(this.entityName, data.Id);
                    pageEventTriggered(this.lowerCaseEntityName + "_created", {"id": data.Id});
                }
                this.loadEntity();
                this.resetValidation();
                this.onAfterSaveEntity(data);
            }.bind(this),
            function (data) {
                showAlert("Error saving " + this.lowerCaseEntityName + ": " + data.Message, "error", "OK", false);
            }.bind(this), false, getComponentElementById(this, "btnSave"), "Saving");
    }

    /**
     * Called last in the success callback of the saveEntity() function, giving room for functionality that should be
     * executed immediately after save
     * @param data Input data object
     */
    onAfterSaveEntity(data) {
        //TODO: Override this as needed;
    }

    /**
     * Returns the input parameters object for the dxRequestInternal function call that deletes the entity
     * @returns {Object} The object to pass to dxRequestInternal
     */
    getDeleteFunctionParameters() {
        return {
            f: "deleteObjectData",
            Id: this.getLoadArgument("entity_id")
        };
    }

    /**
     * Called before the server request fires in the deleteEntity() function, giving room for functionality that should be
     * executed immediately before delete. Can be used to stop the deleteEntity() function by returning false
     */
    onBeforeDeleteEntity() {
        //TODO: Override this as needed;
        return true;
    }

    /**
     * Sends request to the backend to delete entity instance
     */
    deleteEntity() {
        if (!this.onBeforeDeleteEntity()) {
            return;
        }
        dxRequestInternal(
            getComponentControllerPath(this),
            this.getDeleteFunctionParameters(),
            function (data) {
                this.loadEntity();
                pageEventTriggered(this.lowerCaseEntityName + "_deleted");
                this.onAfterDeleteEntity(data);
            }.bind(this),
            function (data) {
                showAlert("Error deleting " + this.lowerCaseEntityName + ": " + data.Message, "error", "OK", false);
            }.bind(this));
    }

    /**
     * Called last in the success callback of the deleteEntity() function, giving room for functionality that should be
     * executed immediately after delete
     * @param data Input data object
     */
    onAfterDeleteEntity(data) {
        //TODO: Override this as needed;
    }

    /**
     * Performs validation upon request submission, taking into account default and custom validation rules.
     * Implemented with CSS class toggle
     */
    validateEntity() {
        let isValid = true;
        this.requiredValidations.forEach(function (item) {
            if (getComponentElementById(this, item).attr("type") !== "checkbox") {
                if (getComponentElementById(this, item).val() == "") {
                    isValid = false;
                    toggleValidationState(this, item, "", false);
                } else {
                    toggleValidationState(this, item, "", true);
                }
            }
        }.bind(this));
        this.dataValidations.forEach(function (item) {
            if (!getComponentElementById(this, item).hasClass("is-invalid")) {
                if (getComponentElementById(this, item).hasClass("validate-number")) {
                    if (isNaN(getComponentElementById(this, item).val())) {
                        isValid = false;
                        toggleValidationState(this, item, "", false);
                    } else {
                        toggleValidationState(this, item, "", true);
                    }
                }
            }
        }.bind(this));
        this.customValidations.forEach(function (item) {
            if (checkValidationState(this, item)) {
                isValid &= this.doCustomValidation(item);
            }
        }.bind(this));
        return isValid;
    }

    /**
     * Set up custom validation rules
     * @param attribute Attribute/s to do custom validation on
     */
    doCustomValidation(attribute) {
        switch (attribute) {
            default:
                return true;
                break;
        }
    }

    /**
     * Resets validation state
     */
    resetValidation() {
        this.requiredValidations.forEach(function (item) {
            toggleValidationState(this, item, "", true, true);
        }.bind(this));
    }
}

/**
 * DivbloxDomEntityDataTableComponent is the base class that manages the component javascript for every entity
 * data table component
 */
class DivbloxDomEntityDataTableComponent extends DivbloxDomBaseComponent {
    /**
     * Initializes all the further variables needed for a Divblox DOM entity data table component
     * @param {Object} inputs The arguments to pass to the component
     * @param {Boolean} supportsNative Indicate whether this component works on native projects
     * @param {Boolean} requiresNative Indicate whether this component works ONLY on native projects
     */
    constructor(inputs, supportsNative, requiresNative) {
        super(inputs, supportsNative, requiresNative);
        this.tableExporter = undefined;
        // Data table export functionality provided by TableExport plugin.
        // Documentation here: https://tableexport.v5.travismclarke.com/#tableexport
        // Default properties:
        /*
        TableExport(document.getElementsByTagName("table"), {
        headers: true,                      // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
        footers: true,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
        formats: ["xlsx", "csv", "txt"],    // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
        filename: "id",                     // (id, String), filename for the downloaded file, (default: 'id')
        bootstrap: false,                   // (Boolean), style buttons using bootstrap, (default: true)
        exportButtons: true,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
        position: "bottom",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
        ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
        ignoreCols: null,                   // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
        trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
        RTL: false,                         // (Boolean), set direction of the worksheet to right-to-left (default: false)
        sheetname: "id"                     // (id, String), sheet name for the exported spreadsheet, (default: 'id')
        });
        */
        this.entityName = undefined;
        this.lowerCaseEntityName = undefined;
        this.currentPage = 1;
        this.itemsOnCurrentPage = [];
        this.currentItemsPerPage = $("#" + this.uid + "_PaginationItemsPerPage").val();
        this.totalItems = 0;
        this.totalPages = 0;
        this.remainingPages = 0;
        this.includedAttributes = [];
        this.includedRelationships = [];
        this.constrainedByEntities = [];
        this.columnNamesInfo = {};
        this.columnNames = [];
        this.currentSortColumn = [];
        this.selectedItems = [];
        this.prerequisites = [
            'project/assets/js/tableexport/xlsx.core.min.js',
            'project/assets/js/tableexport/FileSaver.min.js',
            'project/assets/js/tableexport/tableexport.min.js'];
        this.clickEventColumnIndex = 1;
        // Call this.initDataTableVariables("YourEntityName") in the implementing class
    }

    /**
     * Initializes the necessary Data Table variables
     */
    initDataTableVariables(entityName) {
        this.entityName = entityName;
        this.lowerCaseEntityName = entityName.replace(/([a-z0-9])([A-Z])/g, '$1_$2').toLowerCase();

        this.buildDataTableHeaderHtml();

        this.columnNames = Object.keys(this.columnNamesInfo);
        this.currentSortColumn = [this.columnNames[0], true]; // Sort on first column, desc
        //DataTableHeaderHtml
    }

    /**
     * Creates the table header
     */
    buildDataTableHeaderHtml() {
        getComponentElementById(this, 'DataTableHeaderHtml').html(
            '<th id="' + this.getUid() + '_MultiSelectColumn" class="data_table_header" scope="col">\n' +
            '<input id="' + this.getUid() + '_MultiSelectAll" type="checkbox" name="all" value="all">\n' +
            '</th>');

        this.includedAttributes.forEach(function (attribute) {
            this.columnNamesInfo[attribute] = attribute.replace(/([a-z0-9])([A-Z])/g, '$1 $2');
            getComponentElementById(this, 'DataTableHeaderHtml').append(
                '<th id="' + this.getUid() + '_SortBy' + attribute + '" class="data_table_header" scope="col">' + this.columnNamesInfo[attribute] + '</th>'
            );
        }.bind(this));
        this.includedRelationships.forEach(function (relationship) {
            this.columnNamesInfo[relationship] = relationship.replace(/([a-z0-9])([A-Z])/g, '$1 $2');
            getComponentElementById(this, 'DataTableHeaderHtml').append(
                '<th id="' + this.getUid() + '_SortBy' + relationship + '" class="data_table_header" scope="col">' + this.columnNamesInfo[relationship] + '</th>'
            );
        }.bind(this));
    }

    /**
     * Displays the loading indicator for the table
     */
    showPageLoadingIndicator() {
        let maxColumns = this.columnNames.length + 1;
        getComponentElementById(this, "DataTableBody").html('<tr id="' + this.getUid() + '_DataTableLoading"><td' +
            ' colspan="' + maxColumns + '"' +
            '><div class="dx-loading"></div></td></tr>');
    }

    /**
     * Toggles the "No results" view. Called in the loadPage() function if no data available
     */
    toggleNoResults() {
        let maxColumns = this.columnNames.length + 1;
        if (this.totalItems === 0) {
            getComponentElementById(this, "DataTableBody").html('<tr id="#' + this.getUid() + '_DataTableLoading"><td' +
                ' colspan="' + maxColumns + '"' +
                ' style="text-align: center;">No results</td></tr>');
            getComponentElementById(this, "DataTableLoading").show();
        }
    }

    /**
     * Updates the pagination button content
     */
    updatePagination() {
        let nextPage = this.currentPage + 1;
        let nextNextPage = nextPage + 1;
        getComponentElementById(this, "PaginationCurrentItem").html('<span class="page-link">' + this.currentPage + '</span>');
        getComponentElementById(this, "PaginationNextItem").html('<span class="page-link">' + nextPage + '</span>');
        getComponentElementById(this, "PaginationNextNextItem").html('<span class="page-link">' + nextNextPage + '</span>');
        getComponentElementById(this, "ResultCountWrapper").html('<span class="badge float-right">Total: ' + this.totalItems + '</span>');
    }

    /**
     * Clears the contents of the table
     */
    clearDataTableBody() {
        getComponentElementById(this, "DataTableBody").html("");
    }

    /**
     * A useful function to call to reset the component state
     * @param {Object} inputs The arguments to pass to the component
     * @param {boolean} propagate If true, will also reset all sub components
     */
    reset(inputs, propagate) {
        this.loadPage();
        super.reset(inputs, propagate);
    }

    /**
     * When registering DOM events it is useful to keep track of them per component if we want to offload them
     * later. This method is a wrapper for that functionality
     */
    registerDomEvents() {
        getComponentElementById(this, "BulkActionExportXlsx").on("click", function () {
            let uid = this.getUid();
            this.tableExporter = getComponentElementById(this, "DataTableTableHtml").tableExport({
                exportButtons: false,
                formats: ['xlsx'],
                filename: "dx_xlsx_export_" + moment().format("YYYY-MM-DD_h_mm_ss"),
            });
            this.exportData(this.tableExporter.getExportData()[uid + '_DataTableTableHtml']['xlsx']);
        }.bind(this));
        getComponentElementById(this, "BulkActionExportCsv").on("click", function () {
            let uid = this.getUid();
            this.tableExporter = getComponentElementById(this, "DataTableTableHtml").tableExport({
                exportButtons: false,
                formats: ['csv'],
                filename: "dx_csv_export_" + moment().format("YYYY-MM-DD_h_mm_ss"),
            });
            this.exportData(this.tableExporter.getExportData()[uid + '_DataTableTableHtml']['csv']);
        }.bind(this));
        getComponentElementById(this, "BulkActionExportTxt").on("click", function () {
            let uid = this.getUid();
            this.tableExporter = getComponentElementById(this, "DataTableTableHtml").tableExport({
                exportButtons: false,
                formats: ['txt'],
                filename: "dx_txt_export_" + moment().format("YYYY-MM-DD_h_mm_ss"),
            });
            this.exportData(this.tableExporter.getExportData()[uid + '_DataTableTableHtml']['txt']);
        }.bind(this));
        getComponentElementById(this, "DataTableSearchInput").on("keyup", function () {
            let searchText = getComponentElementById(this, "DataTableSearchInput").val();
            setTimeout(function () {
                if (searchText == getComponentElementById(this, "DataTableSearchInput").val()) {
                    this.currentPage = 1;
                    this.loadPage();
                }
            }.bind(this), 500);
        }.bind(this));
        getComponentElementById(this, "btnResetSearch").on("click", function () {
            getComponentElementById(this, "DataTableSearchInput").val("");
            this.loadPage();
        }.bind(this));
        getComponentElementById(this, "PaginationItemsPerPage").on("change", function () {
            let uid = $(this).attr("id").replace("_PaginationItemsPerPage", "");
            let thisComponent = getRegisteredComponent(uid);
            thisComponent.currentItemsPerPage = $(this).val();
            thisComponent.loadPage();
        });
        getComponentElementById(this, "PaginationResetButton").on("click", function () {
            if ($(this).hasClass("disabled")) {
                return;
            }
            let uid = $(this).attr("id").replace("_PaginationResetButton", "");
            let thisComponent = getRegisteredComponent(uid);
            thisComponent.currentPage = 1;
            thisComponent.loadPage();
        });
        getComponentElementById(this, "PaginationFinalPageButton").on("click", function () {
            if ($(this).hasClass("disabled")) {
                return;
            }
            let uid = $(this).attr("id").replace("_PaginationFinalPageButton", "");
            let thisComponent = getRegisteredComponent(uid);
            thisComponent.currentPage = thisComponent.totalPages;
            thisComponent.loadPage();
        });
        getComponentElementById(this, "PaginationJumpBack").on("click", function () {
            if ($(this).hasClass("disabled")) {
                return;
            }
            let uid = $(this).attr("id").replace("_PaginationJumpBack", "");
            let thisComponent = getRegisteredComponent(uid);
            thisComponent.currentPage = thisComponent.currentPage - 3;
            if (thisComponent.currentPage < 1) {
                thisComponent.currentPage = 1;
            }
            thisComponent.loadPage();
        });
        getComponentElementById(this, "PaginationJumpForward").on("click", function () {
            if ($(this).hasClass("disabled")) {
                return;
            }
            let uid = $(this).attr("id").replace("_PaginationJumpForward", "");
            let thisComponent = getRegisteredComponent(uid);
            thisComponent.currentPage = thisComponent.currentPage + 3;
            if (thisComponent.currentPage > thisComponent.totalPages) {
                thisComponent.currentPage = thisComponent.totalPages;
            }
            thisComponent.loadPage();
        });
        getComponentElementById(this, "PaginationNextItem").on("click", function () {
            this.currentPage = this.currentPage + 1;
            if (this.currentPage > this.totalPages) {
                this.currentPage = this.totalPages;
            }
            this.loadPage();
        }.bind(this));
        getComponentElementById(this, "PaginationNextNextItem").on("click", function () {
            this.currentPage = this.currentPage + 2;
            if (this.currentPage > this.totalPages) {
                this.currentPage = this.totalPages;
            }
            this.loadPage();
        }.bind(this));
        getComponentElementById(this, "MultiSelectAll").on("click", function () {
            let uid = $(this).attr("id").replace("_MultiSelectAll", "");
            let thisComponent = getRegisteredComponent(uid);
            if ($(this).is(":checked")) {
                thisComponent.selectedItems = [];
                $('.select_item_' + uid).each(function () {
                    let idStart = $(this).attr("id").indexOf("_select_item_");
                    let objectId = $(this).attr("id").substring(idStart + 13);
                    thisComponent.selectedItems.push(objectId);
                    $(this).prop("checked", true);
                });
                getComponentElementById(thisComponent, "MultiSelectOptionsButton").show().addClass("d-inline-flex");
            } else {
                thisComponent.selectedItems = [];
                $('.select_item_' + uid).each(function () {
                    $(this).prop("checked", false);
                });
                getComponentElementById(thisComponent, "MultiSelectOptionsButton").hide().removeClass("d-inline-flex");
            }
        });
        getComponentElementById(this, "BulkActionDelete").on("click", function () {
            showAlert("Are you sure?", "warning", ["Cancel", "Delete"], false, 0, this.deleteSelected.bind(this), this.doNothing);
        }.bind(this));
        this.handleOnClassEvent("click", "first-column_" + this.getUid(), "_row_item_", "onItemClicked");
        $(document).on("click", ".select_item_" + this.getUid(), function () {
            let idStart = $(this).attr("id").indexOf("_select_item_");
            let clickedId = $(this).attr("id").substring(idStart + 13);
            let uid = $(this).attr("id").substring(0, idStart);
            let thisComponent = getRegisteredComponent(uid);
            if (thisComponent.selectedItems.indexOf(clickedId) != -1) {
                thisComponent.selectedItems.splice(thisComponent.selectedItems.indexOf(clickedId), 1);
            } else {
                thisComponent.selectedItems.push(clickedId);
            }
            if (thisComponent.selectedItems.length > 0) {
                getComponentElementById(thisComponent, "MultiSelectOptionsButton").show().addClass("d-inline-flex");
            } else {
                getComponentElementById(thisComponent, "MultiSelectOptionsButton").hide().removeClass("d-inline-flex");
            }
        });
        registerEventHandler(document, "click", undefined, ".select_item_" + this.getUid());
        this.columnNames.forEach(function (item) {
            let uid = this.getUid();
            $("#" + uid + "_SortBy" + item).on("click", function () {
                if (typeof this.currentSortColumn[1] !== "undefined") {
                    let sortDown = !this.currentSortColumn[1];
                    this.currentSortColumn = [item, sortDown];
                } else {
                    this.currentSortColumn = [item, true];
                }
                this.columnNames.forEach(function (itemToUpdate) {
                    if (itemToUpdate == item) {
                        if (this.currentSortColumn[1]) {
                            $("#" + uid + "_SortBy" + itemToUpdate).html(this.columnNamesInfo[itemToUpdate] + ' <small><i class="fa' +
                                ' fa-sort-alpha-asc" aria-hidden="true"></i></small>');
                        } else {
                            $("#" + uid + "_SortBy" + itemToUpdate).html(this.columnNamesInfo[itemToUpdate] + ' <small><i class="fa' +
                                ' fa-sort-alpha-desc" aria-hidden="true"></i></small>');
                        }
                    } else {
                        $("#" + uid + "_SortBy" + itemToUpdate).html(this.columnNamesInfo[itemToUpdate]);
                    }
                }.bind(this));
                this.loadPage();
            }.bind(this));
        }.bind(this));
    }

    /**
     * Sets the class variable table_exporter with relevant data.
     * @param tableExporterData
     */
    exportData(tableExporterData) {
        this.tableExporter.export2file(
            tableExporterData.data,
            tableExporterData.mimeType,
            tableExporterData.filename,
            tableExporterData.fileExtension);
    }

    /**
     * Returns the input parameters object for the dxRequestInternal function call that deletes the selection
     * @returns {Object} The object to pass to dxRequestInternal
     */
    getDeleteFunctionParameters() {
        return {
            f: "deleteSelection",
            SelectedItemArray: JSON.stringify(this.selectedItems)
        };
    }

    /**
     * Called before the server request fires in the deleteSelected() function, giving room for functionality that should be
     * executed immediately before delete. Can be used to stop the deleteSelected() function by returning false
     */
    onBeforeDeleteSelected() {
        //TODO: Override this as needed;
        return true;
    }

    /**
     * Backend request to delete selected entires from database.
     */
    deleteSelected() {
        if (!this.onBeforeDeleteSelected()) {
            return;
        }
        dxRequestInternal(
            getComponentControllerPath(this),
            this.getDeleteFunctionParameters(),
            function (data) {
                getComponentElementById(this, "MultiSelectAll").prop("checked", false);
                this.selectedItems = [];
                this.currentPage = 1;
                this.loadPage();
                pageEventTriggered(this.lowerCaseEntityName + "_selection_deleted", {});
                this.onAfterDeleteSelected(data);
            }.bind(this),
            function (data) {
                showAlert("Error deleting items: " + data.Message, "error", "OK", false);
            }.bind(this));
    }

    /**
     * Called last in the success callback of the deleteSelected() function, gives room for functionality to be executed
     * immediately after deletion
     * @param data Full data object from loadPage() function
     */
    onAfterDeleteSelected(data) {
        //TODO: Override this as needed;
    }

    /**
     * Returns the input parameters object for the dxRequestInternal function call that loads the page
     * @returns {Object} The object to pass to dxRequestInternal
     */
    getLoadFunctionParameters() {
        let parameters = {
            f: "getPage",
            CurrentPage: this.currentPage,
            ItemsPerPage: this.currentItemsPerPage,
            SearchText: getComponentElementById(this, "DataTableSearchInput").val(),
            SortOptions: JSON.stringify(this.currentSortColumn)
        };
        if (this.constrainedByEntities.length > 0) {
            this.constrainedByEntities.forEach(function (relationship) {
                parameters['Constraining' + relationship + 'Id'] = getGlobalConstrainById(relationship);
            });
        }
        return parameters;
    }

    /**
     * Called before the server request fires in the loadPage() function, giving room for functionality that should be
     * executed immediately before load. Can be used to stop the loadPage() function by returning false
     */
    onBeforeLoadPage() {
        //TODO: Override this as needed;
        return true;
    }

    /**
     * Load the data table up row by row, including pagination and search functionality
     */
    loadPage() {
        if (!this.onBeforeLoadPage()) {
            return;
        }
        this.showPageLoadingIndicator();
        dxRequestInternal(
            getComponentControllerPath(this),
            this.getLoadFunctionParameters(),
            function (data) {
                this.clearDataTableBody();
                data.Page.forEach(function (item) {
                    this.addRow(item);
                }.bind(this));
                this.totalItems = data.TotalCount;
                this.totalPages = 1 + Math.round(this.totalItems / this.currentItemsPerPage);
                this.remainingPages = this.totalPages - this.currentPage;
                if (this.itemsOnCurrentPage.length > 0) {
                    getComponentElementById(this, "DataTableLoading").hide();
                }
                this.toggleNoResults();
                if (this.currentPage === 1) {
                    getComponentElementById(this, "PaginationResetButton").addClass("disabled");
                    getComponentElementById(this, "PaginationJumpBack").addClass("disabled");
                } else {
                    getComponentElementById(this, "PaginationResetButton").removeClass("disabled");
                    getComponentElementById(this, "PaginationJumpBack").removeClass("disabled");
                }
                if (this.currentPage === this.totalPages) {
                    getComponentElementById(this, "PaginationFinalPageButton").addClass("disabled");
                    getComponentElementById(this, "PaginationJumpForward").addClass("disabled");
                } else {
                    getComponentElementById(this, "PaginationFinalPageButton").removeClass("disabled");
                    getComponentElementById(this, "PaginationJumpForward").removeClass("disabled");
                }
                if (this.remainingPages > 0) {
                    getComponentElementById(this, "PaginationNextItem").show();
                } else {
                    getComponentElementById(this, "PaginationNextItem").hide();
                }
                if (this.remainingPages > 1) {
                    getComponentElementById(this, "PaginationNextNextItem").show();
                } else {
                    getComponentElementById(this, "PaginationNextNextItem").hide();
                }
                this.updatePagination();
                this.onAfterLoadPage(data);
            }.bind(this),
            function (data) {
                this.handleComponentError('Could not retrieve data: ' + data.Message);
            }.bind(this), false, false);
    }

    /**
     * Called last in the success callback of the loadPage() function, gives room for functionality to be executed
     * immediately after loading the data table
     * @param data Full data object from loadPage() function
     */
    onAfterLoadPage(data) {
        //TODO: Override this as needed;
    }

    /**
     * Adds row into the data table
     * @param rowData Data object containing necessary row data
     */
    addRow(rowData) {
        this.itemsOnCurrentPage.push(rowData);
        if (this.selectedItems.length > 0) {
            getComponentElementById(this, "MultiSelectOptionsButton").show().addClass("d-inline-flex");
        } else {
            getComponentElementById(this, "MultiSelectOptionsButton").hide().removeClass("d-inline-flex");
        }
        getComponentElementById(this, "DataTableBody").append(this.getRowHtml(rowData));
    }

    /**
     * Returns the value to be displayed for a specific column for the row
     * @param rowData
     * @param key
     * @returns {string|*}
     */
    getRowColumnHtml(rowData = {}, key = "") {
        if (typeof rowData[key] === "undefined") {
            return key;
        }
        switch (key) {
            //case '[Key]': return 'Your own value';
            //break;
            default:
                return rowData[key];
        }
    }

    /**
     * Returns the complete html for the row to be added to the table
     * @param rowData
     * @returns {string}
     */
    getRowHtml(rowData) {
        let html = '<tr class="' + this.getUid() + '_row_item_' + rowData["Id"] + ' dx-data-table-row">';
        let rowKeys = Object.keys(rowData);
        let checkedHtml = '';
        // Doing it this way since indexOf and includes does not identify the items as being in the array...
        this.selectedItems.forEach(function (item) {
            if (item === rowData["Id"]) {
                checkedHtml = ' checked';
            }
        });
        let columnIndex = 0;
        rowKeys.forEach(function (key) {
            if (key === "Id") {
                html += '<td>' +
                    '<input id="' + this.getUid() + '_select_item_' + rowData["Id"] + '" type="checkbox"' +
                    ' class="select_item_' + this.getUid() + '" name="' + this.getUid() + '_select_item_' + rowData["Id"] + '" value="' + this.getUid() + '_select_item_' + rowData["Id"] + '"' + checkedHtml + '>' +
                    '</td>';
            } else {
                if (columnIndex === this.clickEventColumnIndex) {
                    html += '<th scope="row">' +
                        '<a href="#" id="' + this.getUid() + '_row_item_' + rowData["Id"] + '" class="data-table-first-column first-column_' + this.getUid() + '">' + this.getRowColumnHtml(rowData, key) + '</a>' +
                        '</th>';
                } else {
                    html += '<td>' + this.getRowColumnHtml(rowData, key) + '</td>';
                }
            }
            columnIndex++;
        }.bind(this));
        html += '</tr>';
        return html;
    }

    /**
     * Event handler for clicks on data rows
     * @param id The Id of the row clicked
     */
    onItemClicked(id) {
        setGlobalConstrainById(this.entityName, id);
        pageEventTriggered(this.lowerCaseEntityName + "_clicked", {id: id});
    }
}

/**
 * DivbloxDomEntityDataListComponent is the base class that manages the component javascript for every entity
 * data list component
 */
class DivbloxDomEntityDataListComponent extends DivbloxDomBaseComponent {
    /**
     * Initializes all the further variables needed for a Divblox DOM data list component
     * @param {Object} inputs The arguments to pass to the component
     * @param {Boolean} supportsNative Indicate whether this component works on native projects
     * @param {Boolean} requiresNative Indicate whether this component works ONLY on native projects
     */
    constructor(inputs, supportsNative, requiresNative) {
        super(inputs, supportsNative, requiresNative);
        this.currentListOffset = 0;
        this.listOffsetIncrement = 10;
        this.itemsOnCurrentPage = [];
        this.totalItems = 0;
        this.includedAttributes = {};
        this.includedRelationships = {};
        this.constrainedByEntities = [];
        this.includedAll = {};
        this.currentSortColumn = [];
        this.itemDividerText = " ";
    }

    /**
     * Initializes the necessary Data List variables
     */
    initDataListVariables(entityName) {
        this.entityName = entityName;
        this.lowerCaseEntityName = entityName.replace(/([a-z0-9])([A-Z])/g, '$1_$2').toLowerCase();
        this.includedAll = {...this.includedAttributes, ...this.includedRelationships};
        let includedKeys = Object.keys(this.includedAll);
        this.currentSortColumn = [includedKeys[0], true]; // Sort on first column, desc
    }

    /**
     * Displays the loading indicator for the list
     */
    showPageLoadingIndicator() {
        getComponentElementById(this, "DataListLoading").html('<div class="dx-loading"></div>').show();
    }

    /**
     * A useful function to call to reset the component state
     * @param {Object} inputs The arguments to pass to the component
     * @param {boolean} propagate If true, will also reset all sub components
     */
    reset(inputs, propagate) {
        this.itemsOnCurrentPage = [];
        getComponentElementById(this, "DataList").html("");
        this.loadPage();
        super.reset(inputs, propagate);
    }

    /**
     * When registering DOM events it is useful to keep track of them per component if we want to offload them
     * later. This method is a wrapper for that functionality
     */
    registerDomEvents() {
        getComponentElementById(this, "DataListSearchInput").on("keyup", function () {
            let searchText = getComponentElementById(this, "DataListSearchInput").val();
            setTimeout(function () {
                if (searchText == getComponentElementById(this, "DataListSearchInput").val()) {
                    getComponentElementById(this, "DataList").html("");
                    this.itemsOnCurrentPage = [];
                    this.currentListOffset = 0;
                    this.loadPage();
                }
            }.bind(this), 500);
        }.bind(this));
        getComponentElementById(this, "btnResetSearch").on("click", function () {
            getComponentElementById(this, "DataListSearchInput").val("");
            getComponentElementById(this, "DataList").html("");
            this.itemsOnCurrentPage = [];
            this.currentListOffset = 0;
            this.loadPage();
        }.bind(this));
        getComponentElementById(this, "DataListMoreButton").on("click", function () {
            this.currentListOffset += this.listOffsetIncrement;
            this.loadPage();
        }.bind(this));
        this.handleOnClassEvent("click", "data_list_item_" + this.getUid(), "_row_item_", "onItemClicked");
    }

    /**
     * Returns the input parameters object for the dxRequestInternal function call that loads the page
     * @returns {Object} The object to pass to dxRequestInternal
     */
    getLoadFunctionParameters() {
        let parameters = {
            f: "getPage",
            CurrentOffset: this.currentListOffset,
            ItemsPerPage: this.listOffsetIncrement,
            SearchText: getComponentElementById(this, "DataListSearchInput").val(),
            SortOptions: JSON.stringify(this.currentSortColumn)
        };
        if (this.constrainedByEntities.length > 0) {
            this.constrainedByEntities.forEach(function (relationship) {
                parameters['Constraining' + relationship + 'Id'] = getGlobalConstrainById(relationship);
            });
        }
        return parameters;
    }

    /**
     * Called before the server request fires in the loadPage() function, giving room for functionality that should be
     * executed immediately before load. Can be used to stop the loadPage() function by returning false
     */
    onBeforeLoadPage() {
        //TODO: Override this as needed;
        return true;
    }

    /**
     * Load the data list up row by row, including pagination and search functionality
     */
    loadPage() {
        if (!this.onBeforeLoadPage()) {
            return;
        }
        this.showPageLoadingIndicator();
        dxRequestInternal(
            getComponentControllerPath(this),
            this.getLoadFunctionParameters(),
            function (data) {
                data.Page.forEach(function (item) {
                    this.addRow(item);
                }.bind(this));
                this.totalItems = data.TotalCount;
                getComponentElementById(this, "DataListMoreButton").show();
                if (this.totalItems <= (this.currentListOffset + this.listOffsetIncrement)) {
                    getComponentElementById(this, "DataListMoreButton").hide();
                }
                if (this.itemsOnCurrentPage.length > 0) {
                    getComponentElementById(this, "DataListLoading").hide();
                } else {
                    getComponentElementById(this, "DataListLoading").html("No results").show();
                    getComponentElementById(this, "DataListMoreButton").hide();
                }
                this.onAfterLoadPage(data);
            }.bind(this),
            function (data) {
                getComponentElementById(this, "DataList").hide();
                this.handleComponentError('Could not retrieve data: ' + data.Message);
            }.bind(this), false, false);
    }

    /**
     * Called last in the success callback of the loadPage() function, gives room for functionality to be executed
     * immediately after loading the data list
     * @param data Full data object from loadPage() function
     */
    onAfterLoadPage(data) {
        //TODO: Override this as needed;
    }

    /**
     * Returns the value to be displayed for a specific attribute for the row
     * @param rowData
     * @param key
     * @returns {string|*}
     */
    getAttributeHtml(rowData = {}, key = "") {
        if (typeof rowData[key] === "undefined") {
            return "";
        }
        let formattedContent = '';

        switch (key) {
            //case '[Key]': formatted_content = 'Your own value';
            //	break;
            default:
                formattedContent = rowData[key];
        }
        if (formattedContent === null) {
            return "";
        }
        if (String(formattedContent).trim().length === 0) {
            return "";
        }
        return formattedContent;
    }

    /**
     * Returns the html for a specific wrapper type for the row
     * @param content
     * @param wrapperType
     * @returns {string}
     */
    getAttributeWrapperHtml(content = '', wrapperType = 'normal') {
        if (String(content).trim().length === 0) {
            return "";
        }
        switch (wrapperType.toLowerCase()) {
            case 'header':
                return '<h5 class="mb-1">' + content + '</h5>';
            case 'subtle':
                return '<small>' + content + '</small>';
            case 'normal':
                return '<p>' + content + '</p>';
            case 'footer':
                return '<small>' + content + '</small>';
            default:
                return '<p>' + content + '</p>';
        }
    }

    /**
     * Returns an object containing the various row html components
     * @param rowData
     * @returns {{subtle_components_html: string, footer_components_html: string, header_components_html: string, normal_components_html: string}}
     */
    getRowHtmlObject(rowData) {
        let returnObject = {
            "header_components_html": "",
            "subtle_components_html": "",
            "normal_components_html": "",
            "footer_components_html": ""
        };

        let includedKeys = Object.keys(this.includedAll);
        includedKeys.forEach(function (key) {
            let wrapperType = this.includedAll[key].toLowerCase();
            let itemDividerFinalText = "";
            if (String(returnObject[wrapperType + "_components_html"]).trim().length > 0) {
                itemDividerFinalText = this.itemDividerText;
            }
            returnObject[wrapperType + "_components_html"] += itemDividerFinalText + this.getAttributeWrapperHtml(this.getAttributeHtml(rowData, key), wrapperType);
        }.bind(this));
        return returnObject;
    }

    /**
     * Returns the complete html for the row to be added
     * @param rowData
     * @returns {string}
     */
    getRowHtml(rowData) {
        let rowHtml = '<a href="#" id="' + this.getUid() + '_row_item_' + rowData["Id"] + '" class="list-group-item' +
            ' list-group-item-action flex-column align-items-start data_list_item data_list_item_' + this.getUid() + ' dx-data-list-row">';

        let headerWrappingHtml = '<div class="d-flex w-100 justify-content-between">';
        let rowObj = this.getRowHtmlObject(rowData);

        headerWrappingHtml += rowObj["header_components_html"] + rowObj["subtle_components_html"];
        headerWrappingHtml += '</div>';

        rowHtml += headerWrappingHtml + rowObj["normal_components_html"] + rowObj["footer_components_html"];
        rowHtml += '</a>';
        return rowHtml;
    }

    /**
     * Adds a row into the data table
     * @param rowData Data object containing necessary row data
     */
    addRow(rowData) {
        let currentItemKeys = Object.keys(this.itemsOnCurrentPage);
        let mustAddRow = true;
        currentItemKeys.forEach(function (key) {
            if (this.itemsOnCurrentPage[key]["Id"] === rowData["Id"]) {
                mustAddRow = false;
            }
        }.bind(this));
        if (!mustAddRow) {
            return;
        }
        this.itemsOnCurrentPage.push(rowData);

        getComponentElementById(this, "DataList").append(this.getRowHtml(rowData));
    }

    /**
     * Event handler for clicks on data rows
     * @param id The Id of the row clicked
     */
    onItemClicked(id) {
        setGlobalConstrainById(this.entityName, id);
        pageEventTriggered(this.lowerCaseEntityName + "_clicked", {id: id});
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Divblox initialization related functions
 */

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Loads the Divblox chat widget for the setup page
 */
function loadDxChatWidget() {
    return; //JGL: Disabling this for now as it is available on the Divblox documentation page
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/5e2aa42bdaaca76c6fcfa354/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
}

/**
 * Must be called at load to ensure that Divblox loads correctly for the current environment. This function will set all
 * required paths and load Divblox dependencies
 * @param {Boolean} asNative Tells Divblox whether to initiate for a native environment or web
 */
function initDx(asNative) {
    if (typeof asNative === "undefined") {
        asNative = false;
    }
    setPaths(asNative);
    let storedGlobalVars = getValueFromAppState("global_vars");
    if (storedGlobalVars !== null) {
        globalVars = storedGlobalVars;
    }
    // It is important to call this before other loading functions, since we might need to load a page before all
    // dependencies are ready
    dxPageManager.loadMobilePageAlternatives(function () {
        loadDependencies();
    });
}

/**
 * Loads the Divblox dependencies, recursively. When all dependencies are loaded, checkFrameworkReady() is called.
 * @param {Number} count The index in the variable dependency_array
 */
function loadDependencies(count) {
    if (typeof count === "undefined") {
        count = 0;
    }
    if (count < divbloxDependencies.length) {
        let url = getRootPath() + divbloxDependencies[count];
        if (typeof adminMode !== "undefined") {
            if (adminMode) {
                url = url + getRandomFilePostFix();
            }
        }
        dxGetScript(url, function (data, textStatus, jqxhr) {
            loadDependencies(count + 1);
        });
    } else {
        menuManager.loadMenuDefinitions(
            checkFrameworkReady,
            function () {
                dxLog("Failed to load Menu definitions");
            }
        );

    }
}

/**
 * Sets the Divblox root paths
 * @param {Boolean} asNative Tells Divblox whether to initiate for a native environment or web
 */
function setPaths(asNative) {
    if (typeof asNative === "undefined") {
        asNative = false;
    }
    if (!asNative) {
        // JGL: All app content needs to reside in one of the following folders
        let paths = window.location.pathname.split('/');
        let cleanedPaths = paths.filter(function (el) {
            return el !== "";
        });
        allowableDivbloxPaths.forEach(function (allowablePath) {
            for(i = 0; i < cleanedPaths.length; i++) {
                if (cleanedPaths[i] === allowablePath) {
                    if (typeof cleanedPaths[i + 1] !== "undefined") {
                        if (allowableDivbloxSubPaths.indexOf(cleanedPaths[i + 1]) > -1) {
                            // JGL: Everything before this item is the doc root
                            if (documentRoot.length === 0) {
                                for(j = 0; j < i; j++) {
                                    documentRoot += cleanedPaths[j] + "/";
                                }
                                documentRoot = documentRoot.substring(0, documentRoot.length - 1);
                            }
                        }
                    }
                }
            }
        });

        if (documentRoot.length === 0) {
            for(i = 0; i < cleanedPaths.length; i++) {
                if (cleanedPaths[i].indexOf(".") < 0) {
                    // JGL: This is not a file
                    documentRoot += cleanedPaths[i] + "/";
                }
            }
            documentRoot = documentRoot.substring(0, documentRoot.length - 1);
            if (documentRoot.length === 0) {
                //JGL: Doing a final check here to ensure it works on servers with sub directories
                let pathName = window.location.pathname;
                if (pathName.indexOf("index.html") > -1) {
                    documentRoot = pathName.substr(0, pathName.indexOf("/index.html"));
                }
                if (pathName.indexOf("component_builder.php") > -1) {
                    documentRoot = pathName.substr(0, pathName.indexOf("/component_builder.php"));
                }
            }
        }
        if (documentRoot.indexOf("divblox/config") > -1) {
            documentRoot = "";
        }
    } else {
        documentRoot = "";
        setIsNative();
    }
}

/**
 * Placeholder function that handles the event to call the Install prompt for progressive web apps
 */
function callInstallPrompt() {
    // We can't fire the dialog before preventing default browser dialog
    //TODO: Complete this for custom prompts
    if (installPromptEvent !== undefined) {
        installPromptEvent.prompt();
    }
}

/**
 * Checks if the framework is installed and configured. If so, sets up the offline event handlers. After
 * that we call a generic "on_divblox_ready()" function that the developer can implement
 */
function checkFrameworkReady() {
    if (isNative()) {
        isFeedbackAllowed = localConfig.allowFeedback;
        doAfterInitActions();
        on_divblox_ready();
        return;
    }
    window.addEventListener('beforeinstallprompt', function (event) {
        event.preventDefault();
        installPromptEvent = event;
    });
    isSpaMode = localConfig.isSpaMode;
    isDebugMode = localConfig.inDebugMode;
    isFeedbackAllowed = localConfig.allowFeedback;
    let configCookie = getValueFromAppState('divblox_config');
    if (configCookie === null) {
        dxGetScript(getRootPath() + "divblox/config/framework/check_config.php", function (data) {
            if (!isJsonString(data)) {
                window.open(getRootPath() + 'divblox/config/framework/divblox_admin/initialization_wizard/');
                return;
            }
            let configData = JSON.parse(data);
            if (configData.Success) {
                updateAppState('divblox_config', 'success');
                $(document).ready(function () {
                    if (typeof on_divblox_ready !== "undefined") {
                        on_divblox_ready();
                    }
                });
            } else {
                dxRequestSystem(getRootPath() + 'divblox/config/framework/divblox_admin/initialization_wizard/installation_helper.php?check=1', {},
                    function () {
                        window.open(getRootPath() + 'divblox/config/framework/divblox_admin/initialization_wizard/');
                    },
                    function () {
                        throw new Error("Divblox is not ready! Please visit the setup page at: " + getServerRootPath() + "divblox/");
                    });
            }
        }, function (data) {
            dxRequestSystem(getRootPath() + 'divblox/config/framework/divblox_admin/initialization_wizard/installation_helper.php?check=1', {},
                function () {
                    window.open(getRootPath() + 'divblox/config/framework/divblox_admin/initialization_wizard/');
                },
                function () {
                    throw new Error("Divblox is not ready! Please visit the setup page at: " + getServerRootPath() + "divblox/");
                });
        });
    } else {
        $(document).ready(function () {
            window.addEventListener('offline', networkStatus);
            window.addEventListener('online', networkStatus);

            function networkStatus(e) {
                if (e.type == 'offline')
                    setOffline();
                else
                    setOnline();
            }

            if (isDebugMode) {
                dxLog("Divblox setup page: " + getServerRootPath() + "divblox/", false);
            }
            if (!isInStandaloneMode()) {
                //TODO: Complete this. We need to add a prompt to add to homescreen here that is configurable by the
                // developer
            }
            currentUserRole = getCurrentUserRoleFromAppState();
            if (typeof on_divblox_ready !== "undefined") {
                doAfterInitActions();
                on_divblox_ready();
            }
            if ((typeof isComponentBuilderActive === "undefined") || (isComponentBuilderActive === false)) {
                if (localConfig.enableServiceWorker) {
                    registerServiceWorker();
                } else {
                    dxLog("Service worker disabled");
                    removeServiceWorker();
                }
            } else {
                removeServiceWorker();
            }
            $("#AppReloadButton").on("click", function () {
                serviceWorker.postMessage({action: 'skipWaiting'});
                window.location.reload(true);
            });
            $("#AppReloadDismissButton").on("click", function () {
                $("#AppUpdateWrapper").removeClass("show");
            });
            window.addEventListener('beforeunload', function (e) {
                if ((dxQueue.length > 0) && !forceLogoutOccurred) {
                    e.preventDefault(); //per the standard
                    e.returnValue = "You have attempted to leave this page. There are currently items waiting to be processed on the" +
                        " server. If you close this page now, those changes will be lost." +
                        "  Are you sure you want to exit this page?"; //required for Chrome
                } else if (dxHasUploadsWaiting) {
                    e.preventDefault(); //per the standard
                    e.returnValue = "You have attempted to leave this page. There are currently items waiting to" +
                        " upload. If you close this page now, those uploads will be lost." +
                        "  Are you sure you want to exit this page?"; //required for Chrome
                }
            });
            if (typeof adminMode !== "undefined") {
                if (adminMode) {
                    loadDxChatWidget();
                }
            }
        });
    }
    if (isSpa()) {
        $(window).on("popstate", function (e) {
            let position = Number(window.history.state); // Absolute position in stack
            let direction = Math.sign(position - rootHistoryIndex);
            // One for backward (-1), reload (0) or forward (1)
            loadPageFromRootHistory(direction);
        });
    }
}

/**
 * Shows a notification that indicates an update to the app is available. Only used when the service worker is installed
 */
function showAppUpdateBar() {
    $("#AppUpdateWrapper").addClass("show").css("z-index", getHighestZIndex() + 1);
}

/**
 * Removes the current service worker
 */
function removeServiceWorker() {
    if (!navigator.serviceWorker) {
        return;
    }
    navigator.serviceWorker.getRegistrations().then(registrations => {
        for(let registration of registrations) {
            registration.unregister();
        }
    });
}

/**
 * Returns the current root path of the server.
 * @return {String} The root path which is a valid url, e.g https://divblox.com/
 */
function getServerRootPath() {
    let portNumber = window.location.port;
    if (portNumber.length > 0) {
        portNumber = ":" + portNumber;
    }
    let rootPath = window.location.protocol + "//" + window.location.hostname + portNumber;
    if (documentRoot.length > 0) {
        rootPath += "/" + documentRoot + "/";
    }
    if (rootPath[rootPath.length - 1] !== '/') {
        rootPath += "/";
    }
    return rootPath;
}

/**
 * Returns the current root path from index.html
 * @return {String} The root path which is a relative path
 */
function getRootPath() {
    if (typeof force_server_root !== "undefined") {
        return getServerRootPath();
    }
    return "";
}

/**
 * Sets the value of a url parameter in the app state. Useful when in SPA mode.
 * @param {String} name The name of the parameter
 * @param {String} value The value to set it to
 */
function setUrlInputParameter(name, value) {
    if (urlInputParameters === null) {
        urlInputParameters = new URLSearchParams();
    }
    urlInputParameters.set(name, value);
    updateAppState('page_inputs', "?" + urlInputParameters.toString());
}

/**
 * Returns the value for a url parameter in the app state
 * @param {String} name The name of the parameter
 * @return {String|Null} The value stored in the app state
 */
function getUrlInputParameter(name) {
    if (urlInputParameters === null) {
        return null;
    }
    return urlInputParameters.get(name);
}

/**
 * Updates a value in the app state and calls the function to store the app state
 * @param {String} itemKey The name of the item
 * @param {String} itemValue The value of the item
 */
function updateAppState(itemKey, itemValue) {
    appState[itemKey] = itemValue;
    storeAppState();
}

/**
 * Removes a value from dx_app_state for a given key
 * @param itemKey - key to search for in dx_app_state
 */
function removeFromAppState(itemKey) {
    let appStateEncoded = getItemInLocalStorage("dx_app_state");
    if (appStateEncoded !== null) {
        appState = JSON.parse(atob(appStateEncoded));
        if (appState.hasOwnProperty(itemKey)) {
            delete appState[itemKey];
            storeAppState();
        } else {
            dxLog("Trying to remove property '" + itemKey + "' from dx_app_state but this property is not defined.");
        }
    }

}

/**
 * Stores the current app state in local storage
 */
function storeAppState() {
    appState['globalVars'] = globalVars;
    setItemInLocalStorage("dx_app_state", btoa(JSON.stringify(appState)));
}

/**
 * Returns the current app state from local storage
 * @return {Object} The current app state
 */
function getAppState() {
    let appStateEncoded = getItemInLocalStorage("dx_app_state");
    if (appStateEncoded !== null) {
        appState = JSON.parse(atob(appStateEncoded));
    }
    return appState;
}

/**
 * Returns a specific value stored in the app state
 * @param {String} itemKey The name of the item
 * @return {String|Null} The value of the item
 */
function getValueFromAppState(itemKey) {
    appState = getAppState();
    if (typeof appState[itemKey] !== "undefined") {
        return appState[itemKey];
    }
    return null;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Divblox component and DOM related helper functions
 */

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Checks whether we are in the component builder
 * @return {boolean} true if in component builder, false if not
 */
function checkComponentBuilderActive() {
    if (typeof isComponentBuilderActive !== "undefined") {
        return isComponentBuilderActive;
    }
}

/**
 * Loads a component by creating an instance of the component's DivbloxDomComponent class implementation and then
 * calling the relevant function to load the component HTML, CSS & JavaScript into the DOM
 * @param {String} componentName The fully qualified name of the component, e.g "data_model/account_create"
 * @param {String} parentUid The UID of the component within which this component is being loaded as a sub
 * component. This can by null if the component is loaded as the first component for this page, i.e the page component
 * @param {String} parentElementId The DOM id of the parent component element
 * @param {Object} loadArguments The arguments to pass to the component's constructor
 * @param {Boolean} replaceParentContent If true, the parent element's html is overridden with the component that
 * is being loaded. If false, the component's DOM content is appended to the parent element
 * @param {Boolean} cbActive Flag that indicates whether the component builder is active
 * @param {Function} callback Function to call once the component has been loaded completely
 */
function loadComponent(componentName, parentUid, parentElementId, loadArguments, replaceParentContent, cbActive, callback) {
    parentUid = parentUid || "";
    if (parentElementId != "body") {
        let parent_uid_str = "";
        if (parentUid != "") {
            parent_uid_str = parentUid + "_";
        }
        parentElementId = '#' + parent_uid_str + parentElementId;
    }
    if (typeof callback !== "function") {
        callback = function () {
        };
    }
    if (typeof (replaceParentContent) === "undefined") {
        replaceParentContent = false; // JGL: By default, let's add to the content in the parent element
    }
    if (typeof (cbActive) === "undefined") {
        cbActive = false;
    }
    if (typeof (componentName) !== null) {
        if (typeof (loadArguments) === "undefined") {
            loadArguments = {};
        }
        loadArguments["url_parameters"] = getAllUrlParams();
        loadArguments["component_name"] = componentName.replace(/\//g, '_');
        loadArguments["component_load_name"] = componentName;
        loadArguments["parent_element"] = parentElementId;
        loadArguments["parent_uid"] = parentUid;
        loadArguments["component_path"] = getRootPath() + "project/components/" + componentName;
        generateNextDOMIndex(loadArguments["component_name"]);
        if (typeof (loadArguments["uid"]) !== "undefined") {
            if (typeof (registeredComponents[loadArguments["uid"]]) !== "undefined") {
                throw new Error("The component '" + registeredComponents[loadArguments["uid"]] + "' is already registered in the DOM");
            }
        } else {
            loadArguments["dom_index"] = domComponentIndexMap[loadArguments["component_name"]][domComponentIndexMap[loadArguments["component_name"]].length - 1];
            loadArguments["uid"] = loadArguments["component_name"] + "_" + loadArguments["dom_index"];

        }
        // JGL: Load the component html
        let componentHtmlLoadPath = loadArguments["component_path"] + "/component.html";
        if (isDebugMode || checkComponentBuilderActive()) {
            componentHtmlLoadPath = loadArguments["component_path"] + "/component.html" + getRandomFilePostFix();
        }
        dxGetScript(componentHtmlLoadPath, function (html) {
            let finalHtml = getComponentFinalHtml(loadArguments["uid"].replace("#", ""), html);
            if (cbActive) {
                finalHtml = finalHtml.replace(/col-/g, 'component-builder-column col-');
                finalHtml = finalHtml.replace(/class="row/g, 'class="component-builder-row row');
                finalHtml = finalHtml.replace(/class="container/g, 'class="component-builder-container container');
            }
            if (typeof (parentElementId) !== null) {
                if (replaceParentContent) {
                    $(parentElementId).html(finalHtml);
                } else {
                    $(parentElementId).append(finalHtml);
                }
            } else {
                if (replaceParentContent) {
                    $('body').html(finalHtml);
                } else {
                    $('body').append(finalHtml);
                }
            }
            loadComponentCss(loadArguments["component_path"]);
            loadComponentJs(loadArguments["component_path"], loadArguments, callback);
        }, function () {
            handleLoadComponentError();
        }, false/*We need to return the html from the request here*/);
    } else {
        throw new Error("No component name provided");
    }
}

/**
 * Loads the component's CSS into the DOM
 * @param {String} componentPath The relative path to the component's folder
 */
function loadComponentCss(componentPath) {
    let url = componentPath + '/component.css';
    if (isDebugMode || checkComponentBuilderActive()) {
        url = componentPath + '/component.css' + getRandomFilePostFix();
    }
    if (requestedCacheScripts.indexOf(url) > -1) {
        return;
    } else {
        requestedCacheScripts.push(url);
    }
    $('head').append('<link rel="stylesheet" href="' + url + '" type="text/css" />');
}

/**
 * Loads the component's javascript file into memory
 * @param {String} componentPath The relative path to the component's folder
 * @param {Object} loadArguments The arguments to pass to the component's constructor
 * @param {Function} callback The function to call once the javascript has loaded
 */
function loadComponentJs(componentPath, loadArguments, callback) {
    let className = "" + loadArguments["component_name"];
    if (typeof componentClasses[className] !== "undefined") {
        let component = new componentClasses[className](loadArguments);
        registerComponent(component, component.uid);
        if (typeof (component.onComponentLoaded) !== "undefined") {
            component.onComponentLoaded(true, function () {
                updateAppState('page', getUrlInputParameter("view"));
                callback(component);
            });
        }
    } else {
        let fullComponentPath = componentPath + "/component.js";
        if (isDebugMode || checkComponentBuilderActive()) {
            fullComponentPath = componentPath + '/component.js' + getRandomFilePostFix();
        }
        dxGetScript(fullComponentPath, function (data) {
            // JGL: Execute the on_[component_name]_ready function
            if (checkComponentBuilderActive()) {
                $('img').attr('draggable', 'false');
            }
            let component = new componentClasses[className](loadArguments);
            registerComponent(component, component.uid);
            if (typeof (component.onComponentLoaded) !== "undefined") {
                component.onComponentLoaded(true, function () {
                    updateAppState('page', getUrlInputParameter("view"));
                    callback(component);
                });
            }
        }, function (data) {
            handleLoadComponentError();
        }, false);
    }
}

/**
 * Loads a new page based on the component provided. If in SPA mode, this does not load a new window, but refreshes
 * the DOM with the new component's content
 * @param {String} componentName The fully qualified name of the component, e.g "data_model/account_create"
 * @param {Object} loadArguments The arguments to pass to the component's constructor
 * @param {Function} callback The function to call once the component has loaded
 */
function loadPageComponent(componentName = 'component', loadArguments = {}, callback = undefined) {
    let finalLoadArguments = {"uid": pageUid};
    let parametersStr = '';
    if (typeof loadArguments === "object") {
        let loadArgumentKeys = Object.keys(loadArguments);
        loadArgumentKeys.forEach(function (key) {
            finalLoadArguments[key] = loadArguments[key];
            parametersStr += '&' + key + "=" + loadArguments[key];
        });
    }
    let finalComponentName = dxPageManager.getMobilePageAlternate(componentName);
    if (!isSpa()) {
        redirectToInternalPath('?view=' + finalComponentName + parametersStr);
        return;
    }
    if ((typeof isComponentBuilderActive !== "undefined") && (isComponentBuilderActive)) {
        // JGL: In this case we should inform the user that we are opening a new page in a new component builder window
        if (confirm("This will open a new component builder window for the page: " + finalComponentName)) {
            let loadArgumentsStr = '';
            if (typeof loadArguments === "object") {
                let loadArgumentKeys = Object.keys(loadArguments);
                loadArgumentKeys.forEach(function (key) {
                    loadArgumentsStr += '&' + key + '=' + loadArguments[key];
                });
            }
            redirectToExternalPath(getRootPath() + 'component_builder.php?component=pages/' + finalComponentName + loadArgumentsStr);
            return;
        }
        return;
    }
    registeredComponents = {};
    unRegisterEventHandlers();
    $(document).off();
    $('body').off();
    forceLogoutOccurred = false;
    if (!isRootHistoryProcessed) {
        addPageToRootHistory(finalComponentName);
    } else {
        isRootHistoryProcessed = false;
    }
    setUrlInputParameter("view", finalComponentName);
    updateAppState("CurrentPage", finalComponentName);
    loadComponent("pages/" + finalComponentName, null, 'body', finalLoadArguments, true, undefined, callback);
    if (isDebugMode) {
        setTimeout(function () {
            dxPostExternal(getServerRootPath() + "divblox/config/framework/check_divblox_admin_logged_in.php", {},
                function (data) {
                    let data_obj = JSON.parse(data);
                    if (data_obj.Result == "Success") {
                        let admin_links_html = '<a target="_blank" href="' + getServerRootPath() + 'divblox/" ' +
                            'style="position: fixed;bottom: 10px;right: 10px;">' +
                            '<img src="' + getRootPath() + 'divblox/assets/images/divblox_logo.svg" style="max-height:30px;"/></a>' +
                            '<a target="_blank" href="' + getRootPath() + 'component_builder.php?component=pages/' + componentName + '" ' +
                            'class="btn btn-outline-primary btn-sm" style="position: fixed;bottom: 10px;right: 105px;font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\',' +
                            ' Roboto, \'Helvetica Neue\', Arial, \'Noto Sans\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\',' +
                            ' \'Segoe UI Symbol\', \'Noto Color Emoji\';    font-size:14px;"><i class="fa' +
                            ' fa-wrench"' +
                            ' aria-hidden="true"></i> Component Builder</a>';
                        $('body').append(admin_links_html);
                    }
                },
                function (data) {
                    dxLog("Error: " + data);
                });
        }, 1000);
    }
}

/**
 * Used to handle the onpopstate event when the browser back/forward buttons are clicked in SPA mode
 * @param {Number} direction -1 For backwards, 0 for nothing or 1 for forwards
 */
function loadPageFromRootHistory(direction) {
    if (direction == -1) {
        rootHistoryIndex--;
        if (rootHistoryIndex < 0) {
            rootHistoryIndex = 0;
        }
    } else if (direction == 1) {
        rootHistoryIndex++;
        if (rootHistoryIndex >= rootHistories.length) {
            rootHistoryIndex = rootHistories.length - 1;
        }
    } else {
        return;
    }
    let viewToLoad = rootHistories[rootHistoryIndex];
    let currentView = getUrlInputParameter("view");
    if (viewToLoad !== currentView) {
        isRootHistoryProcessed = true;
        loadPageComponent(viewToLoad);
    }
}

/**
 * Adds the provided page view to the root history array as the current active page
 * @param {String} pageView The name of the page component to add to the root history array
 */
function addPageToRootHistory(pageView) {
    if (!isSpa()) {
        return;
    }
    if (rootHistories[rootHistories.length - 1] !== pageView) {
        rootHistories.push(pageView);
    }
    rootHistoryIndex = rootHistories.length - 1;
    updatePushStateWithCurrentView();
}

/**
 * Updates the window history with the current view for SPA mode
 */
function updatePushStateWithCurrentView() {
    if (!isSpa() || isNative()) {
        return;
    }
    let currentView = getUrlInputParameter("view");
    if (currentView !== null) {
        window.history.pushState(rootHistoryIndex, null, getServerRootPath() + '?view=' + currentView);
        window.history.replaceState(rootHistoryIndex, null, location.pathname);
    }
}

/**
 * Helper function to redirect to the login page when a component load error occurs
 */
function handleLoadComponentError() {
    setTimeout(function () {
        loadPageComponent('login');
    }, 2000);
    throw new Error("Invalid component: Components must be grouped in folders with all relevant scripts." +
        " Click here to visit the setup page: " + getServerRootPath() + "divblox/" +
        "Will redirect to login page in 2s");
}

/**
 * When a component is loaded in the DOM, it's element Id's are prefixed with the component UID. This function
 * modifies the component HTML to provide the final html
 * @param {String} uid The UID of the component
 * @param {String} initialHtml The html as provided by the component
 * @return {String} The final html with the UID's prefixed
 */
function getComponentFinalHtml(uid, initialHtml) {
    let finalHtml = initialHtml.replace(/id="/g, 'id="' + uid + '_');
    finalHtml = finalHtml.replace(/="#/g, '="#' + uid + '_');
    return finalHtml;
}

/**
 * When a component is loaded more than once in the DOM, it needs to have a unique DOM index. This function provides
 * that functionality
 * @param {String} componentName The name of the component
 */
function generateNextDOMIndex(componentName) {
    if (typeof domComponentIndexMap[componentName] !== "undefined") {
        let lastValue = domComponentIndexMap[componentName][domComponentIndexMap[componentName].length - 1];
        domComponentIndexMap[componentName].push(lastValue + 1);
    } else {
        domComponentIndexMap[componentName] = [1];
    }
}

/**
 * Registers a component in the  registered_component_array in order for it to be retrievable later.
 * @param {Object} componentDomObject The component object to register
 * @param {String} uid The UID of the component to register
 */
function registerComponent(componentDomObject, uid) {
    if (typeof (registeredComponents[uid]) !== "undefined") {
        throw new Error("The component '" + uid + "' is already registered in the DOM");
    }
    registeredComponents[uid] = componentDomObject;
}

/**
 * Deregisters a component in the  registered_component_array
 * @param {String} uid The UID of the component to deregister
 * @param {boolean} propagate Whether or not to deregister all subcomponents
 */
function deregisterComponent(uid, propagate = false) {
    if (typeof (registeredComponents[uid]) !== "undefined") {
        if (propagate) {
            let subComponents = registeredComponents[uid].subComponentObjects;
            subComponents.forEach(function (subComponent) {
                deregisterComponent(subComponent.arguments.uid, propagate);
            });
        }
        delete registeredComponents[uid];
    }
}

/**
 * Retrieves a component, based on its UID from registered_component_array
 * @param {String} uid The UID of the component to retrieve
 * @return {Object} The component object
 */
function getRegisteredComponent(uid) {
    if (typeof (registeredComponents[uid]) === "undefined") {
        throw new Error("The component '" + uid + "' is not registered in the DOM");
    }
    return registeredComponents[uid];
}

/**
 * Gets the component name for a given component
 * @param {Object} component The component objet
 * @return {String} The name of the component
 */
function getComponentName(component) {
    return component.getComponentName();
}

/**
 * The current page will always have a default main component. This function returns that component
 * @return {Object} The component object
 */
function getPageMainComponent() {
    return getRegisteredComponent(pageUid);
}

/**
 * Gets an HTML element based on its id and component
 * @param {Object} component The component object
 * @param {String} elementId The original DOM id of the element
 * @return {jQuery|HTMLElement} The element object
 */
function getComponentElementById(component, elementId) {
    return $("#" + component.uid + "_" + elementId);
}

/**
 * Based on the actual DOM element id, retrieves the component UID from an HTML element id
 * @param {String} componentElementId The final DOM id of the element
 * @param {String} elementId The original element id of the element
 * @return {String} The UID of the component where this element is defined
 */
function getUidFromComponentElementId(componentElementId, elementId) {
    return componentElementId.replace("_" + elementId, "");
}

/**
 * Starts the propagation of an event by triggering it on the main page component
 * @param {String} eventName The name of the event
 * @param {Object} parameters The parameters to send along with the event
 */
function pageEventTriggered(eventName, parameters = {}) {
    getPageMainComponent().eventTriggered(eventName, parameters);
}

/**
 * Gets the path to the given component's php script. If in native mode, this returns the full url to the script on
 * the server. If not, it's a relative path to the script.
 * @param {Object} component The component object
 * @return {string} The path to the component's php script
 */
function getComponentControllerPath(component) {
    return component.arguments["component_path"] + "/component.php";
}

/**
 * Loads the given component's html as a jQuery DOM object and passes it to the callback function
 * @param {String} componentPath The path to the component
 * @param {Function} callback The function to execute once the DOM object has been created
 */
function loadComponentHtmlAsDOMObject(componentPath, callback) {
    dxGetScript(componentPath + "/component.html" + getRandomFilePostFix(), function (html) {
        let doctype = document.implementation.createDocumentType('html', '', '');
        let componentDom = document.implementation.createDocument('', 'html', doctype);
        let jqDom = $(componentDom);
        try {
            jqDom.find('html').html(html);
        } catch (e) {
            alert("A parse error occurred.\nThis happens when the html in your component is not properly formed.\n" +
                "- Please ensure all html tags have proper closing tags.\n" +
                "- Please ensure that you do not use strange html print characters in your content." +
                "\n\nError Detail: \n" + e);
        }
        callback(jqDom);
    }, function () {

    }, false/*We need to return the html from the request here*/);
}

/**
 * Gets a component by its wrapper div id
 * @param {String} wrapperDivId The element id of the wrapper div
 * @param {String} parentUid The UID of the component parent
 * @return {Object} The component object
 */
function getComponentByWrapperId(wrapperDivId, parentUid) {
    if (typeof parentUid === "undefined") {
        parentUid = pageUid;
    }
    let wrapperId = "#" + parentUid + "_" + wrapperDivId;
    let wrapperElement = $(wrapperId);
    if (wrapperElement.length < 1) {
        return null;
    }

    let uids = Object.keys(registeredComponents);
    let componentToReturn = null;
    uids.forEach(function (uid) {
        let component = getRegisteredComponent(uid);
        if (component.arguments['parent_element'] === wrapperId) {
            componentToReturn = component;
        }
    });
    return componentToReturn;
}

/**
 * Generates a unique id that can be assigned to a DOM element
 * @return {string} The css id to use
 */
function getUniqueDomCssId() {
    let cssIdCandidate = '';
    let possibleCharacters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let done = false;
    while (!done) {
        for(let i = 0; i < 5; i++) {
            cssIdCandidate += possibleCharacters.charAt(Math.floor(Math.random() * possibleCharacters.length));
        }
        if ($('body').find("#" + cssIdCandidate).length > 0) {
            // That Id exists
        } else {
            done = true;
        }
    }
    return cssIdCandidate;
}

/**
 * Scans the url input parameters and adds them to the current app state
 * @param {String} urlParametersStr The current url input parameters
 */
function preparePageInputs(urlParametersStr) {
    if (typeof urlParametersStr !== "undefined") {
        updateAppState('page_inputs', urlParametersStr);
        if (urlParametersStr.length > 0) {
            let initUrlInputParameters = new URLSearchParams(urlParametersStr);
            if (initUrlInputParameters.get("init_native") != null) {
                //JGL: If this is passed, an auth token must also be passed
                setAuthenticationToken(initUrlInputParameters.get('auth_token'));
                setIsNative();
            }
        }
    }
    if (isSpa()) {
        redirectToInternalPath();
    } else {
        processPageInputs();
    }
}

/**
 * Handles the current page load based on the input parameters provided
 */
function processPageInputs() {
    let pageInputs = getValueFromAppState('page_inputs');
    if (pageInputs === null) {
        loadUserRoleLandingPage("anonymous");
        return;
    }
    if (pageInputs.length > 0) {
        urlInputParameters = new URLSearchParams(pageInputs);
    } else {
        loadUserRoleLandingPage("anonymous");
        return;
    }
    let pageComponent = dxPageManager.getMobilePageAlternate(urlInputParameters.get("view"));
    let view = "pages/" + pageComponent;
    updateAppState("CurrentPage", view);
    if ((typeof urlInputParameters.get("view") === "undefined") || (urlInputParameters.get("view") == null)) {
        throw new Error("Invalid component name provided. Click here to visit the setup page: " + getServerRootPath() + "divblox/");
    } else {
        addPageToRootHistory(pageComponent);
        loadComponent(view, null, 'body', {"uid": pageUid}, false);
    }
    if (isDebugMode) {
        dxPostExternal(getServerRootPath() + "divblox/config/framework/check_divblox_admin_logged_in.php", {},
            function (data) {
                let data_obj = JSON.parse(data);
                if (data_obj.Result == "Success") {
                    let admin_links_html = '<a target="_blank" href="' + getServerRootPath() + 'divblox/" ' +
                        'style="position: fixed;bottom: 10px;right: 10px;">' +
                        '<img src="' + getRootPath() + 'divblox/assets/images/divblox_logo.svg" style="max-height:30px;"/></a>' +
                        '<a target="_blank" href="' + getRootPath() + 'component_builder.php?component=' + view + '" ' +
                        'class="btn btn-outline-primary btn-sm" style="position: fixed;bottom: 10px;right: 105px;font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\',' +
                        ' Roboto, \'Helvetica Neue\', Arial, \'Noto Sans\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\',' +
                        ' \'Segoe UI Symbol\', \'Noto Color Emoji\';    font-size:14px;"><i class="fa fa-wrench"' +
                        ' aria-hidden="true"></i> Component Builder</a>';
                    $('body').append(admin_links_html);
                }
            },
            function (data) {
            });
    }
}

/**
 * Provides a file post fix that is used to ensure files are reloaded from the server and not cached
 * @return {string} The file post fix to append to the file name
 */
function getRandomFilePostFix() {
    let postfixCandidate = '';
    let possibleCharacters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for(let i = 0; i < 5; i++) {
        postfixCandidate += possibleCharacters.charAt(Math.floor(Math.random() * possibleCharacters.length));
    }
    return '?v=' + postfixCandidate;
}

/**
 * Adds an event handler to the registered_event_handlers array to enable diblox to offload it later
 * @param {String} domNode The string describing the dom node to which the event was attached
 * @param {String} event The name of the event
 * @param {String} domId The id of the dom element
 * @param {String} domClass The class of the dom element
 */
function registerEventHandler(domNode, event, domId, domClass) {
    if (typeof domNode === "undefined") {
        return;
    }
    if (typeof event === "undefined") {
        return;
    }
    let eventObj = {dom_node: domNode, event: event, id: domId, class: domClass};
    if (typeof registeredEventHandlers !== "object") {
        registeredEventHandlers = [];
    }
    registeredEventHandlers.push(eventObj);
}

/**
 * Loops through registered_event_handlers and removes the registered event handlers
 */
function unRegisterEventHandlers() {
    let eventKeys = Object.keys(registeredEventHandlers);
    eventKeys.forEach(function (key) {
        let eventObj = registeredEventHandlers[key];
        if (typeof eventObj.id !== "undefined") {
            $(eventObj.dom_node).off(eventObj.event, "#" + eventObj.id);
        } else if (typeof eventObj.class !== "undefined") {
            $(eventObj.dom_node).off(eventObj.event, "." + eventObj.class);
        } else {
            $(eventObj.dom_node).off(eventObj.event);
        }
    });
    registeredEventHandlers = [];
}

/**
 * This function can be used to execute system-wide actions after the DivbloxDomBaseComponent class has loaded a page
 */
function doAfterPageLoadActions() {
    //TODO: Override this
}

/**
 * Iterates over the DOM and removes the 'user-role-visible' class for elements that are visible
 * for a specific subset of user roles only. This function is used by Divblox to hide and show elements based on
 * the current user's user role.
 */
function toggleUserRoleVisibility() {
    if (currentUserRole == null) {
        return;
    }
    if (dxAdminRoles.includes(currentUserRole.toLowerCase())) {
        $('.administrator-visible').removeClass("user-role-visible");
    } else {
        $('.' + currentUserRole.toLowerCase() + '-visible').removeClass("user-role-visible");
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Divblox issue tracking related functions
 */

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Sends a request to the server to log a new feedback item on basecamp. This function will automatically detect the
 * current page component from which the feedback is captured. Also, if a user is logged in, we will automatically
 * capture their details on the feedback as well
 * @param {String} type ISSUE|FEATURE
 * @param {String} title The title of the feedback
 * @param {String} description The description of the feedback
 * @param {String} componentName The name of the specifically affected sub component
 * @param {String} componentUid The UID of the specifically affected sub component
 * @param {Function} onSuccess The function to call when the feedback was captured successfully
 * @param {Function} onFail The function to call when something went wrong
 */
function logNewComponentFeedback(type, title, description, componentName, componentUid, onSuccess, onFail) {
    if (typeof onSuccess !== "function") {
        onSuccess = function () {
        };
    }
    if (typeof onFail !== "function") {
        onFail = function (message) {
        };
    }
    let capturePage = getValueFromAppState('CurrentPage');
    if (capturePage.indexOf("pages") === -1) {
        capturePage = "pages/" + capturePage;
    }
    dxRequestSystem(getRootPath() + 'divblox/config/framework/issue_tracking/issue_request_handler.php?f=newIssue',
        {
            type: type,
            title: title,
            description: description,
            component_name: componentName,
            component_uid: componentUid,
            capture_page: capturePage
        },
        function (data_str) {
            onSuccess(data_str);
        },
        function (data) {
            onFail(data.Message);
        });
}

/**
 * Appends the feedback button and modal to the current page
 */
function initFeedbackCapture() {
    if (!isFeedbackAllowed) {
        return;
    }
    let buttonHtml = '<button id="dxGlobalFeedbackButton" type="button" class="btn btn-dark" data-toggle="modal"' +
        ' data-target="#dxGlobalFeedbackModal">Feedback</button>';
    let modalHtml = '<div class="modal fade" id="dxGlobalFeedbackModal" tabindex="-1" role="dialog"' +
        ' aria-labelledby="FeedbackModal" aria-hidden="true">\n' +
        '    <div class="modal-dialog" role="document">\n' +
        '        <div class="modal-content" style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\',' +
        ' Roboto, \'Helvetica Neue\', Arial, \'Noto Sans\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\',' +
        ' \'Segoe' +
        ' UI Symbol\', \'Noto Color Emoji\';    font-size:1rem;">\n' +
        '            <div class="modal-header">\n' +
        '                <h5 class="modal-title">Provide feedback for this page</h5>\n' +
        '                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
        '                    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>\n' +
        '                </button>\n' +
        '            </div>\n' +
        '            <div class="modal-body">\n' +
        '                <div class="row">\n' +
        '                    <div class="col-12">\n' +
        '<label class="small">Feedback Type</label>' +
        '                        <select id="dxGlobalFeedbackType" class="form-control">' +
        '<option value="ISSUE">Bug</option>' +
        '<option value="FEATURE">Feature Request</option>' +
        '                       </select>' +
        '<label class="mt-2 small">Feedback Title</label>' +
        '                        <input type="text" id="dxGlobalFeedbackTitle" class="form-control"' +
        ' placeholder="Title"/>' +
        '<label class="mt-2 small">Feedback Description (Optional)</label>' +
        '                        <textarea id="dxGlobalFeedbackDescription" class="form-control"' +
        ' placeholder="Describe your issue or feature here..." rows="5"/>' +
        '<div id="dxGlobalFeedbackTechnicalWrapper">' +
        '<label class="mt-2 small">Component (Optional)</label>' +
        '                        <select id="dxGlobalFeedbackComponent" class="form-control">' +
        '<option value="-1">-Select Component-</option>' +
        '                       </select>' +
        '</div>' +
        '                    </div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <div class="modal-footer">\n' +
        '                <button type="button" id="dxGlobalFeedbackSubmitButton" class="btn btn-primary">Submit' +
        ' Feedback</button>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>';
    $('body').append(buttonHtml).append(modalHtml);
    $("#dxGlobalFeedbackSubmitButton").on("click", function () {
        if ($("#dxGlobalFeedbackTitle").val() === "") {
            showAlert("Title is required...", "error", "OK", false);
            return;
        }
        logNewComponentFeedback($("#dxGlobalFeedbackType").val(), $("#dxGlobalFeedbackTitle").val(), $("#dxGlobalFeedbackDescription").val(), $("#dxGlobalFeedbackComponent").val(), undefined,
            function () {
                showAlert("Feedback captured!", "success");
                if (checkComponentBuilderActive()) {
                    setTimeout(function () {
                        window.location.reload(true);
                    }, 1000);
                }
                $("#dxGlobalFeedbackModal").modal('hide');
            },
            function (message) {
                showAlert("Error saving feedback: " + message, "warning", "OK");
            });
    });
    if (!checkComponentBuilderActive()) {
        $("#dxGlobalFeedbackTechnicalWrapper").hide();
        return; //JGL: We only want to give the following options when the user is working on the component builder
    }
    $("#dxGlobalFeedbackTechnicalWrapper").show();
    let thisComponent = getRegisteredComponent(pageUid);
    let subComponents = thisComponent.getSubComponents();
    let subcomponentNames = [];
    subComponents.forEach(function (subComponent) {
        if (subcomponentNames.indexOf(subComponent.arguments.component_name) === -1) {
            subcomponentNames.push(subComponent.arguments.component_name);
        }
    });
    subcomponentNames.forEach(function (subComponentName) {
        $("#dxGlobalFeedbackComponent").append('<option value="' + subComponentName + '">' + subComponentName + '</option>');
    });
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Divblox general helper functions
 */

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Provides a more detailed implementation of console.log that honours the "debug_mode" flag
 * @param {String} message The message to log to console
 * @param {Boolean} showStackTrace If true, includes the current stack trace
 */
function dxLog(message = "No message provided", showStackTrace = true) {
    if (isDebugMode || checkComponentBuilderActive()) {
        if (showStackTrace) {
            let stackData = new Error().stack;
            let stacks = stackData.split("\n");
            console.log(message + " " + stacks[2].trim());
        } else {
            console.log(message);
        }
    }
}

/**
 * Divblox keeps an array of elements that are currently disabled because a request is currently happening. This
 * function adds a specific element to that array, disables it and sets its text to the provided text
 * @param {jQuery|HTMLElement} element The element to add to the loading array
 * @param {String} loadingText The text to display while loading (Optional)
 * @return {String} The id of the element that was added to the array
 */
function addTriggerElementToLoadingElementArray(element, loadingText) {
    let triggerElementId = -1;
    if ((element === false) || (typeof element === "undefined")) {
        // This means the developer intentionally does not want an element to be disabled
        return triggerElementId;
    } else {
        if (typeof loadingText === "undefined") {
            loadingText = "Loading...";
        } else {
            loadingText = loadingText + '...';
        }
        triggerElementId = element.attr("id");
        if (typeof elementLoadingStates[triggerElementId] !== "undefined") {
            if (elementLoadingStates[triggerElementId] === true) {
                return triggerElementId;
            }
        }
        elementLoadingTexts[triggerElementId] = element.html();
        elementLoadingStates[triggerElementId] = true;
        let loadingHtml = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"' +
            ' style="vertical-align: initial;""></span> ' + loadingText;
        element.html(loadingHtml);
        element.attr("disabled", "true");
        element.addClass("disabled");
    }
    return triggerElementId;
}

/**
 * Should be called once a request has completed to restore the trigger element to its original state
 * @param {String} triggerElementId The dom id of the element to restore
 */
function removeTriggerElementFromLoadingElementArray(triggerElementId) {
    if (typeof triggerElementId !== "undefined") {
        if (typeof elementLoadingTexts[triggerElementId] !== "undefined") {
            $("#" + triggerElementId).html(elementLoadingTexts[triggerElementId]).attr("disabled", false).prop("disabled", false).removeClass("disabled");
            if (typeof elementLoadingStates[triggerElementId] !== "undefined") {
                if (elementLoadingStates[triggerElementId] === true) {
                    elementLoadingStates[triggerElementId] = false;
                }
            }
        }
    }
}

/**
 * This function is the default function to send a request to the server from the Divblox frontend. This function
 * does some heavy lifting with regards to sending a request to the server:
 * - Determines the current state of the connection to the server in order to either queue, deny or process the request
 * - Adds the element that triggered the request to the loading element array to be disabled during the request
 * - Adds the request to the dx_queue to be processed.
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} onSuccess A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Success"
 * @param {Function} onFail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Failed"
 * @param {Boolean} queueOnOffline Tells the function to either queue or deny the request, based on the offline state
 * @param {jQuery|HTMLElement} element The element to add to the loading array
 * @param {String} loadingText The text to display while loading (Optional)
 */
function dxRequestInternal(url = '', parameters = {}, onSuccess = undefined, onFail = undefined, queueOnOffline = false, element = undefined, loadingText = 'Loading') {
    if (typeof queueOnOffline === "undefined") {
        queueOnOffline = false;
    }
    if (!checkOnlineStatus() && !isDxOffline) {
        setOffline();
    }
    if (!checkOnlineStatus() && (!queueOnOffline)) {
        onFail({Message: presentOfflineRequestBlockedMessage(), FailureReason: "OFFLINE"});
        return;
    } else {
        if (isDxOffline) {
            setOnline();
        }
    }
    let triggerElementId = addTriggerElementToLoadingElementArray(element, loadingText);
    dxAddToRequestQueue({
        "url": url,
        "parameters": parameters,
        "on_success": onSuccess,
        "on_fail": onFail,
        "trigger_element_id": triggerElementId
    });
}

/**
 * This function processes the next request in the dx_queue
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} onSuccess A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Success"
 * @param {Function} onFail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Failed
 * @param {String} triggerElementId The dom id of the element that triggered the request
 * @return {dxRequestInternalQueued} Unused
 */
function dxRequestInternalQueued(url, parameters, onSuccess, onFail, triggerElementId) {
    dxProcessingQueue = true;
    if (typeof parameters !== "object") {
        parameters = {};
    }
    if (authenticationToken == "") {
        authenticationToken = getValueFromAppState('dxAuthenticationToken');
    }
    if (typeof authenticationToken === "undefined") {
        authenticationToken = "";
    }
    parameters["AuthenticationToken"] = authenticationToken;
    if (isNative()) {
        parameters["is_native"] = 1;
    }
    $.post(url, parameters)
        .done(function (data) {
            dxProcessingQueue = false;
            dxProcessRequestQueue();
            data = getJsonObject(data);
            if (typeof data.AuthenticationToken !== "undefined") {
                updateAppState('dxAuthenticationToken', data.AuthenticationToken);
            }
            if (typeof data.isMaintenanceModeActive !== "undefined") {
                if (data.isMaintenanceModeActive) {
                    if (!maintenanceModeTriggered) {
                        dxLog("A maintenance mode trigger was received. Full return: " + data);
                        maintenanceModeTriggered = true;
                        loadPageComponent("maintenance");
                    }
                    return;
                }
            }
            if (typeof data.ForceLogout !== "undefined") {
                if (data.ForceLogout) {
                    if (!forceLogoutOccurred) {
                        dxLog("A force logout was received. Full return: " + data);
                        forceLogoutOccurred = true;
                        logout();
                    }
                    return;
                }
            }
            if (data.Result != "Success") {
                onFail(data);
            } else {
                onSuccess(data);
            }
            removeTriggerElementFromLoadingElementArray(triggerElementId);
        })
        .fail(function (data) {
            dxProcessingQueue = false;
            dxProcessRequestQueue();
            data = getJsonObject(data);
            onFail(data);
            removeTriggerElementFromLoadingElementArray(triggerElementId);
        });
    return this;
}

/**
 * Sets the given authentication token in the app state
 * @param authTokenToSet
 */
function setAuthenticationToken(authTokenToSet) {
    authenticationToken = authTokenToSet;
    updateAppState('dxAuthenticationToken', authenticationToken);
}

/**
 * Retrieves the current authentication token in the app state
 * @return {String|Null}
 */
function getAuthenticationToken() {
    return getValueFromAppState('dxAuthenticationToken');
}

/**
 * Adds the given request to dx_queue and calls dxProcessRequestQueue()
 * @param {Object} request The request to add to dx_queue
 */
function dxAddToRequestQueue(request) {
    dxQueue.push(request);
    dxProcessRequestQueue();
}

/**
 *Triggers the processing of the next request in dx_queue
 */
function dxProcessRequestQueue() {
    if (dxProcessingQueue) {
        return;
    }
    if (!navigator.onLine) {
        setItemInLocalStorage("dx_queue", JSON.stringify(dxQueue), 2);
        showAlert(presentOfflineRequestQueuedMessage(), "info", "OK", false);
        return;
    }
    if (dxQueue.length > 0) {
        let nextPost = dxQueue.shift();
        dxRequestInternalQueued(nextPost.url, nextPost.parameters, nextPost.on_success, nextPost.on_fail, nextPost.trigger_element_id);
    } else {
        dxProcessingQueue = false;
    }
}

/**
 * Wrapper for jQuery's $.get function that is used to load component scripts. This function will first attempt to
 * check if the script was already loaded before doing an additional request to the server.
 * @param {String} url The url of the script on the server that should be loaded
 * @param {Function} onSuccess The function to call when the script was loaded
 * @param {Function} onFail The function to call when the script could not be loaded
 * @param {Boolean} forceCache If true, the function first checks if the script is flagged as already loaded. If
 * false, the function will ALWAYS load the script from the server
 * @return {*} Used to force the function to exit
 */
function dxGetScript(url, onSuccess, onFail, forceCache) {
    if (typeof onSuccess !== "function") {
        onSuccess = function () {
        };
    }
    if (typeof onFail !== "function") {
        onFail = function () {
        };
    }
    if (typeof forceCache === "undefined") {
        forceCache = true;
    }
    if (forceCache) {
        if (requestedCacheScripts.indexOf(url) > -1) {
            dxLog("Loaded from cache: " + url);
            return onSuccess('Loaded from cache');
        } else {
            requestedCacheScripts.push(url);
        }
    }
    $.get(url, function (data, status) {
        if (status != "success") {
            onFail();
        } else {
            if (forceCache) {
                loadedCacheScripts.push(url);
            }
            onSuccess(data);
        }
    }).done(function () {
    }).fail(function () {
        onFail();
    });
}

/**
 * Used by the component builder and setup scripts to do server requests
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} onSuccess A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 * @param {Function} onFail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 */
function dxRequestSystem(url, parameters, onSuccess, onFail) {
    if (typeof parameters !== "object") {
        parameters = {};
    }
    if (authenticationToken == "") {
        authenticationToken = getValueFromAppState('dxAuthenticationToken');
    }
    if (typeof authenticationToken === "undefined") {
        authenticationToken = "";
    }
    parameters["AuthenticationToken"] = authenticationToken;
    if (isNative()) {
        parameters["is_native"] = 1;
    }
    dxPostExternal(url, parameters, onSuccess, onFail);
}

/**
 * Used by the component builder and setup scripts to do server requests
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} onSuccess A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 * @param {Function} onFail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 */
function dxRequestAdmin(url, parameters, onSuccess, onFail) {
    if (typeof onSuccess !== "function") {
        onSuccess = function () {

        };
    }
    if (typeof onFail !== "function") {
        onFail = function () {

        };
    }
    $.post(url, parameters)
        .done(function (data) {
            onSuccess(data);
        })
        .fail(function (data) {
            onFail(data);
        });
}

/**
 * Used by the component builder and setup scripts to do server requests
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} onSuccess A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 * @param {Function} onFail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 */
function dxPostExternal(url, parameters, onSuccess, onFail) {
    if (typeof onSuccess !== "function") {
        onSuccess = function () {

        };
    }
    if (typeof onFail !== "function") {
        onFail = function () {

        };
    }
    $.post(url, parameters)
        .done(function (data) {
            if (!isJsonString(data)) {
                onFail(data);
                return;
            }
            let data_obj = JSON.parse(data);
            if (typeof data_obj.AuthenticationToken !== "undefined") {
                authenticationToken = data_obj.AuthenticationToken;
                updateAppState('dxAuthenticationToken', authenticationToken);
            }
            onSuccess(data);
        })
    // Removing this since it is causing issues on firefox
    /*.fail(function(data) {
			on_fail(data)
		})*/;
}

/**
 * Determines whether a string is a valid JSON string
 * @param {String} input The string to check
 * @return {boolean} true if valid JSON, false if not
 */
function isJsonString(input) {
    try {
        JSON.parse(input);
    } catch (e) {
        return false;
    }
    return true;
}

/**
 * Returns either a valid JSON object from the input or an empty object
 * @param mixedInput: Can be json string or object
 * @return {any}
 */
function getJsonObject(mixedInput) {
    if (isJsonString(mixedInput)) {
        return JSON.parse(mixedInput);
    }
    let returnObj = {};
    try {
        let encodedString = JSON.stringify(mixedInput);
        if (isJsonString(encodedString)) {
            returnObj = JSON.parse(encodedString);
        }
    } catch (e) {
        return returnObj;
    }
    return returnObj;
}

/**
 * Assumes that the file at the specified path contains valid JSON and then loads it and returns it via the callback
 * function
 * @param filePath the path to the file containing JSON from project root
 * @param callback the function that will be called with the JSON received
 */
function loadJsonFromFile(filePath, callback) {
    if (typeof callback !== "function") {
        callback = function () {
            dxLog("No callback function was specified for loadJsonFromFile");
        };
    }
    $.ajax({
        'async': true,
        'global': false,
        'url': filePath,
        'dataType': "json",
        'success': function (data) {
            callback(data);
        },
        'error': function (e) {
            dxLog("Error loading json from file: " + e);
            callback({});
        }
    });
}

/**
 * Gets the current url input parameters
 * @param {String} url The url to parse
 * @return {Object} A key:value pairing object that represents the current url input parameters
 */
function getAllUrlParams(url) {
    // get query string from url (optional) or window
    let queryString = url ? url.split('?')[1] : window.location.search.slice(1);
    // we'll store the parameters here
    let obj = {};
    // if query string exists
    if (queryString) {
        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];
        // split our query string into its component parts
        let arr = queryString.split('&');
        for(let i = 0; i < arr.length; i++) {
            // separate the keys and the values
            let a = arr[i].split('=');
            // in case params look like: list[]=thing1&list[]=thing2
            let paramNum = undefined;
            let paramName = a[0].replace(/\[\d*\]/, function (v) {
                paramNum = v.slice(1, -1);
                return '';
            });
            // set parameter value (use 'true' if empty)
            let paramValue = typeof (a[1]) === 'undefined' ? true : a[1];
            // (optional) keep case consistent
            paramName = paramName.toLowerCase();
            paramValue = paramValue.toLowerCase();
            // if parameter name already exists
            if (obj[paramName]) {
                // convert value to array (if still string)
                if (typeof obj[paramName] === 'string') {
                    obj[paramName] = [obj[paramName]];
                }
                // if no array index number specified...
                if (typeof paramNum === 'undefined') {
                    // put the value on the end of the array
                    obj[paramName].push(paramValue);
                }
                // if array index number specified...
                else {
                    // put the value at that index number
                    obj[paramName][paramNum] = paramValue;
                }
            }
            // if param name doesn't exist yet, set it
            else {
                obj[paramName] = paramValue;
            }
        }
    }
    return obj;
}

/**
 * Wrapper function for the SweetAlert library to show informational popups with different statuses and potential
 * call backs
 * @param {String} alertMessage The message to alert
 * @param {String} icon The type of icon to show with the message: "success|error|warning|info"
 * @param {String|Array} buttons Can be either a string to display on a single button or an array of strings to
 * display on multiple buttons
 * @param {Boolean} autoHide If true, the sweet alert will auto hide. If false, it needs to be dismissed
 * @param {Number} millisecondsUntilAutoHide If auto_hide is true, this value determines how long to wait before
 * hiding
 * @param {Function} confirmFunction Optional to pass a confirm function that is executed when the confirm button
 * is clicked
 * @param {Function} cancelFunction  Optional to pass a cancel function that is executed when the cancel button
 * is clicked
 */
function showAlert(alertMessage = 'Alert', icon = 'info', buttons = [], autoHide = true, millisecondsUntilAutoHide = 1500, confirmFunction = undefined, cancelFunction = undefined) {
    if (typeof swal !== "undefined") {
        if ((typeof confirmFunction !== "undefined") &&
            (typeof cancelFunction !== "undefined")) {
            swal({
                title: null,
                text: alertMessage,
                icon: icon,
                buttons: buttons,
                dangerMode: true,
            }).then((confirmed) => {
                if (confirmed) {
                    confirmFunction();
                } else {
                    cancelFunction();
                }
            });
        } else {
            swal({
                title: null,
                text: alertMessage,
                icon: icon,
                button: buttons,
            });
            if (autoHide) {
                setTimeout(function () {
                    swal.close();
                }, millisecondsUntilAutoHide);
            }
        }
    } else {
        alert(alertMessage);
    }
}

/**
 * Adds a cookie in the browser for the current path
 * @param {String} name The name of the cookie
 * @param {String} value The value to store
 * @param {Number} days How many days until the cookie should expire
 */
function createCookie(name, value, days) {
    let expires;
    if (days) {
        let currentDate = new Date();
        currentDate.setTime(currentDate.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + currentDate.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

/**
 * Returns the value of a cookie from the browser
 * @param {String} name The name of the cookie to return
 * @return {String|Null} The value of the cookie or null
 */
function readCookie(name) {
    let nameEq = encodeURIComponent(name) + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEq) === 0)
            return decodeURIComponent(c.substring(nameEq.length, c.length));
    }
    return null;
}

/**
 * Removes a cookie from the browser
 * @param {String} name The name of the cookie to remove
 */
function eraseCookie(name) {
    createCookie(name, "", -1);
}

/**
 * Toggles the (Bootstrap) validation state for an attribute that is contained within a component
 * @param {Object} component The containing component object
 * @param {String} attribute The original element id of the attribute
 * @param {String} validationMessage The validation message to display
 * @param {Boolean} isValid If true, toggles the "is-valid" class, if false, toggles "is-invalid"
 * @param {Boolean} isReset If false applies the class specified by is_valid
 */
function toggleValidationState(component, attribute, validationMessage, isValid = false, isReset = false) {
    let valid_class = "is-valid";
    if (!isValid) {
        valid_class = "is-invalid";
    }
    getComponentElementById(component, attribute).removeClass("is-invalid").removeClass("is-valid");
    if (!isReset) {
        getComponentElementById(component, attribute).addClass(valid_class);
    }
    if (validationMessage.length > 0) {
        getComponentElementById(component, attribute + "InvalidFeedback").text(validationMessage);
    }
}

/**
 * Returns true if an attribute that is contained within a component's validation state is valid
 * @param {Object} component The containing component object
 * @param {String} attribute The original element id of the attribute
 * @return {boolean} true if valid, false if not
 */
function checkValidationState(component, attribute) {
    // JGL: returns true if valid
    return !getComponentElementById(component, attribute).hasClass("is-invalid");
}

/**
 * A Quick regex to valid email addresses
 * @param {String} email The email address to validate
 * @return {boolean} true if a valid email, false if not
 */
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Gets the string value for a specified data model attribute
 * @param {String|Object|Null} attribute The attribute to interrogate
 * @param {String|Null} displayType The display type to interrogate
 * @return {String} The string value for the attribute
 */
function getDataModelAttributeValue(attribute, displayType = "text") {
    if (typeof attribute === "object") {
        if (attribute === null) {
            return '';
        }
        if (typeof attribute['date'] !== "undefined") {
            let fullJsDateTime = attribute["date"];
            let jsDate = fullJsDateTime.slice(0, 10);
            let jsTime = fullJsDateTime.slice(11, 16);

            if (displayType === "datetime-local") {
                if (jsTime !== "00:00") {
                    return jsDate + "T" + jsTime;
                }
                return jsDate + "T00:00";
            } else {
                return jsDate;
            }
        }
    }
    return attribute;
}

/**
 * Helper function to return the current highest z-index in die DOM
 * @return {number} The z-index
 */
function getHighestZIndex() {
    var indexHighest = 0;
    $('div').each(function () {
        var indexCurrent = parseInt($(this).css("z-index"), 10);
        if (indexCurrent > indexHighest) {
            indexHighest = indexCurrent;
        }
    });
    return indexHighest;
}

/**
 * Returns the path to the App's main logo
 * @return {string} The path to the logo file
 */
function getAppLogoUrl() {
    return getRootPath() + 'project/assets/images/app_logo.png';
}

/**
 *    Renders the app's logo within any div with class "app_logo"
 */
function renderAppLogo() {
    $(".app_logo").html('<a href="' + getRootPath() + '"><img alt="App Logo" src="' + getAppLogoUrl() + '"' +
        ' class="img-fluid"/></a>');
}

/**
 * Adds a wrapper for the offline notification message to the html body
 */
function addOfflineWrapper() {
    let offlineNotification = $(".OfflineNotificationWrapper");
    if (offlineNotification.length == 0) {
        $("body").append('<div class="OfflineNotificationWrapper"><p id="OfflineMessage"></p></div>');
    }
}

/**
 * Wrapper function to serve both native and web needs with regards to online status
 * @return {boolean} true if online, false if not
 */
function checkOnlineStatus() {
    return navigator.onLine;
}

/**
 * Triggers the required user feedback when offline
 */
function setOffline() {
    addOfflineWrapper();
    $(".OfflineNotificationWrapper").removeClass("BackOnlineNotificationWrapper");
    $(".OfflineNotificationWrapper").fadeIn(500);
    $("#OfflineMessage").html('<i class="fa fa-chain-broken" aria-hidden="true" style="margin-right:10px;"></i>You\'re Offline');
    $(".OfflineNotificationWrapper").css("zIndex", getHighestZIndex() + 1);
    isDxOffline = true;
}

/**
 * Triggers the required user feedback when back online
 */
function setOnline() {
    addOfflineWrapper();
    $("#OfflineMessage").html('<i class="fa fa-plug" aria-hidden="true" style="margin-right:10px;"></i>Back Online');
    $(".OfflineNotificationWrapper").addClass("BackOnlineNotificationWrapper").fadeOut(3500);
    if (!isDxOffline) {
        $(".OfflineNotificationWrapper").css("zIndex", getHighestZIndex() + 1);
    }
    isDxOffline = false;
    dxProcessRequestQueue();
}

/**
 * Checks whether the current logged in user's role is in allowable_role_array.
 * @param {Array} allowableRoles The array of roles that are allowed
 * @param {Function} onNotAllowed The function that is executed when the role is not allowed
 * @param {Function} onAllowed The function that is executed when the role is allowed
 */
function dxCheckCurrentUserRole(allowableRoles, onNotAllowed, onAllowed) {
    if (allowableRoles.length === 0) {
        onAllowed();
        return;
    }
    if (allowableRoles.indexOf("anonymous") > -1) {
        onAllowed();
        return;
    }
    let currentRoleLocal = getValueFromAppState('dx_role');
    if (currentRoleLocal !== null) {
        if (currentRoleLocal.toLowerCase() === "dxadmin") {
            onAllowed();
            return;
        }
    }
    let foundLocal = false;
    allowableRoles.forEach(function (element) {
        if (element.toLowerCase() === currentRoleLocal) {
            foundLocal = true;
        }
    });
    if (foundLocal) {
        onAllowed();
        return;
    }
    getCurrentUserRole(function (role) {
        if (typeof role === "undefined") {
            onNotAllowed();
            return;
        }
        if (role === "dxadmin") {
            onAllowed();
            return;
        }
        let found = false;
        allowableRoles.forEach(function (element) {
            if (element.toLowerCase() === role.toLowerCase()) {
                found = true;
            }
        });
        if (!found) {
            onNotAllowed();
        } else {
            onAllowed();
        }
    });
}

/**
 * Gets the current user's user role from the server
 * @param {Function} callback The function that is executed once the current user's role is retrieved from the
 * server. This function receives the current role
 */
function getCurrentUserRole(callback) {
    dxRequestInternal(getServerRootPath() + 'api/global_functions/getUserRole',
        {AuthenticationToken: getAuthenticationToken()},
        function (data) {
            if (typeof data.CurrentRole !== "undefined") {
                updateAppState('dx_role', data.CurrentRole.toLowerCase());
                callback(data.CurrentRole);
            } else {
                callback(undefined);
            }
        },
        function (data) {
            callback(undefined);
        }, false, false, '');
}

/**
 * Gets the current user's user role from the local app state. Useful when no need for a server call
 * @return {String|Null} The current user role
 */
function getCurrentUserRoleFromAppState() {
    currentUserRole = getValueFromAppState("dx_role");
    return currentUserRole;
}

/**
 * Registers the the current user role in the app state
 * @param {String} userRole The user role to register
 */
function registerUserRole(userRole) {
    updateAppState("dx_role", userRole);
    currentUserRole = userRole;
    doAfterAuthenticationActions();
}

/**
 * Checks whether the current client's OS is mobile
 * @return {boolean} true if mobile, false if not
 */
function isMobile() {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        return true;
    }
    return false;
}

/**
 * Checks whether the current client's screen width is a typical mobile width
 * @return {boolean} true if mobile, false if not
 */
function isScreenWidthMobile() {
    return screen.width < 769;
}

/**
 * Checks whether the current OS is iOS
 * @return {Boolean} true if is iOS, false if not
 */
function isIos() {
    let userAgent = window.navigator.userAgent.toLowerCase();
    return /iphone|ipad|ipod/.test(userAgent);
}

/**
 * Checks whether we are in standalone mode and not in the browser
 * @return {Boolean} true if standalone, false if not
 */
function isInStandaloneMode() {
    // replace standalone with fullscreen or minimal-ui according to your manifest
    if (matchMedia('(display-mode: standalone)').matches) {
        // Android and iOS 11.3+
        return true;
    }
    if (isIos()) {
        return ('standalone' in window.navigator) && (window.navigator.standalone);
    } else {
        return false;
    }
}

/**
 * Triggers the intialization function when in native mode
 */
function setIsNative() {
    is_native = true;
    initNative();
}

/**
 * Checks whether we are in native mode and not in the browser
 * @return {Boolean} true if native, false if not
 */
function isNative() {
    let isNativeStored = getValueFromAppState("is_native");
    if (isNativeStored == null) {
        return is_native;
    }
    return isNativeStored == '1';
}

/**
 * Checks whether we are in Single Page Application mode
 * @return {Boolean} true if SPA or native, false if not
 */
function isSpa() {
    return isSpaMode;
}

/**
 * Updates the navigation class for the active page to highlight the menu item that relates to the page
 * @param {String} pageName The name of the page component
 * @param {String} pageTitle The title to display in the browser
 */
function setActivePage(pageName = "page", pageTitle = "divblox - page") {
    setTimeout(function () {
        // JGL: Let's just give the page component enough time to finish loading...
        $(".navigation-activate-on-" + dxPageManager.getMobilePageAlternate(pageName)).addClass("active");
        if ((typeof isComponentBuilderActive === "undefined") || (isComponentBuilderActive === false)) {
            $("title").text(pageTitle);
        }
    }, 500);
}

/**
 * Wrapper function for window.open() that ensures that we are loading a relative path in the same window
 * @param {String} pathFromRoot The path to load
 */
function redirectToInternalPath(pathFromRoot = "./") {
    window.open(getRootPath() + pathFromRoot, "_self");
}

/**
 * Wrapper function for window.open() that confirms to the user that they will be redirected to a webpage when in
 * native mode. When not in native mode, it opens the provided url in a new window.
 * @param {String} url The url to navigate to
 */
function redirectToExternalPath(url) {
    if (typeof url === "undefined") {
        throw new Error("Path url not provided");
    }
    if (isNative()) {
        window.ReactNativeWebView.postMessage(JSON.stringify({
            function_to_execute: "redirectToExternalPath",
            redirect_url: url
        }));
    } else {
        window.open(url, "_blank");
    }
}

/**
 * A helper function that displays and manages a bootstrap toast message
 * @param {String} title The title of the toast
 * @param {String} toastMessage The message to be displayed in the toast
 * @param {Object} position The position of the toast on the page: {x:"left|middle|right",y:"top|middle|bottom"}
 * @param {String} iconPath The path to the icon file that must be displayed on the toast
 * @param {moment} toastTimeStamp OPTIONAL An instance of a moment object that is used to keep track of when the
 * toast was
 * created
 * @param {Number} autoHide If not provided, the toast will not auto hide. Otherwise, it will hide in "auto_hide" ms
 */
function showToast(title, toastMessage, position, iconPath, toastTimeStamp, autoHide) {
    if (typeof title === "undefined") {
        title = localConfig.appName;
    }
    if (typeof toastMessage === "undefined") {
        toastMessage = 'No message';
    }
    if (typeof position === "undefined") {
        position = {y: "top", x: "right"};
        // JGL: y can be top,middle or bottom. x can be left, right or middle
    }
    if (position.y === "middle") {
        position.x = "middle";
    }
    if (position.x === "middle") {
        position.y = "middle";
    }
    if (typeof iconPath === "undefined") {
        iconPath = getRootPath() + 'project/assets/images/favicon.ico';
    }
    if (typeof toastTimeStamp === "undefined") {
        toastTimeStamp = moment();
    }
    let toastId = getUniqueDomCssId();
    if (typeof autoHide === "undefined") {
        autoHide = 'data-autohide="false"';
    } else {
        autoHide = 'data-delay="' + autoHide + '"';
    }
    registeredToasts.push({id: toastId, toast_time_stamp: toastTimeStamp});
    if (!isUpdatingToasts) {
        setTimeout(function () {
            isUpdatingToasts = true;
            updateToasts();
        }, 3000);
    }
    let posY = position.y + ':0px';
    let additionalStyles = '';
    if (position.y === "middle") {
        additionalStyles = ' style="width:348px;max-width:90%;margin: auto;"';
    }
    // JGL: Let's find the correct toasts wrapper div and add the toast, otherwise create the wrapper div first
    let toastHtml = '<div id="' + toastId + '" class="toast" role="alert" aria-live="assertive" aria-atomic="true"' +
        ' ' + autoHide + additionalStyles + '>' +
        '<div class="toast-header">' +
        '   <img src="' + iconPath + '" class="rounded mr-2" alt="image" style="max-height: 20px;"/>' +
        '   <strong class="mr-auto">' + title + '</strong>' +
        '   <small id="' + toastId + '_time_stamp">' + toastTimeStamp.fromNow() + '</small>' +
        '   <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" style="z-index: 1051;">' +
        '       <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>' +
        '   </button>' +
        '</div>' +
        '<div class="toast-body">' +
        '   ' + toastMessage +
        '</div>' +
        '</div>';

    if (position.x === 'right') {
        if (position.y === 'top') {
            if ($('body').find("#top_right_toast_wrapper").length < 1) {
                $('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
                    'style="position: fixed;' + posY + ';left:0;width:100%;z-index:999999;">' +
                    '<div id="top_right_toast_wrapper" style="position: absolute; top: 0; right: 0;width:348px;max-width:90%;padding: 5px;"/>');
            }
            $("#top_right_toast_wrapper").append(toastHtml);
        }
        if (position.y === 'bottom') {
            if ($('body').find("#bottom_right_toast_wrapper").length < 1) {
                $('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
                    'style="position: fixed;' + posY + ';left:0;width:100%;z-index:999999;">' +
                    '<div id="bottom_right_toast_wrapper" style="position: absolute; bottom:0px; right:' +
                    ' 0;width:348px;max-width:90%;padding: 5px;"/>');
            }
            $("#bottom_right_toast_wrapper").append(toastHtml);
        }
    }
    if (position.x === 'left') {
        if (position.y === 'top') {
            if ($('body').find("#top_left_toast_wrapper").length < 1) {
                $('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
                    'style="position: fixed;' + posY + ';left:0;width:100%;z-index:999999;">' +
                    '<div id="top_left_toast_wrapper" style="position: absolute; top: 0; left:' +
                    ' 0;width:348px;max-width:90%;padding: 5px;"/>');
            }
            $("#top_left_toast_wrapper").append(toastHtml);
        }
        if (position.y === 'bottom') {
            if ($('body').find("#bottom_left_toast_wrapper").length < 1) {
                $('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
                    'style="position: fixed;' + posY + ';left:0;width:100%;z-index:999999;">' +
                    '<div id="bottom_left_toast_wrapper" style="position: absolute; bottom:0px; left:' +
                    ' 0;width:348px;max-width:90%;padding: 5px;"/>');
            }
            $("#bottom_left_toast_wrapper").append(toastHtml);
        }
    }
    if (position.x === 'middle') {
        if ($('body').find("#middle_toast_wrapper").length < 1) {
            $('body').append('<div class="toast-aria" id="middle_toast_wrapper" aria-live="polite" aria-atomic="true" class="d-flex' +
                ' justify-content-center align-items-center"' +
                ' style="position:fixed;top:40%;left:0;width:100%;z-index:999999;"/>');
        }
        $("#middle_toast_wrapper").append(toastHtml);
    }
    $("#" + toastId).toast("show");
}

/**
 * Used to update the time value on a toast
 */
function updateToasts() {
    let toastsLeftToUpdate = 0;
    if ((registeredToasts.length > 0) && (isUpdatingToasts)) {
        registeredToasts.forEach(function (toast) {
            if ($('body').find("#" + toast.id).length < 1) {
                // Already removed
            } else {
                if ($("#" + toast.id).hasClass("hide")) {
                    // Must remove
                } else {
                    toastsLeftToUpdate++;
                    $("#" + toast.id + "_time_stamp").text(toast.toast_time_stamp.fromNow());
                }
            }
        });
        if (toastsLeftToUpdate === 0) {
            isUpdatingToasts = false;
            registeredToasts = [];
            $(".toast-aria").remove();
        } else {
            setTimeout(function () {
                updateToasts();
            }, 3000);
        }
    }
}

/**
 * Adds a key:value pairing to the global_vars array and stores it in the app state
 * @param {String} name The name of the variable to store
 * @param {String} value The value to store
 * @return {Boolean|*} false if a name was not specified.
 */
function setGlobalVariable(name, value) {
    if (typeof name === "undefined") {
        return false;
    }
    if (typeof value === "undefined") {
        value = '';
    }
    globalVars[name] = value;
    storeAppState();
}

/**
 * Returns a global variable from the global_vars array by name
 * @param {String} name The name of the variable to return
 * @return {String} The value to return
 */
function getGlobalVariable(name) {
    if (typeof globalVars[name] === "undefined") {
        return '';
    }
    return globalVars[name];
}

/**
 * Sets a global id that is used to constrain for a specified entity
 * @param {String} entity The name of the entity to which this constrain id applies
 * @param {Number} constrainingId The id to constain by
 * @return {Boolean|*} false if a name was not specified.
 */
function setGlobalConstrainById(entity, constrainingId) {
    if (typeof entity === "undefined") {
        return false;
    }
    if (typeof constrainingId === "undefined") {
        constrainingId = -1;
    }
    setGlobalVariable('Constraining' + entity + 'Id', constrainingId);
}

/**
 * Returns a global id that is used to constrain for a specified entity
 * @param {String} entity The name of the entity to which this constrain id applies
 * @return {Number} The id to constain by. -1 If not set
 */
function getGlobalConstrainById(entity) {
    if (typeof entity === "undefined") {
        return -1;
    }
    let returnValue = getGlobalVariable('Constraining' + entity + 'Id');
    if (returnValue === '') {
        return -1;
    }
    if (typeof returnValue === "undefined") {
        return -1;
    }
    if (returnValue === null) {
        return -1;
    }
    return returnValue;
}

/**
 * Initiates the required functionality for native mode
 */
function initNative() {
    updateAppState('divblox_config', 'success');
    updateAppState('is_native', '1');
}

/**
 * Stores a key:value pairing in local storage
 * @param {String} itemKey The key to store
 * @param {String} itemValue The value to store
 */
function setItemInLocalStorage(itemKey, itemValue) {
    if (typeof (Storage) === "undefined") {
        // JGL: This is a fallback for when local storage is not available.
        createCookie(itemKey, itemKey);
        return;
    }
    localStorage.setItem(itemKey, itemValue);
}

/**
 * Removes a value from local storage by key
 * @param {String} itemKey The key to find
 */
function removeItemFromLocalStorage(itemKey) {
    if (typeof (Storage) === "undefined") {
        // JGL: This is a fallback for when local storage is not available.
        eraseCookie(itemKey);
        return;
    }
    if (typeof localStorage[itemKey] !== "undefined") {
        localStorage.removeItem(itemKey);
    }
}

/**
 * Retrieves a value from local storage by key
 * @param {String} itemKey The key to find
 * @return {String|Null} The value returned from local storage
 */
function getItemInLocalStorage(itemKey) {
    if (typeof (Storage) === "undefined") {
        // JGL: This is a fallback for when local storage is not available.
        return readCookie(itemKey);
    }
    if (typeof localStorage[itemKey] !== "undefined") {
        return localStorage[itemKey];
    }
    return null;
}

/**
 * Fires when the native app is paused
 */
function onNativePause() {
    getRegisteredComponent(pageUid).onNativePause();
}

/**
 * Fires when the native app is resumed
 */
function onNativeResume() {
    getRegisteredComponent(pageUid).onNativeResume();
}

/**
 * The function that registers the Divblox service worker in the browser
 */
function registerServiceWorker() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register(getRootPath() + 'dx.sw.js').then(reg => {
            reg.addEventListener('updatefound', () => {
                // A wild service worker has appeared in reg.installing!
                serviceWorker = reg.installing;
                serviceWorker.addEventListener('statechange', () => {
                    // Has network.state changed?
                    switch (serviceWorker.state) {
                        case 'installed':
                            if (navigator.serviceWorker.controller) {
                                // new update available
                                showAppUpdateBar();
                            }
                            // No update available
                            break;
                    }
                });
            });
        });
    } else {
        dxLog("Service worker not available");
    }
}

/**
 * Provides the message that the system presents when a request is kicked off while offline and the request is to be
 * queued
 * @return {string} The message to be presented
 */
function presentOfflineRequestQueuedMessage() {
    return "You are offline. Your request has been queued and will be processed as soon as you are connected again.";
}

/**
 * Provides the message that the system presents when a request is kicked off while offline and the request is NOT to be
 * queued
 * @return {string} The message to be presented
 */
function presentOfflineRequestBlockedMessage() {
    return "This request cannot be processed at this time because you are offline.";
}

/**
 * Logs out the current user by calling api/global_functions/logoutCurrentAccount to clear the current session and
 * authentication token credentials
 */
function logout() {
    currentUserProfilePicturePath = "";
    registerUserRole("anonymous");
    dxRequestInternal(getServerRootPath() + "api/global_functions/logoutCurrentAccount",
        {AuthenticationToken: getAuthenticationToken()},
        function (data) {
            if (data.LogoutResult === true) {
                if (!isNative()) {
                    loadUserRoleLandingPage("anonymous");
                } else {
                    loadUserRoleLandingPage("native_landing");
                }
            } else {
                throw new Error("Could not logout user: " + JSON.stringify(data));
            }
        },
        function (data) {
            throw new Error("Could not logout user: " + JSON.stringify(data));
        });
}

/**
 * Loads the page that is defined in user_role_landing_pages for the provided role
 * @param {String} userRole The role to load a page for
 */
function loadUserRoleLandingPage(userRole) {
    if ((typeof userRole === "undefined") || (userRole === null)) {
        loadPageComponent('anonymous_landing_page');
        return;
    }
    let userRolePrepared = userRole.toLowerCase();
    if (typeof userRoleLandingPages[userRolePrepared] === "undefined") {
        loadPageComponent('my_profile');
        return;
    }
    if (userRolePrepared === 'anonymous') {
        if (!isNative()) {
            loadPageComponent(userRoleLandingPages[userRolePrepared]);
        } else {
            loadPageComponent('native_landing');
        }
        return;
    }
    loadPageComponent(userRoleLandingPages[userRolePrepared]);
}

/**
 * Updates the system-wide profile picture class "navigation-activate-on-profile" with the current user's profile
 * picture by calling the server to get the picture file path
 * @param {Function} callback A function that is called with the file path when done
 */
function loadCurrentUserProfilePicture(callback) {
    getCurrentUserAttribute('ProfilePicturePath', function (profilePicturePath) {
        if (typeof profilePicturePath === "undefined") {
            $(".navigation-activate-on-profile").html('<img src="' + currentUserProfilePicturePath + '" class="img rounded-circle nav-profile-picture"/>');
            return;
        }
        if (typeof profilePicturePath === null) {
            $(".navigation-activate-on-profile").html('<img src="' + currentUserProfilePicturePath + '" class="img rounded-circle nav-profile-picture"/>');
            return;
        }
        currentUserProfilePicturePath = profilePicturePath;
        $(".navigation-activate-on-profile").html('<img src="' + profilePicturePath + '" class="img rounded-circle nav-profile-picture"/>');
        if (typeof callback === "function") {
            callback(profilePicturePath);
        }
    });
}

/**
 * Queries the server for an attribute that describes the current logged in user
 * @param {String} attribute The attribute to find
 * @param {Function} callback The function that is populated with the value for the given attribute once returned
 * from the server
 */
function getCurrentUserAttribute(attribute, callback) {
    let attributeToReturn = undefined;
    if (attribute === "ProfilePicturePath") {
        attributeToReturn = getRootPath() + "project/assets/images/divblox_profile_picture_placeholder.svg";
    }
    dxRequestInternal(getServerRootPath() + 'api/global_functions/getCurrentAccountAttribute',
        {attribute: attribute, AuthenticationToken: getAuthenticationToken()},
        function (data) {
            if (typeof data.Result === "undefined") {
                callback(attributeToReturn);
                return;
            }
            if (data.Result !== 'Success') {
                callback(attributeToReturn);
                return;
            }
            if (attribute === "ProfilePicturePath") {
                if (data.Attribute === null) {
                    callback(attributeToReturn);
                    return;
                }
                callback(getServerRootPath() + data.Attribute);
            } else {
                callback(data.Attribute);
            }
        },
        function (data) {
            callback(attributeToReturn);
        }, true);
}

/**
 * @todo Any actions that should happen once the document is ready and all dx dependencies have been loaded can be placed
 * here.
 */
function doAfterInitActions() {
    //TODO: Override this as needed
}

/**
 * @todo Any actions that should happen after authentication should be placed here
 */
function doAfterAuthenticationActions() {
    //TODO: Override this as needed
}

/**
 * Checks the current user's connection speed/performance and shows a toast with a message if the connection quality
 * is poor
 */
function checkConnectionPerformance(title, message) {
    if (typeof title === "undefined") {
        title = 'Connection Problem';
    }
    if (typeof message === "undefined") {
        message = 'Your connection seems to be poor. Please reload the app and try again.';
    }
    let startTime = new Date();
    let performance = 1;
    let performanceThreshold = 0.4;
    //JGL: this is tested to start notifying the user when the connection drops to around the "Slow 3G" mark
    dxRequestInternal(getServerRootPath() + "api/global_functions/getConnectionPerformanceResult",
        {},
        function (data) {
            let endTime = new Date();
            let duration = endTime.getTime() - startTime.getTime();
            if (typeof data.ConnectionPerformanceResult !== "undefined") {
                performance = parseInt(data.ConnectionPerformanceResult) / duration;
            }
            if (performance < performanceThreshold) {
                showToast(title, message, {y: "top", x: "right"});
            }
        },
        function (data) {
            //JGL: We won't do anything here... Let this fail silently
            dxLog("Function checkConnectionPerformance() failed with result: " + JSON.stringify(data));
        }, false, false);

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
