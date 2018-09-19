
export const StationTypeFilter = {
    methods: {
        processStationTypeFilter( station, types ){
            if (types.length === 0){ return true;}

            var item = station.type_station;

            if (item != null){
                if (types.indexOf(item.code) >= 0 ){return true;}
            }

            return false;
        }
    }
}