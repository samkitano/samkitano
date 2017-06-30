import Vue from 'vue'
import {
    capitalize,
    head,
    inRange,
    join,
    replace,
    split,
    trimStart,
    uniq
} from 'lodash'

Vue.mixin({
  methods: {
    emailIsInvalid (string) {
      return !/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(string)
    },

    bioIsInvalid (string) {
      return /[^a-zA-Z0-9 _.,#:;@"?!/*+çÇàÀáÀãÃâÂåÅäÄéÉèÈëËíÍóÓòÒõÕôÔöÖøØúÚüÜñýÝÞþßÿ-]/.test(string)
    },

    validationHasErrors (data) {
      return this.verifyErrors(data)
    },

    nameIsInvalid (string) {
      return /[^a-zA-Z çÇàÀáÀãÃâÂåÅäÄéÉèÈëËíÍóÓòÒõÕôÔöÖøØúÚüÜñýÝÞþßÿ.-]/.test(string)
    },

    processValidationErrors (fields) {
      return this.unifyRequired(this.stringifyValidationErrors(fields))
    },

    sizeIsInvalid (string, min, max) {
      if (!max || max === 'undefined') { return string.length < min }
      return !inRange(string.length, min, max + 1)
    },

    // join validation errors in one single string with line breaks
    stringifyValidationErrors (fields) {
      let msg = ''
      let lgth = Object.keys(fields).length
      let c = 1

      for (let f in fields) {
        let errs = fields[f].errors

        if (errs.length) {
          msg = c === lgth ? msg + head(errs) : msg + head(errs) + '<br>'

          this.addErrorClass(f) // @vue-mixins
        }

        c++
      }

      return msg
    },

    unifyRequired (msg) {
      let uni = uniq(split(msg, '<br>')).reverse()
      let str = join(uni, '<br>')
      let res = replace(str, 'required', 'Fields with an asterisk [*] are required')

      return trimStart(res, '<br>')
    },

    validate (data) {
      for (let field in data) {
        let match = data[field].validation.hasOwnProperty('match') ? data[field].validation.match : false
        let type = data[field].validation.type
        let required = data[field].validation.hasOwnProperty('required') ? data[field].validation.required : false
        let value = data[field].value

        if (type === 'name') {
          data[field].errors = this.validateName(value, required)
        } else if (type === 'bio') {
          data[field].errors = this.validateBio(value, required)
        } else if (type === 'email') {
          data[field].errors = this.validateEmail(value, required)
          if (match) {
            let matched = this.validateMatch(type, value, data[match].value)
            if (matched !== true) { data[field].errors.push(matched) }
          }
        } else if (type === 'password') {
          data[field].errors = this.validatePw(value, required)
          if (match) {
            let matched = this.validateMatch(type, value, data[match].value)
            if (matched !== true) { data[field].errors.push(matched) }
          }
        } else {
          required && !value ? data[field].errors = ['required'] : data[field].errors = []
        }
      }

      return data
    },

    validateBio (bio, required) {
      let result = []

      if (required && !bio) { return ['required'] }
      if (this.bioIsInvalid(bio)) { result.push('Allowed chars: letters, numbers, dashes and punctuation') }
      if (this.sizeIsInvalid(bio, 0, 255)) { result.push('Bio size= max: 255') }

      return result
    },

    validateEmail (email, required, match) {
      if (required && !email) { return ['required'] }
      if (this.emailIsInvalid(email)) { return ['Invalid Email Address'] }

      return []
    },

    validateMatch (field, val1, val2) {
      return val1 === val2 ? true : capitalize(`${field}s do not match`)
    },

    validateName (name, required) {
      let result = []

      if (required && !name) { return ['required'] }
      if (this.nameIsInvalid(name)) { result.push('Invalid! Characters not allowed') }
      if (this.sizeIsInvalid(name, 5, 20)) { result.push('Name size= min: 5 | max: 20') }

      return result
    },

    validatePw (pw, required, match) {
      let result = []

      if (required && !pw) { return ['required'] }
      if (this.sizeIsInvalid(pw, 6, 255)) { result.push('Password size= min:6 | max: 255') }

      return result
    },

    verifyErrors (fields) {
      for (let field in fields) {
        if (fields[field].errors.length) { return true }
      }

      return false
    }
  }
})
