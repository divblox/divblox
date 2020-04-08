import 'react-native-gesture-handler';
import React, {Component} from 'react';
import {NavigationContainer} from '@react-navigation/native';
import {createStackNavigator} from '@react-navigation/stack';
import NetInfo from '@react-native-community/netinfo';
import AsyncStorage from '@react-native-community/async-storage';
import {
    SafeAreaView,
    StyleSheet,
    ScrollView,
    View,
    Text,
    StatusBar,
    Button,
    Image,
    Dimensions,
    TouchableHighlight,
    Alert,
    Platform,
    ActivityIndicator
} from 'react-native';
import {WebView} from 'react-native-webview';
import {server_base_url} from './app.json';
import RNBootSplash from 'react-native-bootsplash';
import Divblox from './divblox_react_native';

const dx = new Divblox();
const dimensions = Dimensions.get('window');
const imageHeight_3_6x1 = Math.round(dimensions.width * 1 / 3.6);
const imageWidth_3_6x1 = dimensions.width;
const imageHeight_1x1 = Math.round(dimensions.width);
const imageWidth_1x1 = dimensions.width;
const Stack = createStackNavigator();
const styles = StyleSheet.create({
    container: {
        flex: 1,
        alignItems: 'center',
        backgroundColor:'#fff'
    },
    logo_image: {
        marginTop: 150,
        height: imageHeight_3_6x1,
        width: imageWidth_3_6x1
    },
    icon_image: {
        marginTop: 150,
        height: 200,
        width:200
    },
    heading: {
        marginTop: 60,
    },
    text: {
        marginHorizontal: 8,
        marginVertical: 10
    },
    button: {
        marginRight:40,
        marginLeft:40,
        marginTop:10,
        paddingTop:20,
        paddingBottom:20,
        backgroundColor:'#fff',
        borderRadius:10,
        borderWidth: 1,
        borderColor: "#d7d7d7",
        borderStyle: "solid",
        overflow: 'hidden',
        width:100
    },
    submitText:{
        color:'#6e6e6e',
        textAlign:'center',
    },
    bottom: {
        flex: 1,
        justifyContent: 'flex-end',
        marginBottom: 100
    }
});

let is_connected = true;
let current_screen = 'Init';
let loading_visible = true;
let stored_dx_auth_token = null;
let server_final_url = server_base_url+"/?view=native_landing&init_native=1";

function hideLoadingIndicator() {
    loading_visible = false;
}

const checkFirstLaunch = async ({navigation}) => {
    let first_launch;
    try {
        first_launch = await AsyncStorage.getItem('first_launch');
    } catch (error) {}
    if (first_launch == null) {
        loadScreenByName({navigation},'Welcome');
    } else {
        loadScreenByName({navigation},'Web');
    }
    
    NetInfo.addEventListener(state => {
        if (!state.isConnected && is_connected) {
            loadScreenByName({navigation},'Offline');
        } else if (state.isConnected && !is_connected) {
            loadScreenByName({navigation},current_screen);
        }
        is_connected = state.isConnected;
    });
};
const setFirstLaunchTag = async first_launch_tag => {
    try {
        await AsyncStorage.setItem('first_launch', first_launch_tag);
    } catch (error) {}
};

function InitScreen({navigation}) {
    checkFirstLaunch({navigation});
    return (
        <>
            <StatusBar barStyle="dark-content"/>
            <View style={styles.container}>
                <Image
                    style={styles.logo_image}
                    source={require('./assets/divblox_logo_black.jpg')} />
                <Text style={styles.heading}>Initializing...</Text>
                <View style={styles.bottom}>
                    <TouchableHighlight
                        style={styles.button}
                        onPress={() => loadScreenByName({navigation},'Web')}
                        underlayColor='#d7d7d7'>
                        <Text style={styles.submitText}>Force Load</Text>
                    </TouchableHighlight>
                </View>
            </View>
        </>
    );
}

function WelcomeScreen({navigation}) {
    return (
        <>
            <StatusBar barStyle="dark-content"/>
            <View style={styles.container}>
                <Image
                    style={styles.logo_image}
                    source={require('./assets/divblox_logo_black.jpg')} />
                <Text style={styles.heading}>WELCOME</Text>
                <Text style={styles.text}>This is the default welcome screen for a Divblox native app. It will only show once.</Text>
                <Text style={styles.text}>This is useful for introducing your app to the user and
                    to inform the user that certain requests for permissions might follow (i.e Push notifications)</Text>
                <View style={styles.bottom}>
                    <TouchableHighlight
                        style={styles.button}
                        onPress={() => confirmWelcomeScreen({navigation})}
                        underlayColor='#d7d7d7'>
                        <Text style={styles.submitText}>Next</Text>
                    </TouchableHighlight>
                </View>
            </View>
        </>
    );
}

