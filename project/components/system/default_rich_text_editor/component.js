// JGL: Trumbowyg documentation: https://alex-d.github.io/Trumbowyg/
if (typeof component_classes['system_default_rich_text_editor'] === "undefined") {
	class system_default_rich_text_editor extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
		}
		loadPrerequisites(success_callback,fail_callback) {
			dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/trumbowyg.js", function() {
				$.trumbowyg.svgPath = getRootPath()+"project/assets/packages/trumbowyg/ui/icons.svg";
				dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/base64/trumbowyg.base64.js", function() {
					dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/colors/trumbowyg.colors.js", function() {
						dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/prism.min.js", function() {
							dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/highlight/trumbowyg.highlight.js", function() {
								dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/jquery-resizable.min.js", function() {
									dxGetScript(getRootPath()+"project/assets/packages/trumbowyg/plugins/resizimg/trumbowyg.resizimg.js", function() {
										this.initEditor();
										success_callback();
									}.bind(this));
								}.bind(this));
							}.bind(this));
						}.bind(this));
					}.bind(this));
				}.bind(this));
			}.bind(this));
		}
		initEditor() {
			let trumbowyg_obj = getComponentElementById(this,"ComponentRichTextEditor").trumbowyg({
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
			let this_component = this;
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
		}
	}
	component_classes['system_default_rich_text_editor'] = system_default_rich_text_editor;
}