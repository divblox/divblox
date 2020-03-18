if (typeof component_classes['pages_category_update'] === "undefined") {
    class pages_category_update extends DivbloxDomBaseComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = 
                [{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"data_model/category_crud_update","parent_element":"VrKSD","arguments":{"uid":"data_model_category_crud_update_1"}},
            {"component_load_path":"data_model/subcategory_list","parent_element":"qg1nR","arguments":{}}];
            // Sub component config end
        }

        reset(inputs) {
            setActivePage("page_component_name", "Page Title");
            super.reset(inputs);
            this.updateBreadCrumbs();
        }

        initCustomFunctions() {        //Custom javascript here
            super.initCustomFunctions();
            getComponentElementById(this, "AllCategories").on("click", function () {
                loadPageComponent("admin");
            });
            $(document).on("click", ".category-breadcrumb", function () {
            	let category_id = $(this).attr("id").replace("CategoryId_","");
            	setGlobalConstrainById("Category", category_id);
            	dxLog("Cat id: "+category_id);
				loadPageComponent("category_update");
			});
        }

        updateBreadCrumbs() {
            dxRequestInternal(getComponentControllerPath(this), {
                    f: "getBreadCrumbs",
                    category_id: getGlobalConstrainById("Category")
                },
                function (data_obj) {
                    console.log(data_obj.ReturnData);
                    let html = "";

                    dxLog("Returned: "+data_obj.ReturnData);
                    // dxLog("ReturnData array length:  " + data_obj.ReturnData.length);
                    let category_keys = Object.keys(data_obj.ReturnData);
                    let count = 1;
                    category_keys.forEach(function(key) {
                    	if (count === (category_keys.length)) {
							html = "<li class=\"breadcrumb-item active\">" + key + "</li>";
						} else {
							html = "<li class=\"breadcrumb-item\"><a id=\"CategoryId_" + data_obj.ReturnData[key] + "\" class=\"category-breadcrumb\" href=\"#\">" + key + "</a></li>";
						}

						dxLog("Key: "+key+"; Value: "+data_obj.ReturnData[key]);
						getComponentElementById(this, "CategoryBreadcrumbs").append(html);
						count++;

					}.bind(this));

                    /*console.log(result);
                    for (let i = 0; i < data_obj.ReturnData.length; i++) {
                        htmlContent = htmlContent + "<li class=\"breadcrumb-item\"><a id=\"CategoryId_" + result[i][0] + "\" href=\"#\">" + result[i][1] + "</a></li>";
                    }*/

                    // for (let i = (data_obj.ReturnData.length-1); i >= 0; i--) {
                    // 	htmlContent = htmlContent + "<li class=\"breadcrumb-item\"><a id=\"CategoryId_" + data_obj.ReturnData[i][1] + "\" href=\"#\">" + data_obj.ReturnData[i][0] + "</a></li>";
                    // }

                }.bind(this),
                function (data_obj) {
                    // Failure function
                });
        }
    }

    component_classes['pages_category_update'] = pages_category_update;
}