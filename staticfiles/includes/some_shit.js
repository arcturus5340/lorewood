window._wpemojiSettings = {"baseUrl": "https://s.w.org/images/core/emoji/11.2.0/72x72/",  <!-- Wordpress -->
                         "ext": ".png",
                         "svgUrl": "https://s.w.org/images/core/emoji/11.2.0/svg/",
                         "svgExt":".svg",
                         "source": {"concatemoji": "/static/includes/js/wp-emoji-release.min.js?ver=5.1.1"}
};
!function(a, b, c){
function d(a, b){
  var c=String.fromCharCode;
  l.clearRect(0, 0, k.width,k.height);
  l.fillText(c.apply(this, a), 0, 0);
  var d=k.toDataURL();
  l.clearRect(0, 0, k.width, k.height);
  l.fillText(c.apply(this, b), 0, 0);
  var e=k.toDataURL();
  return d===e
}
function e(a){
  var b;
  if(!l || !l.fillText)
    return !1;
  switch(l.textBaseline="top", l.font="600 32px Arial", a){
    case "flag":
      return !(b=d([55356, 56826, 55356, 56819], [55356, 56826, 8203, 55356, 56819])) &&
              (b=d([55356, 57332, 56128, 56423, 56128, 56418, 56128, 56421, 56128, 56430, 56128, 56423, 56128, 56447],
                   [55356, 57332, 8203, 56128, 56423, 8203, 56128, 56418, 8203, 56128, 56421, 8203, 56128, 56430, 8203, 56128, 56423, 8203, 56128, 56447]), !b);
    case "emoji":
      return b=d([55358, 56760, 9792, 65039], [55358, 56760, 8203, 9792, 65039]), !b
  }
  return !1
}
function f(a){
  var c=b.createElement("script");
  c.src=a;
  c.defer=c.type="text/javascript";
  b.getElementsByTagName("head")[0].appendChild(c)
}
var g, h, i, j, k=b.createElement("canvas");
l=k.getContext && k.getContext("2d");
for(j=Array("flag","emoji"), c.supports={everything: !0, everythingExceptFlag: !0}, i=0; i<j.length; i++)
  c.supports[j[i]]=e(j[i]), c.supports.everything=c.supports.everything && c.supports[j[i]], "flag"!==j[i] && (c.supports.everythingExceptFlag=c.supports.everythingExceptFlag && c.supports[j[i]]);
  c.supports.everythingExceptFlag=c.supports.everythingExceptFlag && !c.supports.flag, c.DOMReady=!1, c.readyCallback=function(){c.DOMReady=!0}, c.supports.everything || (h=function(){c.readyCallback()}, b.addEventListener?(b.addEventListener("DOMContentLoaded", h, !1), a.addEventListener("load", h, !1)): (a.attachEvent("onload", h), b.attachEvent("onreadystatechange", function(){"complete"===b.readyState && c.readyCallback()})), g=c.source || {},g.concatemoji? f(g.concatemoji): g.wpemoji && g.twemoji && (f(g.twemoji), f(g.wpemoji)))}(window, document, window._wpemojiSettings)