<template lang="html">
  <section id="App" class="App-container Articles" v-if="articles">
    <h1 class="Pg-header">{{ pgHeader }}
      <span>({{ articles.length }})</span>

      <a v-if="filter"
         class="Button Button_small Button_default"
         href="#"
         @click.prevent="reset">View All</a>
    </h1>

    <div class="Search" v-if="algolia">
      <div class="Algolia__Search__Box">
        <div class="Algolia__Search__Form" role="form">
          <div class="Algolia-logo">
            <a target="_blank" title="Algolia Website" href="https://www.algolia.com/">
              <svg height="26" viewBox="0 0 391 124" xmlns="http://www.w3.org/2000/svg">
                <title>Algolia Logo</title>
                <g fill="none" fill-rule="evenodd">
                  <path d="M235 48.138l-5.092 18.042 16.398-8.987c-2.382-4.367-6.445-7.67-11.307-9.055zM203.456 33.4c-2.454-2.464-6.434-2.462-8.887 0l-1.11 1.116c-2.455 2.462-2.453 6.46 0 8.924l1.18 1.187c2.52-4.058 5.725-7.64 9.45-10.588l-.634-.64zM241.84 27.224c.01-.136.04-.265.04-.404v-3.155c0-3.484-2.813-6.31-6.282-6.31H224.6c-3.467 0-6.28 2.825-6.28 6.31v3.1c3.5-.985 7.185-1.523 10.995-1.523 4.368 0 8.576.7 12.526 1.982" fill="#46AEDA"/><path d="M229.76 41.553c13.785 0 25 11.215 25 25 0 13.784-11.215 25-25 25-13.784 0-25-11.216-25-25 0-13.785 11.216-25 25-25m-35 25c0 19.328 15.668 35 35 35 19.333 0 35-15.672 35-35s-15.667-35-35-35c-19.332 0-35 15.672-35 35z" fill="#46AEDA"/>
                  <path d="M69.583 99.656c-1.443-3.835-2.8-7.604-4.07-11.31-1.273-3.706-2.59-7.476-3.945-11.31H21.62l-8.015 22.62H.755c3.392-9.37 6.572-18.038 9.542-26.005 2.97-7.965 5.87-15.527 8.716-22.68 2.84-7.16 5.66-13.995 8.46-20.512 2.798-6.517 5.724-12.97 8.778-19.36h11.323c3.054 6.39 5.98 12.843 8.78 19.36 2.798 6.517 5.617 13.353 8.46 20.51 2.84 7.155 5.744 14.717 8.714 22.683 2.968 7.968 6.15 16.635 9.542 26.006H69.582zm-11.577-32.84c-2.716-7.415-5.408-14.59-8.078-21.535-2.673-6.942-5.452-13.607-8.334-19.998-2.97 6.39-5.79 13.056-8.46 20-2.67 6.944-5.324 14.12-7.952 21.533h32.824zM114.11 100.933c-7.295-.17-12.467-1.745-15.52-4.728-3.055-2.98-4.58-7.624-4.58-13.93V2.536L105.84.49v79.87c0 1.96.17 3.578.51 4.856.34 1.277.89 2.3 1.653 3.067.763.765 1.782 1.34 3.055 1.725 1.273.383 2.84.703 4.705.96l-1.654 9.965M170.47 93.01c-1.017.685-2.99 1.555-5.916 2.62-2.926 1.065-6.34 1.598-10.242 1.598-3.987 0-7.74-.64-11.26-1.916-3.52-1.28-6.594-3.26-9.223-5.943-2.63-2.686-4.708-6.028-6.235-10.032-1.525-4.004-2.29-8.775-2.29-14.312 0-4.856.72-9.308 2.163-13.355 1.442-4.044 3.54-7.54 6.298-10.478 2.755-2.94 6.126-5.24 10.113-6.9 3.986-1.662 8.48-2.493 13.485-2.493 5.513 0 10.325.406 14.44 1.214 4.114.81 7.57 1.555 10.368 2.235v59.295c0 10.223-2.627 17.633-7.886 22.234-5.26 4.6-13.233 6.898-23.92 6.898-4.155 0-8.077-.338-11.767-1.02-3.69-.684-6.892-1.49-9.604-2.428l2.163-10.35c2.372.934 5.278 1.767 8.713 2.49 3.435.723 7.017 1.088 10.75 1.088 7.04 0 12.106-1.406 15.203-4.22 3.096-2.81 4.643-7.283 4.643-13.417v-2.81zm-4.898-50.284c-1.995-.297-4.687-.448-8.078-.448-6.36 0-11.26 2.09-14.695 6.263-3.436 4.175-5.153 9.71-5.153 16.612 0 3.835.485 7.115 1.462 9.84.975 2.73 2.29 4.983 3.943 6.774 1.654 1.787 3.562 3.11 5.725 3.96 2.163.853 4.39 1.278 6.68 1.278 3.137 0 6.02-.447 8.65-1.34 2.628-.897 4.707-1.94 6.234-3.13v-38.85c-1.19-.34-2.777-.66-4.77-.958zM297.035 100.934c-7.296-.172-12.468-1.745-15.52-4.728-3.054-2.98-4.58-7.625-4.58-13.93V2.537L288.766.493V80.36c0 1.96.168 3.578.508 4.856.34 1.278.89 2.3 1.655 3.067.762.767 1.778 1.343 3.052 1.726 1.272.383 2.84.7 4.707.957l-1.655 9.967M317.77 21.194c-2.118 0-3.923-.704-5.404-2.108-1.486-1.407-2.227-3.3-2.227-5.687 0-2.386.74-4.283 2.226-5.687 1.48-1.407 3.286-2.11 5.405-2.11 2.12 0 3.924.703 5.41 2.11 1.48 1.404 2.226 3.3 2.226 5.686s-.745 4.28-2.226 5.686c-1.486 1.404-3.29 2.108-5.41 2.108zm-5.85 12.012h11.832v66.45H311.92v-66.45zM365.48 31.545c4.75 0 8.756.62 12.023 1.854 3.263 1.234 5.893 2.98 7.886 5.236 1.992 2.26 3.413 4.943 4.262 8.053.846 3.108 1.272 6.536 1.272 10.285v41.533c-1.018.17-2.44.404-4.26.702-1.826.298-3.882.574-6.172.83-2.29.255-4.772.49-7.442.702-2.673.21-5.325.32-7.952.32-3.734 0-7.167-.383-10.305-1.15-3.14-.767-5.853-1.98-8.143-3.64-2.29-1.662-4.07-3.855-5.343-6.583-1.272-2.727-1.908-6.007-1.908-9.84 0-3.663.74-6.816 2.226-9.457 1.48-2.64 3.496-4.77 6.042-6.39 2.545-1.617 5.513-2.81 8.906-3.578 3.39-.766 6.953-1.15 10.686-1.15 1.186 0 2.418.063 3.69.19 1.272.13 2.482.302 3.625.514 1.145.212 2.142.403 2.992.573.847.172 1.44.3 1.78.383v-3.322c0-1.96-.212-3.897-.637-5.815-.425-1.918-1.188-3.62-2.29-5.11-1.102-1.492-2.608-2.685-4.517-3.58-1.908-.894-4.39-1.342-7.44-1.342-3.905 0-7.316.278-10.244.83-2.925.555-5.11 1.13-6.552 1.726l-1.4-9.84c1.527-.68 4.073-1.34 7.634-1.98 3.562-.64 7.42-.96 11.577-.96zm1.017 59.55c2.8 0 5.28-.065 7.443-.193 2.163-.128 3.965-.36 5.407-.702V70.393c-.85-.425-2.228-.787-4.134-1.086-1.91-.298-4.22-.45-6.935-.45-1.78 0-3.67.13-5.66.386-1.994.255-3.82.79-5.47 1.596-1.656.81-3.035 1.916-4.136 3.323-1.103 1.404-1.654 3.258-1.654 5.558 0 4.26 1.355 7.22 4.07 8.882 2.715 1.663 6.404 2.493 11.07 2.493z" fill="#0C1724"/>
                </g>
              </svg>
            </a>
          </div>

          <div class="Algolia__Search__Form__Search-icon">
            <svg height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <title>search</title>
              <path d="M11.192 12.606C10.024 13.482 8.572 14 7 14c-3.866 0-7-3.134-7-7s3.134-7 7-7 7 3.134 7
              7c0 1.572-.518 3.024-1.394 4.192l1.41 1.41c.666-.18 1.406-.01 1.932.518l2.832 2.83c.78.78.785
              2.042-.002 2.828-.78.78-2.04.787-2.827.002l-2.83-2.832c-.524-.524-.698-1.264-.518-1.932l-1.41-1.41zM7
              12c2.76 0 5-2.24 5-5S9.76 2 7 2 2 4.24 2 7s2.24 5 5 5z" fill-rule="evenodd"/>
            </svg>
          </div>

          <input @keyup="processSrch"
                 @keydown.13="search"
                 @keydown.27="reset"
                 autocomplete="off"
                 autofocus
                 class="Algolia__Search__Input"
                 id="algolia_search"
                 type="search"
                 placeholder="Search me"
                 v-model="query"
          >
        </div>
      </div>
    </div>

    <div class="Articles__List">
      <app-articles :article="article"
                    :key="article.slug"
                    :storedUser="storedUser"
                    v-for="article in articles"/>
    </div>
  </section>

  <section class="Articles" v-else>
    <app-error :error="error"/>
  </section>
