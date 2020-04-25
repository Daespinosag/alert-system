import baseModel from "../baseModel";
import Net from "./net";
import StationType from "./stationType";

import { latLng } from "leaflet"

export default class Station extends baseModel {
    static entity = 'stations'
    //static baseEntity = 'baseModels'

    static fields () {
        return {
            id: this.attr(null),

            station_alert_id: this.attr(null),
            station_type_id: this.attr(null),
            net_id: this.attr(null),
            alert_id: this.attr(null),

            name: this.string(''),
            city: this.string(''),
            latitude: this.attr(null),
            longitude: this.attr(null),

            active: this.boolean(false),
            primary: this.boolean(true),
            visible: this.boolean(true),
            distance: this.attr(null),

            tracking_values: this.boolean(false),
            secondary_calculate: this.attr(null),
            rainfall: this.attr(null),
            water_level: this.attr(null),
            rainfall_recovered: this.attr(null),
            indicator_value: this.attr(null),
            indicator_previous_difference: this.attr(null),
            alert_level: this.attr(null),
            alert_tag: this.attr(null),
            alert_status: this.attr(null),
            date_time_homogenization: this.attr(null),
            error: this.attr(null),
            comment: this.attr(null),

            net: this.hasManyBy(Net,'net_id','id'),
            stationType: this.hasManyBy(StationType,'station_type_id','id')
        }
    }

    /**
     * get latitude and longitude in LatLng leaflet format
     */
    get completeCoordinates(){
        return latLng(this.latitude,this.longitude)
    }
}