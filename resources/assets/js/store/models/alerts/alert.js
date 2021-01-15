import baseModel from "../baseModel";

export default class Alert extends baseModel {
    static entity = 'alerts'
    //static baseEntity = 'baseModel'

    static fields () {
        return {
            id: this.attr(null),
            name: this.string(''),
            code: this.string(''),
            active: this.boolean(false),
            limitRed: this.attr(''),
            icon: this.string(''),
        }
    }
}