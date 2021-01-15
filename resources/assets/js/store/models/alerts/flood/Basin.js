import Alert from "../alert";
import FloodAlert from "./floodAlert";


export default class basin extends Alert {
    static entity = 'basins'
    //static baseEntity = 'alerts'

    static fields () {
        return {
            id: this.attr(null),
            name: this.string(''),
            code: this.string(''),
            description: this.string(''),
            kml: this.string(''),

            alerts: this.hasMany(FloodAlert,'basin_id','id')
        }
    }
}