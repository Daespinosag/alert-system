import { Database } from '@vuex-orm/core';
const database = new Database();

import Role from './models/users/Role';
import User from './models/users/User';
import Permission from './models/users/Permission';
import UserPermission from './models/users/UserPermission';

import { users } from './modules/users/users';
import { roles } from './modules/users/roles';
import { permissions } from "./modules/users/permissions";
import { userPermissions } from "./modules/users/userPermissions";

import stationType from "./models/alerts/stationType";
import net from "./models/alerts/net";

import { stationTypes } from "./modules/alerts/stationTypes";
import { nets } from "./modules/alerts/nets";

database.register( User, users );
database.register( Role , roles );
database.register( Permission , permissions );
database.register( UserPermission , userPermissions );
database.register( stationType , stationTypes );
database.register( net , nets );

import floodAlert from "./models/alerts/flood/floodAlert";
import floodStation from "./models/alerts/flood/floodStation";
import Basin from "./models/alerts/flood/Basin";

import { basins } from "./modules/alerts/flood/basins";
import { floodAlerts } from "./modules/alerts/flood/floodAlerts";
import { floodStations } from "./modules/alerts/flood/floodStations";

database.register( Basin , basins );
database.register( floodAlert , floodAlerts );
database.register( floodStation , floodStations );

import Zone from "./models/alerts/landslide/zone";
import LandslideAlert from "./models/alerts/landslide/landslideAlert";
import LandslideStation from "./models/alerts/landslide/landslideStation";

import { zones } from "./modules/alerts/landslide/zones";
import { landslideAlerts } from "./modules/alerts/landslide/landslideAlerts";
import { landslideStations } from "./modules/alerts/landslide/landslideStations";

database.register( Zone , zones );
database.register( LandslideAlert , landslideAlerts );
database.register( LandslideStation , landslideStations );

export default database;