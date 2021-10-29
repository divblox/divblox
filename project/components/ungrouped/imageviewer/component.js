if (typeof componentClasses['ungrouped_imageviewer'] === "undefined") {
    class ImageViewer extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
        }

        reset(inputs, propagate) {
            if (typeof (this.getImagePath()) !== "undefined") {
                $("#" + this.uid + "_image").attr("src", this.getImagePath());
            }
            super.reset(inputs, propagate);
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

        updateImage(image_path) {
            this.setImagePath(image_path);
            if (typeof (this.getImagePath()) !== "undefined") {
                $("#" + this.uid + "_image").attr("src", this.getImagePath());
            }
        }

        initCustomFunctions() {
            //Custom javascript here
        }
    }

    componentClasses['ungrouped_imageviewer'] = ImageViewer;
}