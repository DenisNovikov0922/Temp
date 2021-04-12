jQuery(document).ready(function( $ ) {
	/* premium indicator */
    $("input.premium").attr('disabled', 'disabled');
    var prem = $("input.premium").parent();
    $(prem).append('<span class="pro">PRO</span>');

    /* premium indicator */
    $("select.premium").attr('disabled', 'disabled');
    var prem = $("select.premium").parent();
    $(prem).append('<span class="pro">PRO</span>');

    /*! jquery-qrcode v0.17.0 - https://larsjung.de/jquery-qrcode/ */ 
    ! function(n) {
        var r = {};

        function o(e) {
            if (r[e]) return r[e].exports;
            var t = r[e] = {
                i: e,
                l: !1,
                exports: {}
            };
            return n[e].call(t.exports, t, t.exports, o), t.l = !0, t.exports
        }
        o.m = n, o.c = r, o.d = function(e, t, n) {
            o.o(e, t) || Object.defineProperty(e, t, {
                enumerable: !0,
                get: n
            })
        }, o.r = function(e) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                value: "Module"
            }), Object.defineProperty(e, "__esModule", {
                value: !0
            })
        }, o.t = function(t, e) {
            if (1 & e && (t = o(t)), 8 & e) return t;
            if (4 & e && "object" == typeof t && t && t.__esModule) return t;
            var n = Object.create(null);
            if (o.r(n), Object.defineProperty(n, "default", {
                    enumerable: !0,
                    value: t
                }), 2 & e && "string" != typeof t)
                for (var r in t) o.d(n, r, function(e) {
                    return t[e]
                }.bind(null, r));
            return n
        }, o.n = function(e) {
            var t = e && e.__esModule ? function() {
                return e.default
            } : function() {
                return e
            };
            return o.d(t, "a", t), t
        }, o.o = function(e, t) {
            return Object.prototype.hasOwnProperty.call(e, t)
        }, o.p = "", o(o.s = 0)
    }([function(e, t, n) {
        (function(e) {
            function n() {
                o.each(a, function(e, t) {
                        var n = o('label[for="' + t[0] + '"]');
                        n.text(n.text().replace(/:.*/, ": " + o("#" + t[0]).val() + t[1]))
                    }),
                    function() {
                        var e = {
                            render: o("#render-mode").val(),
                            ecLevel: o("#error-handling-level").val(),
                            minVersion: parseInt(o("#min-version").val(), 10),
                            fill: o("#fill-color").val(),
                            background: o("#background-color").val(),
                            text: o("#qr-content").val(),
                            size: parseInt(o("#size").val(), 10),
                            radius: .01 * parseInt(o("#corner-radius").val(), 10),
                            quiet: parseInt(o("#quiet-zone").val(), 10),
                            mode: parseInt(o("#label-mode").val(), 10),
                            mSize: .01 * parseInt(o("#logo-size").val(), 10),
                            mPosX: .01 * parseInt(o("#position-x").val(), 10),
                            mPosY: .01 * parseInt(o("#position-y").val(), 10),
                            label: o("#label-text").val(),
                            fontname: o("#font").val(),
                            fontcolor: o("#font-color").val(),
                            image: o("#img-buffer")[0]
                        };
                        o("#qr-code").empty().qrcode(e)
                    }()
            }

            function t() {
                var e = o("#logo-upload")[0];
                if (e.files && e.files[0]) {
                    var t = new r.FileReader;
                    t.onload = function(e) {
                        o("#img-buffer").attr("src", e.target.result), o("#label-mode").val("4"), setTimeout(n, 250)
                    }, t.readAsDataURL(e.files[0])
                }
            }
            var r = e.window,
                o = r.jQuery,
                a = [
                    ["size", "px"],
                    ["min-version", ""],
                    ["quiet-zone", " modules"],
                    ["corner-radius", "%"],
                    ["logo-size", "%"],
                    ["position-x", "%"],
                    ["position-y", "%"]
                ];
            o(function() {
                o("#logo-upload").on("change", t), o("input, textarea, select").on("input change", n), o(r).on("load", n), n()
            })
        }).call(this, n(1))
    }, function(e, t) {
        var n;
        n = function() {
            return this
        }();
        try {
            n = n || new Function("return this")()
        } catch (e) {
            "object" == typeof window && (n = window)
        }
        e.exports = n
    }]);

    /* color picker */
    $('.color-picker').wpColorPicker();

    /* range slider */
    $('#min-version').jRange({
        from: 0,
        to: 6,
        step: 1,
        format: '%s',
        'snap' : 3,
        'showLabels' : false
    });

    $('#quiet-zone').jRange({
        from: 0,
        to: 4,
        step: 1,
        format: '%s',
        'snap' : 2,
        'showLabels' : false
    });

    $('#corner-radius').jRange({
        from: 0,
        to: 50,
        step: 10,
        format: '%s',
        'snap' : 0,
        'showLabels' : false
    });

    $('#logo-size').jRange({
        from: 0,
        to: 100,
        step: 10,
        format: '%s',
        'snap' : 0,
        'showLabels' : false
    });

    $('#position-x').jRange({
        from: 0,
        to: 100,
        step: 10,
        format: '%s',
        'snap' : 0,
        'showLabels' : false
    });

    $('#position-y').jRange({
        from: 0,
        to: 100,
        step: 10,
        format: '%s',
        'snap' : 0,
        'showLabels' : false
    });

    /* font selector */
    $('#font').fontselect({
        fonts: [
          "Aclonica",
          "Allan",
          "Allerta",
          "Amaranth",
          "Anton",
          "Arimo",
          "Artifika",
          "Arvo",
          "Asset",
          "Astloch",
          "Bangers",
          "Bentham",
          "Bevan",
          "Brawler",
          "Cabin",
          "Calligraffitti",
          "Candal",
          "Cantarell",
          "Cardo",
          "Carter One",
          "Caudex",
          "Chewy",
          "Coda",
          "Copse",
          "Cousine",
          "Crushed",
          "Cuprum",
          "Damion",
          "Forum",
          "Geo",
          "Gruppo",
          "Inconsolata",
          "Judson",
          "Jura",
          "Kameron",
          "Kenia",
          "Kranky",
          "Kreon",
          "Kristi",
          "Lato",
          "Lekton",  
          "Limelight",  
          "Lobster",
          "Lobster Two",
          "Lora",
          "Mako",
          "Meddon",
          "MedievalSharp",
          "Megrim",
          "Merriweather",
          "Metrophobic",
          "Michroma",
          "Miltonian Tattoo",
          "Miltonian",
          "Modern Antiqua",
          "Monofett",
          "Molengo",
          "Mountains of Christmas",
          "Muli", 
          "Neucha",
          "Neuton",
          "Nobile",
          "Nunito",
          "Orbitron",
          "Oswald",
          "Pacifico",
          "Philosopher",
          "Play",
          "Podkova",
          "Puritan",
          "Quattrocento",
          "Radley",
          "Redressed",
          "Rokkitt",
          "Schoolbell",
          "Shanti",
          "Slackey",
          "Smythe",
          "Sunshiney",
          "Syncopate",
          "Tangerine",
          "Tinos",
          "Ubuntu",
          "Ultra",
          "Unkempt",
          "UnifrakturMaguntia",
          "Varela",
          "Varela Round",
          "Vibur",
          "Vollkorn",
          "VT323",
          "Wallpoet",
          "Yanone+Kaffeesatz",
          "Zeyada"
        ]
      }).change(function(){
        // replace + signs with spaces for css
        var font = $(this).val().replace(/\+/g, ' ');
        // split font into family and weight
        font = font.split(':');
        // set family on paragraphs
        console.log(font);
        $('canvas').css('font-family', font[0]);
    });

    /* canvas to image converters */
    $('#download').on('click', function() {
        var canvas = $('#qr-code canvas');
        var dataURL = canvas[0].toDataURL('image/png');
        $(this).attr("href", dataURL)
    });

    /* download from column */
    $('#column-download').on('click', function() {
        $(this).attr("href", ajax.data_uri );
    });

    /* save data URI on save post */
    $('.button-primary').on('click', function() {
        /* canvas to data uri */
        var canvas = $('#qr-code canvas');
        var dataURI = canvas[0].toDataURL('image/png');
      
        /* ajax to meta */
        $.ajax({
            type: 'POST',
            url: ajax.ajax_url,
            data: {'action' : 'data_uri_to_meta', 'post_id': ajax.post_id, 'data-uri' : dataURI, 'nonce' : ajax.nonce },
            dataType: 'json',
          });  
    });

    /* copy to clipboard */
	$.fn.CopyToClipboard = function() {
	    var textToCopy = false;
	    if(this.is('select') || this.is('textarea') || this.is('input')){
	        textToCopy = this.val();
	    }else {
	        textToCopy = this.text();
	    }
	    CopyToClipboard(textToCopy);
	};

	function CopyToClipboard( val ) {
	    var hiddenClipboard = $('#_hiddenClipboard_');
	    if(!hiddenClipboard.length){
	        $('body').append('<textarea style="position:absolute;top: -9999px;" id="_hiddenClipboard_"></textarea>');
	        hiddenClipboard = $('#_hiddenClipboard_');
	    }
	    hiddenClipboard.html(val);
	    hiddenClipboard.select();
	    document.execCommand('copy');
	    document.getSelection().removeAllRanges();
	}

	$(function(){
	    $('[data-clipboard-target]').each(function(){
	        $(this).click(function() {
	            $($(this).data('clipboard-target')).CopyToClipboard();
	        });
	    });
	    $('[data-clipboard-text]').each(function(){
	        $(this).click(function(){
	            CopyToClipboard($(this).data('clipboard-text'));
	        });
	    });
    });

    /* message on copy */
	$( '.copy' ).on('click',function() {
		
		$('.copy').parent().append('<span class="copied"><br>Copied.</span>');

		setTimeout(function() {
 			$('.copied').fadeOut(500);
		}, 2000);

		setTimeout(function() {
 			$('.copied').remove();
		}, 2500);

	});
});

