jQuery.fn.extend({
    scrollToMe: function(){
        if(jQuery(this).length){
            var top = jQuery(this).offset().top - 100;
            jQuery('html,body').animate({scrollTop: top}, 300);
        }
    },
    scrollToJustMe: function(){
        if(jQuery(this).length){
            var top = jQuery(this).offset().top;
            jQuery('html,body').animate({scrollTop: top}, 300);
        }
    }
});

jQuery(function($){
    $(document).ready(function(){
        $("#page_banner").owlCarousel({
            loop:true,
            nav:true,
            items:1,
            navText:['<em class="fa fa-chevron-left"></em>','<em class="fa fa-chevron-right"></em>']
        });
        $("#logo_slider").owlCarousel({
            loop:true,
            nav:true,
            dots:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:2,
                    nav:false
                },
                1000:{
                    items:4,
                    nav:true
                }
            },
            navText:['<em class="fa fa-chevron-left"></em>','<em class="fa fa-chevron-right"></em>']
        });
        $(".contact-us .field input[type=text], .contact-us .field textarea").focus(function(){
            $(this).parent().children("label").removeClass("invisible");
        }).blur(function(){
            if(!$(this).val())
                $(this).parent().children("label").addClass("invisible");
        });
        $(".to-bottom > a").click(function(){
            $(".how-we-works").scrollToMe();
        });
        $('nav li a').click(function(){
            $(this).parent().parent().children(".active").removeClass("active");
            $(this).parent().addClass("active");
            var link = $(this).attr('href');
            $(link).scrollToMe();
        });
        $('nav li a').each(function(){
            var link = $(this).attr('href');
            var cur_url = window.location.href;
            if(cur_url.indexOf(link) > -1) {
                $(this).parent().parent().children(".active").removeClass("active");
                $(this).parent().addClass("active");
            }
        });
        
        $('.footer-link').click(function(){
            $("#contactus").scrollToMe();
        });


        // function contactopen(){
        //     alert("This is warning!");
        //     document.write("This");
        // }

        $(window).scroll(function(){
            $("nav li a").each(function(){
                var link = $(this).attr('href');
                var offset = $(link).offset();
                if($(window).scrollTop() > offset.top - $('nav').outerHeight() && $(window).scrollTop() < offset.top + $(link).outerHeight() - $('nav').outerHeight()) {
                    $(this).parent().parent().children(".active").removeClass("active");
                    $(this).parent().addClass("active");
                }
            });
        });

		$(".btn-send").click(function(){
			var file_names = "";
			$("#drop + ul li").each(function(){
				file_names += "," + $(this).find("em").text();
			});
			file_names = file_names.substr(1, file_names.length - 1);
			$.ajax({
				method: "POST",
				url: "sendmail.php",
				data: { name: $("#name").val(), email: $("#e-mail").val(), phone: $("#phone").val(), message: $("#message").val(), attaches:file_names }
			})
			.done(function( msg ) {
				alert( "Email Sent: " + msg );
			});
		});
    });
});
