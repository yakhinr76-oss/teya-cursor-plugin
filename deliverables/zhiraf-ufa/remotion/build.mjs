import * as esbuild from 'esbuild';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const outfile = path.resolve(
  __dirname,
  '../wp-theme/zhiraf-ufa/assets/dist/hero-remotion-player.js'
);

await esbuild.build({
  entryPoints: [path.join(__dirname, 'src/hero-player.tsx')],
  bundle: true,
  outfile,
  format: 'iife',
  platform: 'browser',
  target: ['chrome90', 'firefox90', 'safari14'],
  jsx: 'automatic',
  loader: { '.tsx': 'tsx', '.ts': 'ts' },
  minify: true,
  sourcemap: false,
  define: {
    'process.env.NODE_ENV': '"production"',
  },
  logLevel: 'info',
});

console.log('Wrote', outfile);
