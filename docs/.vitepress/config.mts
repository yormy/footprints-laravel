import { defineConfig } from 'vitepress'

export default defineConfig({
  title: "Footprints",
  description: "",
  base: '/footprints-laravel/',
  head: [
    ['link', { rel: "apple-touch-icon", sizes: "180x180", href: "/assets/images/apple-touch-icon.png"}],
    ['link', { rel: "icon", type: "image/png", sizes: "32x32", href: "/assets/images/favicon-32x32.png"}],
    ['link', { rel: "icon", type: "image/png", sizes: "16x16", href: "/assets/images/favicon-16x16.png"}],
  ],
  themeConfig: {
    search: {
      provider: 'local'
    },
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/v1/introduction/what-is-footprints' },
    ],

    sidebar: [
      {
        text: 'Introduction',
        items: [
          { text: 'What is Footprints', link: '/v1/introduction/what-is-footprints' },
          { text: 'Definitions', link: '/v1/definitions.md' },
          { text: 'Need Support?', link: '/general/support/support-me' },
        ]
      },
      {
        text: 'Getting Started',
        items: [
            { text: 'Cleanup', link: '/v1/guide/cleanup' },
            { text: 'Encryption', link: '/v1/guide/encryption' },
            { text: 'Exceptions', link: '/v1/guide/exceptions' },
            { text: 'Model Changes', link: '/v1/guide/model-changes' },
          // { text: 'Basic Setup', link: '/v1/guide/basic/setup' },
          // { text: 'Basic Configuration', link: '/v1/guide/basic/configuration' },
          // { text: 'Frontend', link: '/v1//guide/basic/frontend' },
        ]
      },

      { text: 'Contributing', items: [
        { text: 'Report Security Issues', link: '/general/report_security' },
        { text: 'Roadmap', link: '/general/roadmap' },
        { text: 'License', link: '/general/license' },
        { text: 'Change log', link: '/general/changelog' },
        { text: 'Contributing', link: '/general/contributing' },
        { text: 'Code of Conduct', link: '/general/code_of_conduct' },
        { text: 'Credits', link: '/general/credits' },
      ]},

      { text: 'Contact', items: [
          { text: 'Contact', link: '/general/contact' },
          { text: 'Support', link: '/general/support/support-me' },
          { text: 'Donations', link: '/general/support/donations' },
        ]},

    ],

    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright © 2022 to present Yormy'
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/yormy/footprints-laravel' }
    ]
  }
})
