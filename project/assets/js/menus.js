/**
 * menus.js is responsible for loading the menu definitions and making them available to the rest of the
 * project
 */
let menuManager = {
    menus: {},
    renderedMenus: [],
    loadMenuDefinitions(successCallback, failCallback) {
        if (isDebugMode) {
            noCacheForceText = getRandomFilePostFix();
        }
        loadJsonFromFile(getRootPath() + 'project/assets/configurations/menus/generated/menus.json' + noCacheForceText, function (json) {
            this.menus = json;
            if (this.menus === {}) {
                failCallback();
            } else {
                successCallback();
            }
        }.bind(this));
    },
    getMenu(menuName) {
        if (typeof this.menus[menuName] !== "undefined") {
            return this.menus[menuName];
        }
        return [];
    },
    getMenuHtml(menuName, itemHtmlTemplate, subMenuWrapperTemplate) {
        let menuHtml = '';
        let defaultItemHtmlTemplate =
            '<li class="nav-item {user-role-visibility}">\n' +
            '   <a class="nav-link navigation-activate-on-{item_active_class}' +
            ' navigation-item-trigger-{item_click_class}"' +
            ' href="#">{item_label}</a>\n' +
            '</li>\n';
        let defaultSubMenuWrapperTemplate =
            '<li class="nav-item dropdown {user-role-visibility}">\n' +
            '   <a class="nav-link navigation-activate-on-{item_active_class} dropdown-toggle"' +
            ' href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
            '{item_label}</a>\n' +
            '<div class="dropdown-menu">\n' +
            '<ul class="nav flex-column">\n' +
            '{sub_menu}' +
            '</ul>\n' +
            '</div>\n' +
            '</li>\n';
        if ((typeof itemHtmlTemplate === "undefined") || itemHtmlTemplate.indexOf('{item_label}') === -1) {
            // JGL: A proper template was not received
            itemHtmlTemplate = defaultItemHtmlTemplate;
        }
        if ((typeof subMenuWrapperTemplate === "undefined") || subMenuWrapperTemplate.indexOf('{sub_menu}') === -1) {
            // JGL: A proper template was not received
            subMenuWrapperTemplate = defaultSubMenuWrapperTemplate;
        }
        let menu = this.getMenu(menuName);
        if (menu.length < 1) {
            return menuHtml;
        }
        menu.forEach(function (menuItem) {
            let preparedHtmlTemplate = '';
            if (menuItem["sub_menu"] !== null) {
                preparedHtmlTemplate = subMenuWrapperTemplate;
                preparedHtmlTemplate = preparedHtmlTemplate.replace('{item_label}', menuItem["item_label"]);
                preparedHtmlTemplate = preparedHtmlTemplate.replace('{item_active_class}', menuItem["item_active_class"]);
                if (menuItem['allowed_user_roles'] !== null) {
                    let userRoleVisibilityStr = 'user-role-visible';
                    menuItem['allowed_user_roles'].forEach(function (role) {
                        userRoleVisibilityStr += ' ' + role.toLowerCase() + '-visible';
                    });
                    preparedHtmlTemplate = preparedHtmlTemplate.replace('{user-role-visibility}', userRoleVisibilityStr);
                } else {
                    preparedHtmlTemplate = preparedHtmlTemplate.replace(' {user-role-visibility}', '');
                }
                let subMenuHtml = this.getMenuHtml(menuItem["sub_menu"], defaultItemHtmlTemplate.replace('nav-link', 'dropdown-item'));
                preparedHtmlTemplate = preparedHtmlTemplate.replace('{sub_menu}', subMenuHtml);
            } else {
                preparedHtmlTemplate = itemHtmlTemplate;
                preparedHtmlTemplate = preparedHtmlTemplate.replace('{item_label}', menuItem["item_label"]);
                if (menuItem["item_active_class"] !== null) {
                    preparedHtmlTemplate = preparedHtmlTemplate.replace('{item_active_class}', menuItem["item_active_class"]);
                } else {
                    preparedHtmlTemplate = preparedHtmlTemplate.replace(' navigation-activate-on-{item_active_class}', '');
                }
                if (menuItem["item_click_class"] !== null) {
                    preparedHtmlTemplate = preparedHtmlTemplate.replace('{item_click_class}', menuItem["item_click_class"]);
                } else {
                    preparedHtmlTemplate = preparedHtmlTemplate.replace(' menu-item-trigger-{item_click_class}', '');
                }
                if (menuItem["show_divider"]) {
                    preparedHtmlTemplate += '<div class="dropdown-divider"></div>\n';
                }
                if (menuItem['allowed_user_roles'] !== null) {
                    let userRoleVisibilityStr = 'user-role-visible';
                    menuItem['allowed_user_roles'].forEach(function (role) {
                        userRoleVisibilityStr += ' ' + role.toLowerCase() + '-visible';
                    });
                    preparedHtmlTemplate = preparedHtmlTemplate.replace('{user-role-visibility}', userRoleVisibilityStr);
                } else {
                    preparedHtmlTemplate = preparedHtmlTemplate.replace(' {user-role-visibility}', '');
                }
            }
            menuHtml += preparedHtmlTemplate;
        }.bind(this));
        return menuHtml;
    },
    initMenuActions(menuName) {
        let menu = this.getMenu(menuName);
        if (menu.length < 1) {
            return;
        }

        menu.forEach(function (menuItem) {
            if ((typeof menuItem["item_click_class"] !== "undefined") &&
                (menuItem["item_click_class"] !== null)) {
                $('.navigation-item-trigger-' + menuItem["item_click_class"]).on("click", function () {
                    let inputParameters = {};
                    if ((typeof menuItem["javascript_inputs"] !== "undefined") &&
                        (menuItem["javascript_inputs"] !== null)) {
                        inputParameters = menuItem["javascript_inputs"];
                    }
                    if ((typeof menuItem["page_to_load"] !== "undefined") &&
                        (menuItem["page_to_load"] !== null)) {
                        loadPageComponent(menuItem["page_to_load"], inputParameters);
                    } else if ((typeof menuItem["function_to_execute"] !== "undefined") &&
                        (menuItem["function_to_execute"] !== null)) {
                        let functionToExecute = window[menuItem["function_to_execute"]];
                        if (typeof functionToExecute === "function") functionToExecute(inputParameters);
                    }
                });
            }
            if ((typeof menuItem["sub_menu"] !== "undefined") &&
                (menuItem["sub_menu"] !== null)) {
                this.initMenuActions(menuItem["sub_menu"]);
            }
        }.bind(this));
    },
    renderMenu(placeholderClass, menuName, itemHtmlTemplate, subMenuWrapperTemplate) {
        if (this.renderedMenus.includes(menuName)) {
            return;
        }
        if ($("." + placeholderClass)[0]) {
            let menuHtml = this.getMenuHtml(menuName, itemHtmlTemplate, subMenuWrapperTemplate);
            $('.' + placeholderClass).replaceWith(menuHtml);
            this.initMenuActions(menuName);
            this.renderedMenus.push(menuName);
        }
    },
    getAvailableMenus() {
        return Object.keys(this.menus);
    },
    getMenuMaxIndex(menuName) {
        let menu = this.getMenu(menuName);
        if (menu.length < 1) {
            return 0;
        }
        let maxIndex = 0;
        menu.forEach(function (menuItem) {
            if (menuItem["index"] !== null) {
                if (parseInt(menuItem["index"]) > maxIndex) {
                    maxIndex = parseInt(menuItem["index"]);
                }
            }
        }.bind(this));
        return maxIndex;
    },
    getMenuMinIndex(menuName) {
        let menu = this.getMenu(menuName);
        if (menu.length < 1) {
            return 0;
        }
        let minIndex = null;
        menu.forEach(function (menuItem) {
            if (menuItem["index"] !== null) {
                if (minIndex === null) {
                    minIndex = parseInt(menuItem["index"]);
                }
                if (parseInt(menuItem["index"]) < minIndex) {
                    minIndex = parseInt(menuItem["index"]);
                }
            }
        }.bind(this));
        if (minIndex === null) {
            minIndex = -1;
        }
        return minIndex;
    },
    getMenuNextIndex(menuName) {
        return this.getMenuMaxIndex(menuName) + 1;
    },
    renderAllMenus() {
        let menuNames = this.getAvailableMenus();
        menuNames.forEach(function (menuName) {
            this.renderMenu('menu-' + menuName + '', menuName);
        }.bind(this));
    }
};
menuManager.loadMenuDefinitions(
    function () {
        return;
    },
    function () {
        dxLog("Failed to load Menu definitions");
    });