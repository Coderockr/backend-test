// Functions

import vxNotifications from './vxNotifications/index.js'
import vxLoading from './vxLoading/index.js'
import vxDialog from './vxDialog/index.js'

const vxFunctions = {
  vxNotifications,
  vxLoading,
  vxDialog
}

export default vm => {
  Object.values(vxFunctions).forEach((vxFunctions) => {
    if(vxFunctions.hasOwnProperty('subName')){
      vm.$vx[vxFunctions.name][vxFunctions.subName] = vxFunctions.vxfunction
    } else {
      vm.$vx[vxFunctions.name] = vxFunctions.vxfunction
    }
  })

  vm.$vx.loading.close = vxLoading.close
}
