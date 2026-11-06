import React from 'react';
import { createRoot } from 'react-dom/client';
import { Player } from '@remotion/player';
import { OrbitPhotos, type OrbitPhotosProps } from './OrbitPhotos';

declare global {
  interface Window {
    zhirafHeroRemotion?: {
      photos: string[];
      width?: number;
      height?: number;
      fps?: number;
      durationInFrames?: number;
    };
  }
}

function prefersReducedMotion(): boolean {
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function mountHeroRemotionPlayer(): void {
  const rootEl = document.getElementById('hero-remotion-root');
  const cfg = window.zhirafHeroRemotion;

  if (!rootEl || !cfg?.photos?.length || cfg.photos.length < 3) {
    return;
  }

  if (prefersReducedMotion() || window.matchMedia('(max-width: 768px)').matches) {
    rootEl.classList.add('hero-remotion-player--static');
    return;
  }

  const width = cfg.width ?? 520;
  const height = cfg.height ?? 500;
  const fps = cfg.fps ?? 30;
  const durationInFrames = cfg.durationInFrames ?? 12 * fps;

  rootEl.classList.add('hero-remotion-player--active');
  const fallback = rootEl.querySelector('.hero-remotion-player__fallback');
  if (fallback instanceof HTMLElement) {
    fallback.hidden = true;
  }

  createRoot(rootEl).render(
    <Player<OrbitPhotosProps>
      component={OrbitPhotos}
      inputProps={{ photos: cfg.photos }}
      durationInFrames={durationInFrames}
      fps={fps}
      compositionWidth={width}
      compositionHeight={height}
      loop
      autoPlay
      controls={false}
      acknowledgeRemotionLicense
      style={{
        width: '100%',
        height: '100%',
      }}
    />
  );
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', mountHeroRemotionPlayer);
} else {
  mountHeroRemotionPlayer();
}
