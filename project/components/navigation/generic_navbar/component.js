if (typeof componentClasses['navigation_generic_navbar'] === "undefined") {
    class GenericNavbar extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
        }
    }

    componentClasses['navigation_generic_navbar'] = GenericNavbar;
}