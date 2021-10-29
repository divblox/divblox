if (typeof componentClasses['system_entity_select'] === "undefined") {
    class EntitySelect extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.entity_type = this.getLoadArgument('entity_type');
            this.selected_id = -1;
            this.entity_display_attr = this.getLoadArgument('display_attr');
            this.search_input = '';
            this.result_list = {};
            this.is_searching = false;
            this.requires_selection = false;
            if (typeof this.getLoadArgument('require_selection') != "undefined") {
                if (this.getLoadArgument('require_selection') != null) {
                    this.requires_selection = this.getLoadArgument('require_selection');
                }
            }
        }

        reset(inputs, propagate) {
            super.reset(inputs, propagate);
            getComponentElementById(this, 'EntitySelectLabel').text('Select ' + this.getEntityTypeDisplay() + ':');
            getComponentElementById(this, 'SelectList').hide();
        }

        registerDomEvents() {
            super.registerDomEvents();
            getComponentElementById(this, 'SelectInput').on("focusin", function () {
                this.updateDataList();
                getComponentElementById(this, 'SelectList').show();
                setTimeout(function () {
                    this.is_searching = false;
                }.bind(this), 100);
            }.bind(this));
            getComponentElementById(this, 'SelectInput').on("focusout", function () {
                setTimeout(function () {
                    if (this.is_searching) {
                        this.is_searching = false;
                    } else {
                        getComponentElementById(this, 'SelectList').hide();
                    }
                }.bind(this), 100);

            }.bind(this));
            getComponentElementById(this, 'SelectInput').on("keyup", function (event) {
                this.search_input = getComponentElementById(this, 'SelectInput').val();
                setTimeout(function () {
                    if (this.search_input == getComponentElementById(this, 'SelectInput').val()) {
                        this.updateDataList();
                        getComponentElementById(this, 'SelectList').show();
                    }
                }.bind(this), 500);
                if (getComponentElementById(this, 'SelectInput').val().length > 0) {
                    getComponentElementById(this, 'SelectInputActionButton').removeClass('dropdown-toggle').html('<span aria-hidden="true">&times;</span>');
                } else {
                    getComponentElementById(this, 'SelectInputActionButton').addClass('dropdown-toggle').html("");
                }

                if (event.which == 13) {
                    this.selected_id = -1;
                    if (Object.keys(this.result_list).length == 1) {
                        this.setSelectedId(Object.keys(this.result_list)[0]);
                        return;
                    }
                    Object.keys(this.result_list).forEach(function (id) {
                        if (getComponentElementById(this, 'SelectInput').val().toLowerCase() === this.result_list[id].toLowerCase()) {
                            this.setSelectedId(id);
                            return;
                        }
                    }.bind(this));
                    return;
                }

            }.bind(this));
            getComponentElementById(this, 'SelectInputActionButton').on("click", function () {
                this.is_searching = true;
                this.selected_id = -1;
                getComponentElementById(this, 'SelectInput').focus();
                getComponentElementById(this, 'SelectInput').val("");
                getComponentElementById(this, 'SelectInputActionButton').addClass('dropdown-toggle').html("");
                this.updateDataList();
            }.bind(this));
        }

        getEntityType() {
            if (this.entity_type == null) {
                return 'N/A';
            }
            return this.entity_type;
        }

        getEntityTypeDisplay() {
            return this.getEntityType().replace(/([a-z])([A-Z])/g, '$1 $2');
        }

        setSelectedId(id) {
            this.selected_id = id;
            getComponentElementById(this, 'SelectInput').val(this.result_list[id]);
            getComponentElementById(this, 'SelectInputActionButton').removeClass('dropdown-toggle').html('<span aria-hidden="true">&times;</span>');
            getComponentElementById(this, 'SelectList').hide();
            this.validateSelection();
        }

        getSelectedId() {
            return this.selected_id;
        }

        validateSelection() {
            if (this.requires_selection) {
                toggleValidationState(this, 'SelectInput', 'Please select an option', true, true);
                if (this.getSelectedId() < 1) {
                    toggleValidationState(this, 'SelectInput', 'Please select an option', false, false);
                }
                return this.getSelectedId() > 0;
            }
            return true;
        }

        updateDataList() {
            let input_wrapper_width = getComponentElementById(this, 'SelectInputWrapper').width();
            getComponentElementById(this, 'SelectListWrapper').css({'width': (input_wrapper_width + 'px')});
            dxRequestInternal(getComponentControllerPath(this),
                {
                    f: "updateDatalist",
                    entity: this.entity_type,
                    display_attr: this.entity_display_attr,
                    input: getComponentElementById(this, 'SelectInput').val()
                },
                function (data) {
                    this.result_list = data.ResultArray;
                    getComponentElementById(this, 'SelectList').html("");
                    Object.keys(data.ResultArray).forEach(function (id) {
                        getComponentElementById(this, 'SelectList').append('<button id="' + this.getUid() + '_result_' + id + '"' +
                            ' type="button"' +
                            ' class="list-group-item list-group-item-action">' + data.ResultArray[id] + '</button>');
                        getComponentElementById(this, 'result_' + id).on("click", function () {
                            let id_start = $(this).attr("id").indexOf("_result_");
                            let clicked_id = $(this).attr("id").substring(id_start + 8);
                            let uid = $(this).attr("id").substring(0, id_start);
                            let thisComponent = getRegisteredComponent(uid);
                            thisComponent.setSelectedId(clicked_id);
                            return false;
                        });
                    }.bind(this));
                    if (Object.keys(data.ResultArray).length == 0) {
                        getComponentElementById(this, 'SelectList').append('<option value="No Results Found">');
                    }
                }.bind(this),
                function (data) {
                    dxLog("Error loading data list for entity select: " + data.Message);
                }.bind(this), false, false);
        }
    }

    componentClasses['system_entity_select'] = EntitySelect;
}