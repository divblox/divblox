if (typeof component_classes['examples_further_examples'] === "undefined") {
	class examples_further_examples extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
		}
	    initCustomFunctions() {
            
            // dfVzo_button Related functionality
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            getComponentElementById(this,"dfVzo_btn").on("click", function() {
				dxRequestInternal(
					getComponentControllerPath(this),
					{f:getComponentElementById(this, "8LSBQ_FormControlSelect").val(),
					additional_input: getComponentElementById(this, "H7u7b_FormControlInput").val()},
					function (data_obj) {
						getComponentElementById(this, "ResultWrapper").html(JSON.stringify(data_obj.ReturnData));
					}.bind(this),
					function (data_obj) {

					}.bind(this),
					false,
					getComponentElementById(this,"dfVzo_btn"),
					"Executing " + getComponentElementById(this, "8LSBQ_FormControlSelect").val()
				);
            }.bind(this));
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
        }
   	}
	component_classes['examples_further_examples'] = examples_further_examples;
}