import json,re
d=json.load(open('results.json'))
pat={"antalya":r"antalya|kaleici|kaleiçi|konyaalt|hadrian|yivli","alanya":r"alanya|cleopatra|kızıl ?kule|red tower","kemer":r"kemer|g[oö]yn[uü]k|beldibi|phaselis|tekirova|kiri[sş]","belek":r"belek|aspendos","side":r"\bside\b","dalaman":r"dalaman|dalyan|[oö]l[uü]deniz|fethiye|kaunos",
"hurghada":r"hurghada","sharm":r"sharm|naama|ras moh","marsa-alam":r"marsa ?alam","soma-bay":r"soma bay","makadi":r"makadi","sahl-hasheesh":r"sahl|hasheesh","el-gouna":r"gouna",
"nha-trang":r"nha ?trang|po nagar|ponagar","phu-quoc":r"ph[uú] ?qu[oố]c","cam-ranh":r"cam ?ranh|bai dai|bãi dài","phan-thiet":r"phan ?thiet|m[uũ]i n[eé]"}
for k,v in d.items():
  seen=set();print('=====',k)
  for x in v:
    if x['id'] in seen: continue
    seen.add(x['id'])
    t=(x['alt']+' '+x['url']).lower()
    if re.search(pat[k],t) and x['w']>=1600 and x['w']>x['h']:
      print(x['id'],x['w'],'x',x['h'],'|',x['author'],'|',x['alt'][:150])
