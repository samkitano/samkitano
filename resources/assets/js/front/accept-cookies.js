let allowCookies = {
  cookieName: 'allow_cookies',
  cookieVal: 1,
  expiration: 365 * 20 * 24 * 60 * 60 * 1000,

  agree () {
    this.setCookie()
    this.hideDialog()
  },

  hasCookie () {
    return (document.cookie.split('; ').indexOf(this.cookieName + '=' + this.cookieVal) !== -1)
  },

  hideDialog () {
    document.getElementById('allow-cookies').style.display = 'none'
  },

  setCookie () {
    let date = new Date()

    date.setTime(date.getTime() + (this.expiration))

    document.cookie = this.cookieName + '=' + this.cookieVal + '; ' + 'expires=' + date.toUTCString() +';path=/'
  },

  init () {
    if (this.hasCookie(this.cookieName)) {
      this.hideDialog()
      return false
    }

    let btn = document.getElementById('allow_cookies_btn')

    btn.addEventListener('click', function () {
      this.agree()
    }.bind(this), false)
  }
}

allowCookies.init()
