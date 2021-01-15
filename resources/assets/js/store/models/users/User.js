import baseModel from "../baseModel";
import UserPermission from './UserPermission'
import Permission from './Permission'
import Role from './Role'

export default class User extends baseModel {
    static entity = 'users'
    //static baseEntity = 'baseModel'

    static fields () {
        return {
            id: this.attr(null),
            role_id: this.attr(null),
            name: this.string(''),
            institution: this.string(''),
            email: this.string(''),
            address: this.attr({}),
            confirmed: this.boolean(false),
            confirmed_code: this.attr(null),
            accepted: this.boolean(false),

            role: this.hasManyBy(Role,'role_id','id'),
            permissions: this.belongsToMany(Permission,UserPermission,'user_id','permission_id'),
            //user_permissions: this.hasMany(UserPermission,'user_id')
        }
    }
}