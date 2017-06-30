import appComments from './components/comments/Comments.vue'
import Vue from 'vue'
import VueSwal from './swal-main'

require('./mixins/mixins')
require('./mixins/filters')

const articles = new Vue ({
  components: { appComments },
  name: 'Comments'
}).$mount('app-comments')

Vue.use(VueSwal)
