if (typeof componentClasses['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomBaseComponent {
		constructor(inputs, supportsNative, requiresNative) {
			super(inputs, supportsNative, requiresNative);
			// Sub component config start
			this.subComponentDefinitions = [
				{
					"component_load_path": "data_model/[EntityName-Lowercase]/[component_name]_data_series",
					"parent_element": "data_series",
					"arguments": {"uid": this.getUid() + "_[component_name]_data_series_component"}
				},
				{
					"component_load_path": "data_model/[EntityName-Lowercase]/[component_name]_update",
					"parent_element": "data_update",
					"arguments": {"uid": this.getUid() + "_[component_name]_update_component"}
				},
				{
					"component_load_path": "data_model/[EntityName-Lowercase]/[component_name]_create",
					"parent_element": "data_create",
					"arguments": {"uid": this.getUid() + "_[component_name]_create_component"}
				}
			];
			// Sub component config end
		}

		reset(inputs, propagate) {
			super.reset(inputs, propagate);
			this.toggleSubView("data_series_wrapper");
		}

		eventTriggered(eventName, parameters) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch (eventName) {
				case '[component_name]_create_clicked':
					this.toggleSubView("data_create_wrapper");
					getRegisteredComponent(this.getUid() + "_[component_name]_create_component").reset();
					break;
				case '[EntityName-Lowercase]_clicked':
					this.toggleSubView("data_update_wrapper");
					getRegisteredComponent(this.getUid() + "_[component_name]_update_component").reset(parameters.id, true);
					break;
				case '[EntityName-Lowercase]_created':
				case '[EntityName-Lowercase]_deleted':
				case '[EntityName-Lowercase]_updated':
				case '[component_name]_back_clicked':
					this.toggleSubView("data_series_wrapper");
					getRegisteredComponent(this.getUid() + "_[component_name]_data_series_component").reset();
					break;
				default:
				dxLog("Event triggered: " + eventName + ": " + JSON.stringify(parameters));
			}
			// Let's pass the event to all sub components
			this.propagateEventTriggered(eventName, parameters);
		}

		registerDomEvents() {
			getComponentElementById(this, "button_create").on("click", function () {
				pageEventTriggered("[component_name]_create_clicked", {});
			});
			getComponentElementById(this, "button_create_back").on("click", function () {
				pageEventTriggered("[component_name]_back_clicked", {});
			});
			getComponentElementById(this, "button_update_back").on("click", function () {
				pageEventTriggered("[component_name]_back_clicked", {});
			});
		}

		toggleSubView(viewElementId) {
			let views = ["data_series_wrapper", "data_update_wrapper", "data_create_wrapper"];
			getComponentElementById(this, viewElementId).fadeIn("slow");
			views.forEach(function (item) {
				if (item !== viewElementId) {
					getComponentElementById(this, item).hide();
				}
			}.bind(this));
		}
	}

	componentClasses['[component_full_name]'] = [component_class_name];
}