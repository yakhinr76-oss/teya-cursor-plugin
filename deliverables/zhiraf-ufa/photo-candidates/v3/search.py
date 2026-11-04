import os,json,sys,urllib.request,urllib.parse
key=os.environ['PEXELS_API_KEY']
qs=json.load(open('queries.json'))
out={}
for resort,terms in qs.items():
  res=[]
  for q in terms:
    for page in (1,2):
      u='https://api.pexels.com/v1/search?'+urllib.parse.urlencode({'query':q,'per_page':40,'page':page})
      r=urllib.request.Request(u,headers={'Authorization':key,'User-Agent':'Mozilla/5.0'})
      try: d=json.load(urllib.request.urlopen(r,timeout=30))
      except Exception as e: print('ERR',q,e,file=sys.stderr); continue
      for p in d['photos']:
        res.append({'id':p['id'],'url':p['url'],'alt':p['alt'],'author':p['photographer'],'w':p['width'],'h':p['height'],'src':p['src']['large2x'],'orig':p['src']['original'],'q':q})
  out[resort]=res
json.dump(out,open('results.json','w'),ensure_ascii=False)
