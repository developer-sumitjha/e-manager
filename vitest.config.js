import { defineConfig } from 'vitest/config'
import { resolve } from 'path'

export default defineConfig({
  test: {
    environment: 'happy-dom',
    globals: true,
    setupFiles: ['./resources/js/tests/setup.js'],
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, './resources/js'),
      '~': resolve(__dirname, './resources'),
    },
  },
})


