import http from 'http';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { routeMap } from './wordstat-routes.mjs';

const __dir = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dir, '..');
const previewDir = __dir;
const port = 8765;

function normalizePath(urlPath) {
  if (!urlPath || urlPath === '/') return '/';
  let p = urlPath.split('?')[0];
  if (!p.endsWith('/')) p += '/';
  return p;
}

function injectBase(html) {
  const base = '<base href="/preview/" />';
  if (html.includes('<base ')) return html;
  return html.replace(/<head>/i, `<head>\n  ${base}`);
}

function sendFile(res, filePath, contentType) {
  fs.readFile(filePath, (err, data) => {
    if (err) {
      res.writeHead(404);
      res.end('Not found');
      return;
    }
    res.writeHead(200, { 'Content-Type': contentType });
    res.end(data);
  });
}

const mime = {
  '.html': 'text/html; charset=utf-8',
  '.css': 'text/css',
  '.js': 'application/javascript',
  '.json': 'application/json',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.webp': 'image/webp',
  '.woff2': 'font/woff2',
  '.svg': 'image/svg+xml',
};

const server = http.createServer((req, res) => {
  const urlPath = req.url || '/';
  const norm = normalizePath(urlPath);

  if (routeMap[norm]) {
    const htmlPath = path.join(previewDir, routeMap[norm]);
    fs.readFile(htmlPath, 'utf8', (err, html) => {
      if (err) {
        res.writeHead(404);
        res.end('Preview HTML missing: ' + routeMap[norm]);
        return;
      }
      res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
      res.end(injectBase(html));
    });
    return;
  }

  // Legacy flat preview URLs
  if (urlPath.startsWith('/preview/')) {
    const rel = urlPath.replace(/^\/preview\//, '').split('?')[0];
    const safe = path.normalize(rel).replace(/^(\.\.(\/|\\|$))+/, '');
    const filePath = path.join(previewDir, safe);
    if (!filePath.startsWith(previewDir)) {
      res.writeHead(403);
      res.end('Forbidden');
      return;
    }
    if (safe.endsWith('.html')) {
      fs.readFile(filePath, 'utf8', (err, html) => {
        if (err) {
          res.writeHead(404);
          res.end('Not found');
          return;
        }
        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(html);
      });
      return;
    }
    const ext = path.extname(filePath).toLowerCase();
    sendFile(res, filePath, mime[ext] || 'application/octet-stream');
    return;
  }

  if (urlPath.startsWith('/wp-theme/')) {
    const rel = urlPath.replace(/^\/wp-theme\//, '');
    const filePath = path.join(root, 'wp-theme', rel);
    if (!filePath.startsWith(path.join(root, 'wp-theme'))) {
      res.writeHead(403);
      res.end('Forbidden');
      return;
    }
    const ext = path.extname(filePath).toLowerCase();
    sendFile(res, filePath, mime[ext] || 'application/octet-stream');
    return;
  }

  res.writeHead(404);
  res.end('Not found: ' + urlPath);
});

server.listen(port, '127.0.0.1', () => {
  console.log(`preview server http://127.0.0.1:${port}/ (${Object.keys(routeMap).length} routes)`);
});