function OfflineScreen({navigation}) {
    return (
        <>
            <StatusBar barStyle="dark-content"/>
            <View style={styles.container}>
                <Image
                    style={styles.icon_image}
                    source={require('./assets/dx_offline.png')} />
                <Text style={styles.heading}>YOU'RE OFFLINE</Text>
                <Text style={styles.text}>Please check your internet connection to proceed</Text>
                <View style={styles.bottom}>
                    <TouchableHighlight
                        style={styles.button}
                        onPress={() => loadScreenByName({navigation},'Web')}
                        underlayColor='#d7d7d7'>
                        <Text style={styles.submitText}>Retry</Text>
                    </TouchableHighlight>
                </View>
            </View>
        </>
    );
}

function LoadingScreen({navigation}) {
    return (
        <>
            <StatusBar barStyle="dark-content"/>
            <View style={styles.container}>
                <LoadingIndicator/>
            </View>
        </>
    );
}

function ErrorScreen({navigation}) {
    return (
        <>
            <StatusBar barStyle="dark-content"/>
            <View style={styles.container}>
                <Image
                    style={styles.icon_image}
                    source={require('./assets/dx_offline.png')} />
                <Text style={styles.heading}>AN ERROR OCCURRED</Text>
                <Text style={styles.text}>Please check your internet connection to proceed</Text>
                <View style={styles.bottom}>
                    <TouchableHighlight
                        style={styles.button}
                        onPress={() => resetAppWrapper({navigation})}
                        underlayColor='#d7d7d7'>
                        <Text style={styles.submitText}>Retry</Text>
                    </TouchableHighlight>
                </View>
            </View>
        </>
    );
}

function LoadingIndicator() {
    return <ActivityIndicator
        style={{ position: 'absolute', left: 0, right: 0, bottom: 0, top: 0, }}
        size="large"
    />
}

function WebScreen({navigation}) {
    return (
        <>
            <StatusBar barStyle="dark-content"/>
            <SafeAreaView style={{flex:1}}>
                <WebView
                    startInLoadingState={true}
                    source={{ uri: server_final_url }}
                    renderLoading={() => {
                        return LoadingIndicator();
                    }}
                />
            </SafeAreaView>
        </>
    );
}

function loadScreenByName({navigation}, screen_name) {
    let screen_loaded = screen_name;
    if (screen_name === "Web") {
        NetInfo.fetch().then(state => {
            if (state.isConnected) {
                screen_loaded = 'Web';
            } else {
                screen_loaded = 'Offline';
            }
        });
        dx.registerDevice(function(stored_token) {
                stored_dx_auth_token = stored_token;
                server_final_url += '&auth_token='+stored_dx_auth_token;
                loadScreenAsReset({navigation},screen_loaded);
            },
            function () {
                loadScreenByName({navigation},"Error");
            });
    } else {
        loadScreenAsReset({navigation},screen_loaded);
    }
    if (screen_loaded !== 'Offline') {
        current_screen = screen_loaded;
    }
}
function loadScreenAsReset({navigation}, screen_name) {
    navigation.reset({
        index: 0,
        routes: [
            {
                name: screen_name
            },
        ],
    });
}
function confirmWelcomeScreen({navigation}) {
    loadScreenByName({navigation},"Loading");
    dx.registerDevice(
        function() {
            setFirstLaunchTag("1");
            loadScreenByName({navigation},'Web');
            dx.registerPushNotifications(function() {
                //Success
            },function() {
                //failed
            });
        },
        function() {
            loadScreenByName({navigation},"Error");
        }
    )
}
function resetAppWrapper({navigation}) {
    loadScreenByName({navigation},"Loading");
    dx.reInitDevice(function() {
        loadScreenByName({navigation},"Init");
    },function() {
        loadScreenByName({navigation},"Init");
    })
}
class DivbloxWebAppWrapper extends Component{
    render() {
        return (
            <NavigationContainer>
                <Stack.Navigator
                    screenOptions={{
                        headerShown: false
                    }}
                >
                    <Stack.Screen
                        name="Init"
                        component={InitScreen}
                        options={{title: 'Init'}}
                    />
                    <Stack.Screen
                        name="Welcome"
                        component={WelcomeScreen}
                        options={{title: 'Welcome'}}
                    />
                    <Stack.Screen
                        name="Web"
                        component={WebScreen}
                        options={{title:null,animationEnabled: false}}/>
                    <Stack.Screen
                        name="Offline"
                        component={OfflineScreen}
                        options={{title:null}}/>
                    <Stack.Screen
                        name="Error"
                        component={ErrorScreen}
                        options={{title: 'Error'}}
                    />
                    <Stack.Screen
                        name="Loading"
                        component={LoadingScreen}
                        options={{title: 'Loading',animationEnabled: false}}
                    />
                </Stack.Navigator>
            </NavigationContainer>
        );
    }
}

export default class App extends Component {
    componentDidMount() {
        RNBootSplash.hide({ duration: 500 }); // fade
    }
    
    render() {
        return (
            <DivbloxWebAppWrapper/>
        )
    }
}
