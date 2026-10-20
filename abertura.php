<?php
// Se clicou em pular ou o vídeo acabou, criamos um cookie que dura 1 dia e vai para o index
if (isset($_GET['visto'])) {
    setcookie('viu_abertura', '1', time() + 86400, '/');
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>LUMEN — Abertura com personagem</title>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400&display=swap" rel="stylesheet">
<style>
:root{color-scheme:dark;--bg:#08090D;--g:#C9A96E;--w:#F5F3EF;box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
html{height:100%;scroll-padding-top:env(safe-area-inset-top,0px)}
body{height:100%;margin:0;background:var(--bg);display:grid;place-items:center;overflow:hidden;font-family:'Jost',system-ui,sans-serif}
canvas{width:min(100vw,calc((100vh - env(safe-area-inset-top,0px) - env(safe-area-inset-bottom,0px))*16/9));aspect-ratio:16/9;background:var(--bg)}
#ov{position:fixed;inset:0;display:flex;flex-direction:column;gap:14px;align-items:center;justify-content:center;background:rgba(8,9,13,.8);color:var(--w)}
#ov.end{background:none;justify-content:flex-end;padding-bottom:24px;pointer-events:none}#ov.end *{pointer-events:auto}
#ov button{font:400 17px 'Jost',sans-serif;letter-spacing:.35em;text-transform:uppercase;color:var(--w);background:transparent;border:1px solid var(--g);padding:16px 30px;cursor:pointer;transition:.3s}
#ov button:hover{background:var(--g);color:var(--bg)}
#ov small{color:var(--g);letter-spacing:.2em;font-size:12px}
#skip-btn{position:fixed;top:20px;right:20px;z-index:99;font:400 13px 'Jost',sans-serif;letter-spacing:.2em;text-transform:uppercase;color:var(--w);background:rgba(8,9,13,.6);border:1px solid var(--g);padding:10px 18px;cursor:pointer;transition:.3s;text-decoration:none}
#skip-btn:hover{background:var(--g);color:var(--bg)}
</style></head><body>
<a id="skip-btn" href="abertura.php?visto=1">Pular Abertura ➔</a>
<canvas id="c"></canvas>
<div id="ov"><button id="b">▶ Assistir abertura</button><small>ATIVE O SOM · 30 SEGUNDOS</small></div>
<script>
const C=document.getElementById('c'),x=C.getContext('2d'),W0=1920,H=1080,cx=960;C.width=W0;C.height=H;
const G='#C9A96E',WH='#F5F3EF',BK='#08090D',GR='#171A22',FL=650;
const cl=(v,a=0,b=1)=>Math.min(b,Math.max(a,v)),sm=v=>{v=cl(v);return v*v*(3-2*v)},ph=(t,a,b)=>sm((t-a)/(b-a)),lp=(a,b,u)=>a+(b-a)*u;
const bk=u=>{u=cl(u);return u>=1?1:1+2.7*Math.pow(u-1,3)+1.7*Math.pow(u-1,2)};
function glow(X,Y,r,a,c='201,169,110'){const g=x.createRadialGradient(X,Y,0,X,Y,r);g.addColorStop(0,`rgba(${c},${a})`);g.addColorStop(1,`rgba(${c},0)`);x.fillStyle=g;x.fillRect(X-r,Y-r,r*2,r*2)}
// ---------- posters 170x255 ----------
function art(i){const k=document.createElement('canvas');k.width=340;k.height=510;const c=k.getContext('2d');c.scale(2,2);
const S=[['#171A22','#6b5732','#C9A96E'],['#08090D','#171A22','#8a7440'],['#171A22','#4a3b24','#C9A96E'],['#F5F3EF','#E8D7AE','#C9A96E'],['#08090D','#171A22','#3a3226']][i];
let g=c.createLinearGradient(0,0,0,255);g.addColorStop(0,S[0]);g.addColorStop(.6,S[1]);g.addColorStop(1,S[2]);c.fillStyle=g;c.fillRect(0,0,170,255);
const R=(n=>()=>(n=(n*16807)%2147483647)/2147483647)(i*31+5),win=(a,b,w,h)=>{for(let y=b;y<b+h-8;y+=10)for(let q=a;q<a+w-5;q+=8)if(R()>.5){c.fillStyle=G;c.fillRect(q+1,y,3,5)}};
const man=(X,Y,s,col)=>{c.fillStyle=col;c.beginPath();c.arc(X,Y-62*s,9*s,0,7);c.fill();c.fillRect(X-9*s,Y-52*s,18*s,34*s);c.fillRect(X-9*s,Y-20*s,7*s,20*s);c.fillRect(X+2*s,Y-20*s,7*s,20*s)};
if(i==0){c.fillStyle='#12141a';for(let b=0;b<170;b+=22){const h=60+R()*70;c.fillRect(b,170-h,20,h);win(b,170-h,20,h)}c.fillStyle='#08090D';c.fillRect(0,170,170,85);man(85,255,1.5,'#08090D');c.beginPath();c.moveTo(70,170);c.lineTo(30,250);c.lineTo(100,230);c.fill()}
if(i==1){c.fillStyle=G;c.beginPath();c.arc(85,150,52,Math.PI,0);c.fill();for(let b=10;b<170;b+=24){const h=50+R()*80;c.fillStyle='#0b0c11';c.fillRect(b,160-h,10,h);c.fillStyle=WH;c.fillRect(b+4,160-h,2,h)}c.fillStyle='#08090D';c.fillRect(0,160,170,95);c.strokeStyle=G;c.lineWidth=1;for(let q=-4;q<=4;q++){c.beginPath();c.moveTo(85+q*8,160);c.lineTo(85+q*60,255);c.stroke()}for(let y=165;y<255;y+=14+(y-160)/8){c.beginPath();c.moveTo(0,y);c.lineTo(170,y);c.stroke()}}
if(i==2){for(let q=0;q<26;q++){c.fillStyle=`rgba(201,169,110,${.15+R()*.3})`;c.beginPath();c.arc(R()*170,40+R()*110,4+R()*10,0,7);c.fill()}man(70,235,1.7,'#08090D');man(102,235,1.55,'#0e1015')}
if(i==3){c.fillStyle='#C9A96E';c.beginPath();c.moveTo(0,200);c.quadraticCurveTo(85,150,170,205);c.lineTo(170,255);c.lineTo(0,255);c.fill();c.fillStyle='#171A22';c.beginPath();c.moveTo(0,230);c.quadraticCurveTo(100,190,170,235);c.lineTo(170,255);c.lineTo(0,255);c.fill();
const bl=(X,Y,r,f)=>{c.fillStyle=f;c.beginPath();c.arc(X,Y,r,0,7);c.fill();[-r*.35,r*.35].forEach(d=>{c.fillStyle=WH;c.beginPath();c.arc(X+d,Y-r*.2,r*.26,0,7);c.fill();c.fillStyle='#08090D';c.beginPath();c.arc(X+d,Y-r*.2,r*.11,0,7);c.fill()})};bl(65,185,40,G);bl(122,205,22,'#171A22');c.fillStyle=G;c.beginPath();c.arc(130,45,20,0,7);c.fill()}
if(i==4){c.fillStyle='#0b0c11';c.fillRect(0,200,170,55);c.beginPath();c.moveTo(45,200);c.lineTo(45,70);c.quadraticCurveTo(85,20,125,70);c.lineTo(125,200);c.fill();g=c.createLinearGradient(0,60,0,200);g.addColorStop(0,'#F0DDB0');g.addColorStop(1,G);c.fillStyle=g;c.fillRect(82,62,6,138);c.fillStyle='rgba(201,169,110,.3)';c.beginPath();c.moveTo(82,200);c.lineTo(30,255);c.lineTo(140,255);c.lineTo(88,200);c.fill();man(85,250,1.2,'#08090D')}
g=c.createRadialGradient(85,127,70,85,127,170);g.addColorStop(0,'rgba(0,0,0,0)');g.addColorStop(1,'rgba(8,9,13,.55)');c.fillStyle=g;c.fillRect(0,0,170,255);return k}
function art2(i){const k=document.createElement('canvas');k.width=340;k.height=510;const c=k.getContext('2d');c.scale(2,2);
const R=(n=>()=>(n=(n*16807)%2147483647)/2147483647)(i*31+9),rc=(a,b,w,h,r,f)=>{c.fillStyle=f;c.beginPath();c.roundRect(a,b,w,h,r);c.fill()};
const lg=(y0,y1,st)=>{const g=c.createLinearGradient(0,y0,0,y1);st.forEach((s,j)=>g.addColorStop(j/(st.length-1),s));return g};
const rg=(X,Y,r,a)=>{const g=c.createRadialGradient(X,Y,0,X,Y,r);g.addColorStop(0,`rgba(240,221,176,${a})`);g.addColorStop(1,'rgba(240,221,176,0)');c.fillStyle=g;c.fillRect(X-r,Y-r,r*2,r*2)};
const man=(X,Y,s,col,cape)=>{c.fillStyle=col;if(cape){c.beginPath();c.moveTo(X-6*s,Y-52*s);c.quadraticCurveTo(X-40*s,Y-30*s,X-50*s,Y+4);c.lineTo(X+6*s,Y-18*s);c.fill()}c.beginPath();c.arc(X,Y-62*s,9*s,0,7);c.fill();c.fillRect(X-9*s,Y-52*s,18*s,34*s);c.fillRect(X-9*s,Y-20*s,7*s,20*s);c.fillRect(X+2*s,Y-20*s,7*s,20*s)};
const rays=(X,Y,n,a)=>{c.fillStyle=`rgba(240,221,176,${a})`;for(let q=0;q<n;q++){const t=q*Math.PI*2/n;c.beginPath();c.moveTo(X,Y);c.lineTo(X+Math.cos(t)*260,Y+Math.sin(t)*260);c.lineTo(X+Math.cos(t+.07)*260,Y+Math.sin(t+.07)*260);c.fill()}};
if(i==0){c.fillStyle=lg(0,255,['#171A22','#6b5732','#E6CF9E']);c.fillRect(0,0,170,255);rg(85,105,100,.9);rays(85,105,14,.07);c.fillStyle='#F5F3EF';c.beginPath();c.arc(85,105,24,0,7);c.fill();
for(let b=4;b<170;b+=17){const h=40+R()*60;c.fillStyle='#171A22';c.fillRect(b,170-h,14,h);if(R()>.5){c.beginPath();c.arc(b+7,170-h,7,Math.PI,0);c.fill()}else c.fillRect(b+6,170-h-14,2,14);for(let y=170-h+6;y<165;y+=9)if(R()>.5){c.fillStyle='#C9A96E';c.fillRect(b+3,y,3,4)}}
c.fillStyle='#08090D';c.beginPath();c.moveTo(30,255);c.lineTo(58,196);c.lineTo(115,196);c.lineTo(145,255);c.fill();man(88,198,1.25,'#08090D',true);c.fillStyle='#F5F3EF';c.fillRect(104,150,2,34)}
if(i==1){c.fillStyle=lg(0,255,['#08090D','#171A22','#8a7440']);c.fillRect(0,0,170,255);rg(85,100,90,.7);c.strokeStyle='#C9A96E';c.lineWidth=8;c.beginPath();c.ellipse(85,100,44,62,0,0,7);c.stroke();c.strokeStyle='#F5F3EF';c.lineWidth=2;c.beginPath();c.ellipse(85,100,36,54,0,0,7);c.stroke();
c.fillStyle=lg(40,160,['#F0DDB0','#C9A96E','#171A22']);c.beginPath();c.ellipse(85,100,34,52,0,0,7);c.fill();c.fillStyle='#08090D';c.fillRect(0,168,170,87);c.strokeStyle='#C9A96E';c.lineWidth=.8;for(let q=-6;q<=6;q++){c.beginPath();c.moveTo(85+q*6,168);c.lineTo(85+q*40,255);c.stroke()}for(let y=172;y<255;y+=8+(y-168)/6){c.beginPath();c.moveTo(0,y);c.lineTo(170,y);c.stroke()}man(85,186,.9,'#08090D')}
if(i==2){c.fillStyle=lg(0,255,['#171A22','#4a3b24','#C9A96E']);c.fillRect(0,0,170,255);for(let q=0;q<30;q++){c.fillStyle=`rgba(240,221,176,${.12+R()*.3})`;c.beginPath();c.arc(R()*170,30+R()*120,3+R()*11,0,7);c.fill()}c.strokeStyle='rgba(245,243,239,.12)';for(let q=0;q<40;q++){const X=R()*190;c.beginPath();c.moveTo(X,0);c.lineTo(X-14,255);c.stroke()}man(68,240,2,'#08090D');man(104,240,1.9,'#101218');c.strokeStyle='rgba(245,243,239,.5)';c.lineWidth=1;c.beginPath();c.arc(77,114,7,-1.2,1.2);c.stroke()}
if(i==3){c.fillStyle=lg(0,255,['#F5F3EF','#E8D7AE','#C9A96E']);c.fillRect(0,0,170,255);rg(130,45,60,1);c.fillStyle='#C9A96E';c.beginPath();c.arc(130,45,16,0,7);c.fill();for(let q=0;q<14;q++){c.fillStyle='rgba(201,169,110,.6)';c.beginPath();c.arc(R()*170,20+R()*110,2+R()*4,0,7);c.fill()}
c.fillStyle='#C9A96E';c.beginPath();c.moveTo(0,185);c.quadraticCurveTo(90,130,170,190);c.lineTo(170,255);c.lineTo(0,255);c.fill();c.fillStyle='#171A22';c.beginPath();c.moveTo(0,215);c.quadraticCurveTo(100,170,170,220);c.lineTo(170,255);c.lineTo(0,255);c.fill();
const bl=(X,Y,r,f)=>{c.fillStyle=f;c.beginPath();c.arc(X,Y,r,0,7);c.fill();[-.33,.33].forEach(d=>{c.fillStyle='#F5F3EF';c.beginPath();c.arc(X+d*r,Y-r*.2,r*.27,0,7);c.fill();c.fillStyle='#08090D';c.beginPath();c.arc(X+d*r+1,Y-r*.2,r*.12,0,7);c.fill()});c.strokeStyle='#08090D';c.lineWidth=1.5;c.beginPath();c.arc(X,Y+r*.15,r*.22,.2,2.9);c.stroke()};bl(62,168,36,'#E6CF9E');bl(122,196,20,'#171A22')}
if(i==4){c.fillStyle=lg(0,255,['#08090D','#10121a','#2a2418']);c.fillRect(0,0,170,255);c.fillStyle='#0b0c11';c.beginPath();c.moveTo(40,215);c.lineTo(40,70);c.quadraticCurveTo(85,15,130,70);c.lineTo(130,215);c.fill();c.fillStyle=lg(50,200,['#FFF3D6','#C9A96E']);c.fillRect(82,48,7,152);c.fillStyle='rgba(201,169,110,.35)';c.beginPath();c.moveTo(82,200);c.lineTo(20,255);c.lineTo(150,255);c.lineTo(89,200);c.fill();man(85,250,1.9,'#08090D');rg(85,230,90,.15)}
c.fillStyle=lg(120,255,['rgba(8,9,13,0)','rgba(8,9,13,.94)']);c.fillRect(0,120,170,135);c.fillStyle=lg(0,60,['rgba(8,9,13,.6)','rgba(8,9,13,0)']);c.fillRect(0,0,170,60);
rc(55,12,60,2,1,'rgba(245,243,239,.55)');rc(65,178,40,1.5,1,'rgba(201,169,110,.9)');
const row=(ws,y,h,col)=>{const t=ws.reduce((a,b)=>a+b,0)+(ws.length-1)*3;let X=85-t/2;ws.forEach(w=>{rc(X,y,w,h,h>9?2:1,col);X+=w+3})};
row([...Array(5)].map(()=>8+R()*12|0),185,16,'#F5F3EF');row([...Array(4)].map(()=>8+R()*9|0),206,5,'#C9A96E');
c.fillStyle='rgba(245,243,239,.35)';c.fillRect(20,224,130,.7);for(let X=22;X<148;X+=2.6)rc(X,229,1.3,6+R()*7,0,'rgba(245,243,239,.55)');
c.strokeStyle='rgba(201,169,110,.45)';c.lineWidth=1;c.strokeRect(3.5,3.5,163,248);
const g=c.createLinearGradient(0,0,170,255);g.addColorStop(0,'rgba(255,255,255,.14)');g.addColorStop(.45,'rgba(255,255,255,0)');c.fillStyle=g;c.fillRect(0,0,170,255);return k}
let posters=[];
function art3(i){const k=document.createElement('canvas');k.width=340;k.height=510;const c=k.getContext('2d');c.scale(2,2);
const R=(n=>()=>(n=(n*16807)%2147483647)/2147483647)(i*17+3);
const lg=(y0,y1,st)=>{const g=c.createLinearGradient(0,y0,0,y1);st.forEach((s,j)=>g.addColorStop(j/(st.length-1),s));return g};
const rg=(X,Y,r,a)=>{const g=c.createRadialGradient(X,Y,0,X,Y,r);g.addColorStop(0,`rgba(240,221,176,${a})`);g.addColorStop(1,'rgba(240,221,176,0)');c.fillStyle=g;c.fillRect(X-r,Y-r,r*2,r*2)};
const head=(X,Y,s,o)=>{c.save();c.translate(X,Y);c.scale(s*(o.f||1),s);c.rotate(o.r||0);
if(o.h=='long'){c.fillStyle='#08090D';c.beginPath();c.ellipse(0,6,31,40,0,0,7);c.fill();c.fillRect(-31,6,62,80)}
c.fillStyle=o.cl||'#0b0c11';c.beginPath();c.moveTo(-62,95);c.quadraticCurveTo(-54,48,-16,42);c.lineTo(16,42);c.quadraticCurveTo(54,48,62,95);c.fill();c.strokeStyle='rgba(240,221,176,.55)';c.lineWidth=1.3;c.beginPath();c.moveTo(-62,95);c.quadraticCurveTo(-54,48,-16,42);c.stroke();
c.fillStyle='#6b5732';c.fillRect(-9,24,18,24);
let g=c.createLinearGradient(-22,0,22,0);g.addColorStop(0,'#F0DDB0');g.addColorStop(.55,'#C9A96E');g.addColorStop(1,'#4a3b24');c.fillStyle=g;c.beginPath();c.ellipse(0,0,21,27,0,0,7);c.fill();
[-9,9].forEach(d=>{c.fillStyle='#08090D';c.beginPath();c.ellipse(d,-2,3.6,2.2,0,0,7);c.fill();c.fillStyle='#F5F3EF';c.fillRect(d+1,-3,1.2,1.2);c.strokeStyle='#171A22';c.lineWidth=1.3;c.beginPath();c.moveTo(d-5,-8);c.lineTo(d+5,-9.5);c.stroke()});
c.strokeStyle='rgba(58,47,30,.75)';c.lineWidth=1.2;c.beginPath();c.moveTo(1,2);c.lineTo(-1,12);c.lineTo(3,13);c.stroke();c.beginPath();c.moveTo(-6,19);c.quadraticCurveTo(0,o.sm?22:19.5,6,19);c.stroke();
c.save();c.beginPath();c.ellipse(0,0,21,27,0,0,7);c.clip();const g2=c.createLinearGradient(-21,0,21,0);g2.addColorStop(0,'rgba(8,9,13,0)');g2.addColorStop(1,`rgba(8,9,13,${o.sh||.35})`);c.fillStyle=g2;c.fillRect(-22,-28,44,56);c.restore();
c.fillStyle='#08090D';if(o.h=='short'||o.h=='long'){c.beginPath();c.moveTo(-23,-2);c.quadraticCurveTo(-25,-35,0,-32);c.quadraticCurveTo(25,-35,23,-2);c.quadraticCurveTo(14,-20,-4,-20);c.quadraticCurveTo(-16,-18,-23,-2);c.fill()}
if(o.h=='hat'){c.fillRect(-36,-17,72,5);c.beginPath();c.moveTo(-21,-17);c.quadraticCurveTo(0,-50,21,-17);c.fill()}c.restore()};
const bl=(X,Y,r,f)=>{c.fillStyle=f;c.beginPath();c.arc(X,Y,r,0,7);c.fill();[-.33,.33].forEach(d=>{c.fillStyle='#F5F3EF';c.beginPath();c.arc(X+d*r,Y-r*.2,r*.27,0,7);c.fill();c.fillStyle='#08090D';c.beginPath();c.arc(X+d*r+1,Y-r*.2,r*.12,0,7);c.fill()});c.strokeStyle='#08090D';c.lineWidth=1.5;c.beginPath();c.arc(X,Y+r*.15,r*.22,.2,2.9);c.stroke()};
if(i==0){c.fillStyle=lg(0,255,['#171A22','#6b5732','#E6CF9E']);c.fillRect(0,0,170,255);rg(85,70,100,.8);for(let b=0;b<170;b+=15){const h=30+R()*50;c.fillStyle='#171A22';c.fillRect(b,170-h,12,h)}c.strokeStyle='#F0DDB0';c.lineWidth=3;c.shadowColor='#C9A96E';c.shadowBlur=12;c.beginPath();c.arc(85,70,46,0,7);c.stroke();c.shadowBlur=0;head(85,104,1.9,{h:'long',sh:.5})}
if(i==1){c.fillStyle=lg(0,255,['#08090D','#171A22','#5a4a2e']);c.fillRect(0,0,170,255);c.strokeStyle='rgba(201,169,110,.5)';c.lineWidth=1;for(let q=0;q<9;q++){c.beginPath();c.moveTo(10+q*18,0);c.lineTo(10+q*18,160);c.stroke()}head(85,108,1.9,{h:'short',sh:.75});c.fillStyle=lg(96,106,['rgba(255,243,214,0)','#FFF3D6','rgba(255,243,214,0)']);c.fillRect(0,96,170,10)}
if(i==2){c.fillStyle=lg(0,255,['#171A22','#4a3b24','#C9A96E']);c.fillRect(0,0,170,255);for(let q=0;q<30;q++){c.fillStyle=`rgba(240,221,176,${.12+R()*.3})`;c.beginPath();c.arc(R()*170,20+R()*110,3+R()*11,0,7);c.fill()}head(56,122,1.55,{h:'long',r:-.1,sh:.55});head(114,130,1.55,{h:'short',r:.1,f:-1,sh:.55,cl:'#101218'});c.strokeStyle='rgba(245,243,239,.12)';for(let q=0;q<40;q++){const X=R()*190;c.beginPath();c.moveTo(X,0);c.lineTo(X-14,255);c.stroke()}}
if(i==3){c.fillStyle=lg(0,255,['#F5F3EF','#E8D7AE','#C9A96E']);c.fillRect(0,0,170,255);rg(130,40,60,1);c.fillStyle='#C9A96E';c.beginPath();c.arc(130,40,15,0,7);c.fill();for(let q=0;q<14;q++){c.fillStyle='rgba(201,169,110,.6)';c.beginPath();c.arc(R()*170,20+R()*100,2+R()*4,0,7);c.fill()}c.fillStyle='#C9A96E';c.beginPath();c.moveTo(0,185);c.quadraticCurveTo(90,130,170,190);c.lineTo(170,255);c.lineTo(0,255);c.fill();bl(66,128,50,'#E6CF9E');bl(132,170,26,'#171A22')}
if(i==4){c.fillStyle=lg(0,255,['#08090D','#10121a','#2a2418']);c.fillRect(0,0,170,255);c.fillStyle='#0b0c11';c.beginPath();c.moveTo(25,255);c.lineTo(25,60);c.quadraticCurveTo(85,0,145,60);c.lineTo(145,255);c.fill();c.fillStyle=lg(30,200,['#FFF3D6','#C9A96E']);c.fillRect(80,32,10,170);rg(85,110,90,.35);head(85,116,1.9,{h:'hat',sh:.9,cl:'#101218'})}
c.fillStyle=lg(100,255,['rgba(8,9,13,0)','rgba(8,9,13,.96)']);c.fillRect(0,100,170,155);c.fillStyle=lg(0,50,['rgba(8,9,13,.5)','rgba(8,9,13,0)']);c.fillRect(0,0,170,50);
const T=['A COROA DE LUZ','PROTOCOLO ÂMBAR','CHUVA DE OURO','PIPO & ZUZU','A PORTA 13'][i],LB=['SÉRIE','FILME','SÉRIE','FILME','SÉRIE'][i];
c.textAlign='center';c.letterSpacing='1px';c.font='400 24px Jost,sans-serif';let ln=[T];if(c.measureText(T).width>140){const w=T.split(' '),m=Math.ceil(w.length/2);ln=[w.slice(0,m).join(' '),w.slice(m).join(' ')]}
let y=240;const tg=c.createLinearGradient(0,y-50,0,y);tg.addColorStop(0,'#F0DDB0');tg.addColorStop(1,'#C9A96E');
for(let q=ln.length-1;q>=0;q--){c.font='400 24px Jost,sans-serif';const sz=Math.min(24,24*142/c.measureText(ln[q]).width);c.font=`400 ${sz}px Jost,sans-serif`;c.fillStyle=(i==0||i==3)?tg:'#F5F3EF';c.fillText(ln[q],85,y);y-=sz*1.08}
c.font='400 5.5px Jost,sans-serif';c.letterSpacing='2px';const tw=c.measureText(LB).width,lx=85-(11+tw)/2,ly=y+3;c.textAlign='left';c.fillStyle='#C9A96E';c.fillRect(lx,ly-8,8,9);c.fillStyle='#08090D';c.font='400 7px Jost,sans-serif';c.letterSpacing='0px';c.fillText('L',lx+2,ly-.8);c.fillStyle='#F5F3EF';c.font='400 5.5px Jost,sans-serif';c.letterSpacing='2px';c.fillText(LB,lx+11,ly-1);c.letterSpacing='0px';
c.strokeStyle='rgba(201,169,110,.45)';c.lineWidth=1;c.strokeRect(3.5,3.5,163,248);const g=c.createLinearGradient(0,0,170,255);g.addColorStop(0,'rgba(255,255,255,.12)');g.addColorStop(.45,'rgba(255,255,255,0)');c.fillStyle=g;c.fillRect(0,0,170,255);return k}
// ---------- layout / timeline ----------
let W,tp,SZ=230,LX=[],ws=[],cap=160,Y0=650,tPush=13,tWave=17.9;
const arc=i=>({x:960+(i-2)*250,B:660-16*Math.abs(i-2),s:1-.1*Math.abs(i-2)});
const p2=i=>({x:360+300*i,B:660,s:1});
function build(){posters=[0,1,2,3,4].map(art2);x.font=`400 ${SZ}px Jost,sans-serif`;ws=[...'LUMEN'].map(c=>x.measureText(c).width);const sp=.2*SZ,tw=ws.reduce((a,b)=>a+b)+sp*4;let X=cx-tw/2;LX=ws.map(w=>{const r=X;X+=w+sp;return r});cap=x.measureText('M').actualBoundingBoxAscent||160;
const top=i=>{const a=arc(i);return a.B-14*a.s-255*a.s},p=(t,X,Y,h,ev)=>({t,x:X,y:Y,h,ev:ev||{}}),Lc=LX[0]+ws[0]*.45;
W=[p(0,960,600,0),p(3.4,960,615,0),p(4.7,960,700,110),p(5.4,520,700,0),p(6,360,646,90,{pop:0}),p(7,660,646,80,{pop:1}),p(7.9,960,646,80,{pop:2}),p(8.6,1260,646,80,{pop:3}),p(9.3,1560,646,80,{pop:4}),
p(9.9,1500,700,60),p(11.6,1180,700,0),p(12.9,1075,700,0,{push:1}),p(13.6,1075,700,0),p(15.9,arc(0).x,top(0),150),p(16.35,arc(1).x,top(1),90),p(16.8,arc(2).x,top(2),90),p(17.25,arc(3).x,top(3),90),p(17.9,1800,672,200,{wave:1}),
p(19.6,1560,700,80),p(20.6,Lc,700,0),p(21.1,Lc,700,150),p(21.7,Lc+40,700,150),p(22.6,LX[0]+ws[0]*.6,Y0-16,100),p(23.5,LX[2]+ws[2]*.18,Y0-cap,330,{M:1}),p(25,1580,700,200,{hit:1}),p(26.4,1640,700,90)];
tp=[];W.forEach(w=>{if(w.ev.pop!=null)tp[w.ev.pop]=w.t;if(w.ev.push)tPush=w.t;if(w.ev.wave)tWave=w.t})}
function chr(t){let i=0;while(i<W.length-1&&W[i+1].t<=t)i++;const a=W[i],b=W[i+1];if(!b)return{x:a.x,y:a.y,sx:1,sy:1+.02*Math.sin(t*3),d:-1};
const dur=b.t-a.t,hd=b.h?Math.min(dur,.45+b.h/500):dur,dep=b.t-hd;let X=a.x,Y=a.y,sx=1,sy=1;
if(t>=dep){const u=(t-dep)/hd;X=lp(a.x,b.x,b.h?u:sm(u));Y=lp(a.y,b.y,b.h?u:sm(u))-4*b.h*u*(1-u);if(b.h){sy=1+.18*Math.sin(u*Math.PI);sx=1/sy}else Y-=Math.abs(Math.sin(u*dur*9))*8}
else{if(t>dep-.15&&b.h){const q=(t-dep+.15)/.15;sy=1-.22*q;sx=1+.18*q}sy+=.02*Math.sin(t*3)}
const lt=t-a.t;if(lt>=0&&lt<.25&&a.h){const q=1-lt/.25;sy*=1-.25*q;sx*=1+.2*q}
return{x:X,y:Y,sx,sy,d:Math.sign(b.x-a.x)||1}}
function lamp(t){if(t<3.3)return;const c=chr(t);x.save();x.translate(c.x,c.y);x.save();x.scale(1,.28);glow(0,0,200,.5);x.restore();glow(0,-50*c.sy,150,.5);
x.scale(c.sx,c.sy);x.fillStyle='#08090D';x.fillRect(-14,-14,8,14);x.fillRect(6,-14,8,14);x.rotate(t<5?Math.sin(t*3)*.12*ph(t,3.4,4.2)*(1-ph(t,4.3,4.7)):0);
let g=x.createLinearGradient(-28,0,28,0);g.addColorStop(0,'#8f7340');g.addColorStop(.4,'#F0DDB0');g.addColorStop(1,G);x.fillStyle=g;x.beginPath();x.roundRect(-28,-80,56,68,26);x.fill();
x.strokeStyle=G;x.lineWidth=3;x.beginPath();x.moveTo(0,-80);x.quadraticCurveTo(8,-98,-4,-108);x.stroke();x.shadowColor=G;x.shadowBlur=24;x.fillStyle=WH;x.beginPath();x.arc(-4,-110,7,0,7);x.fill();x.beginPath();x.arc(c.d*5,-54,17,0,7);x.fill();x.shadowBlur=0;x.fillStyle=GR;x.beginPath();x.arc(c.d*9,-54,7,0,7);x.fill();x.restore()}
// ---------- scene pieces ----------
function slabs(t){const g=6+ph(t,2.2,4.3)*100,sl=ph(t,4.6,6.6)*1000,fo=1-ph(t,5.6,7),be=ph(t,.2,2);x.save();x.globalAlpha=fo;
let f=x.createLinearGradient(0,FL,0,H);f.addColorStop(0,'rgba(201,169,110,'+.5*be+')');f.addColorStop(1,'rgba(201,169,110,0)');x.fillStyle=f;x.beginPath();x.moveTo(cx-g/2,FL);x.lineTo(cx+g/2,FL);x.lineTo(cx+g/2+520,H);x.lineTo(cx-g/2-520,H);x.fill();
glow(cx,330,420,.5*be);x.fillStyle='#0b0c11';x.fillRect(-1000-sl,0,cx-g/2+1000,FL);x.fillRect(cx+g/2+sl,0,2000,FL);
let b=x.createLinearGradient(cx-g/2,0,cx+g/2,0);b.addColorStop(0,G);b.addColorStop(.5,'#FFF3D6');b.addColorStop(1,G);x.shadowColor=G;x.shadowBlur=50;x.globalAlpha=fo*be;x.fillStyle=b;x.fillRect(cx-g/2-sl,0,g,FL);x.restore()}
function card(i,t){const a1=p2(i),a2=arc(i),a=sm((t-13.4-i*.15)/1.8),X=lp(a1.x,a2.x,a),B=lp(a1.B,a2.B,a),s=lp(a1.s,a2.s,a),u=t-(tp[i]||99),r=bk(u/.55),ang=u>0?Math.sin(u*14)*.08*Math.exp(-u*3):0,gl=ph(t,tWave,tWave+.6)*(1-ph(t,tWave+.8,tWave+1.8));
x.save();x.globalAlpha=ph(t,5,5.6)*(1-ph(t,18.6,19.6));x.translate(X,B-14*s);x.fillStyle=GR;x.strokeStyle=G;x.lineWidth=2;x.fillRect(-95*s,0,190*s,14*s);x.strokeRect(-95*s,0,190*s,14*s);
x.rotate(ang);x.scale(s,s*(.16+.84*Math.max(0,r)));x.shadowColor=G;x.shadowBlur=20*cl(r)+70*gl;x.drawImage(posters[i],-85,-255,170,255);x.strokeStyle=gl>.05?'#F0DDB0':G;x.lineWidth=3;x.strokeRect(-85,-255,170,255);x.restore()}
function playSym(t){const a=ph(t,11.6,12.4)*(1-ph(t,18.6,19.6));if(a<=0)return;const u=ph(t,tPush,tPush+2.6),rot=u*Math.PI*4,lift=-170*Math.sin(u*Math.PI),X=1000+60*u;x.save();x.globalAlpha=a;x.translate(X,700+lift);const c=Math.cos(rot);
for(let d=8;d>=0;d--){x.save();x.translate(Math.sin(rot)*d*3,-70);x.scale(c*.9,.9);x.beginPath();x.moveTo(-45,-70);x.lineTo(65,0);x.lineTo(-45,70);x.closePath();x.lineJoin='round';x.fillStyle=d?'#8a8780':WH;x.fill();x.strokeStyle=d?'#5e4a28':G;x.lineWidth=7;x.stroke();x.restore()}x.restore()}
function logo(t){const e=ph(t,18.2,19.8);if(e<=0)return;const oy=(1-e)*70;x.save();x.font=`400 ${SZ}px Jost,sans-serif`;
const L=(i,dx,dy,f)=>{x.fillStyle=f;x.fillText('LUMEN'[i],LX[i]+dx,Y0+dy)};
x.save();x.beginPath();x.rect(0,Y0+4,W0,110);x.clip();x.globalAlpha=e*.14;x.translate(0,2*Y0+oy);x.scale(1,-1);[0,1,2,3,4].forEach(i=>L(i,0,0,i==2?G:WH));x.restore();
x.globalAlpha=e;for(let i=0;i<5;i++){for(let d=18;d>0;d-=2)L(i,d*.4,oy+d*.5,i==2?'#4d3d22':'#34353b');
let g=x.createLinearGradient(0,Y0-cap,0,Y0);if(i==2){g.addColorStop(0,'#F0DDB0');g.addColorStop(.5,G);g.addColorStop(1,'#8f7340')}else{g.addColorStop(0,'#FFF');g.addColorStop(.6,WH);g.addColorStop(1,'#cfc9bd')}L(i,0,oy,g)}
const sw=ph(t,23.5,24.8);if(sw>0&&sw<1){const bx=LX[0]-150+sw*(LX[4]+ws[4]-LX[0]+300);let g=x.createLinearGradient(bx-160,0,bx+160,0);g.addColorStop(0,'rgba(240,221,176,0)');g.addColorStop(.5,'rgba(255,236,190,.95)');g.addColorStop(1,'rgba(240,221,176,0)');for(let i=0;i<5;i++)L(i,0,0,g)}
const hit=ph(t,25,25.4)*(1-ph(t,25.4,27.5));if(hit>0){x.globalCompositeOperation='lighter';glow(cx,Y0-cap/2,700,.5*hit)}x.restore()}
function zoom(t){return 1+.1*ph(t,0,5)*(1-ph(t,5,6))+.12*ph(t,18,24)-.12*ph(t,24.2,26)}
function frame(t){x.setTransform(1,0,0,1,0,0);x.globalAlpha=1;x.globalCompositeOperation='source-over';x.fillStyle=BK;x.fillRect(0,0,W0,H);
let f=x.createLinearGradient(0,FL,0,H);f.addColorStop(0,'#1d2029');f.addColorStop(1,BK);x.fillStyle=f;x.fillRect(0,FL,W0,H-FL);
const z=zoom(t);x.translate(cx,FL);x.scale(z,z);x.translate(-cx,-FL);
if(t>=24.9&&t<25.6){const s=(25.6-t)*12;x.translate((Math.random()-.5)*s,(Math.random()-.5)*s)}
if(t<7)slabs(t);
if(t>=16.7&&t<19.8){x.save();x.globalAlpha=ph(t,16.7,17.3)*(1-ph(t,18.6,19.6));x.fillStyle=GR;x.strokeStyle=G;x.lineWidth=2;x.fillRect(1750,672,100,28);x.strokeRect(1750,672,100,28);x.beginPath();x.ellipse(1800,672,50,9,0,0,7);x.fillStyle='#2a2c33';x.fill();x.stroke();x.restore()}
if(t>=5&&t<19.8){for(let i=0;i<5;i++)card(i,t);playSym(t)}
if(t>=tWave&&t<tWave+2){const r=(t-tWave)*1100;x.save();x.globalAlpha=1-(t-tWave)/2;x.strokeStyle='#F0DDB0';x.shadowColor=G;x.shadowBlur=30;x.lineWidth=5;x.beginPath();x.ellipse(1800,700,r,r*.16,0,0,7);x.stroke();x.restore()}
logo(t);lamp(t);
if(t>=25&&t<26){x.globalAlpha=1-ph(t,25,26);x.fillStyle='#FFF3D6';x.globalAlpha*=.6;x.fillRect(-50,-50,W0+100,H+100);x.globalAlpha=1}
x.setTransform(1,0,0,1,0,0);x.textAlign='center';x.fillStyle=WH;x.letterSpacing='4px';x.font='300 40px Jost,sans-serif';x.globalAlpha=ph(t,25.6,26.6);x.fillText('Onde a luz encontra as histórias.',cx,850);
x.letterSpacing='13px';x.font='400 22px Jost,sans-serif';x.fillStyle=G;x.globalAlpha=ph(t,26.6,27.6);x.fillText('CINEMA · SÉRIES · EXPERIÊNCIAS',cx,925);x.globalAlpha=1;x.textAlign='left';x.letterSpacing='0px'}
// ---------- audio ----------
let A;function audio(){A=new AudioContext();const m=A.createGain();m.gain.value=.75;m.connect(A.destination);const T=A.currentTime+.15;
const o=(f,t0,d,v,ty='sine',f2=0,at)=>{const s=A.createOscillator(),g=A.createGain();s.type=ty;s.frequency.setValueAtTime(f,T+t0);if(f2)s.frequency.exponentialRampToValueAtTime(f2,T+t0+d);at=at??Math.min(d*.2,.4);g.gain.setValueAtTime(0,T+t0);g.gain.linearRampToValueAtTime(v,T+t0+at);g.gain.linearRampToValueAtTime(0,T+t0+d);s.connect(g);g.connect(m);s.start(T+t0);s.stop(T+t0+d+.1)};
const nb=A.createBuffer(1,A.sampleRate*3,A.sampleRate),dd=nb.getChannelData(0);for(let i=0;i<dd.length;i++)dd[i]=Math.random()*2-1;
const n=(t0,d,v,f1,f2,at)=>{const s=A.createBufferSource(),f=A.createBiquadFilter(),g=A.createGain();s.buffer=nb;f.type='bandpass';f.Q.value=1.2;f.frequency.setValueAtTime(f1,T+t0);f.frequency.exponentialRampToValueAtTime(f2,T+t0+d);at=at??d*.5;g.gain.setValueAtTime(0,T+t0);g.gain.linearRampToValueAtTime(v,T+t0+at);g.gain.linearRampToValueAtTime(0,T+t0+d);s.connect(f);f.connect(g);g.connect(m);s.start(T+t0);s.stop(T+t0+d)};
o(55,0,25,.2,'triangle',0,3);o(82.4,0,25,.1,'triangle',0,5);n(.2,4,.12,200,600,2);
for(let i=1;i<W.length;i++){const a=W[i-1],b=W[i];if(b.h){const hd=Math.min(b.t-a.t,.45+b.h/500);o(300,b.t-hd,.18,.12,'sine',720);o(110,b.t,.12,.28,'sine',60);n(b.t,.08,.2,900,500,.005)}else if(i>2)for(let s=a.t+.15;s<b.t;s+=.17)n(s,.06,.12,1200,700,.005)}
const pent=[392,440,523,587,659];tp.forEach((t0,i)=>{o(pent[i],t0,.6,.16,'triangle',0,.01);o(pent[i]*2,t0,.4,.06,'sine',0,.01)});
n(tPush,.5,.45,300,2500);n(tPush+.3,2,.3,400,3000,1);o(880,tPush+1,1.6,.07);o(1318,tPush+1.4,1.6,.06);
[523,659,784,1047,1318].forEach((f,i)=>o(f,tWave+i*.09,1.8,.09));n(tWave,1.4,.35,300,3500,.4);
o(1568,23.5,1.5,.07);n(23.5,1.2,.25,1000,5000,.6);
[110,165,220,277,330].forEach(f=>o(f,5,20,.05,'sawtooth',0,19.5));o(200,19,6,.1,'sawtooth',1500,5.8);
o(90,25,2.8,.8,'sine',28,.01);n(25,2.6,.9,300,50,.01);o(110,25,5,.14,'triangle',0,.5);[165,220,277,330,659].forEach(f=>o(f,25.05,4.5,.06,'triangle',0,.7))}
const ov=document.getElementById('ov'),b=document.getElementById('b');let raf,t0;
function loop(now){const t=(now-t0)/1000;frame(Math.max(0,Math.min(t,30)));if(t>=30.5){window.location.href='abertura.php?visto=1';return}raf=requestAnimationFrame(loop)}
b.onclick=async()=>{try{await document.fonts.load('400 230px Jost');await document.fonts.load('300 40px Jost')}catch(e){}
build();if(A)try{A.close()}catch(e){}ov.style.display='none';ov.className='';try{audio()}catch(e){}t0=performance.now()+150;cancelAnimationFrame(raf);raf=requestAnimationFrame(loop)};
x.fillStyle=BK;x.fillRect(0,0,W0,H);
</script></body></html>