import json,os,urllib.request
from PIL import Image,ImageDraw,ImageFont
from concurrent.futures import ThreadPoolExecutor
d=json.load(open('results.json'));s=json.load(open('short.json'))
idx={}
for v in d.values():
  for x in v: idx[x['id']]=x
os.makedirs('review',exist_ok=True)
jobs=[(r,i) for r,ids in s.items() for i in ids]
def get(j):
  r,i=j;p=f'review/{r}_{i}.jpg'
  if not os.path.exists(p):
    u=idx[i]['src'].replace('h=650&w=940','h=900&w=1300')
    u=u.replace('dpr=2&','');open(p,'wb').write(urllib.request.urlopen(urllib.request.Request(u,headers={'User-Agent':'Mozilla/5.0'}),timeout=60).read())
  return p
with ThreadPoolExecutor(8) as ex: list(ex.map(get,jobs))
font=ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',22)
for r,ids in s.items():
  W=640;H=427;cols=min(3,len(ids));rows=(len(ids)+cols-1)//cols
  sh=Image.new('RGB',(W*cols,H*rows),'white')
  for n,i in enumerate(ids):
    im=Image.open(f'review/{r}_{i}.jpg').convert('RGB');im.thumbnail((W,H))
    x=(n%cols)*W;y=(n//cols)*H;sh.paste(im,(x,y))
    ImageDraw.Draw(sh).text((x+8,y+8),str(i),fill='yellow',font=font,stroke_width=2,stroke_fill='black')
  sh.save(f'review/_{r}.jpg',quality=85)
