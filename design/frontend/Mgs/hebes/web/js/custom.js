require([
	'jquery',
	'waypoints',
	'slick',
	'noframeworkwaypoint',
	'daterangepickerjs'
	], function(jQuery){
		(function($) {
			$.fn.appear = function(fn, options) {

				var settings = $.extend({

				//arbitrary data to pass to fn
				data: undefined,

				//call fn only on the first appear?
				one: true,

				// X & Y accuracy
				accX: 0,
				accY: 0

			}, options);

				return this.each(function() {

					var t = $(this);

				//whether the element is currently visible
				t.appeared = false;

				if (!fn) {

					//trigger the custom event
					t.trigger('appear', settings.data);
					return;
				}

				var w = $(window);

				//fires the appear event when appropriate
				var check = function() {

					//is the element hidden?
					if (!t.is(':visible')) {

						//it became hidden
						t.appeared = false;
						return;
					}

					//is the element inside the visible window?
					var a = w.scrollLeft();
					var b = w.scrollTop();
					var o = t.offset();
					var x = o.left;
					var y = o.top;

					var ax = settings.accX;
					var ay = settings.accY;
					var th = t.height();
					var wh = w.height();
					var tw = t.width();
					var ww = w.width();

					if (y + th + ay >= b &&
						y <= b + wh + ay &&
						x + tw + ax >= a &&
						x <= a + ww + ax) {

						//trigger the custom event
					if (!t.appeared) t.trigger('appear', settings.data);

				} else {

						//it scrolled out of view
						t.appeared = false;
					}
				};

				//create a modified fn with some additional logic
				var modifiedFn = function() {

					//mark the element as visible
					t.appeared = true;

					//is this supposed to happen only once?
					if (settings.one) {

						//remove the check
						w.unbind('scroll', check);
						var i = $.inArray(check, $.fn.appear.checks);
						if (i >= 0) $.fn.appear.checks.splice(i, 1);
					}

					//trigger the original fn
					fn.apply(this, arguments);
				};

				//bind the modified fn to the element
				if (settings.one) t.one('appear', settings.data, modifiedFn);
				else t.bind('appear', settings.data, modifiedFn);

				//check whenever the window scrolls
				w.scroll(check);

				//check whenever the dom changes
				$.fn.appear.checks.push(check);

				//check now
				(check)();
			});
			};
			

		//keep a queue of appearance checks
		$.extend($.fn.appear, {

			checks: [],
			timeout: null,

			//process the queue
			checkAll: function() {
				var length = $.fn.appear.checks.length;
				if (length > 0) while (length--) ($.fn.appear.checks[length])();
			},

			//check the queue asynchronously
			run: function() {
				if ($.fn.appear.timeout) clearTimeout($.fn.appear.timeout);
				$.fn.appear.timeout = setTimeout($.fn.appear.checkAll, 20);
			}
		});

		//run checks when these methods are called
		$.each(['append', 'prepend', 'after', 'before', 'attr',
			'removeAttr', 'addClass', 'removeClass', 'toggleClass',
			'remove', 'css', 'show', 'hide'], function(i, n) {
				var old = $.fn[n];
				if (old) {
					$.fn[n] = function() {
						var r = old.apply(this, arguments);
						$.fn.appear.run();
						return r;
					}
				}
			});
		/**/
		$(document).on("click", ".popup-video", function (e) {
			e.preventDefault();
			$.magnificPopup.open({
				items: {
					src: $(this).attr('href')
				},
				type: 'iframe',
				removalDelay: 160,
				mainClass: 'mfp-img-gallery',
				closeOnBgClick: false,
				preloader: false,
				fixedContentPos: true
			});
		});

		jQuery('li.has-submenu a').on('click',function(e){
			console.log(jQuery(this).parent())
			jQuery(this).parent().toggleClass('active-submenu');
		    jQuery('.brand-megamenu ul.sub-menu').hide()
		    jQuery(this).siblings('.brand-submenu').toggleClass('active')
		})

		/* Layer Navigation */
		$(document).on("click", ".page-layout-1column .filter-title strong", function () {
			if(!$('html').hasClass('filter-active')){
				if($('.page-layout-1column .block.filter .filter-options .filter-item').length){
					$('.page-layout-1column .block.filter').addClass('active');
					$('html').addClass('filter-active');
					$('.page-layout-1column .block.filter .filter-content .filter-wrapper').slideDown("slow");
				}
			}else {
				$('.page-layout-1column .block.filter .filter-content .filter-wrapper').slideUp("slow", function() {
					$('html').removeClass('filter-active');
					$('.page-layout-1column .block.filter').removeClass('active');
				});
			}
		});
		
		$(document).on("click", ".page-layout-1column .block.filter .filter-options-title", function () {
			if($(window).width() > 991.98){
				$(this).toggleClass('active-tg');
				if($(this).parent().find('.filter-options-content .items .item').length < 4){
					$(this).addClass('no-toggle');
				}
				$(this).parent().find('.filter-options-content .items .item:gt(2)').slideToggle();
			}else if($(window).width() > 767.98){
				$(this).toggleClass('active-tg');
				if($(this).parent().find('.filter-options-content .items .item').length < 3){
					$(this).addClass('no-toggle');
				}
				$(this).parent().find('.filter-options-content .items .item:gt(1)').slideToggle();
			}else {
				$(this).toggleClass('active-mbtg');
				$(this).parent().find('.filter-options-content').slideToggle();
			}
		});
		
		$(document).on("click", ".sidebar .block.filter .filter-options-title", function () {
			$(this).toggleClass('active');
			// $(this).parent().find('.filter-options-content').slideToggle();
		});
		
		$(document).on("click", "#btn_view_result", function () {
			$('.page-layout-1column .block.filter .filter-content .filter-wrapper').slideUp("slow", function() {
				$('html').removeClass('filter-active');
				$('.page-layout-1column .block.filter').removeClass('active');
				$('html, body').animate({
					scrollTop: $(".column.main").offset().top
				}, 400);
			});
		});
		/* Show hide my account dropdown */
		$('.my-account-link .toggle-toplinks').click(function(){
			$(this).parent().toggleClass('active');
		});
		
		/* Show hide settings dropdown */
		$('.page-settings .toggle-settings').click(function(){
			$(this).parent().toggleClass('active');
		});
		
		/* Show hide search form */
		$(document).on("click",".block-search .block-title",function(e){
			$(this).parent().toggleClass('active');
		});
		
		$(document).on("click","#search_mini_form label",function(e){
			e.preventDefault();
			$('.header .block-search').removeClass('active');
		});
		
		/* Hide dropdown if click outside element */
		$(document).mouseup(function(e) {
			var ctMyaccount = $(".my-account-link");
			if (!ctMyaccount.is(e.target) && ctMyaccount.has(e.target).length === 0) {
				ctMyaccount.removeClass('active');
			}
			
			var ctSearch = $(".block-search");
			if (!ctSearch.is(e.target) && ctSearch.has(e.target).length === 0) {
				ctSearch.removeClass('active');
			}
			
			var ctSettings = $(".page-settings");
			if (!ctSettings.is(e.target) && ctSettings.has(e.target).length === 0) {
				ctSettings.removeClass('active');
			}
		});
		
		

		$(document).ready(function(){

			//Auto hide error, success message notifications
			setTimeout(function() {
				jQuery(".page.messages").hide('blind', {}, 500)
			}, 8000);

			$('button.mgs-quickview-login').click(function(){
                
                var currentClickedProId = $(this).attr('id');
                
                var clickLink = window.location.protocol + "//" + window.location.host + "/" +"mgs_quickview/catalog_product/view/id/"+currentClickedProId+"/";
                sessionStorage.setItem("url",clickLink);
            });
            
            if (sessionStorage.getItem("url") != null) {
                setTimeout(function() { 
                    $('button.mgs-quickview[data-quickview-url="'+sessionStorage.getItem("url")+'"]').click();
                    sessionStorage.removeItem("url");
                }, 1000);
            }

            if ($("button.btn-tocart-pro-view").hasClass("customer-login-link")) {
            	$('button.btn-tocart-pro-view.customer-login-link').click(function(){
	                var clickClass = 'btn-tocart-pro-view.customer-login-link';
	                sessionStorage.setItem("class",clickClass);
	            });
            }

            if (sessionStorage.getItem("class") != null) {
                setTimeout(function() { 
                    $('button.btn-tocart-pro-view').click();
                    sessionStorage.removeItem("class");
                }, 1000);
            }


			
			jQuery(function() {

	            jQuery('input[name="startDate"]').daterangepicker({
	                  autoUpdateInput: false,
	                  locale: {
	                      cancelLabel: 'Clear'
	                  }
	            });

	            jQuery('input[name="startDate"]').on('apply.daterangepicker', function(ev, picker) {
	                jQuery(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	            });

	            jQuery('input[name="startDate"]').on('cancel.daterangepicker', function(ev, picker) {
	                jQuery(this).val('');
	            });

	        });

			$(".video-categories-text ul li a.list-categories").click(function(){
				$(this).toggleClass("checked");
			});

	        $('.video-categories-text ul li a.list-categories').click(function (e) {

                e.preventDefault();

                var urloffilterby = [];
                $('.video-categories-text ul li a.list-categories.checked').each(function(index, value){
                	urloffilterby.push(""+value+"");
                });

            	var dataPass = {
	                "isajax": true,
	                "currenturl": window.location.toString(),
	                "vlibreryfilterbyid": urloffilterby
	            };

	            jQuery.ajax({
	                url: window.location.protocol + "//" + window.location.host + "/" + "mpvlibrary/vlibrary/view",
	                data: dataPass,
	                type: 'POST',
	                dataType: 'json',
	                success: function (data, status, xhr) {
	                    jQuery(".video-list-section").remove();
	                    jQuery(".message").remove();
                        jQuery(".pager").before(data.popuphtml);
	                },
	                error: function (xhr, status, errorThrown) {
	                    console.log('Error happens. Try again.');
	                    console.log(errorThrown);
	                    alert("Something went wrong, Please try later.");
	                }
	            });
                
            });

            /* starts for vlibreary */

            $('.vlibrary-search input.search-query').keyup(function () {
                
            	var dataPass = {
	                "isajax": true,
	                "currenturl": window.location.toString(),
	                "cat_search_query": $(this).val(),
	                "sort_by_options": $('#vlibrary_sorter').val()
	            };

	            jQuery.ajax({
	                url: window.location.protocol + "//" + window.location.host + "/" + "mpvlibrary/vlibrary/view",
	                data: dataPass,
	                type: 'POST',
	                dataType: 'json',
	                success: function (data, status, xhr) {
	                    jQuery(".video-list-section").remove();
	                    jQuery(".message").remove();
                        jQuery(".pager").before(data.popuphtml);
	                },
	                error: function (xhr, status, errorThrown) {
	                    console.log('Error happens. Try again.');
	                    console.log(errorThrown);
	                    alert("Something went wrong, Please try later.");
	                }
	            });
                
            });

            $('#vlibrary-search-query-form').submit(function (e) {
                
            	e.preventDefault();

            	var dataPass = {
	                "isajax": true,
	                "currenturl": window.location.toString(),
	                "cat_search_query": $('.vlibrary-search input.search-query').val(),
	                "sort_by_options": $('#vlibrary_sorter').val()
	            };

	            jQuery.ajax({
	                url: window.location.protocol + "//" + window.location.host + "/" + "mpvlibrary/vlibrary/view",
	                data: dataPass,
	                type: 'POST',
	                dataType: 'json',
	                success: function (data, status, xhr) {
	                    jQuery(".video-list-section").remove();
	                    jQuery(".message").remove();
                        jQuery(".pager").before(data.popuphtml);
	                },
	                error: function (xhr, status, errorThrown) {
	                    console.log('Error happens. Try again.');
	                    console.log(errorThrown);
	                    alert("Something went wrong, Please try later.");
	                }
	            });
                
            });

            $('#vlibrary_sorter').change(function(){
		        var sortBy = $(this).val();
		        var dataPass = {
	                "isajax": true,
	                "currenturl": window.location.toString(),
	                "sort_by_options": sortBy,
	                "cat_search_query": $('.vlibrary-search input.search-query').val()
	            };
			    $.ajax({
			        url: window.location.protocol + "//" + window.location.host + "/" + "mpvlibrary/vlibrary/view",
	                data: dataPass,
	                type: 'POST',
	                dataType: 'json',
	                success: function (data, status, xhr) {
	                    jQuery(".video-list-section").remove();
	                    jQuery(".message").remove();
                        jQuery(".pager").before(data.popuphtml);
	                },
	                error: function (xhr, status, errorThrown) {
	                    console.log('Error happens. Try again.');
	                    console.log(errorThrown);
	                    alert("Something went wrong, Please try later.");
	                }
			    });
		    });

		    /* over for vlibreary */

			$(".nav.tabs li").click(function () {
	          $(".nav.tabs li").removeClass("active");
	          $(this).addClass("active");        
	        });

			$('.must-part').slick({
				slidesToShow: 4,
				slidesToScroll: 1,
				autoplay: false,
				autoplaySpeed: 2000,
				arrows: true,
				dots: false,

				initialSlide:0,
				pauseOnHover: false,
				responsive: [{
					breakpoint: 768,
					settings: {
						slidesToShow: 1
					}
				}, {
					breakpoint: 520,
					settings: {
						slidesToShow: 1
					}
				}]
			});



			$('.banner-v7 .promobanner a').append('<div class="overlay"><span href="" class="shop-now">shop now</span></div>');
			
			$("[data-appear-animation]").each(function() {
				$(this).addClass("appear-animation");
				if($(window).width() > 767) {
					$(this).appear(function() {

						var delay = ($(this).attr("data-appear-animation-delay") ? $(this).attr("data-appear-animation-delay") : 1);

						if(delay > 1) $(this).css("animation-delay", delay + "ms");
						$(this).addClass($(this).attr("data-appear-animation"));
						$(this).addClass("animated");

						setTimeout(function() {
							$(this).addClass("appear-animation-visible");
						}, delay);

					}, {accX: 0, accY: -150});
				} else {
					$(this).addClass("appear-animation-visible");
				}
			});
			// MEGAMENU JS
			$('.nav-main-menu li.mega-menu-fullwidth.menu-2columns').hover(function(){
				if($(window).width() > 1199){
					var position = $(this).position();
					if(position.right < 560){
						$(this).addClass('hover-right');
					}
				}
			});
			
			$('.nav-main-menu .static-menu li > .toggle-menu a').click(function(){
				$(this).toggleClass('active');
				$(this).parent().parent().find('> ul').slideToggle();
			});
			// END MEGAMENU
			
			
			// Responsive header
			$('.action.nav-toggle').click(function(){
				if ($('html').hasClass('nav-open')) {
					$('html').removeClass('nav-open nav-before-open');
					// setTimeout(function () {
					// 	$('html').removeClass('nav-before-open');
					// }, 42);
				} else {
					$('html').addClass('nav-open nav-before-open');
					// setTimeout(function () {
					// 	$('html').addClass('nav-open');
					// }, 42);
				}
			});
			
			$('.close-nav-button').click(function(){
				$('html').removeClass('nav-open nav-before-open');
				// setTimeout(function () {
				// 	$('html').removeClass('nav-before-open');
				// }, 42);
			});
			
			// Closed filter fixed
			$(document).on("click","#close-filter",function(e){
				$('.block.filter .filter-title .title').click();		
			});
			
			// Closed minicart 
			$(document).on("click","#close-minicart",function(e){
				$('.minicart-wrapper .action.showcart').click();		
			});
			
			/* Shipping & Discount Code */
			$('.checkout-extra > .block > .title').click(function(){
				$('.checkout-extra > .block > .title').removeClass('active');
				$('.checkout-extra > .block > .content').removeClass('active');
				$(this).addClass('active');
				$(this).parent().find('> .content').addClass('active');
			});
			
			$(document).on("click",".products-grid .product-item-info .product-top > a",function(e){
				if($(window).width() < 992 && !$('.products-grid .product-item-info').hasClass('disable_hover_effect')){
					if(!$(this).hasClass('active')){
						$('.products-grid .product-item-info .product-top > a.active').removeClass('active');
						event.returnValue = false;
						event.preventDefault();
						$(this).addClass('active');
					}
				}
			});


			//parallax
			

			// function onScrollInit( items, trigger ) {
			// 	items.each( function() {
			// 		var JSosElement = $(this)[0],
			// 		osElement = $(this),
			// 		osAnimationClass = osElement.attr('data-animation'),
			// 		osAnimationDelay = osElement.attr('data-animation-delay');
			// 		console.log(osElement,JSosElement);
			// 		osElement.css({
			// 			'-webkit-animation-delay':  osAnimationDelay,
			// 			'-moz-animation-delay':     osAnimationDelay,
			// 			'animation-delay':          osAnimationDelay
			// 		});

			// 		var osTrigger = ( trigger ) ? trigger : osElement;

			// 		var waypoint = new Waypoint({
			// 					element: osElement,
			// 					handler:function(){
			// 						osElement.addClass('animated').addClass(osAnimationClass);
			// 					}
			// 		},{
			// 			triggerOnce: false,
			// 			offset: '90%'
			// 		});
			// 		// osTrigger.waypoint(function() {
			// 		// 	osElement.addClass('animated').addClass(osAnimationClass);
			// 		// },{
			// 		// 	triggerOnce: false,
			// 		// 	offset: '90%'
			// 		// });
			// 	});
			// }

			// onScrollInit($('.feacture-img'));

			if($('.imprint-method-img').length>0){
				var Imprint_images = document.getElementsByClassName('imprint-method-img');
				new simpleParallax(Imprint_images,{
					overflow:false,
					orientation:"down - left",
					delay: .5,
					scale:1.6,
					transition: 'cubic-bezier(0,0,0,1)'
				});
			}

			if($('.background-parallax-about').length>0){
				// var Imprint_images = document.getElementsByClassName('background-parallax-about');
				// new simpleParallax(Imprint_images,{
				// 	overflow:true,
				// 	orientation:"down",
				// 	scale:1.2,
				// 	transition: 'cubic-bezier(0,0,0,1)'
				// });
			}

			if($('.our-story-img').length>0){
				// var Imprint_images = document.getElementsByClassName('our-story-img');
				// new simpleParallax(Imprint_images,{
				// 	overflow:true,
				// 	orientation:"up",
				// 	delay: .5,
				// 	scale:1.6,
				// 	transition: 'cubic-bezier(0,0,0,1)'
				// });
			}

			if($('.aboutus-story-img').length>0){
				// var Imprint_images = document.getElementsByClassName('aboutus-story-img');
				// new simpleParallax(Imprint_images,{
				// 	overflow:true,
				// 	orientation:"down",
				// 	delay: .5,
				// 	scale:1.6,
				// 	transition: 'cubic-bezier(0,0,0,1)'
				// });
			}

			if($('.my-paroller').length>0){
				var my_paroller = document.getElementsByClassName('my-paroller');
				new simpleParallax(my_paroller,{
					overflow:false,
					orientation:"down - left",
					delay: .5,
					scale:1.6,
					transition: 'cubic-bezier(0,0,0,1)'
				});
			}
			

			if($('.feacture-img img').length>0){
				console.clear()
			var tempElem = $('.feacture-img img')[0];
			var waypoint = new Waypoint({
				element: tempElem,
				handler: function(direction) {
					var ele = $(tempElem);
					// console.log($(this), direction,ele.offset());
					var hT = $('.featured-product-section').offset().top,
					hH = $('.featured-product-section').outerHeight(),
					wH = $(window).height()+2000,
					wS = $(window).scrollTop();
					console.log((wS,(hT+hH-wH-2000)));
					var images = document.getElementsByClassName('my-paroller');
					// var instance = new simpleParallax(images);
					// 		instance.destroy();
					// 	$(images).removeClass('stop-parallax');
					// 	new simpleParallax(images,{
					// 		overflow:false,
					// 		orientation:"down - left",
					// 		delay: .5,
					// 		scale:1.6,
					// 		transition: 'cubic-bezier(0,0,0,1)'
					// 	});
					if(direction=="down"){
						if (wS > (hT+hH-wH)){
							var images = document.querySelectorAll('img.my-paroller');
							// var instance = new simpleParallax(images);
							// instance.destroy();
							$(images).addClass('stop-parallax');
							console.log('parallax changed to scale 1.2');
						}
					}else{
						$(images).removeClass('stop-parallax');
					}
						// var images = document.querySelectorAll('img');
						// var instance = new simpleParallax(images);
						// instance.destroy();
					}
				},{
					offset: 'bottom-in-view'
				});
			}
			
			// $('.feacture-img img').each(function(){
			// 	var image = $(this);
			// 	new simpleParallax(image,{
			// 		overflow:true,
			// 		orientation:(image.data('orientation')!=undefined)?image.data('orientation'):"up"
			// 	});
			// })

			var newsBG = document.getElementsByClassName('newsletter-bg-img');
			new simpleParallax(newsBG,{
				scale:1.2,
				transition: 'cubic-bezier(0,0,0,1)'
			});

			var images = document.getElementsByClassName('my-paroller');
			new simpleParallax(images,{
				overflow:false,
				orientation:"down - left",
				delay: .5,
				scale:1.6,
				transition: 'cubic-bezier(0,0,0,1)'
			});

			var image1 = document.getElementsByClassName('newsletter-img1');
			new simpleParallax(image1,{
				overflow:true,
				scale:1.6,
				orientation:"down"
			});
			
			var image2 = document.getElementsByClassName('newsletter-img2');
			new simpleParallax(image2,{
				overflow:true,
				scale:1.6,
				orientation:"down"
			});

		});


})(jQuery);
});

require([
	'jquery', 
	'MGS_AjaxCart/js/config',
	'mgs_quickview',
	'magnificPopup'
	], function(jQuery){
		(function($) {
			var thisClass = this;

			$(document).ready(function(){
				$('.btn-loadmore').click(function(){
					var el = $(this);
					el.addClass('loading');
					url = $(this).attr('href');
					$.ajax({
						url: url,
						success: function(data,textStatus,jqXHR ){
							el.removeClass('loading');
							var result = $.parseJSON(data);
							if(result.content!=''){
								$('#'+result.element_id).append(result.content);

								$('.mgs-quickview').bind('click', function () {
									var prodUrl = $(this).attr('data-quickview-url');
									if (prodUrl.length) {
										reInitQuickview($,prodUrl);
									}
								});

								$("img.lazy").unveil(200, function(){
									var self = $(this);
									self.load(function() {
										this.style.opacity = 1;
										self.removeClass('lazy');
										self.parent().closest('.lazy-img').addClass('loaded');
									});
								});

								$('button.tocart').bind('click', function (event) {
									event.preventDefault();
									tag = $(this).parents('form:first');

									var dataForm = tag.serializeArray();
									initAjaxAddToCart(tag, 'catalog-add-to-cart-' + $.now(), tag.attr('action'), dataForm);
								});
							}

							if(result.url){
								el.attr('href', result.url);
							}else{
								el.remove();
							}


						}
					});
					return false;
				});
			});

		})(jQuery);
	});


function initAjaxAddToCart(tag, actionId, url, data){
	require([
		'jquery',
		'MGS_AjaxCart/js/config',
		'magnificPopup'
		], function ($, mgsConfig) {

			data.push({
				name: 'action_url',
				value: tag.attr('action')
			});

			var self = this;
			data.push({
				name: 'ajax',
				value: 1
			});

			if(tag.find('.tocart').length){
				tag.find('.tocart').addClass('tocart-loading');
			}else{
				tag.addClass('tocart-loading');
			}

			$.ajax({
				url: url,
				data: $.param(data),
				type: 'post',
				dataType: 'json',
				beforeSend: function(xhr, options) {
					if(tag.find('.tocart').length){
						tag.find('.tocart').addClass('disabled');
					}else{
						tag.addClass('disabled');
					}
				},
				success: function(response, status) {
					if(tag.find('.tocart').length){
						tag.find('.tocart').removeClass('tocart-loading');
					}else{
						tag.removeClass('tocart-loading');
					}
					if (status == 'success') {
						if(response.backUrl){
							data.push({
								name: 'action_url',
								value: response.backUrl
							});
							self.initAjaxAddToCart(tag, 'catalog-add-to-cart-' + $.now(), response.backUrl, data);
						}else{
							if (response.ui) {
								if(response.productView){
									jQuery.magnificPopup.open({
										items: {
											src: response.ui,
											type: 'iframe'
										},
										mainClass: 'form-ajax--popup',
										closeOnBgClick: false,
										preloader: true,
										tLoading: '',
										callbacks: {
											open: function() {
												jQuery('.mfp-preloader').css('display', 'block');
											},
											beforeClose: function() {
												var url_cart_update = mgsConfig.updateCartUrl;
												jQuery('[data-block="minicart"]').trigger('contentLoading');
												jQuery.ajax({
													url: url_cart_update,
													method: "POST"
												});
											},
											close: function() {
												jQuery('.mfp-preloader').css('display', 'none');
											},
											afterClose: function() {
												if(!response.animationType) {
													if(!parent.jQuery.magnificPopup.instance.isOpen) {
														if(jQuery('html').hasClass('add-item-success')){
															var $source = '';
															if(tag.find('.tocart').length){
																if(tag.closest('.product-item-info').length){
																	$source = tag.closest('.product-item-info');
																	var width = $source.outerWidth();
																	var height = $source.outerHeight();
																}else{
																	$source = tag.find('.tocart');
																	var width = 300;
																	var height = 300;
																}

															}else{
																var width = $source.outerWidth();
																var height = $source.outerHeight();
															}

															var $animatedObject = jQuery('<div class="flycart-animated-add" style="position: absolute;z-index: 99999;">'+response.image+'</div>');

															var left = $source.offset().left;
															var top = $source.offset().top;

															$animatedObject.css({top: top-1, left: left-1});
															jQuery('html').append($animatedObject);

															jQuery('#footer-cart-trigger').addClass('active');
															jQuery('#footer-mini-cart').slideDown(300);

															var gotoX = jQuery("#fixed-cart-footer").offset().left + 20;
															var gotoY = jQuery("#fixed-cart-footer").offset().top;                                          
															$animatedObject.animate({
																opacity: 0.6,
																left: gotoX,
																top: gotoY
															}, 2000,
															function () {
																$animatedObject.fadeOut('fast', function () {
																	$animatedObject.remove();
																	jQuery('html').removeClass('add-item-success');
																});
															});
														}
													} else {
														var $content = '<div></div><div class="popup__main popup--result">'+response.ui + response.related + '</div>';
														parent.jQuery.magnificPopup.instance.items[0] = {src: $content, type: 'inline'};
														parent.jQuery('.mfp-mgs-quickview').addClass('success-ajax--popup');
														parent.jQuery.magnificPopup.instance.updateItemHTML();
														parent.truncateOptions();
														parent.replaceStrings();
													}
												}
											}
										}
									});
								}else{
									var $content = '<div class="popup__main popup--result">'+response.ui + response.related + '</div>';
									if(response.animationType) {
										if(parent.jQuery.magnificPopup.instance.isOpen){
											parent.jQuery.magnificPopup.instance.items[0] = {src: $content, type: 'inline'};
											parent.jQuery.magnificPopup.instance.mainClass = 'success-ajax--popup';
											parent.jQuery.magnificPopup.instance.updateItemHTML();
											parent.truncateOptions();
											parent.replaceStrings();
										}else {
											jQuery.magnificPopup.open({
												mainClass: 'success-ajax--popup',
												items: {
													src: $content,
													type: 'inline'
												},
												callbacks: {
													beforeClose: function() {
														var url_cart_update = mgsConfig.updateCartUrl;
														jQuery('[data-block="minicart"]').trigger('contentLoading');
														jQuery.ajax({
															url: url_cart_update,
															method: "POST"
														});
													}  
												}
											});
										}
									}else{
										if(!parent.jQuery.magnificPopup.instance.isOpen) {
											/* Fly Cart Type */  
											var $source = '';
											if(tag.find('.tocart').length){
												if(tag.closest('.product-item-info').length){
													$source = tag.closest('.product-item-info');
													var width = $source.outerWidth();
													var height = $source.outerHeight();
												}else{
													$source = tag.find('.tocart');
													var width = 300;
													var height = 300;
												}

											}else{
												tag.removeClass('disabled');
												tag.find('.icon').removeClass('fa-spin');
												tag.find('.text').text(textCart);
												tag.find('.icon').removeClass('pe-7s-config');
												tag.find('.icon').addClass('pe-7s-shopbag');
												$source = tag.closest('.product-item-info');
												var width = $source.outerWidth();
												var height = $source.outerHeight();
											}

											var $animatedObject = jQuery('<div class="flycart-animated-add" style="position: absolute;z-index: 99999;">'+response.image+'</div>');
											var left = $source.offset().left;
											var top = $source.offset().top;

											$animatedObject.css({top: top-1, left: left-1});
											jQuery('html').append($animatedObject);

											var gotoX = jQuery("#fixed-cart-footer").offset().left + 20;
											var gotoY = jQuery("#fixed-cart-footer").offset().top;      

											jQuery('#footer-cart-trigger').addClass('active');
											jQuery('#footer-mini-cart').slideDown(300);

											$animatedObject.animate({
												opacity: 0.6,
												left: gotoX,
												top: gotoY
											}, 2000,
											function () {
												$animatedObject.fadeOut('fast', function () {
													$animatedObject.remove();
												});
											});
										}else {
											parent.jQuery.magnificPopup.close();

											var $animatedObject = parent.jQuery('<div class="flycart-animated-add" style="position: fixed;z-index: 99999; bottom: 50%; left: 50%;">'+response.image+'</div>');

											parent.jQuery('html').append($animatedObject);

											var gotoX = parent.jQuery("#fixed-cart-footer").offset().left + 20;
											var gotoY = parent.jQuery("#fixed-cart-footer").offset().top;      

											parent.jQuery('#footer-cart-trigger').addClass('active');
											parent.jQuery('#footer-mini-cart').slideDown(300);


											$animatedObject.animate({
												opacity: 0.6,
												left: 20,
												bottom: 0
											}, 2000,
											function () {
												$animatedObject.fadeOut('fast', function () {
													$animatedObject.remove();
												});
											});
										}
									}
								}
							}
						}                            
					}
				},
				error: function() {
					$('#mgs-ajax-loading').hide();
					window.location.href = ajaxCartConfig.redirectCartUrl;
				}
			});
});
}

function reInitQuickview($, prodUrl){
	if (!prodUrl.length) {
		return false;
	}
	var url = QUICKVIEW_BASE_URL + 'mgs_quickview/index/updatecart';
	$.magnificPopup.open({
		items: {
			src: prodUrl
		},
		type: 'iframe',
		removalDelay: 300,
		mainClass: 'mfp-fade',
		closeOnBgClick: true,
		preloader: true,
		tLoading: '',
		callbacks: {
			open: function () {
				$('.mfp-preloader').css('display', 'block');
			},
			beforeClose: function () {
				$('[data-block="minicart"]').trigger('contentLoading');
				$.ajax({
					url: url,
					method: "POST"
				});
			},
			close: function () {
				$('.mfp-preloader').css('display', 'none');
			}
		}
	});
}

function setLocation(url) {
	require([
		'jquery'
		], function (jQuery) {
			(function () {
				window.location.href = url;
			})(jQuery);
		});
}

require([ 
	'require', 
	'jquery', 
	'mgs/isotope' 
	], function( require, $, Isotope ) {
		require( [ 'bridget' ], function( jQueryBridget ) {
			jQueryBridget( 'isotope', Isotope, $ );
			$(document).ready(function() {
				var container = $('.landing-categories--masonry').isotope({
					itemSelector: '.landing-masonry-item',
					percentPosition: true,
					masonry: {
						columnWidth: '.landing-masonry-item'
					}
				});
			});     
		});
	});