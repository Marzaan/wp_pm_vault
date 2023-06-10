// webpack.mix.js

let mix = require('laravel-mix');

mix.js('src/main.js', 'dist/js/pm-vault-public.js').vue({ version: 2 })

mix.sass('src/main.scss', 'dist/css/pm-vault-public.css')
mix.sass('src/assets/styles/header.scss', 'dist/css/pm-vault-public.css')
mix.sass('src/assets/styles/home.scss', 'dist/css/pm-vault-public.css')
mix.sass('src/assets/styles/item.scss', 'dist/css/pm-vault-public.css')
mix.sass('src/assets/styles/tools.scss', 'dist/css/pm-vault-public.css')