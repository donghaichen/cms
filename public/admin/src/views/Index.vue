<template>
  <div class="index-vue">
    <!-- 侧边栏 -->
    <aside :class="asideClassName">
      <!-- logo -->
      <div class="logo-c">
        <img src="@/assets/img/logo-tran.png" alt="logo" class="logo">
        <span v-show="isShowAsideTitle">后台管理系统</span>
      </div>
      <!-- 菜单栏 -->
      <Menu ref="asideMenu" theme="dark" width="100%">

          <!-- 动态菜单 -->
          <div v-for="(item, index) in menu" :key="index">
              <Submenu v-if="item.children" :name="index">
                  <template slot="title">
                      <Icon :size="item.size" :type="item.type"/>
                      <span v-show="isShowAsideTitle">{{item.text}}</span>
                  </template>
                  <div v-for="(subItem, i) in item.children" :key="index + i">
                      <Submenu v-if="subItem.children" :name="index + '-' + i">
                          <template slot="title" >
                              <Icon :size="subItem.size" :type="subItem.type"/>
                              <span v-show="isShowAsideTitle">{{subItem.text}}</span>
                          </template>
                      </Submenu>
                      <MenuItem v-else v-show="isShowAsideTitle" :name="subItem.name" @click.native="gotoPage(subItem.name)">
                          <Icon :size="subItem.size" :type="subItem.type"/>
                          <span v-show="isShowAsideTitle">{{subItem.text}}</span>
                      </MenuItem>
                  </div>
              </Submenu>
              <MenuItem v-else :name="item.name" @click.native="gotoPage(item.name)">
                  <Icon :size="item.size" :type="item.type" />
                  <span v-show="isShowAsideTitle">{{item.text}}</span>
              </MenuItem>
          </div>
<!--        <Submenu name="1">-->
<!--          <template slot="title">-->
<!--            <Icon type="ios-paper" />-->
<!--            内容管理-->
<!--          </template>-->
<!--          <MenuItem name="1-1"><router-link :to="{name: 'mobile', params: { id: 1 } }"><Icon type="ios-paper" />item 1</router-link></MenuItem>-->
<!--        </Submenu>-->
<!--        <MenuItem name="2">-->
<!--          <Icon type="md-document" />-->
<!--          文章管理-->
<!--        </MenuItem>-->
      </Menu>
    </aside>
    <!-- 右侧部分 -->
    <section class="sec-right">
        <!-- 头部 -->
        <div class="top-c">
            <header>
                <div class="h-left">
                    <!-- 面包屑功能 -->
                    <p class="crumbs"></p>
                </div>
                <div class="h-right">
                    <!-- 消息 -->
                    <div class="notice-c" title="查看新消息">
                    </div>
                    <!-- 用户头像 -->
                    <div class="user-img-c">
                        <img :src="userImg">
                    </div>
                    <!-- 下拉菜单 -->
                    <Dropdown trigger="click" @on-click="userOperate" @on-visible-change="showArrow">
                        <div class="pointer">
                            <span>Hi, {{userName}}</span>
                            <Icon v-show="arrowDown" type="md-arrow-dropdown"/>
                            <Icon v-show="arrowUp" type="md-arrow-dropup"/>
                        </div>
                        <DropdownMenu slot="list">
                            <!-- name标识符 -->
                            <DropdownItem name="userInfo">基本资料</DropdownItem>
                            <DropdownItem name="userUpdate">修改密码</DropdownItem>
                            <DropdownItem divided name="login">退出登陆</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
        </header>
            <Drawer :closable="false" width="640" v-model="drawer">
