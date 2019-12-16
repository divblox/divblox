if (typeof component_classes['system_authentication'] === "undefined") {
	class system_authentication extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
		}
		reset() {
			getComponentElementById(this,'InputUsername').focus();
		}
		registerDomEvents() {
			getComponentElementById(this,"btnProcessAuthentication").on("click", function() {
				this.processAuthentication();
			}.bind(this));
		}
		processAuthentication() {
			dxRequestInternal(getComponentControllerPath(this),{
					f:"doAuthentication",
					Username:getComponentElementById(this,"InputUsername").val(),
					Password:getComponentElementById(this,"InputPassword").val()},
				function(data_obj) {
					setGlobalConstrainById("Account",data_obj.AccountId);
					registerUserRole(data_obj.UserRole);
					if (isNative()) {
						let stored_push_registration = getItemInLocalStorage('PushRegistrationId');
						if (stored_push_registration != null) {
							createPushRegistration(stored_push_registration,
								function() {
									pageEventTriggered("authenticated",data_obj);
								},
								function(data) {
									pageEventTriggered("authenticated",data_obj);
								});
						} else {
							pageEventTriggered("authenticated",data_obj);
						}
					} else {
						pageEventTriggered("authenticated",data_obj);
					}
					
				}.bind(this),
				function(data_obj) {
					showAlert("Authentication failed! Please try again","error");
					pageEventTriggered("authentication_failed",data_obj);
				}.bind(this),false,getComponentElementById(this,"btnProcessAuthentication"),"Authenticating");
		}
	}
	component_classes['system_authentication'] = system_authentication;
}