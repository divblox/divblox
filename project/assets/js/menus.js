/**
 * menus.js is responsible for loading the menu definitions and making them available to the rest of the
 * project
 */
let menu_manager = {
    menus: {},
    renderedMenus: [],
    loadMenuDefinitions(success_callback,failed_callback) {
        if (debug_mode) {
            no_cache_force_str = getRandomFilePostFix();
        }
        loadJsonFromFile(getRootPath()+'project/assets/configurations/menus/generated/menus.json'+no_cache_force_str,function(json) {
            this.menus = json;
            if (this.menus === {}) {
                failed_callback();
            } else {
                success_callback();
            }
        }.bind(this));
    },
    getMenu(menu_name) {
        if (typeof this.menus[menu_name] !== "undefined") {
            return this.menus[menu_name];
        }
        return [];
    },
    getMenuHtml(menu_name,item_html_template,sub_menu_wrapper_template) {
        let menu_html = '';
        let default_item_html_template =
            '<li class="nav-item {user-role-visibility}">\n' +
            '   <a class="nav-link navigation-activate-on-{item_active_class}' +
            ' navigation-item-trigger-{item_click_class}"' +
            ' href="#">{item_label}</a>\n' +
            '</li>\n';
        let default_sub_menu_wrapper_template =
            '<li class="nav-item dropdown {user-role-visibility}">\n' +
            '   <a class="nav-link navigation-activate-on-{item_active_class} dropdown-toggle"' +
            ' href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
            '{item_label}</a>\n' +
            '<div class="dropdown-menu">\n'+
            '<ul class="nav flex-column">\n'+
            '{sub_menu}'+
            '</ul>\n'+
            '</div>\n' +
            '</li>\n';
        if ((typeof item_html_template === "undefined") || item_html_template.indexOf('{item_label}') === -1) {
            // JGL: A proper template was not received
            item_html_template = default_item_html_template;
        }
        if ((typeof sub_menu_wrapper_template === "undefined") || sub_menu_wrapper_template.indexOf('{sub_menu}') === -1) {
            // JGL: A proper template was not received
            sub_menu_wrapper_template = default_sub_menu_wrapper_template;
        }
        let menu = this.getMenu(menu_name);
        if (menu.length < 1) {
            return menu_html;
        }
        menu.forEach(function(menu_obj) {
            let prepared_html_template = '';
            if (menu_obj["sub_menu"] !== null) {
                prepared_html_template = sub_menu_wrapper_template;
                prepared_html_template = prepared_html_template.replace('{item_label}',menu_obj["item_label"]);
                prepared_html_template = prepared_html_template.replace('{item_active_class}',menu_obj["item_active_class"]);
                if (menu_obj['allowed_user_roles'] !== null) {
                    let user_role_visibility_str = 'user-role-visible';
                    menu_obj['allowed_user_roles'].forEach(function(role) {
                        user_role_visibility_str += ' '+role.toLowerCase()+'-visible';
                    });
                    prepared_html_template = prepared_html_template.replace('{user-role-visibility}',user_role_visibility_str);
                } else {
                    prepared_html_template = prepared_html_template.replace(' {user-role-visibility}','');
                }
                let sub_menu_html = this.getMenuHtml(menu_obj["sub_menu"], default_item_html_template.replace('nav-link','dropdown-item'));
                prepared_html_template = prepared_html_template.replace('{sub_menu}',sub_menu_html);
            } else {
                prepared_html_template = item_html_template;
                prepared_html_template = prepared_html_template.replace('{item_label}',menu_obj["item_label"]);
                if (menu_obj["item_active_class"] !== null) {
                    prepared_html_template = prepared_html_template.replace('{item_active_class}',menu_obj["item_active_class"]);
                } else {
                    prepared_html_template = prepared_html_template.replace(' navigation-activate-on-{item_active_class}','');
                }
                if (menu_obj["item_click_class"] !== null) {
                    prepared_html_template = prepared_html_template.replace('{item_click_class}',menu_obj["item_click_class"]);
                } else {
                    prepared_html_template = prepared_html_template.replace(' menu-item-trigger-{item_click_class}','');
                }
                if (menu_obj["show_divider"]) {
                    prepared_html_template += '<div class="dropdown-divider"></div>\n';
                }
                if (menu_obj['allowed_user_roles'] !== null) {
                    let user_role_visibility_str = 'user-role-visible';
                    menu_obj['allowed_user_roles'].forEach(function(role) {
                        user_role_visibility_str += ' '+role.toLowerCase()+'-visible';
                    });
                    prepared_html_template = prepared_html_template.replace('{user-role-visibility}',user_role_visibility_str);
                } else {
                    prepared_html_template = prepared_html_template.replace(' {user-role-visibility}','');
                }
            }
            menu_html += prepared_html_template;
        }.bind(this));
        return menu_html;
    },
    initMenuActions(menu_name) {
        let menu = this.getMenu(menu_name);
        if (menu.length < 1) {return;}
        
        menu.forEach(function(menu_obj) {
            if ((typeof menu_obj["item_click_class"] !== "undefined") &&
                (menu_obj["item_click_class"] !== null)) {
                $('.navigation-item-trigger-'+menu_obj["item_click_class"]).on("click", function() {
                    let input_parameters_obj = {};
                    if ((typeof menu_obj["javascript_inputs"] !== "undefined") &&
                        (menu_obj["javascript_inputs"] !== null)) {
                        input_parameters_obj = menu_obj["javascript_inputs"];
                    }
                    if ((typeof menu_obj["page_to_load"] !== "undefined") &&
                        (menu_obj["page_to_load"] !== null)) {
                        loadPageComponent(menu_obj["page_to_load"],input_parameters_obj);
                    } else if ((typeof menu_obj["function_to_execute"] !== "undefined") &&
                                (menu_obj["function_to_execute"] !== null)) {
                        let functionToExecute = window[menu_obj["function_to_execute"]];
                        if (typeof functionToExecute === "function") functionToExecute(input_parameters_obj);
                    }
                });
            }
            if ((typeof menu_obj["sub_menu"] !== "undefined") &&
                (menu_obj["sub_menu"] !== null)) {
                this.initMenuActions(menu_obj["sub_menu"]);
            }
        }.bind(this));
    },
    renderMenu(placeholder_class,menu_name,item_html_template,sub_menu_wrapper_template) {
        if(this.renderedMenus.includes(menu_name)) {
            return;
        }
        if ($("."+placeholder_class)[0]){
            let menu_html = this.getMenuHtml(menu_name,item_html_template,sub_menu_wrapper_template);
            $('.'+placeholder_class).replaceWith(menu_html);
            this.initMenuActions(menu_name);
            this.renderedMenus.push(menu_name);
        }
    },
    getAvailableMenus() {
        return Object.keys(this.menus);
    },
    getMenuMaxIndex(menu_name) {
        let menu = this.getMenu(menu_name);
        if (menu.length < 1) {return 0;}
        let max_index = 0;
        menu.forEach(function(menu_obj) {
            if (menu_obj["index"] !== null) {
                if (parseInt(menu_obj["index"]) > max_index) {
                    max_index = parseInt(menu_obj["index"]);
                }
            }
        }.bind(this));
        return max_index;
    },
    getMenuMinIndex(menu_name) {
        let menu = this.getMenu(menu_name);
        if (menu.length < 1) {return 0;}
        let min_index = null;
        menu.forEach(function(menu_obj) {
            if (menu_obj["index"] !== null) {
                if (min_index === null) {
                    min_index = parseInt(menu_obj["index"]);
                }
                if (parseInt(menu_obj["index"]) < min_index) {
                    min_index = parseInt(menu_obj["index"]);
                }
            }
        }.bind(this));
        if (min_index === null) {
            min_index = -1;
        }
        return min_index;
    },
    getMenuNextIndex(menu_name) {
        return this.getMenuMaxIndex(menu_name) + 1;
    },
    renderAllMenus() {
        let menu_name_array = this.getAvailableMenus();
        menu_name_array.forEach(function(menu_name) {
            this.renderMenu('menu-'+menu_name+'',menu_name);
        }.bind(this));
    }
};
menu_manager.loadMenuDefinitions(
    function() {return;},
    function(){dxLog("Failed to load Menu definitions")});