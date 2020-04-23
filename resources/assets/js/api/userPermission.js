
import { ALERT_SYSTEM_CONFIG } from '../config.js';

import axios from 'axios/index'

export default {
    /*
        GET /api/v2/user
     */
    getUserPermissions: function(userId){
        return axios.post( ALERT_SYSTEM_CONFIG.API_URL + 'getUserPermissions',userId);
    },
}