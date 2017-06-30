<template lang="html">
  <div class="Article__Footer__Icon Article__Footer__Icon_tags" v-if="article.tags.length">
		<div class="Tags">
			<ul class="Tags__List">
				<li :class="classifyTag(tag)"
				    v-for="tag in tags"
				    ><span @click="filterTags(tag)">{{ tag }}</span></li
        >
			</ul>
		</div>
	</div>
</template>


<script type="text/ecmascript-6">
  export default {
    beforeDestroy () {
      eventBus.$off('cancelFilterTag', this.unsetFilter)
    },

    computed: {
      tags () {
        return this.article.tags.split(',')
      }
    },

    created () {
      eventBus.$on('cancelFilterTag', this.unsetFilter)
    },

    data () {
      return {
        filtered: ''
      }
    },

    methods: {
      classifyTag (tag) {
        return `Tag Tag_${tag.trim().toLowerCase()}`
      },

      filterTags (tag) {
        this.filtered = tag
        this.addActiveClasses(`Tag_${tag}`)
        eventBus.$emit('filterTags', tag)
      },

      unsetFilter (state) {
        if (state) {
          this.removeActiveClasses(`Tag_${this.filtered}`)
          this.filtered = ''
        }
      }
    },

    props: {
      article: {
        required: true,
        type: Object
      }
    }
  }
</script>
