/*
    Defines the API route we are using.
*/

const proxy = 'https://cors-anywhere.herokuapp.com/';

const api_url = 'http://cdiac.manizales.unal.edu.co/alert-system-backend/api/v2/';

const url_img = 'http://cdiac.manizales.unal.edu.co/images/alert-icons/';

const google_maps_js_api = 'AIzaSyDZWMe7WI59R_HaHS55TdqaNmHidkuLNKs';

export const ALERT_SYSTEM_CONFIG = {
    PROXY_URL: proxy,
    API_URL: api_url,
    GOOGLE_MAPS_JS_API: google_maps_js_api,
    URL_IMAGES: url_img
};