export const display = {
    state: {
        showFilters: false,
        showPopOut: false,
    },

    actions: {
        toggleShowFilters( { commit }, data ){
            commit( 'setShowFilters', data.showFilters );
        },

        toggleShowPopOut( { commit }, data ){
            commit( 'setShowPopOut', data.showPopOut );
        }
    },

    mutations: {
        setShowFilters( state, show ){
            state.showFilters = show;
        },

        setShowPopOut( state, show ){
            state.showPopOut = show;
        }
    },

    getters: {
        getShowFilters( state ){
            return state.showFilters;
        },

        getShowPopOut( state ){
            return state.showPopOut;
        }
    }
}