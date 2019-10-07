/*
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * divblox initialization
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let dx_version = "1.3.0";
let bootstrap_version = "4.3.1";
let jquery_version = "3.4.1";
let minimum_required_php_version = "7.2";
let spa_mode = false;
let debug_mode = true;
let allow_feedback = false;
let allowable_divblox_paths = ["divblox","project"];
let allowable_divblox_sub_paths = ["assets","config","components"];
let document_root = "";
let page_uid = "main_page";
let authentication_token = "";
let dx_queue = [];
let dx_processing_queue = false;
let dx_has_uploads_waiting = false;
let dx_offline = false;
let service_worker_update;
let installPromptEvent;
let element_loading_obj = {};
let registered_toasts = [];
let updating_toasts = false;
let global_vars = {};
let app_state = {};
let root_history = [];
let root_history_index = -1;
let root_history_processing = false;
let cache_scripts_requested = [];
let cache_scripts_loaded = [];
let url_input_parameters = null;
let is_native = false;
let registered_event_handlers = [];
if(window.jQuery === undefined) {
	// JGL: We assume that we have jquery available here...
	throw new Error("jQuery has not been loaded. Please ensure that jQuery is loaded before divblox");
}
let component_classes = {};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * divblox initialization related functions
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let dependency_array = [
	"divblox/assets/js/bootstrap/4.3.1/bootstrap.bundle.min.js",
	"divblox/assets/js/sweetalert/sweetalert.min.js",
	"project/assets/js/project.js",
	"project/assets/js/momentjs/moment.js"
];
/**
 * Must be called at load to ensure that divblox loads correctly for the current environment. This function will set all
 * required paths and load divblox dependencies
 * @param {Boolean} as_native Tells divblox whether to initiate for a native environment or web
 */
function initDx(as_native) {
	if (typeof as_native === "undefined") {
		as_native = false;
	}
	setPaths(as_native);
	let stored_global_vars = getValueFromAppState("global_vars");
	if (stored_global_vars !== null) {
		global_vars = stored_global_vars;
	}
	loadDependencies();
}
/**
 * Loads the divblox dependencies, recursively. When all dependencies are loaded, checkFrameworkReady() is called.
 * @param {Number} count The index in the variable dependency_array
 */
function loadDependencies(count) {
	if (typeof count === "undefined") {
		count = 0;
	}
	if (count < dependency_array.length) {
		let url = getRootPath()+dependency_array[count];
		if (typeof admin_mode !== "undefined") {
			if (admin_mode) {
				url = url+getRandomFilePostFix();
			}
		}
		dxGetScript(url, function( data, textStatus, jqxhr ) {
			loadDependencies(count+1);
		});
	} else {
		checkFrameworkReady();
	}
}
/**
 * Sets the divblox root paths
 * @param {Boolean} as_native Tells divblox whether to initiate for a native environment or web
 */
function setPaths(as_native) {
	if (typeof as_native === "undefined") {
		as_native = false;
	}
	if (!as_native) {
		// JGL: All app content needs to reside in one of the following folders
		let path_array = window.location.pathname.split('/');
		let path_array_cleaned = path_array.filter(function(el) {
			return el !== "";
		});
		allowable_divblox_paths.forEach(function(allowable_path) {
			for(i=0;i<path_array_cleaned.length;i++) {
				if (path_array_cleaned[i] === allowable_path) {
					if (typeof path_array_cleaned[i+1] !== "undefined") {
						if (allowable_divblox_sub_paths.indexOf(path_array_cleaned[i+1]) > -1) {
							// JGL: Everything before this item is the doc root
							if (document_root.length === 0) {
								for(j=0;j<i;j++) {
									document_root += path_array_cleaned[j]+"/";
								}
								document_root = document_root.substring(0,document_root.length-1);
							}
						}
					}
				}
			}
		});
		
		if (document_root.length === 0) {
			for(i=0;i<path_array_cleaned.length;i++) {
				if (path_array_cleaned[i].indexOf(".") < 0) {
					// JGL: This is not a file
					document_root += path_array_cleaned[i]+"/";
				}
			}
			document_root = document_root.substring(0,document_root.length-1);
			if (document_root.length === 0) {
				//JGL: Doing a final check here to ensure it works on servers with sub directories
				let path_name = window.location.pathname;
				if (path_name.indexOf("index.html") > -1) {
					document_root = path_name.substr(0,path_name.indexOf("/index.html"));
				}
				if (path_name.indexOf("component_builder.php") > -1) {
					document_root = path_name.substr(0,path_name.indexOf("/component_builder.php"));
				}
			}
		}
		if (document_root.indexOf("divblox/config") > -1) {
			document_root = "";
		}
	} else {
		document_root = "";
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
		allow_feedback = local_config.allow_feedback;
		spa_mode = true;
		doAfterInitActions();
		on_divblox_ready();
		return;
	}
	window.addEventListener('beforeinstallprompt', function(event) {
		event.preventDefault();
		installPromptEvent = event;
	});
	spa_mode = local_config.spa_mode;
	debug_mode = local_config.debug_mode;
	allow_feedback = local_config.allow_feedback;
	let config_cookie = getValueFromAppState('divblox_config');
	if (config_cookie === null) {
		dxGetScript(getRootPath()+"divblox/config/framework/check_config.php", function( data ) {
			if (!isJsonString(data)) {
				window.open(getRootPath()+'divblox/config/framework/divblox_admin/initialization_wizard/');
				return;
			}
			let config_data_obj = JSON.parse(data);
			if (config_data_obj.Success) {
				updateAppState('divblox_config','success');
				$(document).ready(function() {
					if (typeof on_divblox_ready !== "undefined") {
						on_divblox_ready();
					}
				});
			} else {
				dxRequestSystem(getRootPath()+'divblox/config/framework/divblox_admin/initialization_wizard/installation_helper.php?check=1',{},
					function() {
						window.open(getRootPath()+'divblox/config/framework/divblox_admin/initialization_wizard/');
					},
					function() {
						throw new Error("divblox is not ready! Please visit the setup page at: "+getServerRootPath()+"divblox/config/framework/divblox_admin/setup.php");
					});
			}
		}, function(data) {
			dxRequestSystem(getRootPath()+'divblox/config/framework/divblox_admin/initialization_wizard/installation_helper.php?check=1',{},
				function() {
					window.open(getRootPath()+'divblox/config/framework/divblox_admin/initialization_wizard/');
				},
				function() {
					throw new Error("divblox is not ready! Please visit the setup page at: "+getServerRootPath()+"divblox/config/framework/divblox_admin/setup.php");
				});
		});
	} else {
		$(document).ready(function() {
			window.addEventListener('offline', networkStatus);
			window.addEventListener('online', networkStatus);
			function networkStatus(e) {
				if (e.type == 'offline')
					setOffline();
				else
					setOnline();
			}
			if (debug_mode) {
				dxLog("divblox setup page: "+getServerRootPath()+"divblox/config/framework/divblox_admin/setup.php",false);
			}
			if (!isInStandaloneMode()) {
				//TODO: Complete this. We need to add a prompt to add to homescreen here that is configurable by the
				// developer
			}
			if (typeof on_divblox_ready !== "undefined") {
				doAfterInitActions();
				on_divblox_ready();
			}
			if ((typeof cb_active === "undefined") || (cb_active === false)) {
				if (local_config.service_worker_enabled) {
					registerServiceWorker();
				} else {
					dxLog("Service worker disabled");
					removeServiceWorker();
				}
			} else {
				removeServiceWorker();
			}
			$("#AppReloadButton").on("click", function() {
				service_worker_update.postMessage({ action: 'skipWaiting' });
				window.location.reload(true);
			});
			$("#AppReloadDismissButton").on("click", function() {
				$("#AppUpdateWrapper").removeClass("show");
			});
			window.addEventListener('beforeunload', function(e) {
				if(dx_queue.length > 0) {
					e.preventDefault(); //per the standard
					e.returnValue = "You have attempted to leave this page. There are currently items waiting to be processed on the" +
						" server. If you close this page now, those changes will be lost." +
						"  Are you sure you want to exit this page?"; //required for Chrome
				} else if (dx_has_uploads_waiting) {
					e.preventDefault(); //per the standard
					e.returnValue = "You have attempted to leave this page. There are currently items waiting to" +
						" upload. If you close this page now, those uploads will be lost." +
						"  Are you sure you want to exit this page?"; //required for Chrome
				}
			});
		});
	}
	if (isSpa()) {
		$(window).on("popstate", function (e) {
			let position = Number(window.history.state); // Absolute position in stack
			let direction = Math.sign(position - root_history_index);
			// One for backward (-1), reload (0) or forward (1)
			loadPageFromRootHistory(direction);
		});
	}
}
/**
 * Shows a notification that indicates an update to the app is available. Only used when the service worker is installed
 */
function showAppUpdateBar() {
	$("#AppUpdateWrapper").addClass("show").css("z-index",getHighestZIndex()+1);
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
			registration.unregister()
		}
	});
}
/**
 * Returns the current root path of the server.
 * @return {String} The root path which is a valid url, e.g https://divblox.com/
 */
