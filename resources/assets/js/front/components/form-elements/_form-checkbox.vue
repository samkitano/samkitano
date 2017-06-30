<template lang="html">
  <div class="H-Form__Item">
    <label class="H-Form__Item__Checkbox">
      <span :class="{ 'disabled': disabled, 'checked': isChecked }">
        <input @change="change"
               :disabled="disabled"
               :name="name"
               :value="label"
               type="checkbox"
               v-model="model"
        >
      </span>&nbsp;{{ label }}</label
    >
  </div>
</template>


<script type="text/ecmascript-6">
  export default {
    computed: {
      model: {
        get() {
          return this.value !== undefined ? this.value : this.selfModel
        },

        set (val) {
          if (this.value !== undefined) {
            this.$emit('input', val)
          } else {
            this.selfModel = val
          }
        }
      },

      isChecked () {
        if ({}.toString.call(this.model) === '[object Boolean]') {
          return this.model
        } else if (Array.isArray(this.model)) {
          return this.model.indexOf(this.label) > -1
        }
      }
    },

    created () {
      if (this.checked) {
        this.model = true
      }
    },

    data () {
      return {
        selfModel: false
      }
    },

    methods: {
      change (ev) {
        this.$emit('change', ev)
      }
    },

    name: 'FormCheckbox',

    props: {
      checked: {
        default: false,
        required: false,
        type: Boolean
      },
      disabled: {
        default: false,
        required: false,
        type: Boolean
      },
      label: {
        required: true,
        type: [String, Number]
      },
      name: {
        required: false,
        type: String
      },
      value: {
        required: false
      }
    }
  }
</script>
