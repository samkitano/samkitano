import appNav from './components/top-bar/Navigation.vue'
import Vue from 'vue'
import VueSwal from './swal-main'

require('./mixins/mixins');
require('./mixins/validation');

const nav = new Vue ({
  components: { appNav },
  name: 'Nav'
}).$mount('app-nav')

Vue.use(VueSwal)
