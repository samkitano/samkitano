let pp = {
  trigger: false,
  closeBtn: false,
  container: false,
  contClass: false,

  closeContainer (e) {
    this.contClass = 'Footer__Privacy-policy'
    this.container.className = this.contClass

    if (e) {
      e.preventDefault()
    }
  },

  openContainer () {
    this.contClass = 'Footer__Privacy-policy_open'
    this.container.className = this.contClass
  },

  setListener() {
    let that = this

    this.trigger.addEventListener('click', function (e) {
      that.toggleVisibility(e)
    })

    this.closeBtn.addEventListener('click', function (e) {
      that.closeContainer(e)
    })
  },

  toggleVisibility (e) {
    if (this.contClass === 'Footer__Privacy-policy') {
      this.openContainer()
    } else {
      this.closeContainer()
    }

    e.preventDefault()
  },

  init () {
    this.trigger = document.getElementById('p_p_trigger')
    this.closeBtn = document.getElementById('close_p_p')
    this.container = document.getElementById('privacy_policy')
    this.contClass = this.container.className

    this.setListener()
  }
}

pp.init()
