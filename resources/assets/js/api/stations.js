
import { ALERT_SYSTEM_CONFIG } from '../config.js';

import axios from 'axios/index'

export default {
    /*
        GET /api/v2/role
     */
    getRole: function(roleId){
        return axios.post(ALERT_SYSTEM_CONFIG.API_URL + 'getRoleAuthUser',roleId);
    },
}