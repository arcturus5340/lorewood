(function(u,l,o,g,i,n){
    if(typeof l[g] === "undefined")
      l[g] = [];
    l[i] = function(){
      if(l[g].length){
          for(var i = 0; i < l[g].length; i++){
              var f = l[g][i];
              if(typeof f === "function"){
                  f.call(l);
              }
              l[g].splice(i--,1)
          }
      }
    };
    if(typeof l[o] === "undefined"){
      l[o] = {};
      l[o][n] = function(){
          var args = arguments;
          l[g].push(function (){
              l[o].customInit.apply(l[o], args);
          });
      };
    }
    var s = u.createElement("script");
    s.src = "//ulogin.ru/js/ulogin.js?version=1";
    s.async = true;
    s.onload = l[i];
    u.getElementsByTagName("head")[0].appendChild(s);
})(document,window,"uLogin","uLoginCallbacks","uLoginOnload","customInit");