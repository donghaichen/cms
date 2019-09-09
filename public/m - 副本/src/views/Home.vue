<template>
    <div class="home">
        <div class="header container">
            <Col span="6">
                <div class="menu-center">
                    <Icon v-if="isHome" type="ios-menu"  @click="sidebar = true"/>
                    <Icon v-if="isHome === false" type="ios-arrow-back"  @click="$router.back(-1)"/>
                </div>
            </Col>
            <Col span="12">
                <div class="center">
                    <router-link to="home"><img :src="logo"></router-link>
                </div>
            </Col>
            <Col span="6">
                <div class="menu-right">
                    <a v-if="isHome" :href="'tel:' + tel" style="color: #515a6e;"><Icon  type="ios-call"/></a>
                    <Icon v-if="isHome === false" type="ios-menu"  @click="sidebar = true"/>
                </div>
            </Col>
        </div>
        <div class="sidebar">
            <Drawer r title="联系我们" placement="left" :closable="false" v-model="sidebar">
                <div v-html="contact.content"></div>
            </Drawer>
        </div>
        <div class="banner mb20">
            <Carousel autoplay v-model="banners" loop>
                <div v-for="(item, index) in banner" :key="index">
                    <CarouselItem v-if="item.type === 'banner'" >
                        <div class="demo-carousel"><img :src="static + item.src"></div>
                    </CarouselItem>
                </div>
            </Carousel>
        </div>

        <div class="container">
            <transition name="fade">
                <router-view></router-view>
            </transition>
        </div>
        <div class="footer navbar clear">
            <ul class="navbar-nav clear avg-sm-4">
                <li>
                    <router-link to="home">
                        <Icon type="md-home" />
                        <span class="navbar-label">首页</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="product">
                        <Icon type="ios-apps" />
                        <span class="navbar-label">案例中心</span>
                    </router-link>
                </li>

                <li>
                    <a :href="'tel:' + tel">
                        <Icon type="ios-call" />
                        <span class="navbar-label">电话咨询</span>
                    </a>
                </li>
                <li>
                    <a @click="gotoPage('view', {type:'page',id:26})">
                        <Icon type="ios-chatbubbles" />
                        <span class="navbar-label">联系我们</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
  export default {
    name: "Home",
    data () {
      return {
        static: this.api.common.static,
        isHome: true,
        contact_id: 26,
        logo: '',
        nav: {},
        banner: {},
        banners: 0,
        tel: '',
        contact: {},
        isShowAsideTitle: true, // 是否展示侧边栏内容
        sidebar: false,
        loading: false
      }
    },
    watch:{
      $route(now,old){     //监控路由变换，控制返回按钮的显示
        if(now.name === "index"){
          this.isHome = true;
        } else{
          this.isHome = false;
        }
      }
    },
    created: function () {
      this.getContact()
      this.http.get(this.api.common.home).then((response) => {
          let res = response.data.data
          let code = response.data.code
          if (code == 0)
          {
            this.logo = this.static + res.setting.h5_logo
            this.nav = res.nav
            this.banner = res.ads
            this.tel = res.setting.contact_tel
            document.title = res.setting.h5_seo_title
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
      }
      )
    },
    methods: {
      getContact: function()
      {
        this.http.get(this.api.common.view + '?type=page&id=' + this.contact_id).then((response) => {
            let res = response.data.data
            let code = response.data.code
            if (code == 0)
            {
              this.contact = res
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          }
        )
      },
      gotoPage: function (name, params = {}) {
        this.$router.push({  //核心语句
          name: name,   //跳转的路径
          params: params,   //跳转的参数
        })
      },
    }
  }
</script>

<style>
@import "../assets/css/app.css";

</style>