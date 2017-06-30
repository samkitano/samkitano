<template lang="html">
	<nav class="App-nav">
		<div class="App-nav__Wrapper">
			<div class="App-nav__Wrapper__Left">
				<a :class="isActiveLeft('home')" href="/">Home</a>
				<a :class="isActiveLeft('about')" href="/about">About</a>
				<a :class="isActiveLeft('contact')" href="/contact">Contact</a>
        <a :class="isActiveLeft('articles')" href="/articles">{{ blogText }}</a>
			</div>

			<div class="App-nav__Wrapper__Right">
				<user-menu :user="theUser.user" v-if="theUser.user"></user-menu>
				<logout :user="theUser" v-if="theUser.user"></logout>
				<auth-menu :registering="isRegistering" :user="theUser" v-else></auth-menu>
			</div>

			<div class="Clear"></div>
		</div>
	</nav>
</template>


<script type="text/ecmascript-6">
	const activeClass = ' active'
	const leftBtnClass = 'App-nav__Wrapper__Left__button'
	const rightBtnClass = 'App-nav__Wrapper__Right'

	import authMenu from './_auth-menu.vue'
	import logout from './_logout.vue'
	import userMenu from './_user-menu.vue'

	export default {
    beforeDestroy () {
      eventBus.$off('blur', this.toggleBlur)
      eventBus.$off('nameChanged', this.changeUserName)
    },

		components: {
			authMenu,
			logout,
			userMenu
		},

    created () {
      eventBus.$on('blur', (state) => {
        this.toggleBlur(state) // @mixins.js
      })

      eventBus.$on('nameChanged', (name) => {
        this.changeUserName(name)
      })

      this.checkRegistration()

      if (this.swal) {
        setTimeout(() => {
            this.$swal(this.swal).catch((e) => {})
        }, 1)
      }
    },

		data () {
			return {
        isRegistering: false,
				theUser: JSON.parse(this.user)
			}
		},

    computed: {
      blogText () {
        return this.isArticle ? '< Back' : 'Blog'
      },

      swal () {
        return this.alert ? JSON.parse(this.alert) : false
      }
    },

		methods : {
      changeUserName (name) {
        this.theUser.user.name = name
      },

      checkRegistration () {
        if (this.userIsAuthed(this.theUser.user)) {
          this.unsetLocal('registering')
          return true
        }

        let applicant = this.getLocal('registering')

        if (applicant) {
          this.isRegistering = true
          this.theUser = applicant
        }
      },

			isActiveLeft (menuItem) {
				return this.current === menuItem ? `${leftBtnClass}${activeClass}` : leftBtnClass
			},

			isActiveRight (menuItem) {
				return this.current === menuItem ? `${rightBtnClass}${activeClass}` : leftBtnClass
			}
		},

		props : {
      alert: {
        required: true,
        type: String
      },
			current: {
				required: true,
				type: String
			},
      isArticle: {
        default: false,
        required: false,
        type: Boolean
      },
			user: {
				required: true,
				type: String
			}
		},

    mounted () {
      if (this.theUser.active) {
        this.unsetLocal('registering')
        this.unsetLocal('reset')
      }
    }
	}
</script>
