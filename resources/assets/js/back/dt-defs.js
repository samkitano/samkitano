$.extend(true, $.fn.dataTable.defaults, {
  dom: '<r><"row"<"col-xs-4"B><"col-xs-4"l><"col-xs-4"f>><"row"<"col-xs-12"t>><"row"<"col-xs-2"i><"col-xs-10"p>>',
  processing: true,
  buttons: ['copy', 'csv', 'print'],
  responsive: true
});