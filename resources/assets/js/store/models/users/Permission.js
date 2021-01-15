import baseModel from "../baseModel";
import User from './User'
import UserPermission from './UserPermission'

export default class Permission extends baseModel {
    static entity = 'permissions'
    //static baseEntity = 'baseModel'

    static fields () {
        return {
            id: this.attr(null),
            name: this.string(''),
            code: this.string(''),
            type: this.string(''),

            users: this.belongsToMany(User,UserPermission,'permission_id','user_id'),
            //user_permission: this.hasMany(UserPermission,'permission_id')
        }
    }
}