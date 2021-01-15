import Alert from "../alert";
import FloodStation from "./floodStation";
import Basin from "./Basin";

export default class FloodAlert extends Alert {
    static entity = 'floodAlerts'
    //static baseEntity = 'alerts'

    static fields () {
        return {
            basin_id: this.attr(null),
            ...super.fields(),

            stations: this.hasMany(FloodStation, 'alert_id','id'),
            basin: this.hasManyBy(Basin,'basin_id','id')
        }
    }
}