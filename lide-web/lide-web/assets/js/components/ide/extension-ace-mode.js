const config = {
  // Ace Mode => file extensions
  c_cpp: ["cpp", "c", "h", "hpp"],
  javascript: ["js"]
};

// Reverse the map so it's easy to use in code
const reverseMap = {};

// From a map ace_mode => [ext...] to a map ext => ace_mode
Object.keys(config).forEach(key => {
  config[key].forEach(ext => {
    reverseMap[ext] = key;
  });
});

export const AceModeExtensionMap = config;
export const ExtensionAceModeMap = reverseMap;
