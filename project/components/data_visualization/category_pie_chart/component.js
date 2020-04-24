// JGL: Documentation can be found here: https://www.chartjs.org/
if (typeof component_classes['data_visualization_category_pie_chart'] === "undefined") {
    class data_visualization_category_pie_chart extends DivbloxDomBaseComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = {};
            // Sub component config end
            this.chart_obj = null;
            this.prerequisite_array = ['project/assets/js/chartjs/Chart.min.js'];
        }

        reset(inputs, propagate) {
            super.reset(inputs, propagate);
            this.initChart();
        }

        updateChart() {
            dxRequestInternal(getComponentControllerPath(this),
                {f: "getData"},
                function (data_obj) {
                    this.chart_obj.data = data_obj.Data;
                    this.chart_obj.update();
                    dxLog("AA: " + data_obj.DataArray);
                }.bind(this),
                function (data_obj) {
                    throw new Error(data_obj.Message);
                });
        }

        initChart() {
            let ctx = this.uid + "_ComponentChart";
            this.chart_obj = new Chart(ctx, {
                type: 'pie',
                data: {/* Server Data */},
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });
            this.updateChart();
        }
    }

    component_classes['data_visualization_category_pie_chart'] = data_visualization_category_pie_chart;
}