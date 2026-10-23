import json,os,urllib.request,io
from PIL import Image
idx={}
for f in ('results1.json','results2.json'):
  for v in json.load(open(f)).values():
    for x in v: idx[x['id']]=x
picks=json.load(open('picks.json'));n={}
for c,r,label,i,fy in picks:
  n[r]=n.get(r,0)+1; os.makedirs(r,exist_ok=True)
  out=f'{r}/{r}-{n[r]}.jpg'
  u=f'https://images.pexels.com/photos/{i}/pexels-photo-{i}.jpeg?auto=compress&cs=tinysrgb&w=2400'
  im=Image.open(io.BytesIO(urllib.request.urlopen(urllib.request.Request(u,headers={'User-Agent':'Mozilla/5.0'}),timeout=90).read())).convert('RGB')
  W,H=im.size
  if W/H>1.5: nw=int(H*1.5); x0=(W-nw)//2; im=im.crop((x0,0,x0+nw,H))
  else: nh=int(W/1.5); y0=int((H-nh)*fy); im=im.crop((0,y0,W,y0+nh))
  im=im.resize((1200,800),Image.LANCZOS); q=85
  while True:
    im.save(out,quality=q,optimize=True,progressive=True)
    if os.path.getsize(out)<=260000 or q<=60: break
    q-=5
  print(out,i,os.path.getsize(out)//1024,'KB q',q)
