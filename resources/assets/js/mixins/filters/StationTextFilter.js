
export const StationTextFilter = {
    methods: {
        processStationTextFilter( station, text ){

            if (text.length > 0){
                if ((station.name !== null)){
                    if (station.name.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )){ return true; }
                }

                if ((station.netName !== null)){
                    if (station.netName.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )){ return true; }
                }

                if ((station.city !== null)){
                    if (station.city.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )){ return true; }
                }

                if ((station.localization !== null)){
                    if (station.localization.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )){ return true; }
                }
            }else {
                return true;
            }
            return false;
        }
    }
}