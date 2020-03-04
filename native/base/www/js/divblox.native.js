/*
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */
// functions that should only be available when in native mode to be added here
function initNative() {
	createCookie('divblox_config','success');
}
function on_divblox_ready () {
	if (!device_ready) {
		setTimeout(function() {
			on_divblox_ready();
		},500);
	} else {
		if (window.location.search.length > 0) {
			preparePageInputs(window.location.search);
		} else {
			processPageInputs();
		}
	}
}

// We set debug mode to false here for performance. To enable logging, set to true. Should always be false for
// production releases
debug_mode = false;