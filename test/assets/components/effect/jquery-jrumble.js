/* jRumble v1.3 - http://jackrugile.com/jrumble - MIT License */
(function(f) {
    f.fn.jrumble = function(g) {
        var a = f.extend({x: 2, y: 2, rotation: 1, speed: 15, opacity: false, opacityMin: 0.5}, g);
        return this.each(function() {
            var b = f(this), h = a.x * 2, i = a.y * 2, k = a.rotation * 2, g = a.speed === 0 ? 1 : a.speed, m = a.opacity, n = a.opacityMin, l, j, o = function() {
                var e = Math.floor(Math.random() * (h + 1)) - h / 2, a = Math.floor(Math.random() * (i + 1)) - i / 2, c = Math.floor(Math.random() * (k + 1)) - k / 2, d = m ? Math.random() + n : 1, e = e === 0 && h !== 0 ? Math.random() < 0.5 ? 1 : -1 : e, a = a === 0 && i !== 0 ? Math.random() < 0.5 ? 1 : -1 : a;
                b.css("display") === "inline" && (l = true, b.css("display", "inline-block"));
                b.css({position: "relative", left: e + "px", top: a + "px", "-ms-filter": "progid:DXImageTransform.Microsoft.Alpha(Opacity=" + d * 100 + ")", filter: "alpha(opacity=" + d * 100 + ")", "-moz-opacity": d, "-khtml-opacity": d, opacity: d, "-webkit-transform": "rotate(" + c + "deg)", "-moz-transform": "rotate(" + c + "deg)", "-ms-transform": "rotate(" + c + "deg)", "-o-transform": "rotate(" + c + "deg)", transform: "rotate(" + c + "deg)"})
            }, p = {left: 0, top: 0, "-ms-filter": "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)", filter: "alpha(opacity=100)", "-moz-opacity": 1, "-khtml-opacity": 1, opacity: 1, "-webkit-transform": "rotate(0deg)", "-moz-transform": "rotate(0deg)", "-ms-transform": "rotate(0deg)", "-o-transform": "rotate(0deg)", transform: "rotate(0deg)"};
            b.bind({startRumble: function(a) {
                    a.stopPropagation();
                    clearInterval(j);
                    j = setInterval(o, g)
                }, stopRumble: function(a) {
                    a.stopPropagation();
                    clearInterval(j);
                    l && b.css("display", "inline");
                    b.css(p)
                }})
        })
    }
})(jQuery);