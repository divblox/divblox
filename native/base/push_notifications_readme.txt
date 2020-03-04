Follow the steps below in order to add push notification support to your divblox app:

1. divblox push notifications implement the phonegap push plugin:
- This plugin has already been installed
- Should you need to install it again: cordova plugin add phonegap-plugin-push
- We use FCM (Firebase cloud messaging) to deliver push notifications

2. Create a firebase project to download the correct google-services.json and GoogleService-info.plist files and to setup the Apple APNs Authentication key.
- Do NOT follow the Firebase steps to install the Firebase SDK. The phonegap push plugin already takes care of this
- The default export contains divblox placeholders in both google-services.json and GoogleService-info.plist. You need to update these files before compiling
    - https://console.firebase.google.com
    - Ensure that your package name matches your widget id (You configured this for your environment in the divblox setup page)
    - Then navigate to your project settings in Firebase and ensure that you have created an android and an ios app to download the files
    - If this is not correctly configured, you might get an error like "No matching client found for package name 'com.divblox.app'"
- Ensure that you create an Apple APNs Authentication key (https://developer.apple.com/account).
    - Apply this key in your Firebase project settings under "Cloud Messaging" for your iOS app.
- In your project.js file, update the native_config object's push_notification_firebase_sender_id value to equal the sender ID found in Firebase for your app
- Update the Firebase constants in /divblox/config/framework/config.php:
    - Set FIREBASE_SERVER_KEY_STR to your Firebase Server key
    - Set FIREBASE_FCM_ENDPOINT_STR to the Firebase FCM endpoint. It should be correct, but if it needs to be updated, this is where that is done.
3. Compile your iOS and Android apps
4. By default, divblox will call the functions "initPushNotifications()" and "requestPushNotificationPermissions()" on load:
- This will register a new PushRegistration on the divblox backend to match your registration id for FCM
- Now you can use the divblox backend php functions "deliverSinglePushPayload()" and "deliverBatchedPushPayload()" to send push notifications to a single user or group of users