import Alert from "../alert";
import LandslideStation from "./landslideStation";
import Zone from "./zone";

export default class LandslideAlert extends Alert {
    static entity = 'landslideAlerts'
    //static baseEntity = 'alerts'

    static fields () {
        return {
            zone_id: this.attr(null),
            ...super.fields(),
            limitYellow: this.attr(''),
            limitOrange: this.attr(''),

            stations: this.hasMany(LandslideStation, 'alert_id','id'),
            zone: this.hasManyBy(Zone,'zone_id','id')
        }
    }
}