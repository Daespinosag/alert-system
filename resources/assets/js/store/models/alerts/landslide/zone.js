import Alert from "../alert";
import LandslideAlert from "./landslideAlert";

export default class Zone extends Alert {
    static entity = 'zones'
    //static baseEntity = 'alerts'

    static fields () {
        return {
            id: this.attr(null),
            name: this.string(''),
            code: this.string(''),
            description: this.string(''),
            kml: this.string(''),

            alerts: this.hasMany(LandslideAlert,'basin_id','id')
        }
    }
}