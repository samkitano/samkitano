import Vue from 'vue'
import appArticles from './components/articles/Articles.vue'

require('./mixins/mixins')
require('./mixins/filters')

const articles = new Vue ({
  components: { appArticles },
  name: 'Articles'
}).$mount('app-articles');
