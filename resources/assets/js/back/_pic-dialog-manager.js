import ajax from 'axios'

export default {
  albums: [],
  currentAlbumIndex: 0,
  mediaPath: '',

  init () {
    ajax.get('/admin/ajax-albums')
        .then((r) => {
          this.albums = r.data.albums
          this.mediaPath = r.data.media_path
          this.makeTabList()
          this.makeTabContent()
        })
  },

  deselectAll () {
    let els = document.getElementsByClassName('media selected')

    for (let i = 0; i < els.length; i++) {
      els[i].getElementsByClassName('fa')[0].classList.add('hidden')
      els[i].classList.remove('selected')
    }

    document.getElementById('url')
            .value = ''
    document.getElementById('alt')
            .value = ''
  },

  getPics (album) {
    let media = album.media
    let albumName = album.name
    let a = ''
    let i = 0

    if (! media.length) {
      return '<div class="media"><h4 style="margin:10px 0;text-align:center;">No images in this album.</h4></div>'
    }

    for (let m in media) {
      let medium = media[m]

      a += '<div class="media" id="media_' + albumName + i + '"><div class="media-left"><a href="#" class="add_pic">'
        + '<img class="media-object" src="'
        + this.mediaPath
        + '/'
        + albumName
        + '/thumbs/medium_'
        + medium['file_name']
        + '" alt=""'
        + ' data-album-index="'
        + this.currentAlbumIndex
        + '" data-media-index="'
        + i
        + '">'
        + '</a></div>'
        + '<div class="media-body">'
        + '<h4 class="media-heading">' + medium['name'] + '</h4>'
        + '<p>' + medium['description'] + '</p>'
        + '<p>' + medium['width'] + ' x ' + medium['height'] + ' - ' + (this.formatBytes(medium['size'])) + '</p>'
        + '<br><p><i class="fa fa-check-square fa-2x hidden"></i></p>'
        + '</div></div>'

      i++
    }

    return a
  },

  formatBytes (a, b) {
    if (0 === a) {
      return"0 Bytes"
    }

    let c = 1e3
    let d = b || 2
    let e = ["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"]
    let f = Math.floor(Math.log(a)/Math.log(c))

    return parseFloat((a/Math.pow(c,f)).toFixed(d)) + ' ' + e[f]
  },

  makeTabContent () {
    let panelDivs = ''
    let i = 0

    for (let a in this.albums) {
      this.currentAlbumIndex = i

      let album = this.albums[a]
      let pics = this.getPics(album)

      panelDivs += '<div role="tabpanel" class="tab-pane'
                + (panelDivs === '' ? ' active' : '')
                + '" id="tab_'
                + album.name
                + '">'
                + pics
                + '</div>'

      i++
    }

    document.getElementById('album_contents')
            .innerHTML = panelDivs

    this.setSelectListener()
  },

  makeTabList () {
    let lis = ''

    for (let n in this.albums) {
      let a = '<a class="switch_tab" href="tab_'
          + this.albums[n].name
          + '" aria-controls="'
          + this.albums[n].name
          + '" data-toggle="tab" role="tab">'
          + this.albums[n].name
          + '</a>'

      lis += (lis === '' ? '<li class="active">' : '<li>') + a + '</li>'
    }

    document.getElementById('album_tabs')
            .innerHTML = lis
  },

  makeSelection (e) {
    e.preventDefault()

    this.deselectAll()

    let albumIdx = e.target.getAttribute('data-album-index')
    let mediaIdx = e.target.getAttribute('data-media-index')
    let mediaDiv = document.getElementById('media_' + this.albums[albumIdx].name + mediaIdx)
    let mediaSrc = this.albums[albumIdx].media[mediaIdx]

    mediaDiv.classList.add('selected')
    mediaDiv.getElementsByClassName('fa')[0]
            .classList
            .remove('hidden')

    document.getElementById('url')
            .value = this.mediaPath + '/' + this.albums[albumIdx].name + '/' + mediaSrc['file_name']
    document.getElementById('alt')
            .value = mediaSrc['description']
  },

  setSelectListener () {
    let addTriggers = document.getElementsByClassName('add_pic')
    let tabSwitches = document.getElementsByClassName('switch_tab')

    for (let i = 0; i < addTriggers.length; i++) {
      addTriggers[i].addEventListener('click', function(e) {
        this.makeSelection(e)
      }.bind(this), false)
    }

    for (let i = 0; i < tabSwitches.length; i++) {
      tabSwitches[i].addEventListener('click', function(e) {
        this.switchTab(e)
      }.bind(this), false)
    }
  },

  switchTab (e) {
    e.preventDefault()

    let container = document.getElementById('pic_dialog')
    let lis = container.getElementsByTagName('li');
    let panes = container.getElementsByClassName('tab-pane')

    for (let i = 0; i < lis.length; i++) {
      lis[i].classList.remove('active')
    }

    for (let i = 0; i < panes.length; i++) {
      panes[i].classList.remove('active')
    }

    e.target.parentElement
            .classList
            .add('active')
    document.getElementById(e.target.getAttribute('href'))
            .classList
            .add('active')
  }
}
