module.exports = {
  plugins: [
    {
      name: "preset-default",
    },
    {
      name: "prefixIds",
      params: {
        delim: "-",
        prefix: (_, info) => {
          if (info.path != null && info.path.length > 0) {
            return getBasename(info.path).split(".")[0];
          }
          return "prefix";
        },
      },
    },
    "convertStyleToAttrs",
    "removeDimensions",
    "removeScriptElement",
    "removeStyleElement",
    "sortAttrs",
  ],
};

/**
 * extract basename from path
 */
const getBasename = (path) => {
  const matched = path.match(/[/\\]?([^/\\]+)$/);
  if (matched) return matched[1];
  return "";
};
