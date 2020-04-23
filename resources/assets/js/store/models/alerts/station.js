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