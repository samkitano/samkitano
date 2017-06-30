<template lang="html">
	<a :href="href" class="App-nav__Wrapper__Right__button">{{ user.name }}</a>
</template>


<script type="text/ecmascript-6">
	export default {
    computed: {
      href () {
        return `/users/${this.user.slug}`
      }
    },

		props: {
			user: {
        required: true,
        type: Object
      }
		}
	}
</script>