function getServerRootPath() {
	if (isNative()) {
		return server_final_url;
	}
	let port_number_str = window.location.port;
	if (port_number_str.length > 0) {
		port_number_str = ":"+port_number_str;
	}
	let root_path = window.location.protocol+"//"+window.location.hostname+port_number_str;
	if (document_root.length > 0) {
		root_path += "/"+document_root+"/";
	}
	if (root_path[root_path.length - 1] !== '/') {
		root_path += "/";
	}
	return root_path;
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
function setUrlInputParameter(name,value) {
	if (url_input_parameters === null) {
		url_input_parameters = new URLSearchParams();
	}
	url_input_parameters.set(name,value);
	updateAppState('page_inputs',"?"+url_input_parameters.toString());
}
/**
 * Returns the value for a url parameter in the app state
 * @param {String} name The name of the parameter
 * @return {String|Null} The value stored in the app state
 */
function getUrlInputParameter(name) {
	if (url_input_parameters === null) {
		return null;
	}
	return url_input_parameters.get(name);
}
/**
 * Updates a value in the app state and calls the function to store the app state
 * @param {String} item_key The name of the item
 * @param {String} item_value The value of the item
 */
function updateAppState(item_key,item_value) {
	app_state[item_key] = item_value;
	storeAppState();
}
/**
 * Stores the current app state in local storage
 */
function storeAppState() {
	app_state['global_vars'] = global_vars;
	setItemInLocalStorage("dx_app_state",btoa(JSON.stringify(app_state)));
}
/**
 * Returns the current app state from local storage
 * @return {Object} The current app state
 */
function getAppState() {
	let app_state_encoded = getItemInLocalStorage("dx_app_state");
	if (app_state_encoded !== null) {
		app_state = JSON.parse(atob(app_state_encoded));
	}
	return app_state;
}
/**
 * Returns a specific value stored in the app state
 * @param {String} item_key The name of the item
 * @return {String|Null} The value of the item
 */
function getValueFromAppState(item_key) {
	app_state = getAppState();
	if (typeof app_state[item_key] !== "undefined") {
		return app_state[item_key];
	}
	return null;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * divblox component and DOM related functions
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let dom_component_index_map = {};
// JGL: Let's initialize the object that will contain relevant DOM info for our components that are rendered on the page
let registered_component_array = {};
/**
 * DivbloxDomBaseComponent is the base class that manages the component javascript for every component
 */
class DivbloxDomBaseComponent {
	/**
	 * Initializes all the base variables for a divblox dom component
	 * @param {Object} inputs The arguments to pass to the component
	 * @param {Boolean} supports_native Indicate whether this component works on native projects
	 * @param {Boolean} requires_native Indicate whether this component works ONLY on native projects
	 */
	constructor(inputs,supports_native,requires_native) {
		this.arguments = inputs;
		if (typeof supports_native === "undefined") {
			supports_native = true;
		}
		if (typeof requires_native === "undefined") {
			requires_native = false;
		}
		this.supports_native = supports_native;
		this.requires_native = requires_native;
		if (typeof(this.arguments["uid"]) !== "undefined") {
			this.uid = this.arguments["uid"];
		} else {
			this.uid = this.arguments["component_name"] + "_" + this.arguments["dom_index"];
		}
		this.component_success = false;
		this.sub_component_definitions = {};
		this.sub_component_objects = [];
		this.sub_component_loaded_count = 0;
		this.allowed_access_array = [];
		this.is_loading = false;
	}
	/**
	 * Used to load any prerequisites that a component may require before continuing to load the component
	 * @param {Function} success_callback The function to call when prerequisites were loaded successfully
	 * @param {Function} fail_callback The function to call when something went wrong during load
	 */
	loadPrerequisites(success_callback,fail_callback) {
		if (typeof success_callback !== "function") {
			success_callback = function(){};
		}
		if (typeof fail_callback !== "function") {
			fail_callback = function(){};
		}
		success_callback();
	}
	/**
	 * This is the very first method that is called when a component is loaded. This triggers additional default
	 * behaviour for the component
	 * @param {Boolean} confirm_success Indicate whether the component should confirm a successful load or not
	 * @param {Function} callback The function to call once the component is fully loaded and ready to go
	 */
	on_component_loaded(confirm_success,callback) {
		if (isNative()) {
			if (!this.supports_native) {
				this.handleComponentError("Component "+this.uid+" does not support native.");
				return;
			}
		} else {
			if (this.requires_native) {
				this.handleComponentError("Component "+this.uid+" requires a native implementation.");
				return;
			}
		}
		if (typeof confirm_success === "undefined") {
			confirm_success = true;
		}
		if (typeof callback !== "function") {
			callback = function(){};
		}
		this.loadPrerequisites(function() {
			callback();
			dxCheckCurrentUserRole(this.allowed_access_array,function() {
				this.handleComponentAccessError("Access denied");
			}.bind(this), function() {
				if (confirm_success) {
					this.handleComponentSuccess();
				}
				this.registerDomEvents();
				this.initCustomFunctions();
				// Load additional components here
				this.loadSubComponent();
				if (checkComponentBuilderActive()) {
					setTimeout(function() {
						//JGL: Some components might not remove their loading state if they do not receive
						// initialization inputs. When we are in the component builder, we want to override this
						if (this.is_loading) {
							dxLog("Removing loading state if "+this.getComponentName()+" for component builder");
							this.removeLoadingState();
						}
					}.bind(this),1000);
				}
			}.bind(this));
		}.bind(this),function () {
			this.handleComponentError("Error loading component dependencies");
		}.bind(this));
	}
	/**
	 * A useful function to call to reset the component state
	 * @param {Object} inputs The arguments to pass to the component
	 */
	reset(inputs) {
		this.resetSubComponents(inputs);
	}
	/**
	 * Toggles the variable is_loading to true and displays the component loading state
	 */
	setLoadingState() {
		this.is_loading = true;
		$("#"+this.uid+"_ComponentContent").hide();
		$("#"+this.uid+"_ComponentPlaceholder").show();
		$("#"+this.uid+"_ComponentFeedback").html('');
	}
	/**
	 * Toggles the variable is_loading to false and removes the component loading state
	 */
	removeLoadingState() {
		this.is_loading = false;
		$("#"+this.uid+"_ComponentContent").show();
		$("#"+this.uid+"_ComponentPlaceholder").hide();
	}
	/**
	 * Calls the reset function for all of this component's sub components
	 * @param inputs
	 */
	resetSubComponents(inputs) {
		this.sub_component_objects.forEach(function(component) {
			component.reset(inputs);
		}.bind(this));
	}
	/**
	 * Checks whether this component is ready
	 * @return {boolean} true if ready, false if not
	 */
	getReadyState() {
		return this.component_success;
	}
	/**
	 * Used to remove the loading or error state when a component loads successfully
	 * @param additional_input Unused
	 */
	handleComponentSuccess(additional_input) {
		if (this.component_success === true) {
			return;
		}
		this.component_success = true;
		$("#"+this.uid+"_ComponentContent").show();
		$("#"+this.uid+"_ComponentPlaceholder").hide();
		if (typeof cb_active !== "undefined") {
			if (cb_active) {
				addComponentOverlay(this);
			}
		}
	}
	/**
	 * Used to display the error state with a relevant message for this component
	 * @param {String} ErrorMessage The error message to display
	 */
	handleComponentError(ErrorMessage) {
		this.component_success = false;
		$("#"+this.uid+"_ComponentContent").hide();
		$("#"+this.uid+"_ComponentPlaceholder").show();
		$("#"+this.uid+"_ComponentFeedback").html('<div class="alert alert-danger alert-danger-component"><strong><i' +
			' class="fa fa-exclamation-triangle ComponentErrorExclamation" aria-hidden="true"></i>' +
			' </strong><br>'+ErrorMessage+'</div>');
		if (typeof cb_active !== "undefined") {
			if (cb_active) {
				addComponentOverlay(this);
			}
		}
	}
	/**
	 * Used to display an access error for the current component
	 * @param {String} ErrorMessage The error message to display
	 */
	handleComponentAccessError(ErrorMessage) {
		this.handleComponentError(ErrorMessage);
	}
	/**
	 * When registering DOM events it is useful to keep track of them per component if we want to offload them
	 * later. This method is a wrapper for that functionality
	 */
	registerDomEvents() {/*To be overridden in sub class as needed*/}
	/**
	 * Called by on_component_loaded to allow us to initiate additional local functions for this component
	 */
	initCustomFunctions() {/*To be overridden in sub class as needed*/}
	/**
	 * A default callback method that is called whenever a sub component is successfully loaded
	 * @param {Object} component The component that was loaded
	 */
	subComponentLoadedCallBack(component) {
		this.sub_component_objects.push(component);
		this.sub_component_loaded_count++;
		this.loadSubComponent();
		// JGL: Override as needed
	}
	/**
	 * Loads the next sub component as defined in sub_component_definitions
	 */
	loadSubComponent() {
		if (typeof this.sub_component_definitions[this.sub_component_loaded_count] !== "undefined") {
			let sub_component_definition = this.sub_component_definitions[this.sub_component_loaded_count];
			loadComponent(sub_component_definition.component_load_path,this.uid,sub_component_definition.parent_element,sub_component_definition.arguments,false,false,this.subComponentLoadedCallBack.bind(this));
		} else {
			this.reset();
			if (this.getUid() === page_uid) {
				this.postPageLoadActions();
			}
		}
	}
	/**
	 * Gets all the sub components for the current component
	 * @return {Array} The array of sub component objects
	 */
	getSubComponents() {
		return this.sub_component_objects;
	}
	/**
	 * Gets all the sub component definitions for the current component
	 * @return {Array} The array of sub component definitions
	 */
	getSubComponentDefinitions() {
		return this.sub_component_definitions;
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
	 * @param {String} event_name The name of the event that was received
	 * @param {Object} parameters_obj An object with inputs passed with the event
	 */
	eventTriggered(event_name,parameters_obj) {
		switch(event_name) {
			case '[event_name]':
			default:
				dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
		}
		// Let's pass the event to all sub components
		this.propagateEventTriggered(event_name,parameters_obj);
	}
	/**
	 * Propagates an event to this component's sub components
	 * @param {String} event_name The name of the event that was received
	 * @param {Object} parameters_obj An object with inputs passed with the event
	 */
	propagateEventTriggered(event_name,parameters_obj) {
		this.sub_component_objects.forEach(function(component) {
			component.eventTriggered(event_name,parameters_obj);
		});
	}
	/**
	 * Processes the post page load actions if this component is the main page component
	 */
	postPageLoadActions() {
		initFeedbackCapture();
		loadCurrentUserProfilePicture();
		renderAppLogo();
	}
}
/**
 * Checks whether we are in the component builder
 * @return {boolean} true if in component builder, false if not
 */
function checkComponentBuilderActive() {
	if (typeof cb_active !== "undefined") {
		return cb_active;
	}
}
/**
 * Loads a component by creating an instance of the component's DivbloxDomComponent class implementation and then
 * calling the relevant function to load the component HTML, CSS & JavaScript into the DOM
 * @param {String} component_name The fully qualified name of the component, e.g "data_model/account_create"
 * @param {String} parent_uid The UID of the component within which this component is being loaded as a sub
 * component. This can by null if the component is loaded as the first component for this page, i.e the page component
 * @param {String} parent_element_id The DOM id of the parent component element
 * @param {Object} load_arguments The arguments to pass to the component's constructor
 * @param {Boolean} replace_parent_content If true, the parent element's html is overridden with the component that
 * is being loaded. If false, the component's DOM content is appended to the parent element
 * @param {Boolean} component_builder_active Flag that indicates whether the component builder is active
 * @param {Function} callback Function to call once the component has been loaded completely
 */
function loadComponent(component_name,parent_uid,parent_element_id,load_arguments,replace_parent_content,component_builder_active,callback) {
	parent_uid = parent_uid || "";
	if (parent_element_id != "body") {
		let parent_uid_str = "";
		if (parent_uid != "") {
			parent_uid_str = parent_uid+"_";
		}
		parent_element_id = '#'+parent_uid_str+parent_element_id;
	}
	if (typeof callback !== "function") {callback = function() {}}
	if (typeof(replace_parent_content) === "undefined") {
		replace_parent_content = false; // JGL: By default, let's add to the content in the parent element
	}
	if (typeof(component_builder_active) === "undefined") {
		component_builder_active = false;
	}
	if (typeof(component_name) !== null) {
		if (typeof(load_arguments) === "undefined") {
			load_arguments = {};
		}
		load_arguments["url_parameters"] = getAllUrlParams();
		load_arguments["component_name"] = component_name.replace(/\//g,'_');
		load_arguments["component_load_name"] = component_name;
		load_arguments["parent_element"] = parent_element_id;
		load_arguments["parent_uid"] = parent_uid;
		load_arguments["component_path"] = getRootPath()+"project/components/"+component_name;
		generateNextDOMIndex(load_arguments["component_name"]);
		if (typeof(load_arguments["uid"]) !== "undefined") {
			if (typeof(registered_component_array[load_arguments["uid"]]) !== "undefined") {
				throw new Error("The component '"+registered_component_array[load_arguments["uid"]]+"' is already registered in the DOM");
			}
		} else {
			load_arguments["dom_index"] = dom_component_index_map[load_arguments["component_name"]][dom_component_index_map[load_arguments["component_name"]].length-1];
			load_arguments["uid"] = load_arguments["component_name"]+"_" + load_arguments["dom_index"];
			
		}
		// JGL: Load the component html
		let component_html_load_path = load_arguments["component_path"]+"/component.html";
		if (debug_mode || checkComponentBuilderActive()) {
			component_html_load_path = load_arguments["component_path"]+"/component.html"+getRandomFilePostFix();
		}
		dxGetScript(component_html_load_path, function(html) {
			let final_html = getComponentFinalHtml(load_arguments["uid"].replace("#",""),html);
			if (component_builder_active) {
				final_html = final_html.replace(/col-/g,'component-builder-column col-');
				final_html = final_html.replace(/class="row/g,'class="component-builder-row row');
				final_html = final_html.replace(/class="container/g,'class="component-builder-container container');
			}
			if (typeof(parent_element_id) !== null) {
				if (replace_parent_content) {
					$(parent_element_id).html(final_html);
				} else {
					$(parent_element_id).append(final_html);
				}
			} else {
				if (replace_parent_content) {
					$('body').html(final_html);
				} else {
					$('body').append(final_html);
				}
			}
			loadComponentCss(load_arguments["component_path"]);
			loadComponentJs(load_arguments["component_path"],load_arguments,callback);
		},function() {
			handleLoadComponentError();
		},false/*We need to return the html from the request here*/);
	} else {
		throw new Error("No component name provided");
	}
}
/**
 * Loads the component's CSS into the DOM
 * @param {String} component_path The relative path to the component's folder
 */
function loadComponentCss(component_path) {
	let url = component_path+'/component.css';
	if (debug_mode || checkComponentBuilderActive()) {
		url = component_path+'/component.css'+getRandomFilePostFix();
	}
	if (cache_scripts_requested.indexOf(url) > -1) {
		return;
	} else {
		cache_scripts_requested.push(url);
	}
	$('head').append('<link rel="stylesheet" href="'+url+'" type="text/css" />');
}
/**
 * Loads the component's javascript file into memory
 * @param {String} component_path The relative path to the component's folder
 * @param {Object} load_arguments The arguments to pass to the component's constructor
 * @param {Function} callback The function to call once the javascript has loaded
 */
function loadComponentJs(component_path,load_arguments,callback) {
	let class_name = ""+load_arguments["component_name"];
	if (typeof component_classes[class_name] !== "undefined") {
		let component = new component_classes[class_name](load_arguments);
		registerComponent(component,component.uid);
		if (typeof(component.on_component_loaded) !== "undefined") {
			component.on_component_loaded(true,function() {
				updateAppState('page',getUrlInputParameter("view"));
				callback(component);
			});
		}
	} else {
		let full_component_path = component_path+"/component.js";
		if (debug_mode || checkComponentBuilderActive()) {
			full_component_path = component_path+'/component.js'+getRandomFilePostFix();
		}
		dxGetScript(full_component_path, function(data) {
			// JGL: Execute the on_[component_name]_ready function
			let component = new component_classes[class_name](load_arguments);
			registerComponent(component,component.uid);
			if (typeof(component.on_component_loaded) !== "undefined") {
				component.on_component_loaded(true,function() {
					updateAppState('page',getUrlInputParameter("view"));
					callback(component);
				});
			}
		}, function(data) {
			handleLoadComponentError();
		},false);
	}
}
/**
 * Loads a new page based on the component provided. If in SPA mode, this does not load a new window, but refreshes
 * the DOM with the new component's content
 * @param {String} component_name The fully qualified name of the component, e.g "data_model/account_create"
 * @param {Object} load_arguments The arguments to pass to the component's constructor
 * @param {Function} callback The function to call once the component has loaded
 */
function loadPageComponent(component_name,load_arguments,callback) {
	if (!isSpa() && !isNative()) {
		redirectToInternalPath('?view='+component_name);
		return;
	}
	if ((typeof cb_active !== "undefined") && (cb_active)) {
		// JGL: In this case we should inform the user that we are opening a new page in a new component builder window
		if (confirm("This will open a new component builder window for the page: "+component_name)) {
			let load_arguments_str = '';
			if (typeof load_arguments === "object") {
				let load_argument_keys = Object.keys(load_arguments);
				load_argument_keys.forEach(function(key) {
					load_arguments_str += '&'+key+'='+load_arguments[key];
				});
			}
			redirectToExternalPath(getRootPath()+'component_builder.php?component=pages/'+component_name+load_arguments_str);
			return;
		}
		return;
	}
	registered_component_array = {};
	unRegisterEventHandlers();
	$(document).off();
	$('body').off();
	let final_load_arguments = {"uid":page_uid};
	if (typeof load_arguments === "object") {
		let load_argument_keys = Object.keys(load_arguments);
		load_argument_keys.forEach(function(key) {
			final_load_arguments[key] = load_arguments[key];
		});
	}
	if (!root_history_processing) {
		addPageToRootHistory(component_name);
	} else {
		root_history_processing = false;
	}
	setUrlInputParameter("view",component_name);
	updateAppState("CurrentPage",component_name);
	loadComponent("pages/"+component_name,null,'body',final_load_arguments,true,undefined,callback);
	if (debug_mode) {
		setTimeout(function() {
			dxPostExternal(getServerRootPath()+"divblox/config/framework/check_divblox_admin_logged_in.php",{},
				function(data) {
					let data_obj = JSON.parse(data);
					if (data_obj.Result == "Success") {
						let admin_links_html = '<a target="_blank" href="'+getServerRootPath()+'divblox/config/framework/divblox_admin/setup.php" ' +
							'style="position: fixed;bottom: 10px;right: 10px;">' +
							'<img src="'+getRootPath()+'divblox/assets/images/divblox_logo.svg" style="max-height:30px;"/></a>' +
							'<a target="_blank" href="'+getRootPath()+'component_builder.php?component=pages/'+component_name+'" ' +
							'class="btn btn-outline-primary btn-sm" style="position: fixed;bottom: 10px;right: 105px;"><i class="fa fa-wrench" aria-hidden="true"></i> Component Builder</a>';
						$('body').append(admin_links_html);
					}
				},
				function(data) {
				dxLog("Error: "+data);
				});
		},1000)
	}
}
/**
 * Used to handle the onpopstate event when the browser back/forward buttons are clicked in SPA mode
 * @param {Number} direction -1 For backwards, 0 for nothing or 1 for forwards
 */
function loadPageFromRootHistory(direction) {
	if (direction == -1) {
		root_history_index--;
		if (root_history_index < 0) {root_history_index = 0;}
	} else if (direction == 1) {
		root_history_index++;
		if (root_history_index >= root_history.length)  {
			root_history_index = root_history.length - 1;
		}
	} else {
		return;
	}
	let view_to_load = root_history[root_history_index];
	let current_view = getUrlInputParameter("view");
	if (view_to_load !== current_view) {
		root_history_processing = true;
		loadPageComponent(view_to_load);
	}
}
/**
 * Adds the provided page view to the root history array as the current active page
 * @param {String} page_view The name of the page component to add to the root history array
 */
function addPageToRootHistory(page_view) {
	if (!isSpa()) {return;}
	if (root_history[root_history.length - 1] !== page_view) {
		root_history.push(page_view);
	}
	root_history_index = root_history.length - 1;
	updatePushStateWithCurrentView();
}
/**
 * Updates the window history with the current view for SPA mode
 */
function updatePushStateWithCurrentView() {
	if (!isSpa() || isNative()) {return;}
	let current_view = getUrlInputParameter("view");
	if (current_view !== null) {
		window.history.pushState(root_history_index, null, getServerRootPath()+'?view='+current_view);
		window.history.replaceState(root_history_index, null, location.pathname);
	}
}
/**
 * Helper function to redirect to the login page when a component load error occurs
 */
function handleLoadComponentError() {
	setTimeout(function() {
		loadPageComponent('login');
	},2000);
	throw new Error("Invalid component: Components must be grouped in folders with all relevant scripts." +
		" Click here to visit the setup page: "+getServerRootPath()+"divblox/config/framework/divblox_admin/setup.php" +
		"Will redirect to login page in 2s");
}
/**
 * When a component is loaded in the DOM, it's element Id's are prefixed with the component UID. This function
 * modifies the component HTML to provide the final html
 * @param {String} uid The UID of the component
 * @param {String} initial_html The html as provided by the component
 * @return {String} The final html with the UID's prefixed
 */
function getComponentFinalHtml(uid,initial_html) {
	let final_html = initial_html.replace(/id="/g,'id="'+uid+'_');
	final_html = final_html.replace(/="#/g,'="#'+uid+'_');
	return final_html
}
/**
 * When a component is loaded more than once in the DOM, it needs to have a unique DOM index. This function provides
 * that functionality
 * @param {String} component_name The name of the component
 */
function generateNextDOMIndex(component_name) {
	if (typeof dom_component_index_map[component_name] !== "undefined") {
		let LastValue = dom_component_index_map[component_name][dom_component_index_map[component_name].length-1];
		dom_component_index_map[component_name].push(LastValue+1);
	} else {
		dom_component_index_map[component_name] = [1];
	}
}
/**
 * Registers a component in the  registered_component_array in order for it to be retrievable later.
 * @param {Object} component_dom_object The component object to register
 * @param {String} uid The UID of the component to register
 */
function registerComponent(component_dom_object,uid) {
	if (typeof(registered_component_array[uid]) !== "undefined") {
		throw new Error("The component '"+uid+"' is already registered in the DOM");
	}
	registered_component_array[uid] = component_dom_object;
}
/**
 * Retrieves a component, based on its UID from registered_component_array
 * @param {String} uid The UID of the component to retrieve
 * @return {Object} The component object
 */
function getRegisteredComponent(uid) {
	if (typeof(registered_component_array[uid]) === "undefined") {
		throw new Error("The component '"+uid+"' is not registered in the DOM");
	}
	return registered_component_array[uid]
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
	return getRegisteredComponent(page_uid);
}
/**
 * Gets an HTML element based on its id and component
 * @param {Object} component The component object
 * @param {String} element_id The original DOM id of the element
 * @return {jQuery|HTMLElement} The element object
 */
function getComponentElementById(component,element_id) {
	return $("#"+component.uid+"_"+element_id);
}
/**
 * Based on the actual DOM element id, retrieves the component UID from an HTML element id
 * @param {String} component_element_id The final DOM id of the element
 * @param {String} element_id The original element id of the element
 * @return {String} The UID of the component where this element is defined
 */
function getUidFromComponentElementId(component_element_id,element_id) {
	return component_element_id.replace("_"+element_id,"");
}
/**
 * Starts the propagation of an event by triggering it on the main page component
 * @param {String} event_name The name of the event
 * @param {Object} parameters_obj The parameters to send along with the event
 */
function pageEventTriggered(event_name,parameters_obj) {
	getPageMainComponent().eventTriggered(event_name,parameters_obj);
}
/**
 * Gets the path to the given component's php script. If in native mode, this returns the full url to the script on
 * the server. If not, it's a relative path to the script.
 * @param {Object} component The component object
 * @return {string} The path to the component's php script
 */
function getComponentControllerPath(component) {
	if (isNative()) {
		return server_final_url+component.arguments["component_path"]+"/component.php";
	}
	return component.arguments["component_path"]+"/component.php";
}
/**
 * Loads the given component's html as a jQuery DOM object and passes it to the callback function
 * @param {String} component_path The path to the component
 * @param {Function} callback The function to execute once the DOM object has been created
 */
function loadComponentHtmlAsDOMObject(component_path,callback) {
	dxGetScript(component_path+"/component.html"+getRandomFilePostFix(), function(html) {
		let doctype = document.implementation.createDocumentType('html', '', '');
		let component_dom = document.implementation.createDocument('', 'html', doctype);
		let jq_dom = jQuery(component_dom);
		let jq_html = $.parseHTML(html);
		jq_dom.find('html').append(jq_html);
		callback(jq_dom);
	}, function() {
	
	},false/*We need to return the html from the request here*/);
}
/**
 * Gets a component by its wrapper div id
 * @param {String} wrapper_div_id The element id of the wrapper div
 * @param {String} parent_uid The UID of the component parent
 * @return {Object} The component object
 */
function getComponentByWrapperId(wrapper_div_id,parent_uid) {
	if (typeof parent_uid === "undefined") {
		parent_uid = page_uid;
	}
	let wrapper_id_str = "#"+parent_uid+"_"+wrapper_div_id;
	let wrapper_element = $(wrapper_id_str);
	if (wrapper_element.length < 1) {
		return null;
	}
	
	let uids = Object.keys(registered_component_array);
	let component_to_return = null;
	uids.forEach(function(uid) {
		let component = getRegisteredComponent(uid);
		if (component.arguments['parent_element'] === wrapper_id_str) {
			component_to_return = component;
		}
	});
	return component_to_return;
}
/**
 * Generates a unique id that can be assigned to a DOM element
 * @return {string} The css id to use
 */
function getUniqueDomCssId() {
	let css_id_candidate = '';
	let possible_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	let done = false;
	while(!done) {
		for (let i = 0; i < 5; i++) {
			css_id_candidate += possible_characters.charAt(Math.floor(Math.random() * possible_characters.length));
		}
		if ($('body').find("#"+css_id_candidate).length > 0) {
			// That Id exists
		} else {
			done = true;
		}
	}
	return css_id_candidate;
}
/**
 * Scans the url input parameters and adds them to the current app state
 * @param {String} url_parameters_str The current url input parameters
 */
function preparePageInputs(url_parameters_str) {
	if (typeof url_parameters_str !== "undefined") {
		updateAppState('page_inputs',url_parameters_str);
	}
	if (isSpa() || isNative()) {
		redirectToInternalPath();
	} else {
		processPageInputs();
	}
}
/**
 * Handles the current page load based on the input parameters provided
 */
function processPageInputs() {
	let page_inputs = getValueFromAppState('page_inputs');
	if (page_inputs === null) {
		loadUserRoleLandingPage("anonymous");
		return;
	}
	if (page_inputs.length > 0) {
		url_input_parameters = new URLSearchParams(page_inputs);
	} else {
		loadUserRoleLandingPage("anonymous");
		return;
	}
	let view = "pages/"+url_input_parameters.get("view");
	updateAppState("CurrentPage",view);
	if ((typeof url_input_parameters.get("view") === "undefined") || (url_input_parameters.get("view") == null)) {
		throw new Error("Invalid component name provided. Click here to visit the setup page: "+getServerRootPath()+"divblox/config/framework/divblox_admin/setup.php");
	} else {
		addPageToRootHistory(url_input_parameters.get("view"));
		loadComponent(view,null,'body',{"uid":page_uid},false);
	}
	if (debug_mode) {
		dxPostExternal(getServerRootPath()+"divblox/config/framework/check_divblox_admin_logged_in.php",{},
			function(data) {
				let data_obj = JSON.parse(data);
				if (data_obj.Result == "Success") {
					let admin_links_html = '<a target="_blank" href="'+getServerRootPath()+'divblox/config/framework/divblox_admin/setup.php" ' +
						'style="position: fixed;bottom: 10px;right: 10px;">' +
						'<img src="'+getRootPath()+'divblox/assets/images/divblox_logo.svg" style="max-height:30px;"/></a>' +
						'<a target="_blank" href="'+getRootPath()+'component_builder.php?component='+view+'" ' +
						'class="btn btn-outline-primary btn-sm" style="position: fixed;bottom: 10px;right: 105px;"><i class="fa fa-wrench" aria-hidden="true"></i> Component Builder</a>';
					$('body').append(admin_links_html);
				}
			},
			function(data) {});
	}
}
/**
 * Provides a file post fix that is used to ensure files are reloaded from the server and not cached
 * @return {string} The file post fix to append to the file name
 */
function getRandomFilePostFix() {
	let postfix_candidate = '';
	let possible_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	for (let i = 0; i < 5; i++) {
		postfix_candidate += possible_characters.charAt(Math.floor(Math.random() * possible_characters.length));
	}
	return '?v='+postfix_candidate;
}
/**
 * Adds an event handler to the registered_event_handlers array to enable diblox to offload it later
 * @param {String} dom_node The string describing the dom node to which the event was attached
 * @param {String} event The name of the event
 * @param {String} dom_id The id of the dom element
 * @param {String} dom_class The class of the dom element
 */
function registerEventHandler(dom_node,event,dom_id,dom_class) {
	if (typeof dom_node === "undefined"){return;}
	if (typeof event === "undefined"){return;}
	let event_obj = {dom_node:dom_node,event:event,id:dom_id,class:dom_class};
	if (typeof registered_event_handlers !== "object") {
		registered_event_handlers = [];
	}
	registered_event_handlers.push(event_obj);
}
/**
 * Loops through registered_event_handlers and removes the registered event handlers
 */
function unRegisterEventHandlers() {
	let event_keys = Object.keys(registered_event_handlers);
	event_keys.forEach(function(key) {
		let event_obj = registered_event_handlers[key];
		if (typeof event_obj.id !== "undefined") {
			$(event_obj.dom_node).off(event_obj.event,"#"+event_obj.id);
		} else if (typeof event_obj.class !== "undefined") {
			$(event_obj.dom_node).off(event_obj.event,"."+event_obj.class);
		} else {
			$(event_obj.dom_node).off(event_obj.event);
		}
	});
	registered_event_handlers = [];
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * divblox issue tracking related functions
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Sends a request to the server to log a new feedback item on basecamp. This function will automatically detect the
 * current page component from which the feedback is captured. Also, if a user is logged in, we will automatically
 * capture their details on the feedback as well
 * @param {String} type ISSUE|FEATURE
 * @param {String} title The title of the feedback
 * @param {String} description The description of the feedback
 * @param {String} component_name The name of the specifically affected sub component
 * @param {String} component_uid The UID of the specifically affected sub component
 * @param {Function} on_success The function to call when the feedback was captured successfully
 * @param {Function} on_fail The function to call when something went wrong
 */
function logNewComponentFeedback(type,title,description,component_name,component_uid,on_success,on_fail) {
	if (typeof on_success !== "function") {
		on_success = function(){}
	}
	if (typeof on_fail !== "function") {
		on_fail = function(message){}
	}
	let capture_page = getValueFromAppState('CurrentPage');
	if (capture_page.indexOf("pages") === -1) {
		capture_page = "pages/"+capture_page;
	}
	dxRequestSystem(getRootPath()+'divblox/config/framework/issue_tracking/issue_request_handler.php?f=newIssue',
		{type:type,
			title:title,
			description:description,
			component_name:component_name,
			component_uid:component_uid,
			capture_page:capture_page},
		function(data_str) {
			on_success(data_str);
		},
		function(data_obj) {
			on_fail(data_obj.Message);
		});
}
/**
 * Appends the feedback button and modal to the current page
 */
function initFeedbackCapture() {
	if (!allow_feedback) {return;}
	let button_html = '<button id="dxGlobalFeedbackButton" type="button" class="btn btn-dark" data-toggle="modal"' +
		' data-target="#dxGlobalFeedbackModal">Feedback</button>';
	let modal_html = '<div class="modal fade" id="dxGlobalFeedbackModal" tabindex="-1" role="dialog"' +
		' aria-labelledby="FeedbackModal" aria-hidden="true">\n' +
		'    <div class="modal-dialog" role="document">\n' +
		'        <div class="modal-content">\n' +
		'            <div class="modal-header">\n' +
		'                <h5 class="modal-title">Provide feedback for this page</h5>\n' +
		'                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
		'                    <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>\n' +
		'                </button>\n' +
		'            </div>\n' +
		'            <div class="modal-body">\n' +
		'                <div class="row mt-n4">\n' +
		'                    <div class="col-12">\n' +
		'<label class="small">Feedback Type</label>' +
		'                        <select id="dxGlobalFeedbackType" class="form-control">' +
		'<option value="ISSUE">Bug</option>' +
		'<option value="FEATURE">Feature Request</option>' +
		'                       </select>'+
		'<label class="mt-2 small">Feedback Title</label>' +
		'                        <input type="text" id="dxGlobalFeedbackTitle" class="form-control"' +
		' placeholder="Title"/>' +
		'<label class="mt-2 small">Feedback Description (Optional)</label>' +
		'                        <textarea id="dxGlobalFeedbackDescription" class="form-control"' +
		' placeholder="Describe your issue or feature here..." rows="5"/>' +
		'<div id="dxGlobalFeedbackTechnicalWrapper">'+
		'<label class="mt-2 small">Component (Optional)</label>' +
		'                        <select id="dxGlobalFeedbackComponent" class="form-control">' +
		'<option value="-1">-Select Component-</option>' +
		'                       </select>' +
		'</div>'+
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
	$('body').append(button_html).append(modal_html);
	$("#dxGlobalFeedbackSubmitButton").on("click", function() {
		if ($("#dxGlobalFeedbackTitle").val() === "") {
			showAlert("Title is required...","error","OK",false);
			return;
		}
		logNewComponentFeedback($("#dxGlobalFeedbackType").val(),$("#dxGlobalFeedbackTitle").val(),$("#dxGlobalFeedbackDescription").val(),$("#dxGlobalFeedbackComponent").val(),undefined,
			function() {
				showAlert("Feedback captured!","success");
				if (checkComponentBuilderActive()) {
					setTimeout(function() {
						window.location.reload(true);
					},1000)
				}
				$("#dxGlobalFeedbackModal").modal('hide');
			},
			function(message) {
				showAlert("Error saving feedback: "+message,"warning","OK");
			})
	});
	if (!checkComponentBuilderActive()) {
		$("#dxGlobalFeedbackTechnicalWrapper").hide();
		return; //JGL: We only want to give the following options when the user is working on the component builder
	}
	$("#dxGlobalFeedbackTechnicalWrapper").show();
	let current_component = getRegisteredComponent(page_uid);
	let sub_components = current_component.getSubComponents();
	let sub_component_names = [];
	sub_components.forEach(function(sub_component) {
		if (sub_component_names.indexOf(sub_component.arguments.component_name) === -1) {
			sub_component_names.push(sub_component.arguments.component_name);
		}
	});
	sub_component_names.forEach(function(sub_component_name) {
		$("#dxGlobalFeedbackComponent").append('<option value="'+sub_component_name+'">'+sub_component_name+'</option>');
	})
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * divblox general helper functions
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Provides a more detailed implementation of console.log that honours the "debug_mode" flag
 * @param {String} Message The message to log to console
 * @param {Boolean} show_stack_trace If true, includes the current stack trace
 */
function dxLog(Message,show_stack_trace) {
	if (typeof show_stack_trace === "undefined") {
		show_stack_trace = true;
	}
	if (debug_mode || checkComponentBuilderActive()) {
		if (show_stack_trace) {
			let stack = new Error().stack;
			let stack_array = stack.split("\n");
			console.log(Message+" "+stack_array[2].trim());
		} else {
			console.log(Message);
		}
	}
}
/**
 * Divblox keeps an array of elements that are currently disabled because a request is currently happening. This
 * function adds a specific element to that array, disables it and sets its text to the provided text
 * @param {jQuery|HTMLElement} element The element to add to the loading array
 * @param {String} loading_text The text to display while loading (Optional)
 * @return {String} The id of the element that was added to the array
 */
function addTriggerElementToLoadingElementArray(element,loading_text) {
	let trigger_element_id = -1;
	let focused = $(':focus');
	if (element === false) {
		// This means the developer intentionally does not want an element to be disabled
		element = undefined;
	} else {
		if (typeof element === "undefined") {
			if (focused !== "undefined") {
				element = focused;
			}
		}
	}
	
	if (typeof element !== "undefined") {
		if (typeof loading_text === "undefined") {
			loading_text = "Loading...";
		} else {
			loading_text = loading_text + '...';
		}
		trigger_element_id = element.attr("id");
		element_loading_obj[trigger_element_id] = element.html();
		let loading_html = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"' +
			' style="vertical-align: initial;""></span> '+loading_text;
		element.html(loading_html);
		element.attr("disabled","true");
		element.addClass("disabled");
	}
	return trigger_element_id;
}
/**
 * Should be called once a request has completed to restore the trigger element to its original state
 * @param {String} trigger_element_id The dom id of the element to restore
 */
function removeTriggerElementFromLoadingElementArray(trigger_element_id) {
	if (typeof trigger_element_id !== "undefined") {
		if (typeof element_loading_obj[trigger_element_id] !== "undefined") {
			$("#"+trigger_element_id).html(element_loading_obj[trigger_element_id]).attr("disabled",false).removeClass("disabled");
		}
	}
}
/**
 * This function is the default function to send a request to the server from the divblox frontend. This function
 * does some heavy lifting with regards to sending a request to the server:
 * - Determines the current state of the connection to the server in order to either queue, deny or process the request
 * - Adds the element that triggered the request to the loading element array to be disabled during the request
 * - Adds the request to the dx_queue to be processed.
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} on_success A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Success"
 * @param {Function} on_fail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Failed"
 * @param {Boolean} queue_on_offline Tells the function to either queue or deny the request, based on the offline state
 * @param {jQuery|HTMLElement} element The element to add to the loading array
 * @param {String} loading_text The text to display while loading (Optional)
 */
function dxRequestInternal(url,parameters,on_success,on_fail,queue_on_offline,element,loading_text) {
	if (isNative()) {
		if (url.indexOf(server_final_url) === -1) {
			if (url.substr(0,1) === ".") {
				url = url.substr(1);
			}
			url = server_final_url+url;
		}
	}
	if (typeof queue_on_offline === "undefined") {
		queue_on_offline = false;
	}
	if(!checkOnlineStatus() && !dx_offline) {
		setOffline();
	}
	if(!checkOnlineStatus() && (!queue_on_offline)) {
		on_fail({Message:presentOfflineRequestBlockedMessage(),FailureReason:"OFFLINE"});
		return;
	} else {
		if (dx_offline) {
			setOnline();
		}
	}
	let trigger_element_id = addTriggerElementToLoadingElementArray(element,loading_text);
	dxAddToRequestQueue({"url":url,"parameters":parameters,"on_success":on_success,"on_fail":on_fail,"trigger_element_id":trigger_element_id});
}
/**
 * This function processes the next request in the dx_queue
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} on_success A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Success"
 * @param {Function} on_fail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with an object containing a property "Result: Failed
 * @param {String} trigger_element_id The dom id of the element that triggered the request
 * @return {dxRequestInternalQueued} Unused
 */
function dxRequestInternalQueued(url,parameters,on_success,on_fail,trigger_element_id) {
	dx_processing_queue = true;
	if (typeof parameters !== "object") {
		parameters = {};
	}
	if (authentication_token == "") {
		authentication_token = getValueFromAppState('dxAuthenticationToken');
	}
	if (typeof authentication_token === "undefined") {
		authentication_token = "";
	}
	parameters["AuthenticationToken"] = authentication_token;
	if (isNative()) {
		parameters["is_native"] = 1;
	}
	$.post(url,parameters)
		.done(function(data) {
			dx_processing_queue = false;
			dxProcessRequestQueue();
			if (!isJsonString(data)) {
				on_fail({"Result":"Failed","Message":"Returned value is not a valid JSON string","Returned":data});
			} else {
				let data_obj = JSON.parse(data);
				if (typeof data_obj.AuthenticationToken !== "undefined") {
					authentication_token = data_obj.AuthenticationToken;
					updateAppState('dxAuthenticationToken',authentication_token);
				}
				if (data_obj.Result != "Success") {
					on_fail(data_obj);
				} else {
					on_success(data_obj);
				}
			}
			removeTriggerElementFromLoadingElementArray(trigger_element_id);
		})
		.fail(function(data) {
			dx_processing_queue = false;
			dxProcessRequestQueue();
			on_fail(data);
			removeTriggerElementFromLoadingElementArray(trigger_element_id);
		});
	return this;
}
/**
 * Adds the given request to dx_queue and calls dxProcessRequestQueue()
 * @param {Object} request The request to add to dx_queue
 */
function dxAddToRequestQueue(request) {
	dx_queue.push(request);
	dxProcessRequestQueue();
}
/**
 *Triggers the processing of the next request in dx_queue
 */
function dxProcessRequestQueue() {
	if (dx_processing_queue) {return;}
	if (!navigator.onLine) {
		setItemInLocalStorage("dx_queue",JSON.stringify(dx_queue),2);
		showAlert(presentOfflineRequestQueuedMessage(),"info","OK",false);
		return;
	}
	if (dx_queue.length > 0) {
		let next_post = dx_queue.shift();
		dxRequestInternalQueued(next_post.url,next_post.parameters,next_post.on_success,next_post.on_fail,next_post.trigger_element_id);
	} else {
		dx_processing_queue = false;
	}
}
/**
 * Wrapper for jQuery's $.get function that is used to load component scripts. This function will first attempt to
 * check if the script was already loaded before doing an additional request to the server.
 * @param {String} url The url of the script on the server that should be loaded
 * @param {Function} on_success The function to call when the script was loaded
 * @param {Function} on_fail The function to call when the script could not be loaded
 * @param {Boolean} force_cache If true, the function first checks if the script is flagged as already loaded. If
 * false, the function will ALWAYS load the script from the server
 * @return {*} Used to force the function to exit
 */
function dxGetScript(url,on_success,on_fail,force_cache) {
	if (typeof on_success !== "function") {
		on_success = function() {}
	}
	if (typeof on_fail !== "function") {
		on_fail = function() {}
	}
	if (typeof force_cache === "undefined") {
		force_cache = true;
	}
	if (force_cache) {
		if (cache_scripts_requested.indexOf(url) > -1) {
			dxLog("Loaded from cache: "+url);
			return on_success('Loaded from cache');
		} else {
			cache_scripts_requested.push(url);
		}
	}
	if ((isNative() && (url.indexOf(".html") === -1))) {
		let len = $('script').filter(function () {
			return ($(this).attr('src') == url);
		}).length;
		
		if (len === 0) {
			console.log("script tag for "+url+" not yet in scope");
			let script_element = document.createElement('script');
			document.getElementsByTagName('head')[0].appendChild(script_element);
			script_element.src = url;
			script_element.onreadystatechange = function() {
				if (script_element.readyState == 4 || script_element.readyState == "complete") {
					if (force_cache) {
						cache_scripts_loaded.push(url);
					}
					on_success();
				}
			};
			script_element.onload = function() {
				if (force_cache) {
					cache_scripts_loaded.push(url);
				}
				on_success();
			};
		} else {
			console.log("script tag for "+url+" ALREADY in scope");
			if (force_cache) {
				cache_scripts_loaded.push(url);
			}
			on_success();
		}
	} else {
		$.get(url, function(data, status) {
			if (status != "success") {
				on_fail();
			} else {
				if (force_cache) {
					cache_scripts_loaded.push(url);
				}
				on_success(data);
			}
		}).done(function() {}).fail(function() {
			on_fail();
		});
	}
}
/**
 * Used by the component builder and setup scripts to do server requests
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} on_success A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 * @param {Function} on_fail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 */
function dxRequestSystem(url,parameters,on_success,on_fail) {
	if (typeof parameters !== "object") {
		parameters = {};
	}
	if (authentication_token == "") {
		authentication_token = getValueFromAppState('dxAuthenticationToken');
	}
	if (typeof authentication_token === "undefined") {
		authentication_token = "";
	}
	parameters["AuthenticationToken"] = authentication_token;
	if (isNative()) {
		parameters["is_native"] = 1;
	}
	dxPostExternal(url,parameters,on_success,on_fail);
}
/**
 * Used by the component builder and setup scripts to do server requests
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} on_success A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 * @param {Function} on_fail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 */
function dxRequestAdmin(url,parameters,on_success,on_fail) {
	if (typeof on_success !== "function") {
		on_success = function() {
		
		}
	}
	if (typeof on_fail !== "function") {
		on_fail = function() {
		
		}
	}
	$.post(url,parameters)
		.done(function(data) {
			on_success(data)
		})
		.fail(function(data) {
			on_fail(data)
		});
}
/**
 * Used by the component builder and setup scripts to do server requests
 * @param {String} url The url on the server to which the request must be sent
 * @param {Object} parameters The input parameters to send in the POST body
 * @param {Function} on_success A callback function to call when the request is successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 * @param {Function} on_fail A callback function to call when the request is NOT successfully processed. This
 * callback will ALWAYS be populated with a string containing the request result
 */
function dxPostExternal(url,parameters,on_success,on_fail) {
	if (typeof on_success !== "function") {
		on_success = function() {
		
		}
	}
	if (typeof on_fail !== "function") {
		on_fail = function() {
		
		}
	}
	$.post(url,parameters)
		.done(function(data) {
			if (!isJsonString(data)) {
				on_fail(data);
				return;
			}
			let data_obj = JSON.parse(data);
			if (typeof data_obj.AuthenticationToken !== "undefined") {
				authentication_token = data_obj.AuthenticationToken;
				updateAppState('dxAuthenticationToken',authentication_token);
			}
			on_success(data)
		})
		.fail(function(data) {
			on_fail(data)
		});
}
/**
 * Determines whether a string is a valid JSON string
 * @param {String} input_string The string to check
 * @return {boolean} true if valid JSON, false if not
 */
function isJsonString(input_string) {
	try {
		JSON.parse(input_string);
	} catch (e) {
		return false;
	}
	return true;
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
		for (let i=0; i<arr.length; i++) {
			// separate the keys and the values
			let a = arr[i].split('=');
			// in case params look like: list[]=thing1&list[]=thing2
			let paramNum = undefined;
			let paramName = a[0].replace(/\[\d*\]/, function(v) {
				paramNum = v.slice(1,-1);
				return '';
			});
			// set parameter value (use 'true' if empty)
			let paramValue = typeof(a[1])==='undefined' ? true : a[1];
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
 * @param {String} alert_str The message to alert
 * @param {String} icon The type of icon to show with the message: "success|error|warning|info"
 * @param {String|Array} button_array Can be either a string to display on a single button or an array of strings to
 * display on multiple buttons
 * @param {Boolean} auto_hide If true, the sweet alert will auto hide. If false, it needs to be dismissed
 * @param {Number} milliseconds_until_auto_hide If auto_hide is true, this value determines how long to wait before
 * hiding
 * @param {Function} confirm_function Optional to pass a confirm function that is executed when the confirm button
 * is clicked
 * @param {Function} cancel_function  Optional to pass a cancel function that is executed when the cancel button
 * is clicked
 */
function showAlert(alert_str,icon,button_array,auto_hide,milliseconds_until_auto_hide,confirm_function,cancel_function) {
	if (typeof icon === "undefined") {
		icon = 'info'; // Force the user to pass icon = "success, error or warning" if they want other icons
	}
	if (typeof button_array === "undefined") {
		button_array = []; // Force the user to pass ButtonText in order to show a button
	}
	if (typeof auto_hide === "undefined") {
		auto_hide = true; // Force the user to pass auto_hide = false to not auto hide
	}
	if (typeof milliseconds_until_auto_hide === "undefined") {
		milliseconds_until_auto_hide = 1500;
	}
	if (typeof swal !== "undefined") {
		if ((typeof confirm_function !== "undefined") &&
			(typeof cancel_function !== "undefined")) {
			swal({
				title: null,
				text: alert_str,
				icon: icon,
				buttons: button_array,
				dangerMode: true,
			}).then((confirmed) => {
				if (confirmed) {
					confirm_function();
				} else {
					cancel_function();
				}
			});
		} else {
			swal({
				title: null,
				text: alert_str,
				icon: icon,
				button: button_array,
			});
			if (auto_hide) {
				setTimeout(function() {
					swal.close();
				},milliseconds_until_auto_hide)
			}
		}
	} else {
		alert(alert_str);
	}
}
/**
 * Adds a cookie in the browser for the current path
 * @param {String} name The name of the cookie
 * @param {String} value The value to store
 * @param {Number} days How many days until the cookie should expire
 */
function createCookie(name, value, days) {
	let Expires;
	if (days) {
		let CurrentDate = new Date();
		CurrentDate.setTime(CurrentDate.getTime() + (days * 24 * 60 * 60 * 1000));
		Expires = "; expires=" + CurrentDate.toGMTString();
	} else {
		Expires = "";
	}
	document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + Expires + "; path=/";
}
/**
 * Returns the value of a cookie from the browser
 * @param {String} name The name of the cookie to return
 * @return {String|Null} The value of the cookie or null
 */
function readCookie(name) {
	let NameEQ = encodeURIComponent(name) + "=";
	let ca = document.cookie.split(';');
	for (let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) === ' ')
			c = c.substring(1, c.length);
		if (c.indexOf(NameEQ) === 0)
			return decodeURIComponent(c.substring(NameEQ.length, c.length));
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
 * @param {String} validation_message The validation message to display
 * @param {Boolean} is_valid If true, toggles the "is-valid" class, if false, toggles "is-invalid"
 * @param {Boolean} is_reset If false applies the class specified by is_valid
 */
function toggleValidationState(component,attribute,validation_message,is_valid,is_reset) {
	is_valid = is_valid || false;
	is_reset = is_reset || false;
	let valid_class = "is-valid";
	if (!is_valid) {
		valid_class = "is-invalid";
	}
	getComponentElementById(component,attribute).removeClass("is-invalid").removeClass("is-valid");
	if (!is_reset) {
		getComponentElementById(component,attribute).addClass(valid_class);
	}
	if (validation_message.length > 0) {
		getComponentElementById(component,attribute+"InvalidFeedback").text(validation_message);
	}
}
/**
 * Returns true if an attribute that is contained within a component's validation state is valid
 * @param {Object} component The containing component object
 * @param {String} attribute The original element id of the attribute
 * @return {boolean} true if valid, false if not
 */
function checkValidationState(component,attribute) {
	// JGL: returns true if valid
	return !getComponentElementById(component,attribute).hasClass("is-invalid");
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
 * @return {String} The string value for the attribute
 */
function getDataModelAttributeValue(attribute) {
	if (typeof attribute === "object") {
		if (attribute === null) {
			return '';
		}
		if (typeof attribute["date"] !== "undefined") {
			let js_date_str = attribute["date"];
			let js_date = js_date_str.slice(0,10);
			let js_time = js_date_str.slice(11,16);
			if (js_time != "00:00") {
				return js_date+"T"+js_time;
			}
			return js_date;
		}
	}
	return attribute;
}
/**
 * Helper function to return the current highest z-index in die DOM
 * @return {number} The z-index
 */
function getHighestZIndex() {
	var index_highest = 0;
	$('div').each(function(){
		var index_current = parseInt($(this).css("z-index"), 10);
		if(index_current > index_highest) {
			index_highest = index_current;
		}
	});
	return index_highest;
}
/**
 * Returns the path to the App's main logo
 * @return {string} The path to the logo file
 */
function getAppLogoUrl() {
	return getRootPath()+'divblox/assets/images/divblox_logo.svg';
}
/**
 * 	Renders the app's logo within any div with class "app_logo"
 */
function renderAppLogo() {
	$(".app_logo").html('<a href="'+getRootPath()+'"><img alt="App Logo" src="'+getAppLogoUrl()+'"' +
		' class="img-fluid"/></a>');
}
/**
 * Adds a wrapper for the offline notification message to the html body
 */
function addOfflineWrapper() {
	let offline_notification = $(".OfflineNotificationWrapper");
	if (offline_notification.length == 0) {
		$("body").append('<div class="OfflineNotificationWrapper"><p id="OfflineMessage"></p></div>');
	}
}
/**
 * Wrapper function to serve both native and web needs with regards to online status
 * @return {boolean} true if online, false if not
 */
function checkOnlineStatus() {
	if (!isNative()) {
		return navigator.onLine;
	}
	let networkState = navigator.connection.type;
	let states = {};
	states[Connection.UNKNOWN]  = 'Unknown connection';
	states[Connection.ETHERNET] = 'Ethernet connection';
	states[Connection.WIFI]     = 'WiFi connection';
	states[Connection.CELL_2G]  = 'Cell 2G connection';
	states[Connection.CELL_3G]  = 'Cell 3G connection';
	states[Connection.CELL_4G]  = 'Cell 4G connection';
	states[Connection.CELL]     = 'Cell generic connection';
	states[Connection.NONE]     = 'No network connection';
	
	return networkState !== Connection.NONE;
}
/**
 * Triggers the required user feedback when offline
 */
function setOffline() {
	addOfflineWrapper();
	$(".OfflineNotificationWrapper").removeClass("BackOnlineNotificationWrapper")
	$(".OfflineNotificationWrapper").fadeIn(500);
	$("#OfflineMessage").html('<i class="fa fa-chain-broken" aria-hidden="true" style="margin-right:10px;"></i>You\'re Offline');
	$(".OfflineNotificationWrapper").css("zIndex", getHighestZIndex()+1);
	dx_offline = true;
}
/**
 * Triggers the required user feedback when back online
 */
function setOnline() {
	addOfflineWrapper();
	$("#OfflineMessage").html('<i class="fa fa-plug" aria-hidden="true" style="margin-right:10px;"></i>Back Online');
	$(".OfflineNotificationWrapper").addClass("BackOnlineNotificationWrapper").fadeOut(3500);
	if (!dx_offline) {
		$(".OfflineNotificationWrapper").css("zIndex", getHighestZIndex()+1);
	}
	dx_offline = false;
	dxProcessRequestQueue();
}
/**
 * Checks whether the current logged in user's role is in allowable_role_array.
 * @param {Array} allowable_role_array The array of roles that are allowed
 * @param {Function} on_not_allowed The function that is executed when the role is not allowed
 * @param {Function} on_allowed The function that is executed when the role is allowed
 */
function dxCheckCurrentUserRole(allowable_role_array,on_not_allowed,on_allowed) {
	if (allowable_role_array.length === 0) {
		on_allowed();
		return;
	}
	if (allowable_role_array.indexOf("anonymous") > -1){
		on_allowed();
		return;
	}
	let current_role_local = getValueFromAppState('dx_role');
	if (current_role_local !== null) {
		if (current_role_local.toLowerCase() === "dxadmin") {
			on_allowed();
			return;
		}
	}
	let found_local = false;
	allowable_role_array.forEach(function(element) {
		if (element.toLowerCase() === current_role_local) {
			found_local = true;
		}
	});
	if (found_local) {
		on_allowed();
		return;
	}
	getCurrentUserRole(function(role) {
		if (typeof role === "undefined") {
			on_not_allowed();
			return;
		}
		if (role === "dxadmin") {
			on_allowed();
			return;
		}
		let found = false;
		allowable_role_array.forEach(function(element) {
			if (element.toLowerCase() === role.toLowerCase()) {
				found = true;
			}
		});
		if (!found) {
			on_not_allowed();
		} else {
			on_allowed();
		}
	});
}
/**
 * Gets the current user's user role from the server
 * @param {Function} callback The function that is executed once the current user's role is retrieved from the
 * server. This function receives the current role
 */
function getCurrentUserRole(callback) {
	dxRequestSystem(getServerRootPath()+'project/assets/php/global_request_handler.php',{f:'getUserRole'},
		function(data) {
			let data_obj = JSON.parse(data);
			if (typeof data_obj.CurrentRole !== "undefined") {
				updateAppState('dx_role',data_obj.CurrentRole.toLowerCase());
				callback(data_obj.CurrentRole);
			} else {
				callback(undefined);
			}
		},
		function(data) {
			callback(undefined);
		});
}

/**
 * Gets the current user's user role from the local app state. Useful when no need for a server call
 * @return {String|Null} The current user role
 */
function getCurrentUserRoleFromAppState() {
	return getValueFromAppState("dx_role");
}
/**
 * Registers the the current user role in the app state
 * @param {String} user_role The user role to register
 */
function registerUserRole(user_role) {
	updateAppState("dx_role",user_role);
	doAfterAuthenticationActions();
}

/**
 * Checks whether the current client's OS is mobile
 * @return {boolean} true if mobile, false if not
 */
function isMobile() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		return true;
	}
	return false;
}
/**
 * Checks whether the current OS is iOS
 * @return {Boolean} true if is iOS, false if not
 */
function isIos() {
	let user_agent = window.navigator.userAgent.toLowerCase();
	return /iphone|ipad|ipod/.test(user_agent);
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
	return is_native;
}
/**
 * Checks whether we are in Single Page Application mode
 * @return {Boolean} true if SPA or native, false if not
 */
function isSpa() {
	if (!isNative()) {
		return spa_mode;
	}
	return isNative();
}
/**
 * Updates the navigation class for the active page to highlight the menu item that relates to the page
 * @param {String} page_name The name of the page component
 * @param {String} page_title The title to display in the browser
 */
function setActivePage(page_name,page_title) {
	if (typeof page_name === "undefined") {
		page_name = "page";
	}
	if (typeof page_title === "undefined") {
		page_title = "divblox - page";
	}
	setTimeout(function() {
		// JGL: Let's just give the page component enough time to finish loading...
		$(".navigation-activate-on-"+page_name).addClass("active");
		if ((typeof cb_active === "undefined") || (cb_active === false)) {
			$("title").text(page_title);
		}
	},500);
}
/**
 * Wrapper function for window.open() that ensures that we are loading a relative path in the same window
 * @param {String} path_from_root The path to load
 */
function redirectToInternalPath(path_from_root) {
	if (typeof path_from_root === "undefined") {
		path_from_root = './';
	}
	window.open(getRootPath()+path_from_root,"_self");
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
		if (confirm("We are now going to web. Do you want to continue?")) {
			window.open(url,"_blank");
		}
	} else {
		window.open(url,"_blank");
	}
}
/**
 * A helper function that displays and manages a bootstrap toast message
 * @param {String} title The title of the toast
 * @param {String} toast_message The message to be displayed in the toast
 * @param {Object} position The position of the toast on the page: {x:"left|middle|right",y:"top|middle|bottom"}
 * @param {String} icon_path The path to the icon file that must be displayed on the toast
 * @param {moment} toast_time_stamp OPTIONAL An instance of a moment object that is used to keep track of when the
 * toast was
 * created
 * @param {Number} auto_hide If not provided, the toast will not auto hide. Otherwise, it will hide in "auto_hide" ms
 */
function showToast(title,toast_message,position,icon_path,toast_time_stamp,auto_hide) {
	if (typeof title === "undefined") {
		title = local_config.app_name;
	}
	if (typeof toast_message === "undefined") {
		toast_message = 'No message';
	}
	if (typeof position === "undefined") {
		position = {y:"top",x:"right"};
		// JGL: y can be top,middle or bottom. x can be left, right or middle
	}
	if (position.y === "middle") {
		position.x = "middle";
	}
	if (position.x === "middle") {
		position.y = "middle";
	}
	if (typeof icon_path === "undefined") {
		icon_path = getRootPath()+'project/assets/images/favicon.ico';
	}
	if (typeof toast_time_stamp === "undefined") {
		toast_time_stamp = moment();
	}
	let toast_id = getUniqueDomCssId();
	if (typeof auto_hide === "undefined") {
		auto_hide = 'data-autohide="false"';
	} else {
		auto_hide = 'data-delay="'+auto_hide+'"';
	}
	registered_toasts.push({id:toast_id,toast_time_stamp:toast_time_stamp});
	if (!updating_toasts) {
		setTimeout(function() {
			updating_toasts = true;
			updateToasts();
		},3000);
	}
	let position_y = position.y+':0px';
	let additional_styles = '';
	if (position.y === "middle") {
		additional_styles = ' style="width:348px;max-width:90%;margin: auto;"';
	}
	// JGL: Let's find the correct toasts wrapper div and add the toast, otherwise create the wrapper div first
	let toast_html = '<div id="'+toast_id+'" class="toast" role="alert" aria-live="assertive" aria-atomic="true"' +
		' '+auto_hide+additional_styles+'>' +
		'<div class="toast-header">' +
		'   <img src="'+icon_path+'" class="rounded mr-2" alt="image" style="max-height: 20px;"/>' +
		'   <strong class="mr-auto">'+title+'</strong>' +
		'   <small id="'+toast_id+'_time_stamp">'+toast_time_stamp.fromNow()+'</small>' +
		'   <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" style="z-index: 1051;">' +
		'       <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"> </i></span>' +
		'   </button>' +
		'</div>' +
		'<div class="toast-body">' +
		'   '+toast_message+
		'</div>' +
		'</div>';
	
	if (position.x === 'right') {
		if (position.y === 'top') {
			if ($('body').find("#top_right_toast_wrapper").length < 1) {
				$('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
					'style="position: fixed;'+position_y+';left:0;width:100%;z-index:999999;">' +
					'<div id="top_right_toast_wrapper" style="position: absolute; top: 0; right: 0;width:348px;max-width:90%;padding: 5px;"/>');
			}
			$("#top_right_toast_wrapper").append(toast_html);
		}
		if (position.y === 'bottom') {
			if ($('body').find("#bottom_right_toast_wrapper").length < 1) {
				$('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
					'style="position: fixed;'+position_y+';left:0;width:100%;z-index:999999;">' +
					'<div id="bottom_right_toast_wrapper" style="position: absolute; bottom:0px; right:' +
					' 0;width:348px;max-width:90%;padding: 5px;"/>');
			}
			$("#bottom_right_toast_wrapper").append(toast_html);
		}
	}
	if (position.x === 'left') {
		if (position.y === 'top') {
			if ($('body').find("#top_left_toast_wrapper").length < 1) {
				$('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
					'style="position: fixed;'+position_y+';left:0;width:100%;z-index:999999;">' +
					'<div id="top_left_toast_wrapper" style="position: absolute; top: 0; left:' +
					' 0;width:348px;max-width:90%;padding: 5px;"/>');
			}
			$("#top_left_toast_wrapper").append(toast_html);
		}
		if (position.y === 'bottom') {
			if ($('body').find("#bottom_left_toast_wrapper").length < 1) {
				$('body').append('<div class="toast-aria" aria-live="polite" aria-atomic="true" ' +
					'style="position: fixed;'+position_y+';left:0;width:100%;z-index:999999;">' +
					'<div id="bottom_left_toast_wrapper" style="position: absolute; bottom:0px; left:' +
					' 0;width:348px;max-width:90%;padding: 5px;"/>');
			}
			$("#bottom_left_toast_wrapper").append(toast_html);
		}
	}
	if (position.x === 'middle') {
		if ($('body').find("#middle_toast_wrapper").length < 1) {
			$('body').append('<div class="toast-aria" id="middle_toast_wrapper" aria-live="polite" aria-atomic="true" class="d-flex' +
				' justify-content-center align-items-center"' +
				' style="position:fixed;top:40%;left:0;width:100%;z-index:999999;"/>');
		}
		$("#middle_toast_wrapper").append(toast_html);
	}
	$("#"+toast_id).toast("show");
}
/**
 * Used to update the time value on a toast
 */
function updateToasts() {
	let toasts_left_to_update = 0;
	if ((registered_toasts.length > 0) && (updating_toasts)) {
		registered_toasts.forEach(function(toast_obj) {
			if ($('body').find("#"+toast_obj.id).length < 1) {
				// Already removed
			} else {
				if ($("#"+toast_obj.id).hasClass("hide")) {
					// Must remove
				} else {
					toasts_left_to_update++;
					$("#"+toast_obj.id+"_time_stamp").text(toast_obj.toast_time_stamp.fromNow());
				}
			}
		});
		if (toasts_left_to_update === 0) {
			updating_toasts = false;
			registered_toasts = [];
			$(".toast-aria").remove();
		} else {
			setTimeout(function() {
				updateToasts();
			},3000);
		}
	}
}
/**
 * Adds a key:value pairing to the global_vars array and stores it in the app state
 * @param {String} name The name of the variable to store
 * @param {String} value The value to store
 * @return {Boolean|*} false if a name was not specified.
 */
function setGlobalVariable(name,value) {
	if (typeof name === "undefined") {
		return false;
	}
	if (typeof value === "undefined") {
		value = '';
	}
	global_vars[name] = value;
	storeAppState();
}
/**
 * Returns a global variable from the global_vars array by name
 * @param {String} name The name of the variable to return
 * @return {String} The value to return
 */
function getGlobalVariable(name) {
	if (typeof global_vars[name] === "undefined") {
		return '';
	}
	return global_vars[name];
}
/**
 * Sets a global id that is used to constrain for a specified entity
 * @param {String} entity The name of the entity to which this constrain id applies
 * @param {Number} constraining_id The id to constain by
 * @return {Boolean|*} false if a name was not specified.
 */
function setGlobalConstrainById(entity,constraining_id) {
	if (typeof entity === "undefined") {
		return false
	}
	if (typeof constraining_id === "undefined") {
		constraining_id = -1;
	}
	setGlobalVariable('Constraining'+entity+'Id',constraining_id);
}
/**
 * Returns a global id that is used to constrain for a specified entity
 * @param {String} entity The name of the entity to which this constrain id applies
 * @return {Number} The id to constain by. -1 If not set
 */
function getGlobalConstrainById(entity) {
	if (typeof entity === "undefined") {
		return -1
	}
	let return_value = getGlobalVariable('Constraining'+entity+'Id');
	if (return_value === '') {
		return -1;
	}
	if (typeof return_value === "undefined") {
		return -1
	}
	if (return_value === null) {
		return -1;
	}
	return return_value;
}
/**
 * Initiates the required functionality for native mode
 */
function initNative() {
	updateAppState('divblox_config','success');
	console.log("dx native init");
}
/**
 * Stores a key:value pairing in local storage
 * @param {String} item_key The key to store
 * @param {String} item_value The value to store
 */
function setItemInLocalStorage(item_key,item_value) {
	if (typeof(Storage) === "undefined") {
		// JGL: This is a fallback for when local storage is not available.
		createCookie(item_key,item_key);
		return;
	}
	localStorage.setItem(item_key, item_value);
}
/**
 * Retrieves a value from local storage by key
 * @param {String} item_key The key to find
 * @return {String|Null} The value returned from local storage
 */
function getItemInLocalStorage(item_key) {
	if (typeof(Storage) === "undefined") {
		// JGL: This is a fallback for when local storage is not available.
		return readCookie(item_key);
	}
	if (typeof localStorage[item_key] !== "undefined") {
		return localStorage[item_key];
	}
	return null;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////