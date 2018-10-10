
import { ALERT_SYSTEM_CONFIG } from '../config.js';

export default {
    /*
        GET /api/v1/user
     */
    getUser: function(){
        return axios.post( ALERT_SYSTEM_CONFIG.API_URL + '/getAuthUser' );
    },
}