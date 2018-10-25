export const display = {
    state: {
        showFilters: false,
        showPopOut: false,
        existenceFiltersActive: false,
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
        },

        setExistenceFiltersActive(state,show){
            state.existenceFiltersActive = show;
        }

    },

    getters: {
        getShowFilters( state ){
            return state.showFilters;
        },

        getShowPopOut( state ){
            return state.showPopOut;
        },

        getExistenceFiltersActive(state){
            return state.existenceFiltersActive;
        }

    }
}