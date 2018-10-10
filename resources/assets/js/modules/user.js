/*
|-------------------------------------------------------------------------------
| VUEX modules/stations.js
    * estado = 0 -> no se ha iniciado ninguna carga
    * estado = 1 -> la carga ha comenzado
    * estado = 2 -> carga completada con éxito
    * estado = 3 -> carga completada sin éxito
|-------------------------------------------------------------------------------
| The Vuex data store for the stations
*/
import UserAPI from '../api/user.js';

export const user = {
    state: {
        user: [],
        userLoadStatus: 0,
    },
    actions: {
        loadUser( { commit } ){
            commit( 'setUserLoadStatus', 1 );

            UserAPI.getUser()
                .then( function( response ){
                    commit( 'setUser', response.data );
                    commit( 'setUserLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setUser', [] );
                    commit( 'setUserLoadStatus', 3 );
                });
        }
    },
    mutations: {
        setUserLoadStatus( state, status ){
            state.userLoadStatus = status;
        },

        setUser( state, user ){
            state.user = user;
            state.userLoadStatus = 2;
        }
    },
    getters: {
        getUserLoadStatus( state ){
            return state.userLoadStatus;
        },

        getUser( state ){
            return state.user;
        },

        getAlertPermissions( state ){
            return state.user.permissions
        }
    },
}