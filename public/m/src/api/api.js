const BASE_URL = 'http://www.beijingmaotaijiu.com/m/v1/'
// const BASE_URL = 'http://www.cms.com/m/v1/'
export default {
  common: {
    static: 'http://www.beijingmaotaijiu.com',
    // static: 'http://www.cms.com',
    home: BASE_URL + 'home',
    product: BASE_URL + 'product',
    video: BASE_URL + 'video',
    news: BASE_URL + 'news',
    view: BASE_URL + 'view',

  },
  pwd: {
    sendEmail: BASE_URL + '/manage/user/sendEmail',
    resetPwd: BASE_URL + '/manage/user/passwordReset'
  }
}