if (typeof component_classes['navigation_instance_top_navbar'] === "undefined") {
	class navigation_instance_top_navbar extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
		}
		registerDomEvents() {
			getComponentElementById(this,'InstanceTopNavCancel').on("click",function() {
				this.cancelFunction();
				return false;
			}.bind(this));
			getComponentElementById(this,'InstanceTopNavConfirm').on("click",function() {
				this.confirmFunction();
				return false;
			}.bind(this));
		}
		cancelFunction() {
			dxLog("No cancel function provided");
		}
		confirmFunction() {
			dxLog("No confirm function provided");
		}
		setConfirmFunction(confirm_function,confirm_button_text,hide) {
			if (typeof confirm_button_text === "undefined") {
				confirm_button_text = 'Confirm';
			}
			getComponentElementById(this,'InstanceTopNavConfirm').html(confirm_button_text);
			if (typeof hide === "undefined") {
				hide = false;
			}
			if (typeof confirm_function !== "undefined") {
				this.confirmFunction = confirm_function;
			}
			if (hide) {
				getComponentElementById(this,'InstanceTopNavConfirm').hide();
			}
		}
		setCancelFunction(cancel_function,cancel_button_text,hide) {
			if (typeof cancel_button_text === "undefined") {
				cancel_button_text = '<i class="fa fa-angle-left instance-navbar-back-icon"></i> Back';
			}
			getComponentElementById(this,'InstanceTopNavCancel').html(cancel_button_text);
			if (typeof hide === "undefined") {
				hide = false;
			}
			if (typeof cancel_function !== "undefined") {
				this.cancelFunction = cancel_function;
			}
			if (hide) {
				getComponentElementById(this,'InstanceTopNavCancel').hide();
			}
		}
	}
	component_classes['navigation_instance_top_navbar'] = navigation_instance_top_navbar;
}

if (typeof(on_navigation_instance_top_navbar_ready) === "undefined") {
	function on_navigation_instance_top_navbar_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			dxLog("Reset for instance_top_navbar not implemented");
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
		}.bind(this);
		this.subComponentLoadedCallBack = function(component) {
			// Implement additional required functionality for sub components after load here
			// dxLog("Sub component loaded: "+JSON.stringify(component));
		}.bind(this);
		this.getSubComponents = function() {
			return this.dom_component_obj.getSubComponents(this);
		}.bind(this);
		this.getUid = function() {
			return this.dom_component_obj.getUid();
		}.bind(this);
		// Component specific code below
		// Empty array means ANY user role has access. NB! This is merely for UX purposes.
		// Do not rely on this as a security measure. User role security MUST be managed on the server's side
		this.allowedAccessArray = [];
		this.eventTriggered = function(event_name,parameters_obj) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch(event_name) {
				case '[event_name]':
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.dom_component_obj.propagateEventTriggered(event_name,parameters_obj);
		}.bind(this);
		// Sub component config start
		this.sub_components = {};
		// Sub component config end
		// Custom functions and declarations to be added below
		this.cancelFunction = function() {
			dxLog("No cancel function provided");
		}.bind(this);
		this.confirmFunction = function() {
			dxLog("No confirm function provided");
		}.bind(this);
		this.setConfirmFunction = function(confirm_function,confirm_button_text,hide) {
			if (typeof confirm_button_text === "undefined") {
				confirm_button_text = 'Confirm';
			}
			getComponentElementById(this,'InstanceTopNavConfirm').html(confirm_button_text);
			if (typeof hide === "undefined") {
				hide = false;
			}
			if (typeof confirm_function !== "undefined") {
				this.confirmFunction = confirm_function;
			}
			if (hide) {
				getComponentElementById(this,'InstanceTopNavConfirm').hide();
			}
		}.bind(this);
		this.setCancelFunction = function(cancel_function,cancel_button_text,hide) {
			if (typeof cancel_button_text === "undefined") {
				cancel_button_text = '<i class="fa fa-angle-left instance-navbar-back-icon"></i> Back';
			}
			getComponentElementById(this,'InstanceTopNavCancel').html(cancel_button_text);
			if (typeof hide === "undefined") {
				hide = false;
			}
			if (typeof cancel_function !== "undefined") {
				this.cancelFunction = cancel_function;
			}
			if (hide) {
				getComponentElementById(this,'InstanceTopNavCancel').hide();
			}
			
		}.bind(this);
		getComponentElementById(this,'InstanceTopNavCancel').on("click",function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),'InstanceTopNavCancel');
			let this_component = getRegisteredComponent(uid);
			this_component.cancelFunction();
			return false;
		});
		getComponentElementById(this,'InstanceTopNavConfirm').on("click",function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),'InstanceTopNavConfirm');
			let this_component = getRegisteredComponent(uid);
			this_component.confirmFunction();
			return false;
		});
	}
}