</template>


<script type="text/ecmascript-6">
  const algolia = require('algoliasearch')
  const client = algolia(window.algolia.appId, window.algolia.appKey)
  const index = client.initIndex('articles')

  import appArticles from './_articles.vue'
  import appError from '../app-error.vue'
  import ajax from 'axios'
  import {
    debounce,
    filter,
    includes
  } from 'lodash'

  export default {
    beforeDestroy () {
      eventBus.$off('filterTags', this.filterTags)
    },

    beforeMount () {
      this.fetchData()
    },

    components: {
      appArticles,
      appError
    },

    computed: {
      authed () {
        return this.userIsAuthed(this.storedUser)
      },

      pgHeader () {
        return this.filter ? `Articles tagged \"${this.filter}\"` : 'Articles'
      },

      storedUser () {
        return this.user ? JSON.parse(this.user).user : false
      }
    },

    created () {
      eventBus.$on('filterTags', this.filterTags)
    },

    data () {
      return {
        articles: {},
        error: false,
        filter: false,
        query: ''
      }
    },

    methods: {
      fetchData () {
        ajax.get('/api/articles')
            .then((r) => {
              this.articles = r.data.articles
              this.cached = r.data.articles
              this.error = false
              this.query = ''
              this.results = {}
            })
            .catch((e) => {
              this.articles = false
              this.error = {
                status: 404,
                message: 'Unable to load articles.'
              }
            })
      },

      filterTags (tag) {
        this.filter = false
        this.articles = this.cached
        this.filter = tag

        let f = filter(this.articles, ((o) => {
          o.tags.split(',')
          return includes(o.tags, tag)
        }))

        this.articles = f
      },

      processSrch: debounce(
        function (e) {
          this.query = e.target.value
          // limit search to 4 chars min
          this.query.length > 3 ? this.search() : this.reset()
        }, 600
      ),

      reset () {
        this.filter = false
        this.articles = this.cached
        eventBus.$emit('cancelFilterTag', true)
      },

      search () {
        index.search(this.query, {
          attributesToHighlight: ['title', 'subtitle'],
          attributesToSnippet: 'body'
        }, (err, content) => {
          if (err) { console.warn(err) }
          this.articles = content.hits
        })
      }
    },

    name: 'ArticlesList',

    props: {
	    algolia: {
		    default: false,
		    required: false,
		    type: Boolean
	    },
      user: {
        required: true,
        type: String
      }
    },

    watch: {
      'authed' () {
        this.fetchData()
      }
    }
  }
</script>
