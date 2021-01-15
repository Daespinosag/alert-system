import baseModel from "../baseModel";

import User from "./User";

export default class Role extends baseModel {
    static entity = 'role'
    //static baseEntity = 'baseModel'

    static fields () {
        return {
            id: this.attr(null),
            name: this.string(''),
            code: this.string(''),
            description: this.string(''),

            users : this.hasMany(User,'role_id','id')
        }
    }
}