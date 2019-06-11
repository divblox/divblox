The push notification plugin is not installed by default when exporting your divblox app. It requires a bit of configuration
in order to get up and running. Follow the steps below in order to add push notification support to your divblox app:

1. Install the phonegap push plugin: cordova plugin add phonegap-plugin-push
2. Create a firebase project to download the correct google-services.json and GoogleService-info.plist files:
- https://console.firebase.google.com
- Then navigate to your project settings and ensure that you have created an android and an ios app to download the files
3. Ensure that your package name matches your widget id (You configured this for your environment in the divblox setup page)
4. To build for iOS you need to enable the following:
- The development team selected for the project needs to support push notifications
- Your provisioning profile needs to support push notifications
- Your provisioning profile needs to include the aps-environment entitlement