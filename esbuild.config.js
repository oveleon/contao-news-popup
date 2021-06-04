const esbuild = require('esbuild');
const sassPlugin = require('esbuild-plugin-sass')

// Browser
esbuild.build({
    entryPoints: ['./src/Resources/public/src/NewsPopup.js'],
    outfile: './src/Resources/public/build/news-popup.min.js',
    minify: true,
    bundle: true,
    sourcemap: true,
    format: 'iife',
    globalName: 'NewsPopup',
    target: ['es2015']
}).catch((e) => console.error(e.message))

esbuild.build({
    entryPoints: ['./src/Resources/public/src/NewsPopup.scss'],
    outfile: './src/Resources/public/build/news-popup.min.css',
    //minify: true,
    loader: { '.scss': 'css' },
    plugins: [sassPlugin()]
}).catch((e) => console.error(e.message))

// Module
esbuild.build({
    entryPoints: ['./src/Resources/public/src/NewsPopup.js'],
    outfile: './src/Resources/public/build/news-popup.cjs.js',
    platform: 'node',
    format: 'cjs',
    bundle: true
}).catch((e) => console.error(e.message))
