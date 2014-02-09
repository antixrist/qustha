javascript:(function (d, w) {
  if (typeof(w.yaddr) == 'undefined') {
    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () {
          n.parentNode.insertBefore(s, n);
        };
    s.type = "text/javascript";
    s.async = true;
    s.src = "ABS_URL_TO_MAIN_JS_FILE";
    if (w.opera == "[object Opera]") {
      d.addEventListener("DOMContentLoaded", f, false);
    } else {
      f();
    }
  }
  w.yaddr.run();
})(document, window);