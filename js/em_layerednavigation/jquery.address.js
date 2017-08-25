/*
 * jQuery Address Plugin v1.5
 * http://www.asual.com/jquery/address/
 *
 * Copyright (c) 2009-2010 Rostislav Hristov
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Date: 2012-11-18 23:51:44 +0200 (Sun, 18 Nov 2012)
 */
(function(c) {
	c.address = function() {
		var r = function(a) {
			a = c.extend(c.Event(a), function() {
				for (var b = {}, f = c.address.parameterNames(), m = 0, p = f.length; m < p; m++) b[f[m]] = c.address.parameter(f[m]);
				return {
					value: c.address.value(),
					path: c.address.path(),
					pathNames: c.address.pathNames(),
					parameterNames: f,
					parameters: b,
					queryString: c.address.queryString()
				}
			}.call(c.address));
			c(c.address).trigger(a);
			return a
		},
			s = function(a) {
				return Array.prototype.slice.call(a)
			},
			h = function() {
				c().bind.apply(c(c.address), Array.prototype.slice.call(arguments));
				return c.address
			},
			ea = function() {
				c().unbind.apply(c(c.address), Array.prototype.slice.call(arguments));
				return c.address
			},
			E = function() {
				return z.pushState && d.state !== g
			},
			U = function() {
				return ("/" + i.pathname.replace(new RegExp(d.state), "") + i.search + (L() ? "#" + L() : "")).replace(T, "/")
			},
			L = function() {
				var a = i.href.indexOf("#");
				return a != -1 ? t(i.href.substr(a + 1), k) : ""
			},
			u = function() {
				return E() ? U() : L()
			},
			V = function() {
				return "javascript"
			},
			O = function(a) {
				a = a.toString();
				return (d.strict && a.substr(0, 1) != "/" ? "/" : "") + a
			},
			t = function(a, b) {
				if (d.crawlable && b) return (a !== "" ? "!" : "") + a;
				return a.replace(/^\!/, "")
			},
			v = function(a, b) {
				return parseInt(a.css(b), 10)
			},
			H = function() {
				if (!x) {
					var a = u();
					if (decodeURI(e) != decodeURI(a)) if (w && A < 7) i.reload();
					else {
						w && !F && d.history && q(M, 50);
						_old = e;
						e = a;
						G(k)
					}
				}
			},
			G = function(a) {
				var b = r(W);
				a = r(a ? X : Y);
				q(fa, 10);
				if (b.isDefaultPrevented() || a.isDefaultPrevented()) ga()
			},
			ga = function() {
				e = _old;
				if (E()) z.popState({}, "", d.state.replace(/\/$/, "") + (e === "" ? "/" : e));
				else {
					x = n;
					if (B) if (d.history) i.hash = "#" + t(e, n);
					else i.replace("#" + t(e, n));
					else if (e != u()) if (d.history) i.hash = "#" + t(e, n);
					else i.replace("#" + t(e, n));
					w && !F && d.history && q(M, 50);
					if (B) q(function() {
						x = k
					}, 1);
					else x = k
				}
			},
			fa = function() {
				if (d.tracker !== "null" && d.tracker !== I) {
					var a = c.isFunction(d.tracker) ? d.tracker : j[d.tracker],
						b = (i.pathname + i.search + (c.address && !E() ? c.address.value() : "")).replace(/\/\//, "/").replace(/^\/$/, "");
					if (c.isFunction(a)) a(b);
					else if (c.isFunction(j.urchinTracker)) j.urchinTracker(b);
					else if (j.pageTracker !== g && c.isFunction(j.pageTracker._trackPageview)) j.pageTracker._trackPageview(b);
					else j._gaq !== g && c.isFunction(j._gaq.push) && j._gaq.push(["_trackPageview", decodeURI(b)])
				}
			},
			M = function() {
				var a = V() + ":" + k + ";document.open();document.writeln('<html><head><title>" + o.title.replace(/\'/g, "\\'") + "</title><script>var " + C + ' = "' + encodeURIComponent(u()).replace(/\'/g, "\\'") + (o.domain != i.hostname ? '";document.domain="' + o.domain : "") + "\";<\/script></head></html>');document.close();";
				if (A < 7) l.src = a;
				else l.contentWindow.location.replace(a)
			},
			$ = function() {
				if (J && Z != -1) {
					var a, b, f = J.substr(Z + 1).split("&");
					for (a = 0; a < f.length; a++) {
						b = f[a].split("=");
						if (/^(autoUpdate|crawlable|history|strict|wrap)$/.test(b[0])) d[b[0]] = isNaN(b[1]) ? /^(true|yes)$/i.test(b[1]) : parseInt(b[1], 10) !== 0;
						if (/^(state|tracker)$/.test(b[0])) d[b[0]] = b[1]
					}
					J = I
				}
				_old = e;
				e = u()
			},
			ba = function() {
				if (!aa) {
					aa = n;
					$();
					var a = function() {
						ha.call(this);
						ia.call(this)
					},
						b = c("body").ajaxComplete(a);
					a();
					if (d.wrap) {
						c("body > *").wrapAll('<div style="padding:' + (v(b, "marginTop") + v(b, "paddingTop")) + "px " + (v(b, "marginRight") + v(b, "paddingRight")) + "px " + (v(b, "marginBottom") + v(b, "paddingBottom")) + "px " + (v(b, "marginLeft") + v(b, "paddingLeft")) + 'px;" />').parent().wrap('<div id="' + C + '" style="height:100%;overflow:auto;position:relative;' + (B && !window.statusbar.visible ? "resize:both;" : "") + '" />');
						c("html, body").css({
							height: "100%",
							margin: 0,
							padding: 0,
							overflow: "hidden"
						});
						B && c('<style type="text/css" />').appendTo("head").text("#" + C + "::-webkit-resizer { background-color: #fff; }")
					}
					if (w && !F) {
						a = o.getElementsByTagName("frameset")[0];
						l = o.createElement((a ? "" : "i") + "frame");
						l.src = V() + ":" + k;
						if (a) {
							a.insertAdjacentElement("beforeEnd", l);
							a[a.cols ? "cols" : "rows"] += ",0";
							l.noResize = n;
							l.frameBorder = l.frameSpacing = 0
						} else {
							l.style.display = "none";
							l.style.width = l.style.height = 0;
							l.tabIndex = -1;
							o.body.insertAdjacentElement("afterBegin", l)
						}
						q(function() {
							c(l).bind("load", function() {
								var f = l.contentWindow;
								_old = e;
								e = f[C] !== g ? f[C] : "";
								if (e != u()) {
									G(k);
									i.hash = t(e, n)
								}
							});
							l.contentWindow[C] === g && M()
						}, 50)
					}
					q(function() {
						r("init");
						G(k)
					}, 1);
					if (!E()) if (w && A > 7 || !w && F) if (j.addEventListener) j.addEventListener(K, H, k);
					else j.attachEvent && j.attachEvent("on" + K, H);
					else ja(H, 50);
					"state" in window.history && c(window).trigger("popstate")
				}
			},
			ha = function() {
				var a, b = c("a"),
					f = b.size(),
					m = -1,
					p = function() {
						if (++m != f) {
							a = c(b.get(m));
							a.is('[rel*="address:"]') && a.address('[rel*="address:"]');
							q(p, 1)
						}
					};
				q(p, 1)
			},
			ia = function() {
				if (d.crawlable) {
					var a = i.pathname.replace(/\/$/, "");
					c("body").html().indexOf("_escaped_fragment_") != -1 && c('a[href]:not([href^=http]), a[href*="' + document.domain + '"]').each(function() {
						var b = c(this).attr("href").replace(/^http:/, "").replace(new RegExp(a + "/?$"), "");
						if (b === "" || b.indexOf("_escaped_fragment_") != -1) c(this).attr("href", "#" + encodeURI(decodeURIComponent(b.replace(/\/(.*)\?_escaped_fragment_=(.*)$/, "!$2"))))
					})
				}
			},
			g, I = null,
			C = "jQueryAddress",
			K = "hashchange",
			W = "change",
			X = "internalChange",
			Y = "externalChange",
			n = true,
			k = false,
			d = {
				autoUpdate: n,
				crawlable: k,
				history: n,
				strict: n,
				wrap: k
			},
			D = c.browser,
			A = parseFloat(D.version),
			w = !c.support.opacity,
			B = D.webkit || D.safari,
			j = function() {
				try {
					return top.document !== g && top.document.title !== g ? top : window
				} catch (a) {
					return window
				}
			}(),
			o = j.document,
			z = j.history,
			i = j.location,
			ja = setInterval,
			q = setTimeout,
			T = /\/{2,9}/g;
		D = navigator.userAgent;
		var F = "on" + K in j,
			l, J = c("script:last").attr("src"),
			Z = J ? J.indexOf("?") : -1,
			P = o.title,
			x = k,
			aa = k,
			ca = n,
			N = k,
			e = u();
		_old = e;
		if (w) {
			A = parseFloat(D.substr(D.indexOf("MSIE") + 4));
			if (o.documentMode && o.documentMode != A) A = o.documentMode != 8 ? 7 : 8;
			var da = o.onpropertychange;
			o.onpropertychange = function() {
				da && da.call(o);
				if (o.title != P && o.title.indexOf("#" + u()) != -1) o.title = P
			}
		}
		if (z.navigationMode) z.navigationMode = "compatible";
		if (document.readyState == "complete") var ka = setInterval(function() {
			if (c.address) {
				ba();
				clearInterval(ka)
			}
		}, 50);
		else {
			$();
			c(ba)
		}
		c(window).bind("popstate", function() {
			if (decodeURI(e) != decodeURI(u())) {
				_old = e;
				e = u();
				G(k)
			}
		}).bind("unload", function() {
			if (j.removeEventListener) j.removeEventListener(K, H, k);
			else j.detachEvent && j.detachEvent("on" + K, H)
		});
		return {
			bind: function() {
				return h.apply(this, s(arguments))
			},
			unbind: function() {
				return ea.apply(this, s(arguments))
			},
			init: function() {
				return h.apply(this, ["init"].concat(s(arguments)))
			},
			change: function() {
				return h.apply(this, [W].concat(s(arguments)))
			},
			internalChange: function() {
				return h.apply(this, [X].concat(s(arguments)))
			},
			externalChange: function() {
				return h.apply(this, [Y].concat(s(arguments)))
			},
			baseURL: function() {
				var a = i.href;
				if (a.indexOf("#") != -1) a = a.substr(0, a.indexOf("#"));
				if (/\/$/.test(a)) a = a.substr(0, a.length - 1);
				return a
			},
			autoUpdate: function(a) {
				if (a !== g) {
					d.autoUpdate = a;
					return this
				}
				return d.autoUpdate
			},
			crawlable: function(a) {
				if (a !== g) {
					d.crawlable = a;
					return this
				}
				return d.crawlable
			},
			history: function(a) {
				if (a !== g) {
					d.history = a;
					return this
				}
				return d.history
			},
			state: function(a) {
				if (a !== g) {
					d.state = a;
					var b = U();
					if (d.state !== g) if (z.pushState) b.substr(0, 3) == "/#/" && i.replace(d.state.replace(/^\/$/, "") + b.substr(2));
					else b != "/" && b.replace(/^\/#/, "") != L() && q(function() {
						i.replace(d.state.replace(/^\/$/, "") + "/#" + b)
					}, 1);
					return this
				}
				return d.state
			},
			strict: function(a) {
				if (a !== g) {
					d.strict = a;
					return this
				}
				return d.strict
			},
			tracker: function(a) {
				if (a !== g) {
					d.tracker = a;
					return this
				}
				return d.tracker
			},
			wrap: function(a) {
				if (a !== g) {
					d.wrap = a;
					return this
				}
				return d.wrap
			},
			update: function() {
				N = n;
				this.value(e);
				N = k;
				return this
			},
			title: function(a) {
				if (a !== g) {
					q(function() {
						P = o.title = a;
						if (ca && l && l.contentWindow && l.contentWindow.document) {
							l.contentWindow.document.title = a;
							ca = k
						}
					}, 50);
					return this
				}
				return o.title
			},
			value: function(a) {
				if (a !== g) {
					a = O(a);
					if (a == "/") a = "";
					if (e == a && !N) return;
					_old = e;
					e = a;
					if (d.autoUpdate || N) {
						G(n);
						if (E()) z[d.history ? "pushState" : "replaceState"]({}, "", d.state.replace(/\/$/, "") + (e === "" ? "/" : e));
						else {
							x = n;
							if (B) if (d.history) i.hash = "#" + t(e, n);
							else i.replace("#" + t(e, n));
							else if (e != u()) if (d.history) i.hash = "#" + t(e, n);
							else i.replace("#" + t(e, n));
							w && !F && d.history && q(M, 50);
							if (B) q(function() {
								x = k
							}, 1);
							else x = k
						}
					}
					return this
				}
				return O(e)
			},
			path: function(a) {
				if (a !== g) {
					var b = this.queryString(),
						f = this.hash();
					this.value(a + (b ? "?" + b : "") + (f ? "#" + f : ""));
					return this
				}
				return O(e).split("#")[0].split("?")[0]
			},
			pathNames: function() {
				var a = this.path(),
					b = a.replace(T, "/").split("/");
				if (a.substr(0, 1) == "/" || a.length === 0) b.splice(0, 1);
				a.substr(a.length - 1, 1) == "/" && b.splice(b.length - 1, 1);
				return b
			},
			queryString: function(a) {
				if (a !== g) {
					var b = this.hash();
					this.value(this.path() + (a ? "?" + a : "") + (b ? "#" + b : ""));
					return this
				}
				a = e.split("?");
				return a.slice(1, a.length).join("?").split("#")[0]
			},
			parameter: function(a, b, f) {
				var m, p;
				if (b !== g) {
					var Q = this.parameterNames();
					p = [];
					b = b === g || b === I ? "" : b.toString();
					for (m = 0; m < Q.length; m++) {
						var R = Q[m],
							y = this.parameter(R);
						if (typeof y == "string") y = [y];
						if (R == a) y = b === I || b === "" ? [] : f ? y.concat([b]) : [b];
						for (var S = 0; S < y.length; S++) p.push(R + "=" + y[S])
					}
					c.inArray(a, Q) == -1 && b !== I && b !== "" && p.push(a + "=" + b);
					this.queryString(p.join("&"));
					return this
				}
				if (b = this.queryString()) {
					f = [];
					p = b.split("&");
					for (m = 0; m < p.length; m++) {
						b = p[m].split("=");
						b[0] == a && f.push(b.slice(1).join("="))
					}
					if (f.length !== 0) return f.length != 1 ? f : f[0]
				}
			},
			parameterNames: function() {
				var a = this.queryString(),
					b = [];
				if (a && a.indexOf("=") != -1) {
					a = a.split("&");
					for (var f = 0; f < a.length; f++) {
						var m = a[f].split("=")[0];
						c.inArray(m, b) == -1 && b.push(m)
					}
				}
				return b
			},
			hash: function(a) {
				if (a !== g) {
					this.value(e.split("#")[0] + (a ? "#" + a : ""));
					return this
				}
				a = e.split("#");
				return a.slice(1, a.length).join("#")
			}
		}
	}();
	c.fn.address = function(r) {
		var s;
		if (typeof r == "string") {
			s = r;
			r = undefined
		}
		c(this).attr("address") || c(s ? s : this).live("click", function(h) {
			if (h.shiftKey || h.ctrlKey || h.metaKey || h.which == 2) return true;
			if (c(this).is("a")) {
				h.preventDefault();
				h = r ? r.call(this) : /address:/.test(c(this).attr("rel")) ? c(this).attr("rel").split("address:")[1].split(" ")[0] : c.address.state() !== undefined && !/^\/?$/.test(c.address.state()) ? c(this).attr("href").replace(new RegExp("^(.*" + c.address.state() + "|\\.)"), "") : c(this).attr("href").replace(/^(#\!?|\.)/, "");
				c.address.value(h)
			}
		}).live("submit", function(h) {
			if (c(this).is("form")) {
				h.preventDefault();
				h = c(this).attr("action");
				h = r ? r.call(this) : (h.indexOf("?") != -1 ? h.replace(/&$/, "") : h + "?") + c(this).serialize();
				c.address.value(h)
			}
		}).attr("address", true);
		return this
	}
})(jQuery);