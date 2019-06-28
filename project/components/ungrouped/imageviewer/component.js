if (typeof component_classes['ungrouped_imageviewer'] === "undefined") {
	class ungrouped_imageviewer extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
		}
		reset(inputs) {
			if (typeof(this.getImagePath()) !== "undefined") {
				$("#"+this.uid+"_image").attr("src",this.getImagePath());
			}
			super.reset(inputs);
		}
		getImagePath() {
			return this.arguments["image_path"];
		}
		setImagePath(image_path) {
			if (typeof image_path === "undefined") {
				this.arguments["image_path"] = 'divblox/assets/images/no_image.svg';
			} else {
				this.arguments["image_path"] = image_path;
			}
		}
		updateImage (image_path) {
			this.setImagePath(image_path);
			if (typeof(this.getImagePath()) !== "undefined") {
				$("#"+this.uid+"_image").attr("src",this.getImagePath());
			}
		}
	}
	component_classes['ungrouped_imageviewer'] = ungrouped_imageviewer;
}