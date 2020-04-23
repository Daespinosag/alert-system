import Station from "../station";
import LandslideAlert from "./landslideAlert";

export default class LandslideStation extends Station {
    static entity = 'landslideStations'
    //static baseEntity = 'stations'

    static fields () {
        return {
            ...super.fields(),

            alert: this.hasManyBy(LandslideAlert,'alert_id','id')
        }
    }
}