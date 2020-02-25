if (typeof component_classes['data_model_account_administration'] === "undefined") {
	class data_model_account_administration extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [
				{"component_load_path":"data_model/account_administration_data_series","parent_element":"data_series","arguments":{"uid":this.getUid()+"_account_administration_data_series_component"}},
				{"component_load_path":"data_model/account_administration_update","parent_element":"data_update","arguments":{"uid":this.getUid()+"_account_administration_update_component"}},
				{"component_load_path":"data_model/account_administration_create","parent_element":"data_create","arguments":{"uid":this.getUid()+"_account_administration_create_component"}}];
			// Sub component config end
		}
		reset(inputs,propagate) {
			super.reset(inputs,propagate);
			this.toggleSubView("data_series_wrapper");
		}
		eventTriggered(event_name,parameters_obj) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch(event_name) {
				case 'account_administration_create_clicked':this.toggleSubView("data_create_wrapper");
					getRegisteredComponent(this.getUid()+"_account_administration_create_component").reset();
					break;
				case 'account_clicked':this.toggleSubView("data_update_wrapper");
					getRegisteredComponent(this.getUid()+"_account_administration_update_component").reset(parameters_obj.id,true);
					break;
				case 'account_created':
				case 'account_deleted':
				case 'account_updated':
				case 'account_administration_back_clicked':this.toggleSubView("data_series_wrapper");
					getRegisteredComponent(this.getUid()+"_account_administration_data_series_component").reset();
					break;
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.propagateEventTriggered(event_name,parameters_obj);
		}
		registerDomEvents() {
			getComponentElementById(this,"button_create").on("click", function() {
				pageEventTriggered("account_administration_create_clicked",{});
			});
			getComponentElementById(this,"button_create_back").on("click", function() {
				pageEventTriggered("account_administration_back_clicked",{});
			});
			getComponentElementById(this,"button_update_back").on("click", function() {
				pageEventTriggered("account_administration_back_clicked",{});
			});
		}
		toggleSubView(view_element_id) {
			let view_array = ["data_series_wrapper","data_update_wrapper","data_create_wrapper"];
			getComponentElementById(this,view_element_id).fadeIn("slow");
			view_array.forEach(function(item) {
				if (item !== view_element_id) {
					getComponentElementById(this,item).hide();
				}
			}.bind(this));
		}
	}
	component_classes['data_model_account_administration'] = data_model_account_administration;
}