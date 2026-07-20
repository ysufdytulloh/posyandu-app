import preset from "../../../../vendor/filament/filament/tailwind.config.preset";

export default {
  presets: [preset],
  content: [
    "./app/Filament/Kader/**/*.php",
    "./app/Filament/Pages/**/*.php",
    "./app/Filament/Resources/**/*.php",
    "./resources/views/filament/**/*.blade.php",
    "./vendor/filament/**/*.blade.php"
  ]
};
