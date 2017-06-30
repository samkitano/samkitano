function like (e) {
  let liker = {
    tgt: e.target,
    article: e.target.getAttribute("aria-article"),
    cList: e.target.classList,
    processLikes () {
      this.tgt.parentElement.parentElement.setAttribute('aria-label', 'You Like This Article')
      this.tgt.parentElement.classList.add('Button_inactive')
      this.cList.remove('Svg__Icon_clickable')
      this.cList.add('Svg__Icon_unclickable')
      this.cList.add('Svg__Icon_active')
    },
    postLike () {
      if (! _.includes(this.cList, 'Svg__Icon_clickable') || _.includes(this.cList, 'Svg__Icon_unclickable')) {
        return false
      }

      let formData = new FormData()
      let xmlHttp = new XMLHttpRequest()
      let that = this

      formData.append('_token', window.Laravel.csrfToken)

      xmlHttp.onreadystatechange = function () {
        if(xmlHttp.readyState == 4 && xmlHttp.status == 201) {
          that.processLikes();
          return true
        } else {
          return false
        }
      }

      xmlHttp.open("post", "/api/articles/" + this.article + "/like")
      xmlHttp.send(formData)
    }
  };

  liker.postLike();
}
