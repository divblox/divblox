/*
 * Copyright (c) 2020. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */
import React, {Component} from 'react';
import AsyncStorage from '@react-native-community/async-storage';
import {server_base_url as server_url} from './app.json';
import DeviceInfo from 'react-native-device-info';
class Divblox extends Component {
    constructor() {
        super();
        let dx_authentication_token = null;
        let push_registration_id = null;
    }
    async dxRequestInternal(url,post_body_obj,callback) {
        try {
            return fetch(url,{
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(post_body_obj),
            })
                .then((response) => response.json())
                .then((json) => {
                    let data_obj = this.getJsonObject(json);
                    if (typeof data_obj.Result !== "undefined") {
                        if (data_obj.Result === "Success") {
                            callback(data_obj);
                        } else {
                            callback({"Error": data_obj});
                        }
                    } else {
                        callback({"Error": data_obj});
                    }
                })
                .catch((error) => {
                    callback({"Error": error});
                    console.error(error);
                });
        } catch (error) {
        
        }
    }
    async registerDevice(success_callback,failed_callback) {
        try {
            this.dx_authentication_token = await AsyncStorage.getItem('dx_authentication_token');
            this.dxRequestInternal(server_url+'/api/client_authentication_token/registerDevice',
                {
                    AuthenticationToken: this.dx_authentication_token,
                    DeviceUuid:DeviceInfo.getUniqueId(),
                    DevicePlatform:DeviceInfo.getDeviceId(),
                    DeviceOs:DeviceInfo.getSystemName()
                },
                async function(data_obj) {
                    if (typeof data_obj.Error !== "undefined") {
                        failed_callback();
                        return;
                    }
                    if (data_obj.Result === "Success") {
                        this.dx_authentication_token = data_obj.DeviceLinkedAuthenticationToken;
                        await AsyncStorage.setItem('dx_authentication_token', data_obj.DeviceLinkedAuthenticationToken);
                        success_callback(this.dx_authentication_token);
                        return;
                    }
                    failed_callback();
                });
            
        } catch (error) {
            failed_callback();
        }
    }
    async registerPushNotifications(success_callback,failed_callback) {
        console.log("TODO: Put your code that asks for push notification permissions here. Once a successful Push" +
            " registration ID is received, send it to the server with");
        //this.createPushRegistration("The ID received from the push service",success_callback,failed_callback);
    }
    async createPushRegistration(registration_id,success_callback,failed_callback) {
        if (typeof success_callback !== "function") {
            success_callback = function() {};
        }
        if (typeof failure_callback !== "function") {
            failed_callback = function() {};
        }
        if (typeof registration_id === "undefined") {
            failed_callback("No registration id provided");
            return;
        }
        this.push_registration_id = await AsyncStorage.getItem('PushRegistrationId');
        if (this.push_registration_id !== null) {
            // We only want to send the push registration once
            success_callback(this.push_registration_id);
            return;
        }
    
        this.dxRequestInternal(server_url+'/api/global_functions/updatePushRegistration',
            {
                registration_id: registration_id,
                device_uuid: DeviceInfo.getUniqueId(),
                device_platform: DeviceInfo.getDeviceId(),
                device_os:DeviceInfo.getSystemName(),
                AuthenticationToken:this.dx_authentication_token
            },
            async function(data_obj) {
                if (typeof data_obj.Error !== "undefined") {
                    failed_callback();
                    return;
                }
                if (data_obj.Result === "Success") {
                    await AsyncStorage.setItem('PushRegistrationId', registration_id);
                    success_callback(registration_id);
                    return;
                }
                failed_callback();
            });
    }
    async reInitDevice(success_callback,failed_callback) {
        await AsyncStorage.removeItem('dx_authentication_token');
        this.registerDevice(success_callback,failed_callback);
    }
    /**
     * Determines whether a string is a valid JSON string
     * @param {String} input_string The string to check
     * @return {boolean} true if valid JSON, false if not
     */
    isJsonString(input_string) {
        try {
            JSON.parse(input_string);
        } catch (e) {
            return false;
        }
        return true;
    }
    /**
     * Returns either a valid JSON object from the input or an empty object
     * @param mixed_input: Can be json string or object
     * @return {any}
     */
    getJsonObject(mixed_input) {
        if (this.isJsonString(mixed_input)) {
            return JSON.parse(mixed_input);
        }
        let return_obj = {};
        try {
            let encoded_string = JSON.stringify(mixed_input);
            if (this.isJsonString(encoded_string)) {
                return_obj = JSON.parse(encoded_string);
            }
        } catch (e) {
            return return_obj;
        }
        return return_obj;
    }
}
export default Divblox;