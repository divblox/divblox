// JGL: Documentation can be found here: https://www.chartjs.org/
if (typeof component_classes['data_visualization_graph_3'] === "undefined") {
	class data_visualization_graph_3 extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
			this.chart_obj = null;
		}
		loadPrerequisites(success_callback,fail_callback) {
			dxGetScript(getRootPath()+"project/assets/js/chartjs/Chart.min.js",function() {
				this.initChart();
				success_callback();
			}.bind(this));
		}
		updateChart() {
			dxRequestInternal(getComponentControllerPath(this),
				{f:"getData"},
				function(data_obj) {
					this.chart_obj.data = data_obj.Data;
					this.chart_obj.update();
				}.bind(this),
				function(data_obj) {
					throw new Error(data_obj.Message);
				});
		}
		initChart() {
			let ctx = this.uid+"_ComponentChart";
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
			this.updateChart();
		}
	}
	component_classes['data_visualization_graph_3'] = data_visualization_graph_3;
}