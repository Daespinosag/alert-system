export const displayModule = {
    state: {
        showFilters: false,
        showPopOut: false,
        existenceFiltersActive: false,
        alertsView: 'map',
    },

    actions: {
        toggleShowFilters( { commit }, data ){
            commit( 'setShowFilters', data.showFilters );
        },

        toggleShowPopOut( { commit }, data ){
            commit( 'setShowPopOut', data.showPopOut );
        },
        changeAlertsView( { commit, state, dispatch }, view ){
            commit( 'setAlertsView', view );
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
        },
        setAlertsView(state,view){
            state.alertsView = view;
        },

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
        },
        getAlertsView(state){
            return state.alertsView;
        },
    }
}