<!--                <p :style="pStyle">用户详情</p>-->
                <p :style="pStyle">基本信息</p>
                <div class="drawer-profile">
                    <Row>
                        <Col span="12">
                            <Tag type="border">用户名 : </Tag>{{user.username}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">头像  : </Tag><Avatar :src="imgUrl(user.avatar)" icon="ios-person" />
                        </Col>
                        <Col span="12">
                            <Tag type="border">昵称  : </Tag>{{user.nickname}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">邮箱  : </Tag>{{user.email}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">用户状态 : </Tag>
                            <Tag type="dot" color="success" v-if="user.status == 1">正常</Tag>
                            <Tag type="dot" color="warning" v-if="user.status == 2">禁用</Tag>
                            <Tag type="dot" color="error" v-if="user.status !== 1 && user.status !== 2">未知</Tag>
                        </Col>
                        <Col span="12">
                            <Tag type="border">注册时间 : </Tag>{{user.created_at}}
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <Tag type="border">注册IP : </Tag>{{user.created_ip}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">更新时间 : </Tag>{{user.updated_at}}
                        </Col>
                    </Row>

                </div>
                <Divider />
                <p :style="pStyle">个人简介</p>
                <div class="drawer-profile">
                    <Row>
                        <Col span="24" class="desc">
                            {{user.desc}}
                        </Col>
                    </Row>
                </div>
                <Divider />
                <p :style="pStyle">联系方式</p>
                <div class="drawer-profile">
                    <Row>
                        <Col span="12">
                            <Tag type="border">手机号 : </Tag>{{user.mobile}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">个人主页: </Tag>{{user.url}}
                        </Col>
                    </Row>
                </div>
            </Drawer>
        <!-- 标签栏 -->
        <div class="div-tags">
        </div>
      </div>
      <!-- 页面主体 -->
      <div class="main-content">
          <!-- 子页面 -->
          <transition name="fade">
              <router-view></router-view>
          </transition>
          <div class="loading-c" v-show="showLoading">
              <Spin size="large"></Spin>
      </div>
          <footer class="layout-foote ivu-global-footer i-copyright">
              <div class="ivu-global-footer-copyright">
                  Code based on Vue, iView development,
                  <a class="a-style" @click="link('https://github.com/donghaichen')">
                      view more to github <Icon type="ios-heart" /></a>
              </div>
          </footer>
      </div>
    </section>
  </div>
</template>

<script>
import avatar from '@/assets/img/avatar.png'
  export default {
    data () {
      return {
        drawer: false,
        user: JSON.parse(localStorage.getItem('userInfo')),
        pStyle: {
          fontSize: '16px',
          color: 'rgba(0,0,0,0.85)',
          lineHeight: '24px',
          display: 'block',
          marginBottom: '16px'
        },
        avatar: avatar,
        menu: [],
        theme: 'dark',
        isShowRouter: true,
        showLoading: false, // 是否显示loading
        // 用于储存页面路径
        paths: {},
        // 当前显示页面
        currentPage: '',
        openMenus: [], // 要打开的菜单名字 name属性
        menuCache: [], // 缓存已经打开的菜单
        asideClassName: 'aside-big', // 控制侧边栏宽度变化
        userName: '',
        userImg: '',
        isShowAsideTitle: true, // 是否展示侧边栏内容
        arrowUp: false, // 用户详情向上箭头
        arrowDown: true, // 用户详情向下箭头
      }
    },
    created: function () {
      console.log(this.user)
      this.menu = JSON.parse(localStorage.getItem('menu'))
    },
    watch: {
      // '$route' (to, from) {
      //   this.$router.go(0);
      // }
    },
    methods: {
      link (url) {
        window.open(url,'_blank') // 新窗口打开外链接
      },
      // 用户操作
      userOperate(name) {
        switch(name) {

          case 'login':
            // 退出登陆 清除用户资料
            localStorage.setItem('token', '')
            this.$router.replace({name})
            break
          case 'userUpdate':
            this.gotoPage(name,{
              uid:localStorage.getItem('uid')
            })
            break
          case 'userInfo':
            this.drawer = true
            break
        }
      },
      // 控制用户三角箭头显示状态
      showArrow(flag) {
        this.arrowUp = flag
        this.arrowDown = !flag
      },
      gotoPage: function(name, params = {}){
        this.showLoading = true
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          params:params,   //跳转的参数
        })
        this.showLoading = false
      },
      imgUrl(src)
      {
        if(src.length <= 0)
        {
          return false
        }
        if(src.substr(0,4).toLowerCase() != "http")
        {
          src = this.api.static.image + src;
        }
        return src
      },
    },
    mounted() {
      // 设置用户信息
      this.userName = localStorage.getItem('userName')
      let userImg = localStorage.getItem('userImg')
      this.userImg = this.imgUrl(userImg)
    }
  }
</script>

<style>
    .ivu-menu-dark{
        background: transparent;
    }
    ul.ivu-menu li ul div{
        margin-left: 10px;
    }
    .ivu-menu-dark.ivu-menu-vertical .ivu-menu-opened .ivu-menu-submenu-title {
        background: #13182b;
    }
    .ivu-menu-dark.ivu-menu-vertical .ivu-menu-opened {
        background: #13182b;
    }
    .ivu-menu-dark.ivu-menu-vertical .ivu-menu-item:hover, .ivu-menu-dark.ivu-menu-vertical .ivu-menu-submenu-title:hover {
        color: #2d8cf0;
        background: #13182b;
    }
    .ivu-global-footer {
        margin: 0;
        padding: 24px 16px;
        text-align: center;
    }
    .i-copyright {
        flex: 0 0 auto;
    }
  .index-vue {
    height: 100%;
    display: flex;
    justify-content: space-between;
    color: #666;
  }
  /* 侧边栏 */
  aside {
    min-width: 80px;
    background: #20222A;
    height: 100%;
    transition: all .5s;
    overflow: auto;
  }
  .logo-c {
    display: flex;
    align-items: center;
    color: rgba(255,255,255,.8);
    font-size: 16px;
    margin: 20px 0;
    justify-content: center;
  }
  .logo {
    width: 40px;
    margin-right: 10px;
  }
  .aside-big {
    min-width: 220px;
  }
  /* 主体页面 */
  .sec-right {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    transition: width .5s;
  }
  /* 主体页面头部 */
  .top-c {
    background: rgba(230,230,230,.5);
    width: 100%;
  }
  header {
    height: 50px;
    border-bottom: none;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,.05);
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-right: 40px;
    padding-left: 10px;
    font-size: 14px;
  }
  header .ivu-icon {
    font-size: 24px;
  }
  .refresh-c {
    margin: 0 30px;
    cursor: pointer;
  }
  .h-right {
    display: flex;
    align-items: center;
  }
  .h-left {
    display: flex;
    align-items: center;
  }
  .user-img-c img {
    width: 100%;
  }
  .notice-c {
    cursor: pointer;
    position: relative;
  }
  .newMsg {
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #FF5722;
    right: 0;
    top: 0;
  }
  .user-img-c {
    width: 34px;
    height: 34px;
    background: #ddd;
    border-radius: 50%;
    margin: 0 5px 0 20px;
    overflow: hidden;
  }
  .tag-options {
    cursor: pointer;
    position: relative;
  }
  .div-tags {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .div-icons {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    background: #fff;
    height: 34px;
    width: 160px;
    font-size: 18px;
  }
  /* 标签栏 */
  .ul-c {
    height: 34px;
    margin-top: 2px;
    background: #fff;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    padding: 0 10px;
    overflow: hidden;
    width: calc(100% - 160px);
  }
  .ul-c li {
    border-radius: 3px;
    cursor: pointer;
    font-size: 12px;
    height: 24px;
    padding: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 3px 5px 2px 3px;
    border: 1px solid #e6e6e6;
  }
  a {
    color: #666;
    transition: none;
  }
  .li-a {
    max-width: 80px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .ul-c .ivu-icon {
    margin-left: 6px;
  }
  .active {
    background: #409eff;
    border: 1px solid #409eff;
  }
  .active a {
    color: #fff;
  }
  .active .ivu-icon {
    color: #fff;
  }
  /* 主要内容区域 */
  .main-content {
    overflow: auto;
    height: 100%;
    width: 100%;
    background: #eee;
    padding: 20px;
  }
   .content {
       padding-right: 20px;
    }
    .content .card_search {
        margin-bottom: 20px;
    }
    .content .card_search .ivu-card{
        background: rgba(255, 255, 255, .6);
    }
  .pointer {
    cursor: pointer;
  }
  /* loading */
  .loading-c {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    position: absolute;
    background: rgba(255,255,255,.5);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .mask {
    position: fixed;
    background: #eee;
    height: 10px;
    width: 100%;
    top: 85px;
    z-index: 10;
  }
  .crumbs {
    margin-left: 10px;
    color: #97a8be;
    cursor: default;
  }
  .menu-level-3 .ivu-icon {
    font-size: 18px;
  }
  .ivu-menu-vertical .ivu-menu-item, .ivu-menu-vertical .ivu-menu-submenu-title {
    padding: 10px 24px!important;
  }
.content h1{
    padding-bottom: 20px;
    margin-bottom: 30px;
    border-bottom: 1px solid rgb(215, 215, 215);
}
.content h1 a {
    float: right;
    display: inline-block;
}
/*
用户详情
*/
.drawer-profile{
    font-size: 14px;
}
.drawer-profile .ivu-col{
    margin-bottom: 12px;
}
.drawer-profile .desc{
    background-color: #f3f3f3;
    width: 100%;
    font-size: 13px;
    color: #666666;
    overflow: auto;
    height: 196px;
    padding: 20px;
}
.drawer-profile .ivu-col .ivu-tag-border {
    border: none;
    width: 70px;
}
</style>
