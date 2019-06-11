// JGL: Documentation can be found here: https://www.chartjs.org/
if (typeof(on_data_visualization_chartjs_example_ready) === "undefined") {
	function on_data_visualization_chartjs_example_ready(load_arguments) {
		// JGL: This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			dxLog("Reset for chartjs_example not implemented");
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
			let this_component = this;
			dxGetScript(getRootPath()+"project/assets/js/chartjs/Chart.min.js",function() {
				this_component.initChart();
			});
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
		this.chart_obj = null;
		this.updateChart = function() {
			let this_component = this;
			dxRequestInternal(getComponentControllerPath(this_component),
				{f:"getData"},
				function(data_obj) {
					this_component.chart_obj.data = data_obj.Data;
					this_component.chart_obj.update();
				},
				function(data_obj) {
					throw new Error(data_obj.Message);
				});
		}.bind(this);
		this.initChart = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			let ctx = uid+"_ComponentChart";
			this.chart_obj = new Chart(ctx, {
				type: 'bar',
				data: {/*JGL: We don't provide data here since we will get the data from the server*/},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero:true
							}
						}]
					}
				}
			});
			this_component.updateChart();
		}.bind(this);
	}
}
