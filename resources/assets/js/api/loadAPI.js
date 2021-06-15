
import { ALERT_SYSTEM_CONFIG } from '../config.js';

import axios from 'axios/index'

export default {

    /** POST /api/v2/landslideInformation **/
    landslideInformation: function(){
        return axios.post( ALERT_SYSTEM_CONFIG.API_URL + 'landslideInformation');
    },

    /** POST /api/v2/landslideInformation **/
    floodInformation: function(){
        return axios.post(  ALERT_SYSTEM_CONFIG.API_URL + 'floodInformation');
    },

    /** POST /api/v2/userInformation **/
    userInformation: function(userId){
        return axios.post( ALERT_SYSTEM_CONFIG.API_URL + 'userInformation', userId);
    },

    /** POST /api/v2/getAllDataStationById **/
    currentStationInfo: function(stationData){
        return axios.post(ALERT_SYSTEM_CONFIG.API_URL + 'getAllDataStationById', stationData)
    }
}