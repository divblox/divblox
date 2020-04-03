if (typeof component_classes['data_model_note_crud_update'] === "undefined") {
	class data_model_note_crud_update extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions = [];
            // Sub component config end
			this.included_attribute_array = ['NoteDescription',];
			this.included_relationship_array = [];
			this.constrain_by_array = ['Ticket',];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['NoteDescription',];
			this.initCrudVariables("Note");
		}
		reset(inputs,propagate) {
			if (typeof inputs !== "undefined") {
				this.setEntityId(inputs);
			}
			super.reset(inputs,propagate);
			getComponentElementById(this,"HPxt9_modal").modal("hide");
		}

	    initCustomFunctions() {
            
            // HPxt9_modal Related functionality
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            getComponentElementById(this,"HPxt9_btn-close").on("click", function() {
                // Your custom code here
            }.bind(this));
            
            // Modal functions
            // Show the modal using javascript
            //getComponentElementById(this,"HPxt9_modal").modal("show");
            // Hide the modal using javascript
            //getComponentElementById(this,"HPxt9_modal").modal("hide");
            // Toggle the modal using javascript
            //getComponentElementById(this,"HPxt9_modal").modal("toggle");
            
            // Modal events
            getComponentElementById(this,"HPxt9_modal").on("show.bs.modal", function(e) {
                // Your custom code here
				loadComponent("system/note_attachment_uploader",
					this.getUid(),
					"XLGKu",
					{"note_id": this.getEntityId()},
					true);
            }.bind(this));
            getComponentElementById(this,"HPxt9_modal").on("shown.bs.modal", function(e) {
                // Your custom code here
            }.bind(this));
            getComponentElementById(this,"HPxt9_modal").on("hide.bs.modal", function(e) {
               // Your custom code here
            }.bind(this));
            getComponentElementById(this,"HPxt9_modal").on("hidden.bs.modal", function(e) {
                // Your custom code here
            }.bind(this));
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
        }

		eventTriggered(event_name, parameters_obj) {
			switch (event_name) {
				case 'FileUploaded':
					this.reset();
					break;
				default:
					dxLog("Event triggered: " + event_name + ": " + JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.propagateEventTriggered(event_name, parameters_obj);
		}

		setValues() {
			super.setValues();
			dxLog(this.component_obj["FileDocument"]);
		}

		onAfterLoadEntity(data_obj) {
			// TODO: Override this as needed;
			getComponentElementById(this, "DownloadWrapper").html("");
			if (typeof data_obj.AttachmentPath !== "undefined") {
				if (data_obj.AttachmentPath.length > 0) {
					getComponentElementById(this, "DownloadWrapper").html('<a href="'+data_obj.AttachmentPath+'" target="_blank" class="btn btn-link fullwidth">Download Attachment</a>');
				}
			}
		}
   	}
	component_classes['data_model_note_crud_update'] = data_model_note_crud_update;
}