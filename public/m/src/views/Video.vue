<template>
    <div class="">
        <div class="content-list clear">
<!--            <ul class="list-left" id="tab">-->
<!--                <li>-->
<!--                    <a @click="cate(all_id)">全部</a>-->
<!--                </li>-->
<!--                <li v-for="(item, index) in category" :key="index">-->
<!--                    <a @click="cate(item.id)">{{item.name}}</a>-->
<!--                </li>-->
<!--            </ul>-->
            <div class="list-right clear" style="width: 100%">
                    <Row style="margin: 0 -5px;">
                        <Col span="8" v-for="(item, index) in list" :key="index">
                            <div class="item">
                                <a @click="gotoPage('view', {type:'video',id:item.id})">
                                    <img :src="static + item.image">
                                    <h3 class="title">{{item.title}}</h3>
                                </a>
                            </div>
                        </Col>
                    </Row>
                <Page v-if="total > per_page" :total="total" show-total @on-change="changePage" />
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        all_id: 3,
        category_id: this.$route.params.id,
        static: this.api.common.static,
        total: 0,
        per_page: 0,
        list: {},
        category: {},
      }
    },
    computed: {
      params() {
        return{
          //这里先定义要传递给后台的数据
          //然后将每次请求20条的参数一起提交给后台
          pageSize: this.pageSize
        }
      }
    },
    created: function () {
      if (!this.category_id)
      {
        this.category_id = this.all_id
      }
      this.getData(this.category_id)

    },
    methods: {
      changePage (index) {
        this.list = this.getData(this.category_id, index);
      },
      getData: function(category_id, page = 1) {
        this.http.get(this.api.common.video + '?category_id=' + category_id + '&page= ' + page)
          .then((response) => {
            let res = response.data.data
            let code = response.data.code
            if (code == 0)
            {
              this.list = res.list.data
              this.total = res.list.total
              this.per_page = res.list.per_page
              console.log(res.list.data)
              this.category = res.category
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          }
        )
      },
      cate: function(id)
      {
        this.category_id = id === this.all_id ? this.all_id : id
        this.getData(id)
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

<style scoped>
    .list-content{ position: relative; overflow: hidden; }
    .list-left{ width: 25%; position: fixed;  height: 100%;  border-right: 1px solid #ddd; padding-bottom: 200px; overflow: auto;}
    .list-left li {width: 100%; height: 40px;
        line-height: 40px;
        text-align: left;
        padding-left: 10px;
        border-bottom: 1px solid #ddd;overflow: hidden;}
    .list-left li a{ color: #666;}
    .list-left .current{ background: #2f4864;}
    .list-left .current a{ color: #fff;}
    .list-right{ float: right; width: 75%;    padding-left: 20px; }
    .item img {
        width: 100%;
        height: 80px;}

</style>