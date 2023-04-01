import { defineConfig } from "vite";
import liveReload from "vite-plugin-live-reload";
import multiInput from "rollup-plugin-multi-input";

export default defineConfig(({ mode }) => {
  return {
    plugins: [multiInput(), liveReload(["*.theme", "templates/**/*.twig"])],
    base:
      mode === "production"
        ? "/themes/custom/badaniatypu/dist/"
        : "/themes/custom/badaniatypu/",
    build: {
      manifest: true,
      rollupOptions: {
        input: [
          "assets/scss/**/*.scss",
          "!assets/scss/**/_*.scss",
          "assets/js/*.js",
          "assets/js/*.ts",
        ],
      },
    },
    optimizeDeps: {
      disabled: true,
    },
    server: {
      host: true,
      port: process.env.VITE_PORT || 3000,
    },
  };
});
