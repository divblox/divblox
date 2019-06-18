// JGL: Trumbowyg documentation: https://alex-d.github.io/Trumbowyg/
if (typeof(on_system_default_rich_text_editor_ready) === "undefined") {
	function on_system_default_rich_text_editor_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			dxLog("Reset for default_rich_text_editor not implemented");
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
			let this_component = this;
			dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/trumbowyg.js", function() {
				$.trumbowyg.svgPath = getRootPath()+"project/assets/packages/trumbowyg/ui/icons.svg";
				dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/base64/trumbowyg.base64.js", function() {
					dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/colors/trumbowyg.colors.js", function() {
						dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/prism.min.js", function() {
							dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/highlight/trumbowyg.highlight.js", function() {
								dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/jquery-resizable.min.js", function() {
									dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/resizimg/trumbowyg.resizimg.js", function() {
										this_component.initEditor();
									});
								});
							});
						});
					});
				});
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
		// Custom functions and declarations to be added below
		this.initEditor = function() {
			let this_component = this;
			let trumbowyg_obj = getComponentElementById(this_component,"ComponentRichTextEditor").trumbowyg({
				resetCss: true,
				autogrow: true,
				autogrowOnEnter: true,
				imageWidthModalEdit: true,
				btnsDef: {
					// Create a new dropdown
					image: {
						dropdown: ['insertImage', 'base64'],
						ico: 'insertImage'
					}
				},
				plugins: {
					resizimg : {
						minSize: 64,
						step: 16,
					}
				},
				btns: [
					['viewHTML'],
					['undo', 'redo'], // Only supported in Blink browsers
					['formatting'],
					['foreColor', 'backColor'],
					['strong', 'em', 'del'],
					['superscript', 'subscript'],
					['link'],
					['image'],
					['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
					['unorderedList', 'orderedList'],
					['highlight'],
					['horizontalRule'],
					['removeformat'],
					['fullscreen']
				]
			});
			dxRequestInternal(getComponentControllerPath(this_component),
				{f:"getInitData"},
				function(data_obj) {
					trumbowyg_obj.html(data_obj.Html);
				},
				function(data_obj) {
					throw new Error(data_obj.Message);
				},false,false);
			trumbowyg_obj.on('tbwchange', function() {
				let current_data = trumbowyg_obj.html();
				setTimeout(function() {
					if (current_data == trumbowyg_obj.html()) {
						dxRequestInternal(getComponentControllerPath(this_component),
							{f:"saveData",
								html:trumbowyg_obj.html()},
							function(data_obj) {
								dxLog(data_obj.Message);
							},
							function(data_obj) {
								throw new Error(data_obj.Message);
							},false,false);
					}
				},1000);
			});
		}.bind(this);
	}
}
