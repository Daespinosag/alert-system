
export const StationTextFilter = {
    methods: {
        processStationTextFilter( searchArray, text ){
            if (text.length > 0){
                let i = 0;
                while (i < searchArray.length){
                    if (searchArray[i].toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*')){
                        return true;
                    }
                    i++;
                }
            }
            else{
                return true;
            }
            return false;
        }
    }
};