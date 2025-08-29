import { createViteConfig } from 'vite-config-factory'

const entries = {
  'js/main-js': './source/ts/main.ts',
  'css/main-css': './source/sass/main.scss',
  'css/custom-css': './source/sass/custom.scss'
}

export default createViteConfig(entries, {
  manifestFile: 'manifest.json',
})