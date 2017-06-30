import appUser from './components/user/User.vue'
import Vue from 'vue'
import VueSwal from './swal-main'

require('./mixins/mixins')
require('./mixins/filters')
require('./mixins/validation')

const articles = new Vue ({
  components: { appUser },
  name: 'User'
}).$mount('app-user')

Vue.use(VueSwal)
