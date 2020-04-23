import { Model } from '@vuex-orm/core'
/*
|-------------------------------------------------------------------------------
    * loadStatus = 0 -> No se ha iniciado ninguna carga
    * loadStatus = 1 -> La carga ha comenzado
    * loadStatus = 2 -> Carga completada con éxito
    * loadStatus = 3 -> Carga completada sin éxito
|-------------------------------------------------------------------------------
*/

export default class baseModel extends Model {
    static entity = 'baseModel'

    static state ()  {
        return {
            loadStatus: 0
        }
    }
}