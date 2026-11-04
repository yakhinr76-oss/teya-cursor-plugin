import json,csv
from PIL import Image,ImageDraw,ImageFont
idx={}
for f in ('results1.json','results2.json'):
  for v in json.load(open(f)).values():
    for x in v: idx[x['id']]=x
picks=json.load(open('picks.json'))
for p in picks:
  pass
fb=ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',26)
fs=ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',17)
names={'turciya':('turkey','Турция'),'egipet':('egypt','Египет'),'vietnam':('vietnam','Вьетнам')}
rows=[];n={}
for c,r,label,i,fy in picks:
  n[r]=n.get(r,0)+1; x=idx[i]
  rows.append(dict(country=names[c][1],resort=label,candidate=n[r],file=f'{r}/{r}-{n[r]}.jpg',pexels_id=i,page_url=x['url'],author=x['author'],license='Pexels License (free commercial use)',description=x['alt']))
with open('credits_v3.csv','w',newline='') as f:
  w=csv.DictWriter(f,fieldnames=list(rows[0]));w.writeheader();w.writerows(rows)
TW,TH=600,400;CAP=70
for c,(en,ru) in names.items():
  items=[r for r in rows if r['country']==ru]
  res=list(dict.fromkeys(r['resort'] for r in items))
  sh=Image.new('RGB',(TW*2+30,80+len(res)*(TH+CAP+15)),'white');d=ImageDraw.Draw(sh)
  d.text((10,20),f'{ru} — кандидаты фото курортов (Pexels), 19.10.2026',font=fb,fill='black')
  for ri,rs in enumerate(res):
    for it in [r for r in items if r['resort']==rs]:
      x=10+(it['candidate']-1)*(TW+10);y=80+ri*(TH+CAP+15)
      im=Image.open(it['file']).resize((TW,TH));sh.paste(im,(x,y))
      d.text((x,y+TH+4),f"{rs} — вариант {it['candidate']}"+(' (Аспендос)' if it['pexels_id']==19004827 else ''),font=fb,fill='black')
      d.text((x,y+TH+38),f"{it['author']} · pexels.com/photo/{it['pexels_id']}",font=fs,fill='#555')
  sh.save(f'sheet-{en}.png',optimize=True);print(f'sheet-{en}.png',sh.size)
