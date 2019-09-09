<template>
    <div class="">
        <div class="news mb20">
            <Tabs  @on-click="handleTabRemove">
                <TabPane :name="'' + item.id" :label="item.name" v-for="(item, index) in category" :key="index">
                    <ul class="list">
                        <li v-for="(item, index) in list" :key="index">
                            <a @click="gotoPage('view', {type:'news',id:item.id})">{{item.title}}</a>
                            <span class="date">{{item.created_at.split(' ')[0]}}</span>
                        </li>
                    </ul>
                    <Page v-if="total > per_page" :total="total" show-total @on-change="changePage" />
                </TabPane>
            </Tabs>
        </div>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        category_id: 18,
        static: this.api.common.static,
        total: 0,
        per_page: 0,
        list: {},
        category: {},
      }
    },
    created: function () {
      this.getData(this.category_id)
    },
    methods: {
      handleTabRemove (name) {
        this.category_id = name
        this.getData(name)
        console.log(name)
      },
      changePage (index) {
        this.list = this.getData(this.category_id, index);
      },
      getData: function(category_id, page = 1) {
        this.http.get(this.api.common.news + '?category_id=' + category_id + '&page= ' + page)
          .then((response) => {
              let res = response.data.data
              let code = response.data.code
              if (code == 0)
              {
                this.total = res.list.total
                this.per_page = res.list.per_page
                this.list = res.list.data
                this.category = res.category
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

<style scoped>
    .list-left {
        width: 25%;
        position: absolute;
        height: auto;
        border-right: 1px solid #ddd;
        /* padding-bottom: 200px; */
        overflow: auto;
    }
</style>