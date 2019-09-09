<template>
    <div class="">
        <div class="article-pic">
            <img :src="static + data.image" v-if="data.image">
            <p class="article-tit">&nbsp;{{data.title}}</p>
            <p class="article-time" v-if="data.created_at">发布时间&nbsp;|&nbsp;{{data.created_at}} </p>
        </div>
        <video v-if="video" width="100%" height="auto" controls="">
            <source :src="static + data.src" type="video/mp4">
        </video>
        <div class="article" v-html="data.content">
        </div>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        static: this.api.common.static,
        data: {},
        video: false,
      }
    },
    watch:{

    },
    created: function () {
      let type = this.$route.params.type
      let id = this.$route.params.id
      if (!id)
      {
        // this.$Message.error('页面禁止刷新，将为您返回到首页');
        this.gotoPage('index')
        return false
      }
      this.http.get(this.api.common.view + '?type='+ type +'&id=' + id).then((response) => {
          let res = response.data.data
          let code = response.data.code
          if (code == 0)
          {
            this.data = res
            console.log(res.src)
            console.log(res.src.length)
            if (res.src.length > 0)
            {
              this.video = true
            }
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
      beforeunloadHandler (e) {
      }
    },
    mounted() {
      window.addEventListener('beforeunload', e => this.beforeunloadHandler(e))
    }
  }
</script>

<style scoped>

</style>