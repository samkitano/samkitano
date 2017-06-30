$('#dt_vendor').DataTable({
  'order': [[ 0, 'desc' ]],
  searchHighlight: true,
  responsive: false
})

$('#dt_orphans').DataTable({
  'order': [[ 0, 'desc' ]],
  searchHighlight: true,
  responsive: false
})

$('tr.searchable').on('click', function () {
  $('#' + $(this).data('display')).toggle()
})

$("[id^=dt_log]").DataTable({
  "order": [ 2, 'desc' ],
  searchHighlight: true,
  responsive: false
})

$(document).ready(function () {
  panels.init()
})

let panels = {
  isCollapsed: false,
  currentPanel: {},
  currentParent: {},
  savedStates: {},
  getSaved () {
    this.savedStates = JSON.parse(localStorage.getItem('dash.panels'))
  },
  closePanel () {
    this.currentPanel.parents('.panel').find('.panel-body').slideUp()
    this.currentPanel.addClass('panel-collapsed')
    this.currentPanel.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down')
    this.savePanels()
  },
  currentPanelIsCollapsed () {
    return this.currentPanel.hasClass('panel-collapsed')
  },
  setPanels () {
    if (this.savedStates) {
      for (let item in this.savedStates) {
        this.currentPanel = $(`#${item}`)
        this.savedStates[item] ? this.closePanel() : this.openPanel()
      }
    }
  },
  savePanels () {
    let save = {}

    $('div.panel-heading').each(function (i, panel) {
      let id = $(this).attr('id')
      let isCollapsed = $(this).hasClass('panel-collapsed') ? true : false

      save[id] = isCollapsed
    })

    localStorage.setItem('dash.panels', JSON.stringify(save))
  },
  openPanel () {
    this.currentPanel.parents('.panel').find('.panel-body').slideDown()
    this.currentPanel.removeClass('panel-collapsed')
    this.currentPanel.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up')
    this.savePanels()
  },
  toogle ($e) {
    this.currentPanel = $e
    this.currentPanelIsCollapsed() ? this.openPanel() : this.closePanel()
  },
  init () {
    this.getSaved()
    this.setPanels()

    $(document).on('click', '.panel-heading', function () {
      panels.toogle($(this))
    })
  }
}
