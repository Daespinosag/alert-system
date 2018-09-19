
export const StationAlertFilter = {
    methods: {
        processStationAlertFilter( station, alert ){

            let ban = false;

            if (alert === 'all'){ return true; }

            if (!(alert == null )){
                station.alerts.forEach(function (element) {
                    if (element.code === alert){ ban = true;}
                });
            }else { ban = true; }

            return ban;
        }
    }
}