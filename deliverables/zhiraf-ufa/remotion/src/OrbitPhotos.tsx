import { AbsoluteFill, Img, useCurrentFrame, useVideoConfig } from 'remotion';

export type OrbitPhotosProps = {
  photos: string[];
};

export const OrbitPhotos: React.FC<OrbitPhotosProps> = ({ photos }) => {
  const frame = useCurrentFrame();
  const { fps, width, height } = useVideoConfig();

  const sources = photos.length >= 3 ? photos.slice(0, 3) : photos;
  if (sources.length < 3) {
    return null;
  }

  const duration = 12 * fps;
  const progress = (frame % duration) / duration;
  const centerX = width / 2;
  const centerY = height / 2;
  const rx = width * 0.44;
  const ry = height * 0.3;
  const cardW = Math.min(250, width * 0.48);
  const cardH = cardW * 1.32;

  return (
    <AbsoluteFill style={{ background: 'transparent' }}>
      {sources.map((src, index) => {
        const angle =
          -progress * Math.PI * 2 +
          (index / sources.length) * Math.PI * 2 -
          Math.PI / 2;
        const x = Math.cos(angle) * rx;
        const y = Math.sin(angle) * ry;

        return (
          <div
            key={`${src}-${index}`}
            style={{
              position: 'absolute',
              left: centerX + x,
              top: centerY + y,
              width: cardW,
              height: cardH,
              transform: 'translate(-50%, -50%)',
              borderRadius: 24,
              overflow: 'hidden',
              boxShadow: '0 20px 40px rgba(0,0,0,0.14)',
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
