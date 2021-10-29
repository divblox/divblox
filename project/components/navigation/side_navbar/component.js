if (typeof componentClasses['navigation_side_navbar'] === "undefined") {
    class SideNavbar extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = {};
            // Sub component config end
            this.sidebar_width = 150;
            this.sidebar_hidden_margin = Math.round(this.sidebar_width / 2);
            this.sidebar_shown_margin = this.sidebar_hidden_margin + 25;
        }

        reset(inputs, propagate) {
            if (isMobile()) {
                $(".component-wrapper-compact-sidebar").removeClass("sidebar_compact_left_visible");
                $(".sidebar-left").css("margin-left", "-100px").addClass("slide-left");
            }
            super.reset(inputs, propagate);
            let default_sub_menu_wrapper_template =
                '<li class="nav-item dropright {user-role-visibility}">\n' +
                '   <a class="nav-link navigation-activate-on-{item_active_class} dropdown-toggle"' +
                ' href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                '{item_label}</a>\n' +
                '<div class="dropdown-menu">\n' +
                '<ul class="nav flex-column">\n' +
                '{sub_menu}' +
                '</ul>\n' +
                '</div>\n' +
                '</li>\n';
            menuManager.renderMenu('example-side-menu', "default_side_nav", undefined, default_sub_menu_wrapper_template);
        }

        registerDomEvents() {
            $(".sidebar_toggle_left").on("click", function () {
                let content_wrapper = $(".component-wrapper-compact-sidebar");
                if (content_wrapper.hasClass("sidebar_compact_left_visible")) {
                    $(".sidebar-left").removeClass("slide-left").removeClass("slide-right").addClass("slide-left").css("margin-left", "-" + this.sidebar_hidden_margin + "px");
                    $(".component-wrapper-compact-sidebar").removeClass("sidebar_compact_left_visible");
                } else {
                    $(".sidebar-left").removeClass("slide-left").removeClass("slide-right").addClass("slide-right").css("margin-left", "-" + this.sidebar_shown_margin + "px").addClass("slide-right");
                    $(".component-wrapper-compact-sidebar").addClass("sidebar_compact_left_visible");
                }
            }.bind(this));
            getComponentElementById(this, 'navigation_item_my_profile').on("click", function () {
                loadPageComponent("my_profile");
                return false;
            });
            registerEventHandler('.sidebar_toggle_left', "click");
        }
    }

    componentClasses['navigation_side_navbar'] = SideNavbar;
}