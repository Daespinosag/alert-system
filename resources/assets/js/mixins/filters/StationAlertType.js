
export const StationAlertType = {
    methods: {
        processStationTextFilter( station, type ){
            return station[type] === true;
        }
    }
}