export default [
  {
    name: 'Link',
    icon: 'link',
    shortcut: 'Ctrl Shift L',
    content: '<a href="{VAR link}"{IF title:} title="{VAR title}"{:IF}{IF target:} target="{VAR target}"{:IF}>{S:}{VAR placeholder}{:S}</a>',
    dialog: {
      header: 'Links',
      url: '/templates/markitup/dialogs/link.html'
    }
  },
  {
    name: 'Picture',
    icon: 'picture',
    shortcut: 'Ctrl Shift P',
    content: '<img src="{VAR url}"{IF alt:} alt="{VAR alt}"{:IF} />',
    dialog: {
      header: 'Picture',
      url: '/templates/markitup/dialogs/picture.html'
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
        before: '<h1>',
        after: '</h1>'
      },
      {
        name: 'Heading level 2',
        shortcut: 'Ctrl Shift 2',
        before: '<h2>',
        after: '</h2>'
      },
      {
        name: 'Heading level 3',
        shortcut: 'Ctrl Shift 3',
        before: '<h3>',
        after: '</h3>'
      },
      {
        name: 'Heading level 4',
        shortcut: 'Ctrl Shift 4',
        before: '<h4>',
        after: '</h4>'
      },
      {
        name: 'Paragraph',
        shortcut: 'Ctrl Shift 1',
        before: '<p>',
        after: '</p>'
      }
    ]
  },
  {
    name: 'Bold',
    icon: 'bold',
    shortcut: 'Ctrl Shift B',
    before: '{A:}<strong>{OR}<b>{:A}',
    after: '{A:}</strong>{OR}</b>{:A}'
  },
  {
    name: 'Italic',
    icon: 'italic',
    shortcut: 'Ctrl Shift I',
    before: '{A:}<em>{OR}<i>{:A}',
    after: '{A:}</em>{OR}</i>{:A}'
  },
  {
    name: 'Strike Through',
    icon: 'strikethrough',
    shortcut: 'Ctrl Shift D',
    before: '<del>',
    after: '</del>'
  },
  {
    separator: true
  },
  {
    name: 'Align left',
    icon: 'align-left',
    before: '<div style="text-align:left;">',
    after: '</div>'
  },
  {
    name: 'Align center',
    icon: 'align-center',
    before: '<div style="text-align:center;">',
    after: '</div>'
  },
  {
    name: 'Align right',
    icon: 'align-right',
    before: '<div style="text-align:right;">',
    after: '</div>'
  },
  {
    name: 'Align justify',
    icon: 'align-justify',
    before: '<div style="text-align:justify;">',
    after: '</div>'
  },
  {
    separator: true
  },
  {
    name: 'Unordered List',
    icon: 'list-ul',
    beforeBlock: '<ul>\n',
    before: '{T}<li>',
    after: '</li>',
    afterBlock: '\n</ul>',
    multiline: true
  },
  {
    name: 'Ordered List',
    icon: 'list-ol',
    content: '<ol>\n{M:}{T}<li>{M}</li>{:M}\n</ol>'
  },
  {
    separator: true
  },
  {
    name: 'Code',
    icon: 'code',
    dropdown: [
      {
        name: 'PHP',
        before: '<pre><code class="language-php">',
        after: '</code></pre>'
      },
      {
        name: 'javascript',
        before: '<pre><code class="language-javascript">',
        after: '</code></pre>'
      },
      {
        name: 'html',
        before: '<pre><code class="language-html">',
        after: '</code></pre>'
      },
      {
        name: 'bash',
        before: '<pre><code class="language-bash">',
        after: '</code></pre>'
      },
      {
        name: 'sass',
        before: '<pre><code class="language-sass">',
        after: '</code></pre>'
      },
      {
        name: 'css',
        before: '<pre><code class="language-css">',
        after: '</code></pre>'
      }
    ]
  },
  {
    separator: true
  },
  {
    name: 'Preview',
    icon: 'preview',
    shortcut: 'Ctrl Shift R'
  }
]
