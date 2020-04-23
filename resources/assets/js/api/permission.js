
import { ALERT_SYSTEM_CONFIG } from '../config.js';

import axios from 'axios/index'

export default {
    /*
        GET /api/v2/user
     */
    getPermissions: function(){
        return axios.post( ALERT_SYSTEM_CONFIG.API_URL + 'getPermissions');
    },
}