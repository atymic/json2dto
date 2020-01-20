import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import VueClipboard from 'vue-clipboard2'

import "./main.css";
import 'codemirror/lib/codemirror.css'
import Converter from './components/Converter'
import CliDocs from './components/CliDocs'

Vue.config.productionTip = false
Vue.use(VueClipboard)
Vue.use(VueRouter)

window.isJsonString = (str) => {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

const router = new VueRouter({
  mode: 'history',
  routes: [
    { path: '/', component: Converter },
    { path: '/cli', component: CliDocs }
  ]
})

new Vue({
  render: h => h(App),
  router
}).$mount('#app')
