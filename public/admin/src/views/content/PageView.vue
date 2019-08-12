<template>
    <div class="content">
        <Card :bordered="false">
            <p slot="title"></p>
            <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                <FormItem label="上级页面" prop="parent_id">
                    <Select v-model="formItem.parent_id" filterable>
                        <Option :value="0" :key="0">无上级 </Option>
                        <Option v-for="item in parentList" :value="item.id" :key="item.id">{{ item.mark + item.title}} </Option>
                    </Select>
                </FormItem>
                <FormItem label="标题" prop="title">
                    <Input v-model="formItem.title" placeholder=""></Input>
                </FormItem>
                <FormItem label="别名" prop="slug">
                    <Input v-model="formItem.slug" placeholder=""></Input>
                    <p>别名必须为英文，多个词用采用（中划线，下划线，大小写）组合 例如（test-category, test_category, testCategory）</p>
                </FormItem>
                <FormItem label="SEO 标题" prop="meta_title">
                    <Input v-model="formItem.meta_title" placeholder=""></Input>
                </FormItem>
                <FormItem label="SEO 关键词" prop="keywords">
                    <Input v-model="formItem.keywords" placeholder=""></Input>
                </FormItem>
                <FormItem label="SEO 描述" prop="description">
                    <Input v-model="formItem.description" placeholder=""></Input>
                </FormItem>
                <FormItem label="内容" prop="content">
                    <tinymce-editor ref="editor"
                                    v-model="formItem.content"
                    >
                    </tinymce-editor>
                </FormItem>

                <FormItem>
                    <FormItem>
                        <Button type="primary" @click="handleSubmit('formItem')">保存</Button>
                        <Button @click="handleReset('formItem')" style="margin-left: 8px">重置</Button>
                    </FormItem>
                </FormItem>
            </Form>
        </Card>
    </div>
</template>

<script>
  import TinymceEditor from '../../components/Tinymce'
  // string: Must be of type string. This is the default type.
  //   number: Must be of type number.
  //   boolean: Must be of type boolean.
  //   method: Must be of type function.
  // regexp: Must be an instance of RegExp or a string that does not generate an exception when creating a new RegExp.
  // integer: Must be of type number and an integer.
  //   float: Must be of type number and a floating point number.
  //   array: Must be an array as determined by Array.isArray.
  //   object: Must be of type object and not Array.isArray.
  //   enum: Value must exist in the enum.
  // date: Value must be valid as determined by Date
  // url: Must be of type url.
  //   hex: Must be of type hex.
  //   email: Must be of type email
  export default {
    components: {
      TinymceEditor
    },
    data () {
      return {
        parentList: [],
        formItem: {
          id: 0,
          title: '',
          slug: '',
          meta_title: '',
          keywords: '',
          description: '',
          parent_id: [0],
        },
        ruleValidate: {
          title: [
            { required: true, message: '标题不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '标题必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 200, message: '标题必须小于200 个字', trigger: 'blur' },
          ],
          slug: [
            { required: true, message: '别名不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '别名必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 50, message: '别名称必须小于 50 个字', trigger: 'blur' },
            { type: "string", pattern: /^[a-zA-Z0-9_-]+$/, message: '别名不能包含特殊字符和中文（英文下划线 中划线 - _ 除外）', trigger: 'blur' }
          ],
          content: [
            { required: true, message: '内容不能为空', trigger: 'blur' },
          ],
        }
      }
    },
    created() {
      //option 下拉框
      this.$axios.get(this.api.content.page)
        .then((response) => {
          if (response.data.code == 0)
          {
            this.parentList = response.data.data
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        })
      //表单内容
      if (this.$route.params.id > 0)
      {
        this.$axios.get(this.api.content.pageView + '/' + this.$route.params.id)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.formItem = response.data.data
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          })
      }
    },
    methods: {
      // 清空内容
      clear () {
        this.$refs.editor.clear()
      },
      format (labels, selectedData) {
        const index = labels.length - 1;
        const data = selectedData[index] || false;
        if (data && data.code) {
          return labels[index] + ' - ' + data.code;
        }
        return labels[index];
      },
      query(data) {
        var url = this.api.user.view;
        this.$axios.post(url, data)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.$Message.success('操作成功');
            }else{
              this.$Message.error(response.data.msg);
            }
          })
      },
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            var url = this.api.content.pageSave;
            this.$axios.post(url, this.formItem)
              .then((response) => {
                if (response.data.code == 0)
                {
                  this.$Message.success('操作成功');
                  this.handleReset(name)
                }else{
                  this.$Message.error(response.data.msg);
                }
              })
          } else {
            this.$Message.error('请正确输入表单内容');
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      }
    }
  }
</script>

<style scoped>

</style>