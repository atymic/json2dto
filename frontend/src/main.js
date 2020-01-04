import Vue from 'vue'
import App from './App.vue'

import "./main.css";
import 'codemirror/lib/codemirror.css'

Vue.config.productionTip = false


new Vue({
  render: h => h(App),
}).$mount('#app')
