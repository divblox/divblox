// This file allows you to create your own global javascript functionality.
// Functions declared here can be used to override default divblox behaviour
// The first section of this file is reserved for divblox code generation. Do not modify these lines manually
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let local_config = {
	spa_mode:false,
	service_worker_enabled:false,
	debug_mode:true,
	allow_feedback:true,
	app_name:'divblox'
};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Safe to modify from here downwards
let user_role_landing_pages = {
	"anonymous":"anonymous_landing_page",
	"Administator":"system_account_management",
	//"User":"the_desired_landing_page"
};
let current_user_profile_picture_path = getRootPath+"project/assets/images/divblox_profile_picture_placeholder.svg";
function registerServiceWorker() {
	if ('serviceWorker' in navigator) {
		navigator.serviceWorker.register(getRootPath()+'dx.sw.js').then(reg => {
			reg.addEventListener('updatefound', () => {
				// A wild service worker has appeared in reg.installing!
				service_worker_update = reg.installing;
				service_worker_update.addEventListener('statechange', () => {
					// Has network.state changed?
					switch (service_worker_update.state) {
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
function getAppLogoUrl() {
	return getRootPath()+'project/assets/images/app_logo.png';
}
function presentOfflineRequestQueuedMessage() {
	return "You are offline. Your request has been queued and will be processed as soon as you are connected again.";
}
function presentOfflineRequestBlockedMessage() {
	return "This request cannot be processed at this time because you are offline.";
}
function logout() {
	current_user_profile_picture_path = "";
	dxRequestInternal(getRootPath()+"project/assets/php/global_request_handler.php",{f:"logoutCurrentAccount"},
		function(data_obj) {
			if (data_obj.Result === "Success") {
				setItemInLocalStorage("dx_role","anonymous");
				loadUserRoleLandingPage("anonymous");
			}
		},
		function(data_obj) {
			throw new Error("Could not logout user: "+JSON.stringify(data_obj));
		})
}
function loadUserRoleLandingPage(user_role) {
	if (typeof user_role === "undefined") {
		loadPageComponent('my_profile');
		return;
	}
	if (typeof user_role_landing_pages[user_role] === "undefined") {
		loadPageComponent('my_profile');
		return;
	}
	loadPageComponent(user_role_landing_pages[user_role]);
}
function loadCurrentUserProfilePicture(callback) {
	getCurrentUserAttribute('ProfilePicturePath',function(profile_picture_path) {
		if (typeof profile_picture_path === "undefined") {
			$(".navigation-activate-on-profile").html('<img src="'+current_user_profile_picture_path+'" class="img rounded-circle nav-profile-picture"/>');
			return;
		}
		if (typeof profile_picture_path === null) {
			$(".navigation-activate-on-profile").html('<img src="'+current_user_profile_picture_path+'" class="img rounded-circle nav-profile-picture"/>');
			return;
		}
		current_user_profile_picture_path = profile_picture_path;
		$(".navigation-activate-on-profile").html('<img src="'+profile_picture_path+'" class="img rounded-circle nav-profile-picture"/>');
		if (typeof callback === "function") {
			callback(profile_picture_path);
		}
	});
}
function getCurrentUserAttribute(attribute,callback) {
	let attribute_to_return = undefined;
	if (attribute === "ProfilePicturePath") {
		attribute_to_return = getRootPath()+"project/assets/images/divblox_profile_picture_placeholder.svg";
	}
	dxRequestInternal(getServerRootPath()+'project/assets/php/global_request_handler.php',{f:'getCurrentAccountAttribute',attribute:attribute},
		function(data_obj) {
			if (typeof data_obj.Result === "undefined") {
				callback(attribute_to_return);
				return;
			}
			if (data_obj.Result !== 'Success') {
				callback(attribute_to_return);
				return;
			}
			if (attribute === "ProfilePicturePath") {
				if (data_obj.Attribute === null) {
					callback(attribute_to_return);
					return;
				}
				callback(getServerRootPath()+data_obj.Attribute);
			} else {
				callback(data_obj.Attribute);
			}
		},
		function(data) {
			callback(attribute_to_return);
		},true);
}
function createPushRegistration(registration_id,success_callback,failure_callback) {
	// JGL: The success_callback function will receive the internal push id that is stored on the server
	// JGL: The failure_callback function will receive a failure message
	if (typeof success_callback !== "function") {
		success_callback = function() {};
	}
	if (typeof failure_callback !== "function") {
		failure_callback = function() {};
	}
	if (typeof registration_id === "undefined") {
		failure_callback("No registration id provided");
		return;
	}
	let device_uuid = 'browser';
	let device_platform = 'browser';
	let device_os = 'browser';
	if (isNative()) {
		device_uuid = device.uuid;
		device_platform = device.platform;
		device_os = device.version;
	}
	dxRequestInternal(getServerRootPath()+'project/assets/php/global_request_handler.php',
		{f:'updatePushRegistration',
			registration_id: registration_id,
			device_uuid: device_uuid,
			device_platform: device_platform,
			device_os:device_os,
		},
		function(data_obj) {
			success_callback(data_obj.InternalId);
		},
		function(data_obj) {
			failure_callback(data_obj.Message);
		});
}
function doPostPageLoadActions() {
	setTimeout(function() {
		initFeedbackCapture();
		loadCurrentUserProfilePicture();
	},1000); //JGL: We set a delay here to ensure everything else on the page has completed
}
function doAfterInitActions() {
	//JGL: Any actions that should happen once the document is ready and all dx dependencies have been loaded can be
	// placed here.
}