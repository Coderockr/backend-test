import Vue from 'vue';
import utils from '../../utils'
import vxNotifications from './index.vue'

const NotiConstructor = Vue.extend(vxNotifications);


let instance;

export default {name:'notify',vxfunction(parameters){
  if(parameters.fullWidth){
    if(parameters.position) {
      parameters.position = parameters.position.replace('right','left')
    }
  }


  instance = new NotiConstructor({
    data: parameters,
  });
  instance.vm = instance.$mount();
  parameters.click?instance.vm.$on('click',parameters.click):null
  utils.insertBody(instance.vm.$el);
}
}
