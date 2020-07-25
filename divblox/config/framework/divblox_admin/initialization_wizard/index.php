<?php
/**
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */
require('installation_helper.php');
if (!defined('ALLOW_WIZARD_ACCESS')) {
    die("Access not allowed. Check flag 'ALLOW_WIZARD_ACCESS'");
}
if (!ALLOW_WIZARD_ACCESS) {
    die("Access not allowed. Check flag 'ALLOW_WIZARD_ACCESS'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../../../../assets/css/bootstrap/4.3.1/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../../../project/assets/css/project.css">
    <link rel="icon" href="../../../../../divblox/assets/images/divblox_favicon.ico" />
    <title>dx Setup Wizard</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <img src="../../../../assets/images/divblox_logo.svg" class="img-fluid" style="max-height:50px;"/>
        </div>
        <div class="col-12 text-center">
            <a href="../setup.php"><i class="fa fa-cogs"></i> Open divblox setup</a> |
            <a id="btnRecheck" href="#"><i class="fa fa-refresh"></i> Recheck Installation</a>
            <div class="alert alert-warning">
                <strong>IMPORTANT: </strong>You should disable access to this page for production deployments.
                You can disable access to this page by setting the constant "ALLOW_WIZARD_ACCESS" to false in
                the file installation_helper.php
            </div>
        </div>
        <div class="col-md-6">
            <h4>divblox System checks <small id="StatusCheckSymbol"></small></h4>
            <div id="CheckConfigWrapper" class="alert alert-info">
                <span id="CheckingConfig">Checking configuration...<br></span>
                <span id="ConfigNotPresent" style="display:none;">
                    The file /divblox/config/framework/config.php does not exist. Please download the latest version of divblox to obtain this file<br>
                </span>
                <span id="ConfigPresent" style="display:none;">
                    /divblox/config/framework/config.php is present.<br>
                </span>
                <span id="EnvironmentsNotPresent" style="display:none;">
                    The file /divblox/config/framework/environments.php does not exist. <a id="btnCreateEnvironments" href="#" class="btn btn-link">Create it?</a><br>
                </span>
                <span id="EnvironmentsPresent" style="display:none;">
                    /divblox/config/framework/environments.php is present.<br>
                </span>
            </div>
            <div id="CheckPHPVersionsWrapper" class="alert alert-info">
                <span id="CheckingPHPVersions">Checking php version...<br></span>
                Current PHP version: <span id="CurrentPhpVersion">N/A</span><br>
                Minimum required PHP version: <span id="MinimumRequiredPhpVersion">N/A</span><br>
                Maximum supported PHP version: <span id="MaximumRequiredPhpVersion">N/A</span><br>
                PHP version status: <span id="PhpVersionStatus">N/A</span><br>
            </div>
            <div id="CheckDbVersionsWrapper" class="alert alert-info">
                <span id="CheckingDbVersions">Checking Database version...<br></span>
                Current database version: <span id="CurrentDBVersion">N/A</span><br>
                <strong>MySql</strong><br>
                Minimum required Database version: <span id="MinimumRequiredMySqlVersion">N/A</span><br>
                Maximum supported Database version: <span id="MaximumRequiredMySqlVersion">N/A</span><br>
                <strong>MariaDB</strong><br>
                Minimum required Database version: <span id="MinimumRequiredMariaDBVersion">N/A</span><br>
                Maximum supported Database version: <span id="MaximumRequiredMariaDBVersion">N/A</span><br>
                Database table names case configuration: <span id="TableCaseConfig">N/A</span><br>
                Database version status: <span id="DBVersionStatus">N/A</span><br>
            </div>
            <div id="CheckCurlWrapper" class="alert alert-info">
                <span id="CheckingCurl">Checking connection to divblox basecamp...<br></span>
                <span id="CurlFailure" style="display:none;">
                    Cannot connect to divblox basecamp. Check your internet connection and/or PHP curl installation. <br>Error: <span id="CurlError"></span>
                </span>
                <span id="CurlSuccess" style="display:none;">
                    Connected to divblox basecamp.<br>
                </span>
            </div>
            <div id="CheckWritePermissionsWrapper" class="alert alert-info">
                <span id="CheckingWritePermissions">Checking write permissions for folders...<br></span>
                <span id="WritePermissionsORMFailure" style="display:none;">
                    The folder /divblox/config/database/datamodel_orm is not writable. Please ensure that the apache user (<?php echo exec('whoami'); ?>) can write to this folder.<br>
                </span>
                <span id="WritePermissionsORMSuccess" style="display:none;">
                    Datamodel ORM folder is writable<br>
                </span>
                <span id="WritePermissionsProjectFailure" style="display:none;">
                    The folder /project is not writable. Please ensure that the apache user (<?php echo exec('whoami'); ?>) can write to this folder.<br>
                </span>
                <span id="WritePermissionsProjectSuccess" style="display:none;">
                    Project folder is writeable<br>
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <h4>ionCube installation check</h4>
            <iframe src="ionCube/index.php" style="width: 100%;height: 100vh;border: none;"></iframe>
        </div>
        <div class="col-12 text-center">
            <h4>Php Info</h4>
        </div>
        <div class="col-12">
            <pre><?php printCleanPhpInfo();?></pre>
        </div>
    </div>
</div>
<script type="text/javascript" src="../../../../assets/js/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../../../../assets/js/bootstrap/4.3.1/bootstrap.min.js"></script>
<script>
    let config_ok = true;
	$( document ).ready(function() {
		checkConfiguration();
		checkBasecampConnection();
		checkWritePermissions();
	});

	function checkConfiguration() {
		$.post('../../check_config.php',{},function(data) {
			let data_obj = JSON.parse(data);
			if (typeof data_obj["Success"] === "undefined") {
				updateStatusCheckSymbol(true);
				if (typeof data_obj["Failed"] !== "undefined") {
					if (data_obj["Failed"] === 'Config is not present') {
						$("#ConfigNotPresent").show();
						$("#ConfigPresent").hide();
                    } else if (data_obj["Failed"] === 'Environment is not present') {
						$("#ConfigPresent").show();
						$("#ConfigNotPresent").hide();
						$("#EnvironmentsNotPresent").show();
						$("#EnvironmentsPresent").hide();
                    }
                }
				$("#CheckConfigWrapper").removeClass("alert-info").addClass("alert-danger");
				$("#CheckingConfig").html('<strong>Check configuration: </strong><br>');
				$("#CheckingPHPVersions").html('<strong>Check PHP version: </strong><br>');
                $("#CheckingDbVersions").html('<strong>Check Database version: </strong><br>');
            } else {
				updateStatusCheckSymbol(false);
				$("#ConfigPresent").show();
				$("#ConfigNotPresent").hide();
				$("#EnvironmentsPresent").show();
				$("#EnvironmentsNotPresent").hide();
				$("#CheckConfigWrapper").removeClass("alert-info").addClass("alert-success");
				$("#CheckingConfig").html('<strong>Check configuration: </strong><br>');
                $("#CheckingPHPVersions").html('<strong>Check PHP version: </strong><br>');
                $("#CheckingDbVersions").html('<strong>Check Database version: </strong><br>');

                $("#CurrentPhpVersion").html(data_obj.PhpVersion);
				$("#MinimumRequiredPhpVersion").html(data_obj.MinimumRequiredPhpVersion);
                $("#MaximumRequiredPhpVersion").html(data_obj.MaximumRequiredPhpVersion);
				if (data_obj.PhpVersionOk !== false) {
					$("#PhpVersionStatus").html("All good!");
					$("#CheckPHPVersionsWrapper").removeClass("alert-info").addClass("alert-success");
                } else {
					$("#PhpVersionStatus").html("<strong>Minimum PHP version not met</strong>");
					updateStatusCheckSymbol(true);
					$("#CheckPHPVersionsWrapper").removeClass("alert-info").addClass("alert-danger");
                }
				if (data_obj["DbVersion"]["Version"] === "N/A") {
					$("#CurrentDBVersion").html(data_obj["DbVersion"]["Version"]+"; Reason: "+data_obj["DbVersion"]["Reason"]);
                } else {
					$("#CurrentDBVersion").html(data_obj["DbVersion"]["Server"]+" "+data_obj["DbVersion"]["Version"]);
                }
                $("#MinimumRequiredMySqlVersion").html(data_obj.MinimumRequiredMySqlVersion);
                $("#MaximumRequiredMySqlVersion").html(data_obj.MaximumRequiredMySqlVersion);
                $("#MinimumRequiredMariaDBVersion").html(data_obj.MinimumRequiredMariaDBVersion);
                $("#MaximumRequiredMariaDBVersion").html(data_obj.MaximumRequiredMariaDBVersion);
				$("#RequiredDBVersion").html(data_obj["DbVersion"]["Server"]+" "+data_obj["RequiredDbVersion"]);
				if (typeof data_obj["DbVersion"]["LowerCaseTableNamesOK"] !== "undefined") {
					if (data_obj["DbVersion"]["LowerCaseTableNamesOK"] === true) {
						$("#TableCaseConfig").html("OK!");
					} else {
						updateStatusCheckSymbol(true);
						$("#CheckVersionsWrapper").removeClass("alert-info").addClass("alert-danger");
						$("#TableCaseConfig").html("Failed! Please ensure that the database variable 'lower_case_table_names' is set to 2");
					}
                }
				if (data_obj["DbVersionOk"] !== false) {
					$("#DBVersionStatus").html("All good!");
					$("#CheckDbVersionsWrapper").removeClass("alert-info").addClass("alert-success");
				} else {
					$("#DBVersionStatus").html("<strong>Minimum database version not met</strong>");
					updateStatusCheckSymbol(true);
					$("#CheckDbVersionsWrapper").removeClass("alert-info").addClass("alert-danger");
				}
            }
		}).fail(function() {
			updateStatusCheckSymbol(true);
			$("#EnvironmentsNotPresent").show();
			$("#EnvironmentsPresent").hide();
			$("#CheckConfigWrapper").removeClass("alert-info").addClass("alert-danger");
			$("#CheckingConfig").html('<strong>Check configuration: </strong><br>');
		});
	}
	function updateStatusCheckSymbol(has_errors) {
		if (typeof has_errors === "undefined") {
			has_errors = true;
        }
        if (has_errors) {
        	config_ok = false;
        }
        if (config_ok) {
        	$("#StatusCheckSymbol").html('<i class="fa fa-check"></i>');
        } else {
	        $("#StatusCheckSymbol").html('<i class="fa fa-exclamation-triangle"></i>');
        }
		//
    }

    function checkBasecampConnection() {
	    $.post('installation_helper.php?checkCurl=1',{},function(data) {
            console.log(data);
            let data_obj = JSON.parse(data);
            if (typeof data_obj["Success"] !== "undefined") {
	            $("#CurlFailure").hide();
	            $("#CurlSuccess").show();
	            $("#CheckCurlWrapper").removeClass("alert-info").addClass("alert-success");
	            $("#CheckingCurl").html('<strong>Check connection to divblox basecamp: </strong><br>');
            } else {
	            updateStatusCheckSymbol(true);
	            $("#CurlFailure").show();
	            $("#CurlSuccess").hide();
	            $("#CurlError").html('<strong>'+data_obj["Failed"]+'</strong>');
	            $("#CheckCurlWrapper").removeClass("alert-info").addClass("alert-danger");
	            $("#CheckingCurl").html('<strong>Check connection to divblox basecamp: </strong><br>');
            }
	    }).fail(function() {
		    updateStatusCheckSymbol(true);
		    $("#CurlFailure").show();
		    $("#CurlSuccess").hide();
		    $("#CurlError").html('<strong>Could not connect to installation_helper.php</strong>');
		    $("#CheckCurlWrapper").removeClass("alert-info").addClass("alert-danger");
		    $("#CheckingCurl").html('<strong>Check connection to divblox basecamp: </strong><br>');
	    });
    }
    function checkWritePermissions() {
	    $.post('installation_helper.php?checkWritePermissions=1',{},function(data) {
		    console.log("File write permissions: "+data);
		    let data_obj = JSON.parse(data);
		    $("#WritePermissionsORMFailure").hide();
		    $("#WritePermissionsProjectFailure").hide();
		    $("#WritePermissionsORMSuccess").show();
		    $("#WritePermissionsProjectSuccess").show();
		    if (typeof data_obj["Success"] !== "undefined") {
			    $("#WritePermissionsORMFailure").hide();
			    $("#WritePermissionsProjectFailure").hide();
			    $("#WritePermissionsORMSuccess").show();
			    $("#WritePermissionsProjectSuccess").show();
			    $("#CheckWritePermissionsWrapper").removeClass("alert-info").addClass("alert-success");
			    $("#CheckingWritePermissions").html('<strong>Check write permissions for folders: </strong><br>');
		    } else {
			    updateStatusCheckSymbol(true);
                if (data_obj["Datamodel"] === false) {
	                $("#WritePermissionsORMFailure").show();
	                $("#WritePermissionsORMSuccess").hide();
                }
			    if (data_obj["Project"] === false) {
				    $("#WritePermissionsProjectFailure").show();
				    $("#WritePermissionsProjectSuccess").hide();
			    }
			    $("#CheckWritePermissionsWrapper").removeClass("alert-info").addClass("alert-danger");
			    $("#CheckingWritePermissions").html('<strong>Check write permissions for folders: </strong><br>');
		    }
	    }).fail(function() {
		    updateStatusCheckSymbol(true);
		    $("#CheckWritePermissionsWrapper").removeClass("alert-info").addClass("alert-danger");
		    $("#CheckingWritePermissions").html('<strong>Check write permissions for folders: </strong><br>');
	    });
    }

	$("#btnCreateEnvironments").on("click", function() {
		$.post('../initialize_environments.php',{},function() {
			location.reload();
		});
	});
    $("#btnRecheck").on("click", function() {
	    location.reload();
    });


</script>
</body>
</html>
