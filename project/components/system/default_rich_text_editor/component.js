// JGL: Trumbowyg documentation: https://alex-d.github.io/Trumbowyg/
if (typeof componentClasses['system_default_rich_text_editor'] === "undefined") {
    class DefaultRickTextEditor extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.prerequisites = [
                "project/assets/packages/trumbowyg/trumbowyg.js",
                "project/assets/packages/trumbowyg/plugins/base64/trumbowyg.base64.js",
                "project/assets/packages/trumbowyg/plugins/colors/trumbowyg.colors.js",
                "project/assets/packages/trumbowyg/prism.min.js",
                "project/assets/packages/trumbowyg/plugins/highlight/trumbowyg.highlight.js",
                "project/assets/packages/trumbowyg/jquery-resizable.min.js",
                "project/assets/packages/trumbowyg/plugins/resizimg/trumbowyg.resizimg.js"
            ];
            this.editor_obj = null;
        }

        reset(inputs, propagate) {
            this.initEditor();
            super.reset(inputs, propagate);
        }

        initEditor() {
            $.trumbowyg.svgPath = getRootPath() + "project/assets/packages/trumbowyg/ui/icons.svg";
            this.editor_obj = getComponentElementById(this, "ComponentRichTextEditor").trumbowyg({
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
                    resizimg: {
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
            this.editor_obj.on('tbwchange', function () {
                let current_data = this.editor_obj.html();
                setTimeout(function () {
                    if (current_data == this.editor_obj.html()) {
                        this.doAutoSave();
                    }
                }.bind(this), 1000);
            }.bind(this));
        }

        setData(html) {
            this.editor_obj.html(html);
        }

        getData() {
            return this.editor_obj.html();
        }

        doAutoSave() {
            dxLog("Auto save function for rich text editor not implemented");
        }
    }

    componentClasses['system_default_rich_text_editor'] = DefaultRickTextEditor;
}