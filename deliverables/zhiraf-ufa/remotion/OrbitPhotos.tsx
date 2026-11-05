import {
  AbsoluteFill,
  Img,
  useCurrentFrame,
  useVideoConfig,
} from 'remotion';

const photos = [
  '/images/hero-uae.jpg',
  '/images/hero-turkey.jpg',
  '/images/hero-egypt.jpg',
];

/** Three cards on an ellipse; 12s loop; cards stay upright (no self-rotation). */
export const OrbitPhotos = () => {
  const frame = useCurrentFrame();
  const { fps, width, height } = useVideoConfig();

  const duration = 12 * fps;
  const progress = (frame % duration) / duration;

  const centerX = width / 2;
  const centerY = height / 2;

  return (
    <AbsoluteFill>
      {photos.map((src, index) => {
        const angle =
          -progress * Math.PI * 2 +
          (index / photos.length) * Math.PI * 2 -
          Math.PI / 2;

        const x = Math.cos(angle) * 230;
        const y = Math.sin(angle) * 150;

        return (
          <div
            key={src}
            style={{
              position: 'absolute',
              left: centerX + x,
              top: centerY + y,
              width: 250,
              height: 330,
              transform: 'translate(-50%, -50%)',
              borderRadius: 30,
              overflow: 'hidden',
              boxShadow: '0 24px 45px rgba(0,0,0,0.14)',
              zIndex: Math.round(y + 200),
            }}
          >
            <Img
              src={src}
              style={{
                width: '100%',
                height: '100%',
                objectFit: 'cover',
              }}
            />
          </div>
        );
      })}
    </AbsoluteFill>
  );
};
