import baseModel from "../baseModel";
//import Permission from "./Permission";
//import User from "./User";

export default class UserPermission extends baseModel {
    static entity = 'user_permissions'
    //static baseEntity = 'baseModel'

    static fields () {
        return {
            id: this.attr(null),
            user_id: this.attr(null),
            permission_id: this.attr(null),
            active: this.boolean(false),
            active_email: this.boolean(false),

            //permissions: this.belongsTo(Permission, "permission_id"),
            //user: this.belongsTo(User, "user_id")
        }
    }
}