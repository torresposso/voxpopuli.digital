// @ts-check

import mdx from '@astrojs/mdx';
import sitemap from '@astrojs/sitemap';
import { defineConfig } from 'astro/config';

import tailwindcss from '@tailwindcss/vite';

import alpinejs from '@astrojs/alpinejs';

export default defineConfig({
  site: 'https://voxpopuli.digital',
  integrations: [mdx(), sitemap(), alpinejs()],

  vite: {
    plugins: [tailwindcss()],
  },
});