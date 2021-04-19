export const displayModule = {
    state: {
        showFilters: false,
        showPopOut: false,
        existenceFiltersActive: false,
        alertsView: 'map',
        floodLayerVisible: true,
        landslideLayerVisible: true,
    },

    actions: {
        toggleShowFilters( { commit }, data ){
            commit( 'setShowFilters', data.showFilters );
        },
        toggleShowAlertInfo( { commit }, data){
            commit( 'setShowAlertInfo', data.showAlertInfo);
        },
        toggleShowPopOut( { commit }, data ){
            commit( 'setShowPopOut', data.showPopOut );
        },
        changeAlertsView( { commit, state, dispatch }, view ){
            commit( 'setAlertsView', view );
        },
        toggleFloodLayerVisible({ commit }, data){
            commit ('setFloodLayerVisible', data.floodLayerVisible );
        },
        toggleLandslideLayerVisible({ commit }, data){
            commit ('setLandslideLayerVisible', data.landslideLayerVisible );
        },
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
        setFloodLayerVisible(state, show){
            state.floodLayerVisible = show;
        },
        setLandslideLayerVisible(state, show){
            state.landslideLayerVisible = show;
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
        getFloodLayerVisible(state){
            return state.floodLayerVisible;
        },
        getLandslideLayerVisible(state){
            return state.landslideLayerVisible;
        }
    }
}