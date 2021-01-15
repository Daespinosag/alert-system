import baseModel from "../baseModel";
import Station from "./station";

export default class StationType extends baseModel {
    static entity = 'stationTypes'
    //static baseEntity = 'baseModel'

    static fields () {
        return {
            id: this.attr(null),
            code: this.string(''),
            name: this.string(''),
            description: this.string(''),
            etl_method: this.string(''),

            stations: this.hasMany(Station,'station_type_id','id')
        }
    }
}