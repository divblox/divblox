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
// divblox initialization
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let dx_version = "1.2.5";
let bootstrap_version = "4.3.1";
let jquery_version = "3.4.1";
let minimum_required_php_version = "7.2";
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
// divblox initialization related functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let dependency_array = [
	"divblox/assets/js/bootstrap/4.3.1/bootstrap.bundle.min.js",
	"divblox/assets/js/sweetalert/sweetalert.min.js",
	"project/assets/js/project.js",
	"project/assets/js/momentjs/moment.js"
];
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
function loadDependencies(count) {
	if (typeof count === "undefined") {
		count = 0;
	}
	if (count < dependency_array.length) {
		dxGetScript(getRootPath()+dependency_array[count], function( data, textStatus, jqxhr ) {
			loadDependencies(count+1);
		});
	} else {
		checkFrameworkReady();
	}
}
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
function isIos() {
	let user_agent = window.navigator.userAgent.toLowerCase();
	return /iphone|ipad|ipod/.test(user_agent);
}
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
function callInstallPrompt() {
	// We can't fire the dialog before preventing default browser dialog
	//TODO: Complete this for custom prompts
	if (installPromptEvent !== undefined) {
		installPromptEvent.prompt();
	}
}
function checkFrameworkReady() {
	// Check if the framework is installed and configured.
	// After that we call a generic "on_divblox_ready()" function
	if (isNative()) {
		allow_feedback = local_config.allow_feedback;
		doAfterInitActions();
		on_divblox_ready();
		return;
	}
	window.addEventListener('beforeinstallprompt', function(event) {
		event.preventDefault();
		installPromptEvent = event;
	});
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
	if (debug_mode && !isNative()) {
		setTimeout(function() {
			// JGL: Disabling this for now since component builder cache settings seem to fix the problem we are trying
			// to address with this code. This code will be removed in future dx releases
			/*if (typeof override_console_check === "undefined") {
				let t = performance.now();
				for (i = 0; i < 100; i++) {
					console.warn("DEBUG MODE: Checking console open...");
				}
				let duration = performance.now() - t;
				if (duration < 10) {
					showToast("Console not opened","Debug mode is currently on. When debug mode is on, it is" +
						" recommended to always have the browser console opened with cache disabled to avoid caching" +
						" issues.");
				}
			}*/
		},3000);
	}
}
function showAppUpdateBar() {
	$("#AppUpdateWrapper").addClass("show").css("z-index",getHighestZIndex()+1);
}
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
function getRootPath() {
	if (typeof force_server_root !== "undefined") {
		return getServerRootPath();
	}
	return "";
}
function setUrlInputParameter(name,value) {
	if (url_input_parameters === null) {
		url_input_parameters = new URLSearchParams();
	}
	url_input_parameters.set(name,value);
	updateAppState('page_inputs',"?"+url_input_parameters.toString());
}
function getUrlInputParameter(name) {
	if (url_input_parameters === null) {
		return null;
	}
	return url_input_parameters.get(name);
}
function updateAppState(item_key,item_value) {
	app_state[item_key] = item_value;
	storeAppState();
}
function storeAppState() {
	app_state['global_vars'] = global_vars;
	setItemInLocalStorage("dx_app_state",btoa(JSON.stringify(app_state)));
}
function getAppState() {
	let app_state_encoded = getItemInLocalStorage("dx_app_state");
	if (app_state_encoded !== null) {
		app_state = JSON.parse(atob(app_state_encoded));
	}
	return app_state;
}
function getValueFromAppState(item_key) {
	app_state = getAppState();
	if (typeof app_state[item_key] !== "undefined") {
		return app_state[item_key];
	}
	return null;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// divblox component and DOM related functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let dom_component_index_map = {};
// JGL: Let's initialize the object that will contain relevant DOM info for our components that are rendered on the page
let registered_component_array = {};
/*DivbloxDomBaseComponent is the base class that manages the component javascript for every component*/
class DivbloxDomBaseComponent {
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
	loadPrerequisites(success_callback,fail_callback) {
		if (typeof success_callback !== "function") {
			success_callback = function(){};
		}
		if (typeof fail_callback !== "function") {
			fail_callback = function(){};
		}
		success_callback();
	}
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
	reset(inputs) {
		this.resetSubComponents(inputs);
	}
	setLoadingState() {
		this.is_loading = true;
		$("#"+this.uid+"_ComponentContent").hide();
		$("#"+this.uid+"_ComponentPlaceholder").show();
		$("#"+this.uid+"_ComponentFeedback").html('');
	}
	removeLoadingState() {
		this.is_loading = false;
		$("#"+this.uid+"_ComponentContent").show();
		$("#"+this.uid+"_ComponentPlaceholder").hide();
	}
	resetSubComponents(inputs) {
		this.sub_component_objects.forEach(function(component) {
			component.reset(inputs);
		}.bind(this));
	}
	getReadyState() {
		return this.component_success;
	}
	handleComponentSuccess(additional_input) {
		if (this.component_success === true) {
			return;
		}
		this.component_success = true;
		$("#"+this.uid+"_ComponentContent").show();
		$("#"+this.uid+"_ComponentPlaceholder").hide();
		if (typeof cb_active !== "undefined") {
			if (cb_active) {
				on_divblox_component_success(this);
			}
		}
	}
	handleComponentError(ErrorMessage) {
		this.component_success = false;
		$("#"+this.uid+"_ComponentContent").hide();
		$("#"+this.uid+"_ComponentPlaceholder").show();
		$("#"+this.uid+"_ComponentFeedback").html('<div class="alert alert-danger alert-danger-component"><strong><i' +
			' class="fa fa-exclamation-triangle ComponentErrorExclamation" aria-hidden="true"></i>' +
			' </strong><br>'+ErrorMessage+'</div>');
		if (typeof cb_active !== "undefined") {
			if (cb_active) {
				on_divblox_component_error(this);
			}
		}
	}
	handleComponentAccessError(ErrorMessage) {
		this.handleComponentError(ErrorMessage);
	}
	registerDomEvents() {/*To be overridden in sub class as needed*/}
	initCustomFunctions() {/*To be overridden in sub class as needed*/}
	subComponentLoadedCallBack(component) {
		this.sub_component_objects.push(component);
		this.sub_component_loaded_count++;
		this.loadSubComponent();
		// JGL: Override as needed
	}
	loadSubComponent() {
		if (typeof this.sub_component_definitions[this.sub_component_loaded_count] !== "undefined") {
			let sub_component_definition = this.sub_component_definitions[this.sub_component_loaded_count];
			loadComponent(sub_component_definition.component_load_path,this.uid,sub_component_definition.parent_element,sub_component_definition.arguments,false,false,this.subComponentLoadedCallBack.bind(this));
		} else {
			this.reset();
		}
	}
	getSubComponents() {
		return this.sub_component_objects;
	}
	getSubComponentDefinitions() {
		return this.sub_component_definitions;
	}
	getUid() {
		return this.uid;
	}
	getComponentName() {
		return this.arguments['component_name'];
	}
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
	eventTriggered(event_name,parameters_obj) {
		switch(event_name) {
			case '[event_name]':
			default:
				dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
		}
		// Let's pass the event to all sub components
		this.propagateEventTriggered(event_name,parameters_obj);
	}
	propagateEventTriggered(event_name,parameters_obj) {
		this.sub_component_objects.forEach(function(component) {
			component.eventTriggered(event_name,parameters_obj);
		});
	}
}
function on_divblox_component_success(component) {
	let uid = component.getUid();
	component.dom_component_obj.component_success = true;
	$("#"+uid+"_ComponentContent").show();
	$("#"+uid+"_ComponentPlaceholder").hide();
	
}
function on_divblox_component_error(component,message) {
	let uid = component.getUid();
	component.dom_component_obj.component_success = false;
	$("#"+uid+"_ComponentContent").hide();
	$("#"+uid+"_ComponentPlaceholder").show();
	$("#"+uid+"_ComponentFeedback").html('<div class="alert alert-danger alert-danger-component"><strong><i' +
		' class="fa fa-exclamation-triangle ComponentErrorExclamation" aria-hidden="true"></i>' +
		' </strong><br>'+message+'</div>');
}
function checkComponentBuilderActive() {
	if (typeof cb_active !== "undefined") {
		return cb_active;
	}
}
function loadComponent(component_name,parent_uid,parent_element_id,load_arguments,replace_parent_content,component_builder_active,callback) {
	// JGL: Views are simply components that are used as a collection of components, so this function is also used
	// to load views. arguments is an optional parameter that will be in the form of objects
	// {input:value,input2:value2}, etc.
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
function loadPageComponent(component_name,load_arguments,callback) {
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
	doPostPageLoadActions();
}
function handleLoadComponentError() {
	setTimeout(function() {
		loadPageComponent('login');
	},2000);
	throw new Error("Invalid component: Components must be grouped in folders with all relevant scripts." +
		" Click here to visit the setup page: "+getServerRootPath()+"divblox/config/framework/divblox_admin/setup.php" +
		"Will redirect to login page in 2s");
}
function getComponentFinalHtml(uid,initial_html) {
	let final_html = initial_html.replace(/id="/g,'id="'+uid+'_');
	final_html = final_html.replace(/="#/g,'="#'+uid+'_');
	return final_html
}
function generateNextDOMIndex(component_name) {
	if (typeof dom_component_index_map[component_name] !== "undefined") {
		let LastValue = dom_component_index_map[component_name][dom_component_index_map[component_name].length-1];
		dom_component_index_map[component_name].push(LastValue+1);
	} else {
		dom_component_index_map[component_name] = [1];
	}
}
function registerComponent(component_dom_object,uid) {
	if (typeof(registered_component_array[uid]) !== "undefined") {
		throw new Error("The component '"+uid+"' is already registered in the DOM");
	}
	registered_component_array[uid] = component_dom_object;
	renderAppLogo();
}
function getRegisteredComponent(uid) {
	if (typeof(registered_component_array[uid]) === "undefined") {
		throw new Error("The component '"+uid+"' is not registered in the DOM");
	}
	return registered_component_array[uid]
}
function getComponentName(component) {
	return component.getComponentName();
}
function getPageMainComponent() {
	return getRegisteredComponent(page_uid);
}
function getComponentElementById(component,element_id) {
	return $("#"+component.uid+"_"+element_id);
}
function getUidFromComponentElementId(component_element_id,element_id) {
	return component_element_id.replace("_"+element_id,"");
}
function pageEventTriggered(event_name,parameters_obj) {
	getPageMainComponent().eventTriggered(event_name,parameters_obj);
}
function getComponentControllerPath(component) {
	if (isNative()) {
		return server_final_url+component.arguments["component_path"]+"/component.php";
	}
	return component.arguments["component_path"]+"/component.php";
}
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
function preparePageInputs(url_parameters_str) {
	if (typeof url_parameters_str !== "undefined") {
		updateAppState('page_inputs',url_parameters_str);
	}
	redirectToInternalPath();
}
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
	doPostPageLoadActions();
}
function getRandomFilePostFix() {
	let postfix_candidate = '';
	let possible_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	for (let i = 0; i < 5; i++) {
		postfix_candidate += possible_characters.charAt(Math.floor(Math.random() * possible_characters.length));
	}
	return '?v='+postfix_candidate;
}
function registerEventHandler(dom_node,event,dom_id,dom_class) {
	if (typeof dom_node === "undefined"){return;}
	if (typeof event === "undefined"){return;}
	let event_obj = {dom_node:dom_node,event:event,id:dom_id,class:dom_class};
	if (typeof registered_event_handlers !== "object") {
		registered_event_handlers = [];
	}
	registered_event_handlers.push(event_obj);
}
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// divblox issue tracking related functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function logNewComponentFeedback(type,title,description,component_name,component_uid,on_success,on_fail) {
	//JGL: This function will automatically detect the current page component from which the feedback is captured
	//JGL: Also, if a user is logged in, we will automatically capture their details on the feedback as well
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// divblox helper functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
function removeTriggerElementFromLoadingElementArray(trigger_element_id) {
	if (typeof trigger_element_id !== "undefined") {
		if (typeof element_loading_obj[trigger_element_id] !== "undefined") {
			$("#"+trigger_element_id).html(element_loading_obj[trigger_element_id]).attr("disabled",false).removeClass("disabled");
		}
	}
}
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
function dxAddToRequestQueue(request) {
	dx_queue.push(request);
	dxProcessRequestQueue();
}
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
function isJsonString(input_string) {
	try {
		JSON.parse(input_string);
	} catch (e) {
		return false;
	}
	return true;
}
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
function showAlert(AlertStr,Icon,ButtonArray,AutoHide,TimeUntilAutoHide,ConfirmFunction,CancelFunction) {
	if (typeof Icon === "undefined") {
		Icon = 'info'; // Force the user to pass Icon = "success, error or warning" if they want other icons
	}
	if (typeof ButtonArray === "undefined") {
		ButtonArray = []; // Force the user to pass ButtonText in order to show a button
	}
	if (typeof AutoHide === "undefined") {
		AutoHide = true; // Force the user to pass AutoHide = false to not auto hide
	}
	if (typeof TimeUntilAutoHide === "undefined") {
		TimeUntilAutoHide = 1000;
	}
	if (typeof swal !== "undefined") {
		if ((typeof ConfirmFunction !== "undefined") &&
			(typeof CancelFunction !== "undefined")) {
			swal({
				title: null,
				text: AlertStr,
				icon: Icon,
				buttons: ButtonArray,
				dangerMode: true,
			}).then((confirmed) => {
				if (confirmed) {
					ConfirmFunction();
				} else {
					CancelFunction();
				}
			});
		} else {
			swal({
				title: null,
				text: AlertStr,
				icon: Icon,
				button: ButtonArray,
			});
			if (AutoHide) {
				setTimeout(function() {
					swal.close();
				},TimeUntilAutoHide)
			}
		}
	} else {
		alert(AlertStr);
	}
}
function createCookie(Name, Value, Days) {
	let Expires;
	if (Days) {
		let CurrentDate = new Date();
		CurrentDate.setTime(CurrentDate.getTime() + (Days * 24 * 60 * 60 * 1000));
		Expires = "; expires=" + CurrentDate.toGMTString();
	} else {
		Expires = "";
	}
	document.cookie = encodeURIComponent(Name) + "=" + encodeURIComponent(Value) + Expires + "; path=/";
}
function readCookie(Name) {
	let NameEQ = encodeURIComponent(Name) + "=";
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
function eraseCookie(Name) {
	createCookie(Name, "", -1);
}
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
function checkValidationState(component,attribute) {
	// JGL: returns true if valid
	return !getComponentElementById(component,attribute).hasClass("is-invalid");
}
function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}
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
function getAppLogoUrl() {
	return getRootPath()+'divblox/assets/images/divblox_logo.svg';
}
function renderAppLogo() {
	// Renders the app's logo within any div with class "app_logo"
	$(".app_logo").html('<a href="'+getRootPath()+'"><img alt="App Logo" src="'+getAppLogoUrl()+'"' +
		' class="img-fluid"/></a>');
}
function addOfflineWrapper() {
	let offline_notification = $(".OfflineNotificationWrapper");
	if (offline_notification.length == 0) {
		$("body").append('<div class="OfflineNotificationWrapper"><p id="OfflineMessage"></p></div>');
	}
}
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
function setOffline() {
	addOfflineWrapper();
	$(".OfflineNotificationWrapper").removeClass("BackOnlineNotificationWrapper")
	$(".OfflineNotificationWrapper").fadeIn(500);
	$("#OfflineMessage").html('<i class="fa fa-chain-broken" aria-hidden="true" style="margin-right:10px;"></i>You\'re Offline');
	$(".OfflineNotificationWrapper").css("zIndex", getHighestZIndex()+1);
	dx_offline = true;
}
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
function getCurrentUserRole(callback/*Sends input with current role string*/) {
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
function isMobile() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		return true;
	}
	return false;
}
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
function redirectToInternalPath(path_from_root) {
	if (typeof path_from_root === "undefined") {
		path_from_root = './';
	}
	window.open(getRootPath()+path_from_root,"_self");
}
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
function getGlobalVariable(name) {
	if (typeof global_vars[name] === "undefined") {
		return '';
	}
	return global_vars[name];
}
function setGlobalConstrainById(entity,constraining_id) {
	if (typeof entity === "undefined") {
		return false
	}
	if (typeof constraining_id === "undefined") {
		constraining_id = -1;
	}
	setGlobalVariable('Constraining'+entity+'Id',constraining_id);
}
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
function setIsNative() {
	is_native = true;
	initNative();
}
function isNative() {
	return is_native;
}
function initNative() {
	updateAppState('divblox_config','success');
	console.log("dx native init");
}
function setItemInLocalStorage(item_key,item_value) {
	if (typeof(Storage) === "undefined") {
		// JGL: This is a fallback for when local storage is not available.
		createCookie(item_key,item_key);
		return;
	}
	localStorage.setItem(item_key, item_value);
}
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