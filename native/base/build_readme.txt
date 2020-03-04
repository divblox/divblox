You can now build and run this project as a standalone <a target="_blank" href="https://cordova.apache.org/">cordova <i class="fa fa-external-link"></i></a> project.
To quickly build your project, open the terminal and navigate to the project folder.
Then, run any of the build.sh scripts available. build_all_debug.sh will create both an Android and iOS debug package.

Native build prerequisites:
1. Ensure that nodejs is installed.
- Installation instructions can be found here: <a target="_blank" href="https://nodejs.org/en/">https://nodejs.org/en/ <i class="fa fa-external-link"></a></i>
2. Ensure that cordova is installed.
- Install latest using node package manager: npm install -g cordova
3. For iOS development xcode is required.
- Ensure xcode is installed on your Mac.
- Additionally, ensure that cocoapods is installed.
- CLI install: gem install cocoapods
4. For Android development ensure that JDK 8 and Gradle are installed.
- JDK 8 can be found here: <a target="_blank" href="http://www.oracle.com/technetwork/java/javase/downloads/jdk8-downloads-2133151.html">http://www.oracle.com/technetwork/java/javase/downloads/jdk8-downloads-2133151.html <i class="fa fa-external-link"></i></a>
- Install Gradle using CLI: brew install gradle or find more information here: <a target="_blank" href="https://gradle.org/">https://gradle.org/ <i class="fa fa-external-link"></a></i>
5. For android emulators:
- Install Android Studio: <a target="_blank" href="https://developer.android.com/studio/index.html">https://developer.android.com/studio/index.html <i class="fa fa-external-link"></i></a>
- and then manage your emulators through the android AVD (Virtual device manager)
6. For push notifications we require a firebase project for Android and an Apple app id for iOS.
- Useful resources can be found here: <a target="_blank" href="https://github.com/phonegap/phonegap-plugin-push/tree/master/docs">https://github.com/phonegap/phonegap-plugin-push/tree/master/docs <i class="fa fa-external-link"></i></a>
- and <a target="_blank" href="http://macdonst.github.io/push-workshop/module3.html">http://macdonst.github.io/push-workshop/module3.html <i class="fa fa-external-link"></i></a>
- divblox ships with api operations to allow you to register and manage your push registrations. Read more <a target="_blank" href="https://docs.divblox.com">here <i class="fa fa-external-link"></i></a>

For more information regarding building for Android: <a target="_blank" href="https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html">Click here <i class="fa fa-external-link"></i></a>
For more information regarding building for iOS: <a target="_blank" href="https://cordova.apache.org/docs/en/latest/guide/platforms/ios/index.html">Click Here <i class="fa fa-external-link"></i></a>