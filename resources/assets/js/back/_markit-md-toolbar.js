export default [
  {
    name: 'Link',
    icon: 'link',
    shortcut: 'Ctrl Shift L',
    content: '[{S:}{VAR placeholder}{:S}]({VAR link}{IF title:} "{VAR title}"{:IF})',
    dialog: {
      header: 'Links',
      url: 'dialogs/link.html'
    }
  },
  {
    name: 'Picture',
    icon: 'picture',
    shortcut: 'Ctrl Shift P',
    content: '![{VAR alt}]({VAR url})',
    dialog: {
      header: 'Picture',
      url: 'dialogs/picture.html'
    }
  },
  {
    separator: true
  },
  {
    name: 'Headings',
    icon: 'header',
    dropdown: [
      {
        name: 'Heading level 1',
        shortcut: 'Ctrl Shift 1',
        before: '# ',
        after: '\n'
      },
      {
        name: 'Heading level 2',
        shortcut: 'Ctrl Shift 2',
        before: '## ',
        after: '\n'
      },
      {
        name: 'Heading level 3',
        shortcut: 'Ctrl Shift 3',
        before: '### ',
        after: '\n'
      },
      {
        name: 'Heading level 4',
        shortcut: 'Ctrl Shift 4',
        before: '#### ',
        after: '\n'
      }
    ]
  },
  {
    name: 'Bold',
    icon: 'bold',
    shortcut: 'Ctrl Shift B',
    before: '**',
    after: '**'
  },
  {
    name: 'Italic',
    icon: 'italic',
    shortcut: 'Ctrl Shift I',
    before: '*',
    after: '*'
  },
  {
    separator: true
  },
  {
    name: 'Unordered List',
    icon: 'list-ul',
    before: '* ',
    multiline: true
  },
  {
    name: 'Ordered List',
    icon: 'list-ol',
    before: '{#}. ',
    multiline: true
  },
  {
    separator: true
  },
  {
    name: 'indent',
    icon: 'indent',
    click: function () {
      this.indent();
    }
  },
  {
    name: 'Outdent',
    icon: 'outdent',
    click: function () {
      this.outdent();
    }
  },
  {
    separator: true
  },
  {
    name: 'Preview',
    icon: 'preview',
    shortcut: 'Ctrl Shift R'
  },
]