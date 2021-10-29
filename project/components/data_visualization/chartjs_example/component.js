// JGL: Documentation can be found here: https://www.chartjs.org/
if (typeof componentClasses['data_visualization_chartjs_example'] === "undefined") {
    class ChartJsExample extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = {};
            // Sub component config end
            this.chart_obj = null;
            this.prerequisites = ['project/assets/js/chartjs/Chart.bundle.min.js'];
        }

        reset(inputs, propagate) {
            super.reset(inputs, propagate);
            this.initChart();
        }

        updateChart() {
            dxRequestInternal(getComponentControllerPath(this),
                {f: "getData"},
                function (data) {
                    this.chart_obj.data = data.Data;
                    this.chart_obj.update();
                }.bind(this),
                function (data) {
                    throw new Error(data.Message);
                });
        }

        initChart() {
            let ctx = this.uid + "_ComponentChart";
            this.chart_obj = new Chart(ctx, {
                type: 'bar',
                data: {/*JGL: We don't provide data here since we will get the data from the server*/},
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            this.updateChart();
        }
    }

    componentClasses['data_visualization_chartjs_example'] = ChartJsExample;
}