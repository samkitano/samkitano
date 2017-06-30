let $toolbar = $('nav.Toolbar')

let resourceToolbar = {
  aDestroy: $toolbar.find('a.Toolbar__Crud_destroy'),
  btnCancel: $toolbar.find('button.btn_cancel'),
  btnConfirm: $toolbar.find('button.btn_destroy'),
  liCancel: $toolbar.find('li.toolbar_cancel'),
  liDestroy: $toolbar.find('a.Toolbar__Crud_destroy').parent('li'),
  liConfirm: $toolbar.find('li.toolbar_confirm'),

  showCancel () {
    this.liCancel.show(300)

    return this
  },

  hideCancel () {
    this.liCancel.hide(300)

    return this
  },

  disableDestroy () {
    this.liDestroy.addClass('disabled')

    return this
  },

  enableDestroy () {
    this.liDestroy.removeClass('disabled')

    return this
  },

  showConfirm () {
    this.liConfirm.show(300)

    return this
  },

  hideConfirm () {
    this.liConfirm.hide(300)

    return this
  },

  resetForm: function (resource) {
    let rVal = `input#${resource}_resource_id`
    let actn = `form#${resource}_del_form`

    $(rVal).val('')
    $(actn).attr('action', '')

    return this
  },

  setForm: function(resource, id, route) {
    let rVal = `input#${resource}_resource_id`
    let actn = `form#${resource}_del_form`

    $(rVal).val(id)
    $(actn).attr('action', route)

    return this
  }
}

/**
 * Manage Delete Resource Confirmation
 */
$('a.Toolbar__Crud_destroy').on('click', function (e) {
  let $_this = $(this)

  if ($_this.parent('li').hasClass('disabled')) {
    return false
  }

  let _route = $_this.attr('href')
  let _segs = _route.split('/')
  let _resourceId = _segs.reverse()[0]
  let _resource = _segs[1]

  if (!$.isNumeric(_resourceId)) {
    _resourceId = _segs[1]
    _resource = _segs[2]
  }

  resourceToolbar
      .showCancel()
      .disableDestroy()
      .resetForm(_resource)
      .setForm(_resource, _resourceId, _route)
      .showConfirm()

  resourceToolbar.btnCancel.on('click', function () {
    resourceToolbar
        .hideCancel()
        .hideConfirm()
        .resetForm(_resource)
        .enableDestroy()
  })

  e.preventDefault()
})
