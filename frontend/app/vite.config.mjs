import { createViteConfig } from 'vite-config-factory'

const entries = {
  'js/main': './source/ts/main.ts',
  'css/styleguide': './source/sass/styleguide.scss',
  'css/custom': './source/sass/custom.scss',
}

export default createViteConfig(entries, {
  manifestFile: 'manifest.json',
  outDir: 'assets/dist',
})