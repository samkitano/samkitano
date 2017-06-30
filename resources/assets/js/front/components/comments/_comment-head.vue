<template lang="html">
  <div class="Comment__Head">
    <div class="Comment__Head_left" v-if="comment.author.avatar">
	     <img :src="comment.author.avatar" :alt="imgalt">
	  </div>

    <div class="Comment__Head_left" v-else>
      <avatar/>
    </div>

		<div class="Comment__Head_right">
			<div class="Comment__Heading__Username" v-if="comment.author.name">
        <a :href="`/users/${comment.author.slug}`">{{ comment.author.name }}</a>
			</div>

      <div class="Comment__Heading__Username" v-html="'A User'" v-else/>

			<div class="Comment__Dates">
				<p>
					<span class="Comment__Created">
						<small>Posted {{ comment.created | dateTimeFormat }}</small>
					</span>

					<br/>

					<span class="Comment__Updated" v-if="edited">
						<small>Updated {{ comment.updated | dateTimeFormat }}</small>
					</span>
				</p>
			</div>
		</div>
  </div>
</template>


<script type="text/ecmascript-6">
  import avatar from '../_icons/_avatar.vue'

  export default {
    components: {
      avatar
    },

    computed: {
      edited () {
        return this.comment.created !== this.comment.updated
      },

      imgalt () {
        return this.comment.author.name || 'Please login'
      }
    },

    props: {
      comment: {
        required: true,
        type: Object
      }
    }
  }
</script>
