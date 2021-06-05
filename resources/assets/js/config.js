/*
    Defines the API route we are using.
*/
export const ALERT_SYSTEM_CONFIG = {
    PROXY_URL: process.env.MIX_PROXY,
    API_URL: process.env.MIX_API_URL,
    GOOGLE_MAPS_JS_API: process.env.MIX_GOOGLE_MAPS_JS_API,
    URL_IMAGES: process.env.MIX_URL_IMG
};