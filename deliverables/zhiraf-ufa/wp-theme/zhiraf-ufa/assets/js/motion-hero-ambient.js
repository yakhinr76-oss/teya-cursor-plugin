(function () {
  'use strict';

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }
  if (window.matchMedia('(max-width: 767px)').matches) {
    return;
  }
  if (typeof THREE === 'undefined') {
    return;
  }

  var root = document.querySelector('[data-three-canvas="hero-ambient"]');
  if (!root) {
    return;
  }

  var holder = root.querySelector('.hero-ambient-canvas');
  if (!holder) {
    holder = document.createElement('div');
    holder.className = 'hero-ambient-canvas';
    holder.setAttribute('aria-hidden', 'true');
    root.insertBefore(holder, root.firstChild);
  }

  var renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
  holder.appendChild(renderer.domElement);

  var scene = new THREE.Scene();
  var camera = new THREE.PerspectiveCamera(45, 1, 0.1, 100);
  camera.position.z = 5;

  var count = 48;
  var positions = new Float32Array(count * 3);
  for (var i = 0; i < count; i++) {
    positions[i * 3] = (Math.random() - 0.5) * 4;
    positions[i * 3 + 1] = (Math.random() - 0.5) * 2.5;
    positions[i * 3 + 2] = (Math.random() - 0.5) * 1;
  }
  var geometry = new THREE.BufferGeometry();
  geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
  var material = new THREE.PointsMaterial({
    color: 0xe87a5d,
    size: 0.06,
    transparent: true,
    opacity: 0.55,
    depthWrite: false,
  });
  var points = new THREE.Points(geometry, material);
  scene.add(points);

  var running = false;
  var rafId = 0;

  function resize() {
    var w = holder.clientWidth || root.clientWidth;
    var h = holder.clientHeight || root.clientHeight;
    if (!w || !h) {
      return;
    }
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
  }

  function tick() {
    points.rotation.y += 0.0015;
    points.rotation.x += 0.0006;
    renderer.render(scene, camera);
    if (running) {
      rafId = requestAnimationFrame(tick);
    }
  }

  function start() {
    if (running) {
      return;
    }
    running = true;
    resize();
    tick();
  }

  function stop() {
    running = false;
    if (rafId) {
      cancelAnimationFrame(rafId);
    }
  }

  var io = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          start();
        } else {
          stop();
        }
      });
    },
    { threshold: 0.05 }
  );
  io.observe(root);

  document.addEventListener('visibilitychange', function () {
    if (document.hidden) {
      stop();
    } else if (root.getBoundingClientRect().top < window.innerHeight) {
      start();
    }
  });

  window.addEventListener('resize', resize);

  function dispose() {
    stop();
    io.disconnect();
    geometry.dispose();
    material.dispose();
    renderer.dispose();
  }

  window.addEventListener('pagehide', dispose);
})();
