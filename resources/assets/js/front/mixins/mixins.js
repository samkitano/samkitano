import Vue from 'vue'
import {
  replace,
  split
} from 'lodash'

Vue.mixin({
  methods: {
    // add class 'active' to elements with given class
    addActiveClasses(elsClass) {
      if (!elsClass) {
        return false
      }

      let activeEls = document.getElementsByClassName(elsClass)

      for (let i = 0; i < activeEls.length; i++) {
        let el = activeEls[i]

        if (el.classList) {
          el.classList.add('active')
        }
      }
    },

    // add class 'error' to input element
    addErrorClass (id) {
      if (!id) {
        return false
      }

      let el = document.getElementById(id)

      if (el) {
        el.className += ' error'
      }
    },

    // pulsing button
    buttonPulse (type = 'default') {
      return `Button Button_${type} Button_pulse`
    },

    // default button
    buttonStill (type = 'default') {
      return `Button Button_${type}`
    },

    // check mark with message
    checkMark (text) {
      if (!text) {
        text = ''
      }

      return `<span style="color:#4DBA87;">&nbsp;&#10003;&nbsp;${text}</span>`
    },

    // remove spaces, colons and dashes from dates
    digitizeDate (date) {
      if (!date) {
        return ''
      }

      let s = split(date, ' ')
      let s0 = replace(s[0], new RegExp('-', 'g'), '')
      let s1 = replace(s[1], new RegExp(':', 'g'), '')

      return `${s0}${s1}`
    },

    // disable all fields and buttons in a form
    disableForm (container) {
      let flds = container.getElementsByClassName('Field')
      let btns = container.getElementsByClassName('Button')

      for (let f of flds) {
        f.setAttribute("disabled", true)
      }

      for (let b of btns) {
        b.setAttribute("disabled", true)
      }
    },

    // enable all fields and buttons in a form
    enableForm (container) {
      let flds = container.getElementsByClassName('Field')
      let btns = container.getElementsByClassName('Button')

      for (let f of flds) {
        f.removeAttribute("disabled")
      }

      for (let b of btns) {
        b.removeAttribute("disabled")
      }
    },

    // times mark with message
    errorMark (text) {
      if (!text) {
        text = ''
      }

      return `<span style="color:#E74430;">&nbsp;&#33;&nbsp;${text}</span>`
    },

    // focus element
    focus (id) {
      if (!id) {
        return false
      }

      let el = document.getElementById(id)

      if (el) {
        el.focus()
      }
    },

    // focus first input in dom
    focusFirstInput () {
      let els = document.getElementsByClassName('Field')

      els[0].focus()
    },

    // focus first input with error class
    focusFirstError () {
      let errEls = document.getElementsByClassName('error')

      if (!errEls.length) {
        return false
      }

      errEls[0].focus()
    },

    // get something from localStorage
    getLocal (key) {
      let response = false

      if(this.supportsLocalStorage() && localStorage.getItem(key) && localStorage.getItem(key) !== undefined) {
        try {
          response = JSON.parse(localStorage.getItem(key))
        } catch(e) {
          response = localStorage.getItem(key)
        } finally {
          return response
        }
			}

			return response;
    },

    // check if a property is present in object
    isDefined (obj, prop) {
      return obj.hasOwnProperty(prop) && obj[prop] !== undefined
    },

    // check if error is validation error
    isErrorBag (e) {
      return e.response && e.response.status && e.response.status === 422
    },

    // ordinalize numbers
    ordinalize (number) {
      if (!number || isNaN(number)) { return number }

      let ten = number % 10
      let hund = number % 100
      if (ten === 1 && hund !== 11) { return `${number}st` }
      if (ten === 2 && hund !== 12) { return `${number}nd` }
      if (ten === 3 && hund !== 13) { return `${number}rd` }

      return `${number}th`
    },

    // clear all form error classes
    removeErrorClasses () {
      let errEls = document.getElementsByClassName('Field')

      for (let i = 0; i < errEls.length; i++) {
        let el = errEls[i]

        if (el.classList) {
          el.classList.remove('error')
        }
      }
    },

    removeActiveClasses (el) {
      let errEls = document.getElementsByClassName(el)

      for (let i = 0; i < errEls.length; i++) {
        let el = errEls[i]

        if (el.classList) {
          el.classList.remove('active')
        }
      }
    },

    // sanitizes email addresses
    sanitizeEmail (email) {
      return email.trim().toLowerCase()
    },

    // computes elapsed seconds since a given date
    secondsSince (since) {
      let dtNow = new Date()
      let dtNowSecs = dtNow.getTime() / 1000
      let dtSince = new Date(since)
      let dtSinceSecs = dtSince.getTime() / 1000

      return Math.trunc(dtNowSecs - dtSinceSecs)
    },

    // put something in localStorage
    setLocal (key, value) {
      let isJason = false

      if (this.supportsLocalStorage()) {
        try {
          isJason = value instanceof Object
        } catch(e) {
          isJason = false
        } finally {
          if (isJason !== false) {
            localStorage.setItem(key, JSON.stringify(value))
          } else {
            localStorage.setItem(key, value)
          }

          return true
        }
      }

      return false
    },

    // check localStorage support
    supportsLocalStorage () {
      try {
          return 'localStorage' in window && window['localStorage'] !== null
      } catch(e) {
          return false
      }
    },

    // blur content
    toggleBlur (state) {
      let appContainer = document.getElementById('App')

      if (state) {
        appContainer.className = 'App-container App-container_blur'
        this.disableForm(appContainer)
      } else {
        appContainer.className = 'App-container'
        this.enableForm(appContainer)
      }
    },

    // delete key from local storage
    unsetLocal (key) {
      if (key) {
				localStorage.removeItem(key)
			} else {
				localStorage.clear()
			}
    },

    // check if user is registered but not confirmed
    userAwaitsConfirmation (user) {
      if (user === undefined || !user || !typeof user === 'object') {
        return false
      }

      return !user.active
    },

    // check if user is authenticated
    userIsAuthed (user) {
      if (user === undefined || !user || !typeof user === 'object') {
        return false
      }

      return user.active
    }
  }
})
