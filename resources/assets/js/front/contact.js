import appContact from './components/contact/Contact.vue'
import Vue from 'vue'
import VueSwal from './swal-main'

require('./mixins/mixins');
require('./mixins/validation');

const nav = new Vue ({
  components: { appContact },
  name: 'Contact'
}).$mount('app-contact')

Vue.use(VueSwal)
