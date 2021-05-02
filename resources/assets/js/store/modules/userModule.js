import loadAPI from "../../api/loadAPI";

import User from "../models/users/User";
import Permission from "../models/users/Permission";
import Role from "../models/users/Role";
import UserPermission from "../models/users/UserPermission";

export const userModule = {
    actions: {
        initUserInformation( { commit }, userId){
            return new Promise((resolve, reject) => {
                loadAPI.userInformation(userId)
                    .then( function( response ){
                        resolve([
                            commit('createModel', { model: User, data: response.data.user}),
                            commit('createModel', { model: Permission, data: response.data.permissions}),
                            commit('createModel',  { model : UserPermission , data : response.data.userPermissions}),
                            commit('createModel',  { model : Role , data : response.data.role}),
                        ])
                    })
                    .catch( function(error){
                        console.log('Error General')
                        reject(error)
                    })
            })
        }
    },
    mutations: {
        createModel(state, { model, data }) {
            model.commit((state) => { state.loadStatus = 1 })
            model.insert({ data })
                .then((response) => {
                    model.commit((state) => { state.loadStatus = 2})
                    console.log('Genial: '+ model.entity + ' se cargo correctamente')
                })
                .catch((error) => {
                    console.log(error)
                    model.commit((state) => { state.loadStatus = 3})
                    console.log('Oops: '+ model.entity + ' no se pudo cargar')
                })
        }
    },
    getters:{

    }
}