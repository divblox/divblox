// This file allows you to create your own global javascript functionality.
// Functions declared here can and should be used to override default Divblox behaviour
// The first section of this file is reserved for Divblox code generation. Do not modify these lines manually
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
let localConfig = {
    isSpaMode: false,
    enableServiceWorker: false,
    inDebugMode: true,
    allowFeedback: true,
    appName: 'divblox'
};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Safe to modify from here downwards
// Ensure that user roles are lower case
let userRoleLandingPages = {
    "anonymous": "anonymous_landing_page",
    "administrator": "system_account_management",
    "user": "my_profile"
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Insert own GLOBALLY AVAILABLE functionality below or
// Override functions from divblox.js as needed