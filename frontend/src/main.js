import Vue from 'vue'
import App from './App.vue'
import VueClipboard from 'vue-clipboard2'

import "./main.css";
import 'codemirror/lib/codemirror.css'

Vue.config.productionTip = false
Vue.use(VueClipboard)

window.isJsonString = (str) => {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

new Vue({
  render: h => h(App),
}).$mount('#app')
