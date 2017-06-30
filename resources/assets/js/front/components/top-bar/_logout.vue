<template lang="html">
	<a @click.prevent="doLogout"
	   class="App-nav__Wrapper__Right__button"
	   href="#"
	>Logout</a>
</template>


<script type="text/ecmascript-6">
	export default {
    computed: {
      username () {
        return this.user.user.name
      }
    },

		methods: {
			doLogout () {
        this.$swal({
          title: 'Logout',
          text: `Leaving already, ${this.username}?`,
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes'
        }).then(() => {
          ajax.post('/logout')
              .then((r) => {
                this.$swal({
                  title: 'See you soon!',
                  text: 'Your session was terminated.',
                  type: 'success',
                  timer: 3000
                })
                .then(
                  () => { location.reload() },
                  (dismiss) => { location.reload() }
                )
              })
        }, (dismiss) => {})
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
