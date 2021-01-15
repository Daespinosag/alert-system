import Station from "../station";
import FloodAlert from "./floodAlert";

export default class FloodStation extends Station {
    static entity = 'floodStations'
    //static baseEntity = 'stations'

    static fields () {
        return {
            ...super.fields(),

            alert: this.hasManyBy(FloodAlert,'alert_id','id')
        }
    }
}