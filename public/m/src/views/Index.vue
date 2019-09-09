<template>
    <div class="index">
        <div class="dh clear">
<!--            <ul>-->
<!--                <li class="h200" @click="gotoPage('view', {type:'page',id:1})"><Icon type="ios-at" /><span>关于我们</span></li>-->
<!--                <a  @click="gotoPage('view', {type:'page',id:28})"><li><Icon type="logo-buffer" /><span>主营业务</span></li></a>-->
<!--                <a  @click="gotoPage('news')"><li><Icon type="ios-book" /><span>新闻资讯</span></li></a>-->
<!--                <a  @click="gotoPage('product')"><li><Icon type="ios-basket" /><span>案例中心</span></li></a>-->
<!--                <a @click="gotoPage('video')"><li><Icon type="ios-easel" /><span>视频中心 </span></li></a>-->
<!--                <a @click="gotoPage('view', {type:'page',id:26})"><li><Icon type="ios-contacts" /><span>联系我们</span></li></a>-->
<!--                <a :href="'tel:' + tel"><li class="w200"><Icon type="ios-headset" /><span>电话咨询</span></li></a>-->
<!--            </ul>-->
        </div>
        <div class="product mb20">
            <div class="titlebar">
                <h2 class="title ">产品中心</h2>
                <nav class="nav">
                    <router-link to="product">更多 »</router-link>
                </nav>
            </div>
            <Row style="margin: 0 -5px;">
                <Col span="12" v-for="(item, index) in product" :key="index">
                    <div class="item">
                        <a @click="gotoPage('view', {type:'product',id:item.id})">
                            <img :src="static + item.image">
                            <h3 class="title">{{item.title}}</h3>
                        </a>
                    </div>
                </Col>
            </Row>
        </div>
        <div class="news mb20">
            <div class="titlebar">
                <h2 class="title ">  新闻资讯 </h2>
                <nav class="nav">
                    <router-link to="news">更多 »</router-link>
                </nav>
            </div>
            <ul class="list">

                <li v-for="(item, index) in news" :key="index">
                    <a @click="gotoPage('view', {type:'news',id:item.id})">{{item.title}}</a>
                    <span class="date">{{item.created_at.split(' ')[0]}}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        static: this.api.common.static,
        product: {},
        news: {},
        tel: '',
      }
    },
    created: function () {
      // this.loading = true
      this.http.get(this.api.common.home).then((response) => {
          let res = response.data.data
          let code = response.data.code
          if (code == 0)
          {
            this.product = res.product
            this.news = res.news
            this.tel = res.setting.contact_tel
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        }
      )
    },
    methods: {
      gotoPage: function (name, params = {}) {
        this.$router.push({  //核心语句
          name: name,   //跳转的路径
          params: params,   //跳转的参数
        })
      },
    }
  }
</script>

<style scoped>

</style>