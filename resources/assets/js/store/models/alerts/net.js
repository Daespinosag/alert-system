import baseModel from "../baseModel";
import Station from "./station";

export default class Net extends baseModel {
    static entity = 'nets'
    //static baseEntity = 'baseModel'

    static fields () {
        return {
            id: this.attr(null),
            name: this.string(''),
            description: this.string(''),
            administrator_name: this.string(''),
            rt_active: this.boolean(true),

            stations: this.hasMany(Station,'net_id','id')
        }
    }
}