import { injectDirectionClass } from "../utils/rtl";
import vsFunctions from '../functions'
/**
 * Vuesax global mixin, all vueasx functions and properties injected
 * in the @beforeCreate hook.
 */

export default (Vue, options) => {
  Vue.mixin({
    watch: {
      '$vx.rtl': {
        handler(val) {
          injectDirectionClass(val)
        }
      }
    },
    beforeCreate() {
      // create $vx property if not exist
      if(!this.$vx) {
        // define $vx reactive properties
        this.$vx = Vue.observable(options);
        // define $vx functions
        vsFunctions(this);
      }
    },
    mounted() {
      // inject the direction class for the initial options
      injectDirectionClass(this.$vx.rtl)
    }
  })
};
