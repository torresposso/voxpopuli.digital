// @ts-check

import alpinejs from "@astrojs/alpinejs";
import mdx from "@astrojs/mdx";
import sitemap from "@astrojs/sitemap";

import tailwindcss from "@tailwindcss/vite";
import { defineConfig } from "astro/config";

export default defineConfig({
  site: "https://voxpopuli.digital",
  integrations: [mdx(), sitemap(), alpinejs()],

  vite: {
    plugins: [tailwindcss()],
  },
});
