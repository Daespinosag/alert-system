export const displayModule = {
    state: {
        showFilters: false,
        showPopOut: false,
        existenceFiltersActive: false,
        alertsView: 'map',
        floodLayerVisible: true,
        landslideLayerVisible: true,
        floodIconsVisible: true,
        floodPolygonsVisible: true,
        landslideIconsVisible: true,
        landslidePolygonsVisible: false,
        soundAlertEnabled: true,
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
            commit ('setFloodIconsVisible', data.floodLayerVisible );
            commit ('setFloodPolygonsVisible', data.floodLayerVisible );
        },
        toggleLandslideLayerVisible({ commit }, data){
            commit ('setLandslideLayerVisible', data.landslideLayerVisible );
            commit ('setLandslideIconsVisible', data.landslideLayerVisible );
            commit ('setLandslidePolygonsVisible', data.landslideLayerVisible );
        },
        toggleFloodIconsVisible({ commit }, data){
            commit ('setFloodIconsVisible', data.floodIconsVisible );
        },
        toggleFloodPolygonsVisible({ commit }, data){
            commit ('setFloodPolygonsVisible', data.floodPolygonsVisible );
        },
        toggleLandslideIconsVisible({ commit }, data){
            commit ('setLandslideIconsVisible', data.landslideIconsVisible );
        },
        toggleLandslidePolygonsVisible({ commit }, data){
            commit ('setLandslidePolygonsVisible', data.landslidePolygonsVisible );
        },
        toggleSoundAlert({commit}, data){
            commit ("setSoundAlertEnabled", data.soundAlertEnabled);
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
        setFloodLayerVisible(state, show){
            state.floodLayerVisible = show;
        },
        setLandslideLayerVisible(state, show){
            state.landslideLayerVisible = show;
        },
        setFloodIconsVisible(state, show){
            state.floodIconsVisible = show;
        },
        setFloodPolygonsVisible(state, show){
            state.floodPolygonsVisible = show;
        },
        setLandslideIconsVisible(state, show){
            state.landslideIconsVisible = show;
        },
        setLandslidePolygonsVisible(state, show){
            state.landslidePolygonsVisible = show;
        },
        setSoundAlertEnabled(state, show){
            state.soundAlertEnabled = show;
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
        },
        getAlertsView(state){
            return state.alertsView;
        },
        getFloodLayerVisible(state){
            return state.floodLayerVisible;
        },
        getLandslideLayerVisible(state){
            return state.landslideLayerVisible;
        },
        getFloodIconsVisible(state){
            return state.floodIconsVisible;
        },
        getFloodPolygonsVisible(state){
            return state.floodPolygonsVisible;
        },
        getLandslideIconsVisible(state){
            return state.landslideIconsVisible;
        },
        getLandslidePolygonsVisible(state){
            return state.landslidePolygonsVisible;
        },
        getSoundAlertEnabled(state){
            return state.soundAlertEnabled;
        }
    }
}