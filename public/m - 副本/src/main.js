import Vue from 'vue'
import App from './App.vue'
import router from './router'
import iView from 'iview';
import 'iview/dist/styles/iview.css';
import './permission'

/****** 全局注册axios ******/
import axios from 'axios'
import api from './api/api'
import './api/http'
Vue.prototype.api = api
Vue.prototype.http = axios
Vue.use(iView);

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
