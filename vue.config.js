const { defineConfig } = require("@vue/cli-service");
const path = require("path");

module.exports = defineConfig({
  transpileDependencies: true,

  // Ensure output files are generated in the 'dist' directory
  outputDir: "dist",

  // Disable filename hashing for predictable file names
  filenameHashing: false,

  css: {
    // Extract CSS into a single file with a custom name
    extract: {
      filename: "css/app.css",
    },
  },

  configureWebpack: {
    // Configure source maps for debugging
    devtool: "source-map",

    // Ensure single JS file with a custom name
    output: {
      filename: "js/app.js",
    },

    // Add custom plugins (if any)
    plugins: [
      new (require("webpack").DefinePlugin)({
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
      }),
    ],
  },

  chainWebpack: (config) => {
    // Disable splitting of chunks for a single bundle
    config.optimization.splitChunks(false);
  },
});
