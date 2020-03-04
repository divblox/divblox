let graph = new joint.dia.Graph();
let gridsize = 1;
let currentScale = 1;
//Get the div that will hold the graph
let targetElement = $('#paper')[0];
let dragStartPosition = {x:0,y:0};
delete dragStartPosition.x;
delete dragStartPosition.y;
let MaxInitX = 1;
let MaxInitY = 1;
let ScaleIsDefault = false;
let uml = joint.shapes.uml;
let classes = {};
let relations = [];
let ConfirmFunction = "RESET";
let CurrentDataObj = {"Attributes":[],"AttributeTypes":[],"Relationships":[],"Module":null};
let CurrentEntityName = null;
let CurrentEntityCoords = {x:0,y:0};
let AllObjectArray = [];
let UserRoleArray = [];
let ModuleArray = [];
let ModuleColorsObject = {};
let EntityModulesObject = {};
let isEditing = false;
let paper = new joint.dia.Paper({
    el: $('#paper'),
    width: window.innerWidth,
    height: window.innerHeight - 100,
    gridSize: gridsize,
    model: graph,
	snapLinks: true,
	linkPinning: false,
	embeddingMode: true,
	fontSize: 24
});
setGrid(paper, gridsize*15, '#808080');
paper.on('blank:pointerdown', function(event, x, y) {
	let scale = V(paper.viewport).scale();
	dragStartPosition = { x: x * scale.sx, y: y * scale.sy};
});
paper.on('cell:pointerup blank:pointerup', function(cellView, x, y) {
	delete dragStartPosition.x;
	delete dragStartPosition.y;
	saveCoordinates(classes);
});
paper.on('blank:pointerdblclick', function(event, x, y) {
	CurrentEntityCoords = {x:x,y:y};
	isEditing = false;
	openEntityModal('');
});
$("#paper").mousemove(function(event) {
	if (typeof dragStartPosition.x !== 'undefined' && typeof dragStartPosition.y !== 'undefined') {
		paper.translate(
			event.offsetX - dragStartPosition.x,
			event.offsetY - dragStartPosition.y);
	}
});
function setGrid(paper, size, color, offset) {
	// Set grid size on the JointJS paper object (joint.dia.Paper instance)
	paper.options.gridsize = gridsize;
	// Draw a grid into the HTML 5 canvas and convert it to a data URI image
	let canvas = $('<canvas/>', { width: size, height: size });
	canvas[0].width = size;
	canvas[0].height = size;
	let context = canvas[0].getContext('2d');
	context.beginPath();
	context.rect(1, 1, 1, 1);
	context.fillStyle = color || '#AAAAAA';
	context.fill();
	// Finally, set the grid background image of the paper container element.
	let gridBackgroundImage = canvas[0].toDataURL('image/png');
	$(paper.el.childNodes[0]).css('background-image', 'url("' + gridBackgroundImage + '")');
	if(typeof(offset) != 'undefined'){
		$(paper.el.childNodes[0]).css('background-position', offset.x + 'px ' + offset.y + 'px');
	}
}
function increaseScale(increment) {
	increment = increment || 0.1;
	currentScale += increment;
	gridsize += increment;
	setGrid(paper, gridsize*15, '#808080');
	paper.scale(currentScale);
	ScaleIsDefault = false;
}
function decreaseScale(increment) {
	increment = increment || 0.1;
	currentScale -= increment;
	gridsize -= increment;
	setGrid(paper, gridsize*15, '#808080');
	if (currentScale < 0.05)
		currentScale = 0.05;
	paper.scale(currentScale);
	ScaleIsDefault = false;
}
function resetScale() {
	if (ScaleIsDefault)
		return;
	let ScaleWidth = 0.6*window.innerWidth;
	let ScaleHeight = 0.6*(window.innerHeight-100);
	let ScaleFactor = ScaleWidth / MaxInitX;
	if ((ScaleHeight / MaxInitY) < ScaleFactor)
		ScaleFactor = ScaleHeight / MaxInitY;
	if (ScaleFactor > 5)
		ScaleFactor = 5;
	if (ScaleFactor < 0.01)
		ScaleFactor = 0.01;
	if (currentScale > ScaleFactor) {
		while (currentScale > ScaleFactor) {
			decreaseScale(0.1);
		}
	} else {
		while (currentScale < ScaleFactor) {
			increaseScale(0.1);
		}
	}
	ScaleIsDefault = true;
}
$('#paper').bind('mousewheel', function(e){
	if(e.originalEvent.wheelDelta /120 > 0) {
		increaseScale(0.03);
	} else {
		decreaseScale(0.03)
	}
});
function downloadDataModel() {
	showAlert("Coming soon","info",[]);
	return;
	
	let encodedData;
	let s = new XMLSerializer().serializeToString(document.getElementById("paper"));
	encodedData = window.btoa(s);
}
function initEntities() {
	classes = {};
	relations = [];
	_.each(graph.getCells(), function(Cell) {Cell.remove();});
	getProposedDataModel(classes,relations,redrawModel);
}
function redrawModel() {
	_.each(classes, function(c) { graph.addCell(c); });
	_.each(relations, function(r) { graph.addCell(r); });
	resetScale();
	restoreClickHandlers();
}
function refreshDataModel() {
	_.each(graph.getCells(), function(Cell) {
		Cell.remove();
	});
	_.each(classes, function(c) { graph.addCell(c); });
	_.each(relations, function(r) { graph.addCell(r); });
	restoreClickHandlers();
}
function restoreClickHandlers() {
	$('.joint-cell').dblclick(function() {
		isEditing = true;
		openEntityModal($(this).attr("model-id"));
		$('#RemoveEntityBtn').show();
	});
	$('.joint-cell').contextmenu(function() {
		//TODO: We can do something with a right click here...
	});
}
function getHexColorLightness(HexValue) {
	if (HexValue.indexOf("rgba") > -1) {
		// This is not a HEX value, let's first convert it
		//yourNumber = parseInt(hexString, 16);
		HexValue = rgbaToHex(HexValue).replace("#","");
		if (HexValue.length > 6) {
			let AlphaValue = 1 - (parseInt(HexValue.substring(6,8),16)/255);
			return AlphaValue * 100;
		}
	}
	let Result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(HexValue);
	
	let r = parseInt(Result[1], 16);
	let g = parseInt(Result[2], 16);
	let b = parseInt(Result[3], 16);
	
	r /= 255, g /= 255, b /= 255;
	var max = Math.max(r, g, b), min = Math.min(r, g, b);
	var h, s, l = (max + min) / 2;
	
	if(max == min){
		h = s = 0; // achromatic
	} else {
		var d = max - min;
		s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
		switch(max) {
			case r: h = (g - b) / d + (g < b ? 6 : 0); break;
			case g: h = (b - r) / d + 2; break;
			case b: h = (r - g) / d + 4; break;
		}
		h /= 6;
	}
	
	s = s*100;
	s = Math.round(s);
	l = l*100;
	l = Math.round(l);
	return l;
}
function trim (str) {
	return str.replace(/^\s+|\s+$/gm,'');
}
function rgbaToHex (rgba) {
	var parts = rgba.substring(rgba.indexOf("(")).split(","),
		r = parseInt(trim(parts[0].substring(1)), 10),
		g = parseInt(trim(parts[1]), 10),
		b = parseInt(trim(parts[2]), 10),
		a = parseFloat(trim(parts[3].substring(0, parts[3].length - 1))).toFixed(2);
	
	return ('#' + r.toString(16) + g.toString(16) + b.toString(16) + (a * 255).toString(16).substring(0,2));
}
function getDataModelEntity(EntityName,isSystemEntity,Attributes,AttributeTypes,x,y) {
	if (typeof EntityName === 'undefined') {
		return;
	}
	x = x || 200+((window.innerWidth-400) * Math.random());
	y = y || 200+((window.innerHeight-400) * Math.random());
	let FormattedAttributes = [];
	let MaxLength = EntityName.length;
	let MaxHeight = 20;
	for (i=0;i<Attributes.length;i++) {
		let StringToAdd = Attributes[i]+': ['+AttributeTypes[i]+']';
		FormattedAttributes.push(StringToAdd);
		if (StringToAdd.length > MaxLength)
			MaxLength = StringToAdd.length;
		MaxHeight += 22;
		MaxHeight -= 13.5*(i/Attributes.length);
	}
	let EntityColor = 'rgba(165,18,18,0.24)'; // By default this color indicates an entity isn't linked to a module
	let EntityTextColor = "#fff";
	let EntityStrokeColor = "#fff";
	if ((typeof EntityModulesObject[EntityName] !== "undefined") &&
		(typeof ModuleColorsObject[EntityModulesObject[EntityName]] !== "undefined")) {
		EntityColor = ModuleColorsObject[EntityModulesObject[EntityName]];
	}
	EntityStrokeColor = EntityColor;
	if (isSystemEntity) {
		EntityColor = 'rgba(65, 119, 142,0.14)';
		EntityStrokeColor = 'rgba(0, 0, 0,0)';
	}
	if (getHexColorLightness(EntityColor.replace("#","")) > 75) {
		EntityTextColor = "#5f5f5f";
	}
	
	return new uml.Class({
		position: { x:x,y:y },
		size: { width: (8.2*MaxLength) /*Attribute with most characters [Character count * 8]*/, height: MaxHeight },
		name: EntityName,
		attributes: FormattedAttributes,
		//methods: ["Special Renders","test1","test2","test3"], // This might be used to show special renders
		attrs: {
			'.uml-class-name-rect': {
				fill: EntityColor,
				stroke: EntityStrokeColor,
				'stroke-width': 0.5
			},
			'.uml-class-attrs-rect': {
				fill: EntityColor,
				stroke: EntityStrokeColor,
				'stroke-width': 0.5
			},
			'.uml-class-methods-rect': {
				//display: 'none' // Disabled for now. Not using special renders
			},
			'.uml-class-attrs-text': {
				fill: EntityTextColor,
				'ref-y': 0.5,
				'y-alignment': 'middle'
			},
			'.uml-class-name-text': {
				fill: EntityTextColor,
				'ref-y': 0.5,
				'y-alignment': 'middle'
			}
		},
		id: EntityName
	});
}
function addDataModelRelationships(EntityName,SingleRelationships) {
	if (typeof EntityName === 'undefined') {
		return;
	}
	if (typeof SingleRelationships === 'undefined') {
		return;
	}
	if (SingleRelationships) {
		SingleRelationships.forEach(function (Relationship) {
			addRelationship(EntityName,Relationship);
		});
	}
}
function addRelationship(EntityName,Relationship) {
	if (typeof EntityName === 'undefined') {
		return;
	}
	if (typeof Relationship === 'undefined') {
		return;
	}
	relations.push(new uml.Association({ source: { id: EntityName }, target: { id: Relationship }}));
}
function disableButtons(TriggeredButton) {
	$("#DropDownButtonGroup").prop("disabled", true);
	$("#SyncDb").prop("disabled", true);
	$("#ExportDb").prop("disabled", true);
	$("#CleanAndResetDb").prop("disabled", true);
	$("#RestoreDb").prop("disabled", true);
	$("#SaveEntityBtn").prop("disabled", true);
	$("#RemoveEntityBtn").prop("disabled", true);
	$("#ExitModeller").prop("disabled", true);
	$("#"+TriggeredButton).text("Working...");
	$("#DropDownButtonGroup").text("Working...");
}
function enableButtons(TriggeredButton,ButtonRestoreText) {
	$("#DropDownButtonGroup").prop("disabled", false);
	$("#SyncDb").prop("disabled", false);
	$("#ExportDb").prop("disabled", false);
	$("#CleanAndResetDb").prop("disabled", false);
	$("#RestoreDb").prop("disabled", false);
	$("#SaveEntityBtn").prop("disabled", false);
	$("#RemoveEntityBtn").prop("disabled", false);
	$("#ExitModeller").prop("disabled", false);
	$("#"+TriggeredButton).text(ButtonRestoreText);
	$("#DropDownButtonGroup").html("Actions <span class=\"caret\"></span>");
	getDataModelStatus();
}
function openEntityModal(EntityName) {
	if (EntityName != '') {
		CurrentEntityName = EntityName;
		getCurrentDataObject(afterOpenEntityModal);
	} else {
		CurrentEntityName = null;
		$("#NewEntityName").val("");
		$('#NewEntity').modal('show');
	}
}
function afterOpenEntityModal() {
	$('#EntityDetail').modal('show');
	updateEntityModal();
}
function addAttributeToCurrentDataObject(AttributeName,AttributeType) {
	if (!checkReservedWord(AttributeName))
		return;
	let AttributeArray = AttributeName.split(" ");
	let FinalAttributeName = '';
	AttributeArray.forEach(function(Attr) { FinalAttributeName += jsUcfirst(Attr)});
	if (CurrentDataObj.Attributes.indexOf(FinalAttributeName) != -1) {
		showAlert("Attribute "+FinalAttributeName+" already exists. Cannot duplicate!","error",[],true,3000);
		return;
	}
	CurrentDataObj.Attributes.push(FinalAttributeName);//AttributeName.replace(/\s/g, "")
	CurrentDataObj.AttributeTypes.push(AttributeType);
}
function removeAttributeFromCurrentDataObject(AttributeName) {
	let AttrIndex = 0;
	CurrentDataObj.Attributes.forEach(function(Attr) {
		if (Attr == AttributeName) {
			delete CurrentDataObj.Attributes[AttrIndex];
			delete CurrentDataObj.AttributeTypes[AttrIndex];
		}
		AttrIndex++;
	});
	cleanUpCurrentDataObject();
}
function addRelationshipToCurrentDataObject(Relationship) {
	if (Relationship == CurrentEntityName)
		return;
	if (CurrentDataObj.Relationships) {
		CurrentDataObj.Relationships.push(Relationship);
	} else {
		CurrentDataObj.Relationships = [Relationship];
	}
}
function removeRelationshipFromCurrentDataObject(Relationship) {
	let RelIndex = 0;
	CurrentDataObj.Relationships.forEach(function(Rel) {
		if (Rel == Relationship) {
			delete CurrentDataObj.Relationships[RelIndex];
		}
		RelIndex++;
	});
	RelIndex = 0;
	_.each(relations, function(r) {
		if (r.attributes.source.id == CurrentEntityName) {
			if (r.attributes.target.id == Relationship) {
				delete relations[RelIndex];
			}
		}
		RelIndex++;
	});
	relations = relations.filter(function(n){ return n != undefined });
	cleanUpCurrentDataObject();
}
function cleanUpCurrentDataObject() {
	if (!CurrentDataObj)
		return;
	if (CurrentDataObj.Attributes)
		CurrentDataObj.Attributes = CurrentDataObj.Attributes.filter(function(n){ return n != undefined });
	if (CurrentDataObj.AttributeTypes)
		CurrentDataObj.AttributeTypes = CurrentDataObj.AttributeTypes.filter(function(n){ return n != undefined });
	if (CurrentDataObj.Relationships)
		CurrentDataObj.Relationships = CurrentDataObj.Relationships.filter(function(n){ return n != undefined });
}
function updateEntityModal() {
	if (CurrentDataObj && CurrentEntityName) {
		$('#EntityComponentsWrapper').show();
		$('#EntityDetail').find('.modal-title').text(CurrentEntityName);
		$('#EntityAttributesWrapper').html(getEntityAttributeHeadingHtml(CurrentEntityName));
		$('#EntityRelationshipsWrapper').html("");
		let IterationCount = 0;
		CurrentDataObj.Attributes.forEach(function(Attribute) {
			$('#EntityAttributesWrapper').append(getAttributeInputHtml(CurrentEntityName,Attribute,CurrentDataObj.AttributeTypes[IterationCount]));
			IterationCount++;
		});
		$('#EntityAttributesWrapper').append(getNewAttributeInputHtml());
		if (CurrentDataObj.Relationships) {
			CurrentDataObj.Relationships.forEach(function(Relationship) {
				$('#EntityRelationshipsWrapper').append(getRelationshipHtml(Relationship));
			});
		}
		$('#EntityRelationshipsWrapper').append(getRelationshipsSelectHtml());
		if (CurrentDataObj.Module === null) {
			CurrentDataObj.Module = -1;
		}
		$('.EntityModuleName_Select').val(CurrentDataObj.Module);
		if (CurrentDataObj.Module == -1) {
			// Hide the other elements until a module is selected
			$('#EntityComponentsWrapper').hide();
		}
	} else {
		$('#EntityAttributesWrapper').html("");
		$('#EntityRelationshipsWrapper').html("");
	}
	/*dxLog("Current Entity: "+CurrentEntityName);
	dxLog("Current Coords: "+JSON.stringify(CurrentEntityCoords));
	dxLog("Current DO: "+JSON.stringify(CurrentDataObj));*/
}
function getAttributeInputHtml(EntityName,Attribute,AttributeType) {
	return '<div class="row AttributeRow">' +
		'<div class="col-sm-6">' +
		'<input id="'+EntityName+'_Attribute_'+Attribute+'" class="form-control" type="text" value="'+Attribute+'"/>' +
		'</div>' +
		'<div class="col-sm-4">' +
		getDataTypesSelectHtml(AttributeType,Attribute)+
		'</div>' +
		'<div class="col-sm-2 UniqueCheckbox">' +
		getUniqueCheckBoxHtml(EntityName,Attribute,AttributeType)+
		'<button id="Remove_'+EntityName+'_'+Attribute+'" type="button" class="close RemoveAttributeBtn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		'</div></div>';
}
function getNewAttributeInputHtml() {
	return '<hr><div class="row AttributeRow">' +
		'<div class="col-sm-6">' +
		'<input id="NewAttributeInput" class="form-control" type="text" placeholder="Add Attribute..."/>' +
		'</div>' +
		'<div class="col-sm-4">' +
		getDataTypesSelectHtml()+
		'</div>' +
		'<div class="col-sm-2 UniqueCheckbox">' +
		'<input id="NewAttributeUnique"type="checkbox"/>' +
		'<button id="AddAttributeBtn" type="button" class="close AddAttributeBtn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-plus" aria-hidden="true"></i></span></button>' +
		'</div></div>';
}
function getEntityAttributeHeadingHtml(EntityName) {
	return '<div class="row"><div class="col-sm-6">' +
		'Attribute' +
		'</div>' +
		'<div class="col-sm-4">' +
		'Data Type' +
		'</div>' +
		'<div class="col-sm-2">' +
		'Unique' +
		'</div></div>';
}
function getUniqueCheckBoxHtml(EntityName,Attribute,AttributeType) {
	if (checkAttributeUniqueness(AttributeType))
		return '<input id="Unique_'+EntityName+'_'+Attribute+'" type="checkbox" checked disabled="true"/>';
	return '<input id="Unique_'+EntityName+'_'+Attribute+'" type="checkbox" disabled="true"/>';
}
function checkAttributeUniqueness(AttributeType) {
	let AttributeTypeArray = [''];
	if (AttributeType)
		AttributeTypeArray = AttributeType.split(" ");
	if (AttributeTypeArray.length > 1) {
		return AttributeTypeArray[1] == "UNIQUE";
	}
	return false;
}
function getDataTypesSelectHtml(SelectedValue,Attribute) {
	let ElementId = "NewAttributeTypeSelect";
	let SelectedValueArray = [''];
	if (SelectedValue)
		SelectedValueArray = SelectedValue.split(" ");
	if (CurrentEntityName) {
		if (Attribute)
			ElementId = CurrentEntityName+'_'+Attribute+'_Select';
	}
	let SelectableDataTypes = ["VARCHAR(10)","VARCHAR(25)","VARCHAR(50)","VARCHAR(150)","VARCHAR(250)",
		"TEXT","MEDIUMTEXT","LONGTEXT","INT","BIGINT","FLOAT","DOUBLE","BOOLEAN","DATE","DATETIME"];
	let HtmlToReturn = '<select id="'+ElementId+'">';
	let isSelectedValueSet = false;
	SelectableDataTypes.forEach(function(DataType) {
		if (DataType == SelectedValueArray[0]) {
			HtmlToReturn = HtmlToReturn+'<option value="'+DataType+'" selected>'+DataType+'</option>';
			isSelectedValueSet = true;
		} else {
			HtmlToReturn = HtmlToReturn+'<option value="'+DataType+'">'+DataType+'</option>';
		}
	});
	if (!isSelectedValueSet && (SelectedValueArray[0])) {
		HtmlToReturn = HtmlToReturn+'<option value="'+SelectedValue+'" selected>'+SelectedValue+'</option>';
	}
	HtmlToReturn = HtmlToReturn+'</select>';
	return HtmlToReturn;
}
function getRelationshipHtml(Relationship) {
	if (!CurrentEntityName)
		return '';
	return '<div class="alert alert-info alert-dismissible" role="alert">\n' +
		'  <button id="Remove_'+CurrentEntityName+'_'+Relationship+'" type="button" class="close RemoveRelationshipBtn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' +
		'  ' +Relationship+
		'</div>'
}
function getRelationshipsSelectHtml() {
	let ElementId = "NewRelationshipSelect";
	let SelectableDataRelationships = [];
	AllObjectArray.forEach(function (Entity) {
		if (EntityModulesObject[Entity] == CurrentDataObj.Module) {
			if (Entity != CurrentEntityName) {
				if (CurrentDataObj.Relationships) {
					if (CurrentDataObj.Relationships.indexOf(Entity) == -1) {
						SelectableDataRelationships.push(Entity);
					}
				} else {
					SelectableDataRelationships.push(Entity);
				}
			}
		}
	});
	SelectableDataRelationships.sort();
	if (SelectableDataRelationships.length == 0)
		return '';
	let HtmlToReturn = '<select id="'+ElementId+'" style="width:80%;">';
	SelectableDataRelationships.forEach(function(Relationship) {
		HtmlToReturn = HtmlToReturn+'<option value="'+Relationship+'">'+Relationship+'</option>';
	});
	HtmlToReturn = HtmlToReturn+'</select>';
	return '<hr><div class="row AttributeRow">' +
		'<div class="col-sm-12">' + HtmlToReturn+
		'<button id="AddRelationshipBtn" type="button" class="close AddRelationshipBtn" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-plus" aria-hidden="true"></i></span></button>' +
		'</div></div>';
}
function jsUcfirst(String) {
	let FirstChar = String.charAt(0).toUpperCase();
	let Rest = forceNoAllCaps(String.slice(1));
	return FirstChar + Rest;
}
function forceNoAllCaps(String) {
	let FinalString = '';
	let LastWasUpperCase = true;
	for(i=0;i<String.length;i++) {
		if (!LastWasUpperCase)
			FinalString += String.charAt(i);
		else
			FinalString += String.charAt(i).toLowerCase();
		if (String.charAt(i) == String.charAt(i).toUpperCase()) {
			LastWasUpperCase = true;
		} else {
			LastWasUpperCase = false;
		}
	}
	return FinalString;
}
function checkReservedWord(StringToCheck) {
	let ReservedWords_php = ["__HALT_COMPILER", "ABSTRACT", "AND", "ARRAY", "AS", "BREAK", "CALLABLE", "CASE", "CATCH", "CLASS", "CLONE", "CONST", "CONTINUE", "DECLARE", "DEFAULT", "DIE", "DO", "ECHO", "ELSE", "ELSEIF", "EMPTY", "ENDDECLARE", "ENDFOR", "ENDFOREACH", "ENDIF", "ENDSWITCH", "ENDWHILE", "EVAL", "EXIT", "EXTENDS", "FINAL", "FOR", "FOREACH", "FUNCTION", "GLOBAL", "GOTO", "IF", "IMPLEMENTS", "INCLUDE", "INCLUDE_ONCE", "INSTANCEOF", "INSTEADOF", "INTERFACE", "ISSET", "LIST", "NAMESPACE", "NEW", "OR", "PRINT", "PRIVATE", "PROTECTED", "PUBLIC", "REQUIRE", "REQUIRE_ONCE", "RETURN", "STATIC", "SWITCH", "THROW", "TRAIT", "TRY", "UNSET", "USE", "VAR", "WHILE", "XOR"];
	let ReservedWords_mysql = ["A","ABORT","ABS","ABSOLUTE","ACCESS","ACTION","ADA","ADD","ADMIN","AFTER","AGGREGATE","ALIAS","ALL","ALLOCATE","ALSO","ALTER","ALWAYS","ANALYSE","ANALYZE","AND","ANY","ARE","ARRAY","AS","ASC","ASENSITIVE","ASSERTION","ASSIGNMENT","ASYMMETRIC","AT","ATOMIC","ATTRIBUTE","ATTRIBUTES","AUDIT","AUTHORIZATION","AUTO_INCREMENT","AVG","AVG_ROW_LENGTH","BACKUP","BACKWARD","BEFORE","BEGIN","BERNOULLI","BETWEEN","BIGINT","BINARY","BIT","BIT_LENGTH","BITVAR","BLOB","BOOL","BOOLEAN","BOTH","BREADTH","BREAK","BROWSE","BULK","BY","C","CACHE","CALL","CALLED","CARDINALITY","CASCADE","CASCADED","CASE","CAST","CATALOG","CATALOG_NAME","CEIL","CEILING","CHAIN","CHANGE","CHAR","CHAR_LENGTH","CHARACTER","CHARACTER_LENGTH","CHARACTER_SET_CATALOG","CHARACTER_SET_NAME","CHARACTER_SET_SCHEMA","CHARACTERISTICS","CHARACTERS","CHECK","CHECKED","CHECKPOINT","CHECKSUM","CLASS","CLASS_ORIGIN","CLOB","CLOSE","CLUSTER","CLUSTERED","COALESCE","COBOL","COLLATE","COLLATION","COLLATION_CATALOG","COLLATION_NAME","COLLATION_SCHEMA","COLLECT","COLUMN","COLUMN_NAME","COLUMNS","COMMAND_FUNCTION","COMMAND_FUNCTION_CODE","COMMENT","COMMIT","COMMITTED","COMPLETION","COMPRESS","COMPUTE","CONDITION","CONDITION_NUMBER","CONNECT","CONNECTION","CONNECTION_NAME","CONSTRAINT","CONSTRAINT_CATALOG","CONSTRAINT_NAME","CONSTRAINT_SCHEMA","CONSTRAINTS","CONSTRUCTOR","CONTAINS","CONTAINSTABLE","CONTINUE","CONVERSION","CONVERT","COPY","CORR","CORRESPONDING","COUNT","COVAR_POP","COVAR_SAMP","CREATE","CREATEDB","CREATEROLE","CREATEUSER","CROSS","CSV","CUBE","CUME_DIST","CURRENT","CURRENT_DATE","CURRENT_DEFAULT_TRANSFORM_GROUP","CURRENT_PATH","CURRENT_ROLE","CURRENT_TIME","CURRENT_TIMESTAMP","CURRENT_TRANSFORM_GROUP_FOR_TYPE","CURRENT_USER","CURSOR","CURSOR_NAME","CYCLE","DATA","DATABASE","DATABASES","DATE","DATETIME","DATETIME_INTERVAL_CODE","DATETIME_INTERVAL_PRECISION","DAY","DAY_HOUR","DAY_MICROSECOND","DAY_MINUTE","DAY_SECOND","DAYOFMONTH","DAYOFWEEK","DAYOFYEAR","DBCC","DEALLOCATE","DEC","DECIMAL","DECLARE","DEFAULT","DEFAULTS","DEFERRABLE","DEFERRED","DEFINED","DEFINER","DEGREE","DELAY_KEY_WRITE","DELAYED","DELETE","DELIMITER","DELIMITERS","DENSE_RANK","DENY","DEPTH","DEREF","DERIVED","DESC","DESCRIBE","DESCRIPTOR","DESTROY","DESTRUCTOR","DETERMINISTIC","DIAGNOSTICS","DICTIONARY","DISABLE","DISCONNECT","DISK","DISPATCH","DISTINCT","DISTINCTROW","DISTRIBUTED","DIV","DO","DOMAIN","DOUBLE","DROP","DUAL","DUMMY","DUMP","DYNAMIC","DYNAMIC_FUNCTION","DYNAMIC_FUNCTION_CODE","EACH","ELEMENT","ELSE","ELSEIF","ENABLE","ENCLOSED","ENCODING","ENCRYPTED","END","END-EXEC","ENUM","EQUALS","ERRLVL","ESCAPE","ESCAPED","EVERY","EXCEPT","EXCEPTION","EXCLUDE","EXCLUDING","EXCLUSIVE","EXEC","EXECUTE","EXISTING","EXISTS","EXIT","EXP","EXPLAIN","EXTERNAL","EXTRACT","FALSE","FETCH","FIELDS","FILE","FILLFACTOR","FILTER","FINAL","FIRST","FLOAT","FLOAT4","FLOAT8","FLOOR","FLUSH","FOLLOWING","FOR","FORCE","FOREIGN","FORTRAN","FORWARD","FOUND","FREE","FREETEXT","FREETEXTTABLE","FREEZE","FROM","FULL","FULLTEXT","FUNCTION","FUSION","G","GENERAL","GENERATED","GET","GLOBAL","GO","GOTO","GRANT","GRANTED","GRANTS","GREATEST","GROUP","GROUPING","HANDLER","HAVING","HEADER","HEAP","HIERARCHY","HIGH_PRIORITY","HOLD","HOLDLOCK","HOST","HOSTS","HOUR","HOUR_MICROSECOND","HOUR_MINUTE","HOUR_SECOND","IDENTIFIED","IDENTITY","IDENTITY_INSERT","IDENTITYCOL","IF","IGNORE","ILIKE","IMMEDIATE","IMMUTABLE","IMPLEMENTATION","IMPLICIT","IN","INCLUDE","INCLUDING","INCREMENT","INDEX","INDICATOR","INFILE","INFIX","INHERIT","INHERITS","INITIAL","INITIALIZE","INITIALLY","INNER","INOUT","INPUT","INSENSITIVE","INSERT","INSERT_ID","INSTANCE","INSTANTIABLE","INSTEAD","INT","INT1","INT2","INT3","INT4","INT8","INTEGER","INTERSECT","INTERSECTION","INTERVAL","INTO","INVOKER","IS","ISAM","ISNULL","ISOLATION","ITERATE","JOIN","K","KEY","KEY_MEMBER","KEY_TYPE","KEYS","KILL","LANCOMPILER","LANGUAGE","LARGE","LAST","LAST_INSERT_ID","LATERAL","LEADING","LEAST","LEAVE","LEFT","LENGTH","LESS","LEVEL","LIKE","LIMIT","LINENO","LINES","LISTEN","LN","LOAD","LOCAL","LOCALTIME","LOCALTIMESTAMP","LOCATION","LOCATOR","LOCK","LOGIN","LOGS","LONG","LONGBLOB","LONGTEXT","LOOP","LOW_PRIORITY","LOWER","M","MAP","MATCH","MATCHED","MAX","MAX_ROWS","MAXEXTENTS","MAXVALUE","MEDIUMBLOB","MEDIUMINT","MEDIUMTEXT","MEMBER","MERGE","MESSAGE_LENGTH","MESSAGE_OCTET_LENGTH","MESSAGE_TEXT","METHOD","MIDDLEINT","MIN","MIN_ROWS","MINUS","MINUTE","MINUTE_MICROSECOND","MINUTE_SECOND","MINVALUE","MLSLABEL","MOD","MODE","MODIFIES","MODIFY","MODULE","MONTH","MONTHNAME","MORE","MOVE","MULTISET","MUMPS","MYISAM","NAME","NAMES","NATIONAL","NATURAL","NCHAR","NCLOB","NESTING","NEW","NEXT","NO","NO_WRITE_TO_BINLOG","NOAUDIT","NOCHECK","NOCOMPRESS","NOCREATEDB","NOCREATEROLE","NOCREATEUSER","NOINHERIT","NOLOGIN","NONCLUSTERED","NONE","NORMALIZE","NORMALIZED","NOSUPERUSER","NOT","NOTHING","NOTIFY","NOTNULL","NOWAIT","NULL","NULLABLE","NULLIF","NULLS","NUMBER","NUMERIC","OBJECT","OCTET_LENGTH","OCTETS","OF","OFF","OFFLINE","OFFSET","OFFSETS","OIDS","OLD","ON","ONLINE","ONLY","OPEN","OPENDATASOURCE","OPENQUERY","OPENROWSET","OPENXML","OPERATION","OPERATOR","OPTIMIZE","OPTION","OPTIONALLY","OPTIONS","OR","ORDER","ORDERING","ORDINALITY","OTHERS","OUT","OUTER","OUTFILE","OUTPUT","OVER","OVERLAPS","OVERLAY","OVERRIDING","OWNER","PACK_KEYS","PAD","PARAMETER","PARAMETER_MODE","PARAMETER_NAME","PARAMETER_ORDINAL_POSITION","PARAMETER_SPECIFIC_CATALOG","PARAMETER_SPECIFIC_NAME","PARAMETER_SPECIFIC_SCHEMA","PARAMETERS","PARTIAL","PARTITION","PASCAL","PASSWORD","PATH","PCTFREE","PERCENT","PERCENT_RANK","PERCENTILE_CONT","PERCENTILE_DISC","PLACING","PLAN","PLI","POSITION","POSTFIX","POWER","PRECEDING","PRECISION","PREFIX","PREORDER","PREPARE","PREPARED","PRESERVE","PRIMARY","PRINT","PRIOR","PRIVILEGES","PROC","PROCEDURAL","PROCEDURE","PROCESS","PROCESSLIST","PUBLIC","PURGE","QUOTE","RAID0","RAISERROR","RANGE","RANK","RAW","READ","READS","READTEXT","REAL","RECHECK","RECONFIGURE","RECURSIVE","REF","REFERENCES","REFERENCING","REGEXP","REGR_AVGX","REGR_AVGY","REGR_COUNT","REGR_INTERCEPT","REGR_R2","REGR_SLOPE","REGR_SXX","REGR_SXY","REGR_SYY","REINDEX","RELATIVE","RELEASE","RELOAD","RENAME","REPEAT","REPEATABLE","REPLACE","REPLICATION","REQUIRE","RESET","RESIGNAL","RESOURCE","RESTART","RESTORE","RESTRICT","RESULT","RETURN","RETURNED_CARDINALITY","RETURNED_LENGTH","RETURNED_OCTET_LENGTH","RETURNED_SQLSTATE","RETURNS","REVOKE","RIGHT","RLIKE","ROLE","ROLLBACK","ROLLUP","ROUTINE","ROUTINE_CATALOG","ROUTINE_NAME","ROUTINE_SCHEMA","ROW","ROW_COUNT","ROW_NUMBER","ROWCOUNT","ROWGUIDCOL","ROWID","ROWNUM","ROWS","RULE","SAVE","SAVEPOINT","SCALE","SCHEMA","SCHEMA_NAME","SCHEMAS","SCOPE","SCOPE_CATALOG","SCOPE_NAME","SCOPE_SCHEMA","SCROLL","SEARCH","SECOND","SECOND_MICROSECOND","SECTION","SECURITY","SELECT","SELF","SENSITIVE","SEPARATOR","SEQUENCE","SERIALIZABLE","SERVER_NAME","SESSION","SESSION_USER","SET","SETOF","SETS","SETUSER","SHARE","SHOW","SHUTDOWN","SIGNAL","SIMILAR","SIMPLE","SIZE","SMALLINT","SOME","SONAME","SOURCE","SPACE","SPATIAL","SPECIFIC","SPECIFIC_NAME","SPECIFICTYPE","SQL","SQL_BIG_RESULT","SQL_BIG_SELECTS","SQL_BIG_TABLES","SQL_CALC_FOUND_ROWS","SQL_LOG_OFF","SQL_LOG_UPDATE","SQL_LOW_PRIORITY_UPDATES","SQL_SELECT_LIMIT","SQL_SMALL_RESULT","SQL_WARNINGS","SQLCA","SQLCODE","SQLERROR","SQLEXCEPTION","SQLSTATE","SQLWARNING","SQRT","SSL","STABLE","START","STARTING","STATE","STATEMENT","STATIC","STATISTICS","STATUS","STDDEV_POP","STDDEV_SAMP","STDIN","STDOUT","STORAGE","STRAIGHT_JOIN","STRICT","STRING","STRUCTURE","STYLE","SUBCLASS_ORIGIN","SUBLIST","SUBMULTISET","SUBSTRING","SUCCESSFUL","SUM","SUPERUSER","SYMMETRIC","SYNONYM","SYSDATE","SYSID","SYSTEM","SYSTEM_USER","TABLE","TABLE_NAME","TABLES","TABLESAMPLE","TABLESPACE","TEMP","TEMPLATE","TEMPORARY","TERMINATE","TERMINATED","TEXT","TEXTSIZE","THAN","THEN","TIES","TIME","TIMESTAMP","TIMEZONE_HOUR","TIMEZONE_MINUTE","TINYBLOB","TINYINT","TINYTEXT","TO","TOAST","TOP","TOP_LEVEL_COUNT","TRAILING","TRAN","TRANSACTION","TRANSACTION_ACTIVE","TRANSACTIONS_COMMITTED","TRANSACTIONS_ROLLED_BACK","TRANSFORM","TRANSFORMS","TRANSLATE","TRANSLATION","TREAT","TRIGGER","TRIGGER_CATALOG","TRIGGER_NAME","TRIGGER_SCHEMA","TRIM","TRUE","TRUNCATE","TRUSTED","TSEQUAL","TYPE","UESCAPE","UID","UNBOUNDED","UNCOMMITTED","UNDER","UNDO","UNENCRYPTED","UNION","UNIQUE","UNKNOWN","UNLISTEN","UNLOCK","UNNAMED","UNNEST","UNSIGNED","UNTIL","UPDATE","UPDATETEXT","UPPER","USAGE","USE","USER","USER_DEFINED_TYPE_CATALOG","USER_DEFINED_TYPE_CODE","USER_DEFINED_TYPE_NAME","USER_DEFINED_TYPE_SCHEMA","USING","UTC_DATE","UTC_TIME","UTC_TIMESTAMP","VACUUM","VALID","VALIDATE","VALIDATOR","VALUE","VALUES","VAR_POP","VAR_SAMP","VARBINARY","VARCHAR","VARCHAR2","VARCHARACTER","VARIABLE","VARIABLES","VARYING","VERBOSE","VIEW","VOLATILE","WAITFOR","WHEN","WHENEVER","WHERE","WHILE","WIDTH_BUCKET","WINDOW","WITH","WITHIN","WITHOUT","WORK","WRITE","WRITETEXT","X509","XOR","YEAR","YEAR_MONTH","ZEROFILL","ZONE"];
	if (ReservedWords_php.indexOf(StringToCheck.toUpperCase()) != -1) {
		showAlert(StringToCheck+" cannot be used because it is a reserved word","error",[],true,2000);
		return false;
	}
	if (ReservedWords_mysql.indexOf(StringToCheck.toUpperCase()) != -1) {
		showAlert(StringToCheck+" cannot be used because it is a reserved word","error",[],true,2000);
		return false;
	}
	return true;
}
function refreshUserRoleList() {
	UserRoleArray.sort();
	$("#UserRolesWrapper").html("");
	UserRoleArray.forEach(function(UserRole) {
		$("#UserRolesWrapper").append('<div class="alert alert-info alert-dismissible" role="alert">' + UserRole +
			'  <button id="Remove_UserRole_'+UserRole+'" type="button" class="close RemoveUserRoleBtn"' +
			' data-dismiss="alert"' +
			' aria-label="Close"><span' +
			' aria-hidden="true">&times;</span></button>\n' +
			'</div>');
	});
}