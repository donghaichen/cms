import axios from 'axios'


// http request 拦截器
axios.interceptors.request.use(
  config => {
    // Spin.show();
    // if (store.state.token) {  // 判断是否存在token，如果存在的话，则每个http header都加上token
    //   config.headers.Authorization = `token ${store.state.token}`;
    // }
    return config;
  },
  err => {
    return Promise.reject(err);
  });
