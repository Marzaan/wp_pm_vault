// webpack.mix.js

let mix = require('laravel-mix');

mix.js('src/admin/main.js', 'dist/js/pm-vault-admin.js').vue({ version: 2 })

mix.sass('src/admin/main.scss', 'dist/css/pm-vault-admin.css')
mix.sass('src/admin/assets/styles/header.scss', 'dist/css/pm-vault-admin.css')
mix.sass('src/admin/assets/styles/home.scss', 'dist/css/pm-vault-admin.css')
mix.sass('src/admin/assets/styles/item.scss', 'dist/css/pm-vault-admin.css')
mix.sass('src/admin/assets/styles/tools.scss', 'dist/css/pm-vault-admin.css')

mix.js('src/public/assets/js/item.js', 'dist/js/pm-vault-public.js')
mix.sass('src/public/assets/scss/item.scss', 'dist/css/pm-vault-public.css')