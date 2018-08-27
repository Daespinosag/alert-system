
import { ALERT_SYSTEM_CONFIG } from '../config.js';

export default {
    /*
        GET /api/v1/consultAlert
     */

    genetareAlert: function(form){
        return axios.post( ALERT_SYSTEM_CONFIG.API_URL + '/consultAlert',form);
    }
}