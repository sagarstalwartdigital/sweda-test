if(CATALOG_AJAX){
	define([
		"jquery",
		"jquery/ui",
		"mage/translate",
		"magnificPopup",
		"Magento_Catalog/js/product/list/toolbar"
	], function($, ui) {
		/**
		 * ProductListToolbarForm Widget - this widget is setting cookie and submitting form according to toolbar controls
		 */
		$.widget('mage.productListToolbarForm', $.mage.productListToolbarForm, {

			options:
			{
				modeControl: '[data-role="mode-switcher"]',
				directionControl: '[data-role="direction-switcher"]',
				orderControl: '[data-role="sorter"]',
				limitControl: '[data-role="limiter"]',
				pagerControl: '[data-role="pager"], .pages-items a',
				mode: 'product_list_mode',
				direction: 'product_list_dir',
				order: 'product_list_order',
				limit: 'product_list_limit',
				pager: 'p',
				modeDefault: 'grid',
				directionDefault: 'asc',
				orderDefault: 'position',
				limitDefault: '9',
				pagerDefault: '1',
				productsToolbarControl:'.toolbar.toolbar-products',
				productsListBlock: '.products.wrapper',
				layeredNavigationFilterBlock: '.block.filter',
				filterItemControl: '.block.filter .item a, .block.filter .filter-clear,.block.filter .swatch-option-link-layered, .pagination .item a',
				url: ''
			},

			_create: function () {
				this._super();
				this._bind($(this.options.pagerControl), this.options.pager, this.options.pagerDefault);
				$(this.options.filterItemControl)
					.off('click.'+this.namespace+'productListToolbarForm')
					.on('click.'+this.namespace+'productListToolbarForm', {}, $.proxy(this.applyFilterToProductsList, this))
				;
				//console.log('toolbar');
			},
			_bind: function (element, paramName, defaultValue) {
				/**
				 * Prevent double binding of these events because this component is being applied twice in the UI
				 */
				if (element.is("select")) {
					element
						.off('change.'+this.namespace+'productListToolbarForm')
						.on('change.'+this.namespace+'productListToolbarForm', {paramName: paramName, default: defaultValue}, $.proxy(this._processSelect, this));
				} else {
					element
						.off('click.'+this.namespace+'productListToolbarForm')
						.on('click.'+this.namespace+'productListToolbarForm', {paramName: paramName, default: defaultValue}, $.proxy(this._processLink, this));
				}
			},
			applyFilterToProductsList: function (evt) {
				var link = $(evt.currentTarget);
				var urlParts = link.attr('href').split('?');
				this.makeAjaxCall(urlParts[0], urlParts[1]);
				evt.preventDefault();
			},
			updateUrl: function (url, paramData) {
				if (!url) {
					return;
				}
				if (paramData && paramData.length > 0) {
					url += '?' + paramData;
				}
				if (typeof history.replaceState === 'function') {
					history.replaceState(null, null, url);
				}
			},

			getParams: function (urlParams, paramName, paramValue, defaultValue) {
				var paramData = {},
					parameters;

				for (var i = 0; i < urlParams.length; i++) {
					parameters = urlParams[i].split('=');
					if (parameters[1] !== undefined) {
						paramData[parameters[0]] = parameters[1];
					} else {
						paramData[parameters[0]] = '';
					}
				}

				paramData[paramName] = paramValue;
				if (paramValue == defaultValue) {
					delete paramData[paramName];
				}
				return window.decodeURIComponent($.param(paramData).replace(/\+/g, '%20'));
			},
			_updateContent: function (content, pageLayout) {
				$(this.options.productsToolbarControl).remove();
				
				if(content.products_list){
					$(this.options.productsListBlock)
						.replaceWith(content.products_list)
					;
				}

				if(content.filters){
					$(this.options.layeredNavigationFilterBlock).replaceWith(content.filters)
				}
				
				if(pageLayout=='1column'){
					$('.category-product-actions:first').remove();
				}

				$('body').trigger('contentUpdated');
				
				setTimeout(this.reInitFunction(), 100);
			},

			updateContent: function (content, pageLayout) {
				$('html, body').animate(
					{
						scrollTop: $(this.options.productsToolbarControl+":first").offset().top
					},
					100,
					'swing',
					this._updateContent(content, pageLayout)
				);
			},


			changeUrl: function (paramName, paramValue, defaultValue) {
				var urlPaths = this.options.url.split('?'),
					baseUrl = urlPaths[0],
					urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
					paramData = this.getParams(urlParams, paramName, paramValue, defaultValue);

				this.makeAjaxCall(baseUrl, paramData);
			},

			makeAjaxCall: function (baseUrl, paramData) {
				var self = this;
				$.ajax({
					url: baseUrl,
					data: (paramData && paramData.length > 0 ? paramData + '&catalogajax=1' : 'catalogajax=1'),
					type: 'get',
					dataType: 'json',
					cache: true,
					showLoader: true,
					timeout: 10000
				}).done(function (response) {
					if (response.success) {
						self.updateUrl(baseUrl, paramData);
						self.updateContent(response.html, response.page_layout);
					} else {
						var msg = response.error_message;
						alert(msg);
					}
				}).fail(function (error) {
					alert($.mage.__('Sorry, something went wrong. Please try again later.'));
				});
			},
			
			initAjaxAddToCart: function(tag, actionId, url, data){
				
					data.push({
						name: 'action_url',
						value: tag.attr('action')
					});
					var textCart = $.mage.__('Add To Cart');	
					$addToCart = tag.find('.tocart').text();
						
					var self = this;
					data.push({
						name: 'ajax',
						value: 1
					});
					
					$.ajax({
						url: url,
						data: $.param(data),
						type: 'post',
						dataType: 'json',
						beforeSend: function(xhr, options) {
							if(ajaxCartConfig.animationType){
								$('#mgs-ajax-loading').show();
							}else{
								if(tag.find('.tocart').length){
                                    tag.find('.tocart').addClass('disabled');
                                    tag.find('.tocart .icon').removeClass('pe-7s-shopbag');
                                    tag.find('.tocart .icon').addClass('fa-spin pe-7s-config');
                                    tag.find('.tocart .text').text('Adding...');
                                    tag.find('.tocart').attr('title','Adding...');
                                }else{
                                    tag.addClass('disabled');
                                    tag.find('.text').text('Adding...');
                                    tag.attr('title','Adding...');
                                } 
							}
						},
						success: function(response, status) {
                            if (status == 'success') {
                                if(response.backUrl){
                                    data.push({
                                        name: 'action_url',
                                        value: response.backUrl
                                    });
                                    self.initAjaxAddToCart(tag, actionId, response.backUrl, data);
                                }else{
                                    if (response.ui) {
                                        if(response.productView){
                                            jQuery('#mgs-ajax-loading').hide();
                                                jQuery.magnificPopup.open({
                                                    items: {
                                                        src: response.ui,
                                                        type: 'iframe'
                                                    },
                                                    mainClass: 'success-ajax--popup',
                                                    closeOnBgClick: false,
                                                    preloader: true,
                                                    tLoading: '',
                                                    callbacks: {
                                                        open: function() {
                                                            jQuery('#mgs-ajax-loading').hide();
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
                                                                    var $source = '';
                                                                    if(tag.find('.tocart').length){
                                                                        tag.find('.tocart').removeClass('disabled');
                                                                        tag.find('.tocart .text').text(textCart);
                                                                        tag.find('.tocart .icon').removeClass('pe-7s-config');
                                                                        tag.find('.tocart .icon').removeClass('fa-spin');
                                                                        tag.find('.tocart .icon').addClass('pe-7s-shopbag');
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
                                                                    
                                                                    $animatedObject.css({top: top-1, left: left-1, width: width, height: height});
                                                                    jQuery('html').append($animatedObject);
                                                                    
                                                                    jQuery('#footer-cart-trigger').addClass('active');
                                                                    jQuery('#footer-mini-cart').slideDown(300);
                                                                    
                                                                    var gotoX = jQuery("#fixed-cart-footer").offset().left + 20;
                                                                    var gotoY = jQuery("#fixed-cart-footer").offset().top;                                          
                                                                    $animatedObject.animate({
                                                                        opacity: 0.6,
                                                                        left: gotoX,
                                                                        top: gotoY,
                                                                        width: $animatedObject.width()/2,
                                                                        height: $animatedObject.height()/2
                                                                    }, 2000,
                                                                    function () {
                                                                        jQuery(".minicart-wrapper").fadeOut('fast', function () {
                                                                            jQuery(".minicart-wrapper").fadeIn('fast', function () {
                                                                                $animatedObject.fadeOut('fast', function () {
                                                                                    $animatedObject.remove();
                                                                                });
                                                                            });
                                                                        });
                                                                    });
                                                                } else {
                                                                    var $content = '<div></div><div class="popup__main popup--result">'+response.ui + response.related + '</div>';
                                                                    jQuery('#mgs-ajax-loading').hide();
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
                                            if(response.animationType) {
                                                /* Popup Type */
                                                var $content = '<div></div><div class="popup__main popup--result">'+response.ui + response.related + '</div>';
                                                jQuery('#mgs-ajax-loading').hide();
                                                if(parent.jQuery.magnificPopup.instance.isOpen){
                                                    parent.jQuery.magnificPopup.instance.items[0] = {src: $content, type: 'inline'};
                                                    parent.jQuery('.mfp-mgs-quickview').addClass('success-ajax--popup');
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
                                                            open: function() {
                                                                jQuery('#mgs-ajax-loading').hide();
                                                            },
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
                                                        tag.find('.tocart').removeClass('disabled');
                                                        tag.find('.tocart .text').text(textCart);
                                                        tag.find('.tocart .icon').removeClass('pe-7s-config');
                                                        tag.find('.tocart .icon').removeClass('fa-spin');
                                                        tag.find('.tocart .icon').addClass('pe-7s-shopbag');
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
                                                    
                                                    $animatedObject.css({top: top-1, left: left-1, width: width, height: height});
                                                    jQuery('html').append($animatedObject);
                                                    
                                                    var gotoX = jQuery("#fixed-cart-footer").offset().left + 20;
                                                    var gotoY = jQuery("#fixed-cart-footer").offset().top;      
                                                    
                                                    jQuery('#footer-cart-trigger').addClass('active');
                                                    jQuery('#footer-mini-cart').slideDown(300);
                                                    
                                                    $animatedObject.animate({
                                                        opacity: 0.6,
                                                        left: gotoX,
                                                        top: gotoY,
                                                        width: $animatedObject.width()/2,
                                                        height: $animatedObject.height()/2
                                                    }, 2000,
                                                    function () {
                                                        jQuery(".minicart-wrapper").fadeOut('fast', function () {
                                                            jQuery(".minicart-wrapper").fadeIn('fast', function () {
                                                                $animatedObject.fadeOut('fast', function () {
                                                                    $animatedObject.remove();
                                                                });
                                                            });
                                                        });
                                                    });
                                                }else {
                                                    var $content = '<div></div><div class="popup__main popup--result">'+response.ui + response.related + '</div>';
                                                    jQuery('#mgs-ajax-loading').hide();
                                                    parent.jQuery.magnificPopup.instance.items[0] = {src: $content, type: 'inline'};
                                                    parent.jQuery('.mfp-mgs-quickview').addClass('success-ajax--popup');
                                                    parent.jQuery.magnificPopup.instance.updateItemHTML();
                                                    parent.truncateOptions();
                                                    parent.replaceStrings();
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
			},
			
			reInitFunction: function(){
				$(".mgs-quickview").bind("click", function() {
					var b = $(this).attr("data-quickview-url");
					b.length && reInitQuickview($, b)
				});
                
                $("img.lazy").unveil(25, function(){
                    var self = $(this);
                    setTimeout(function(){
                        self.removeClass('lazy');
                    }, 0);
                });
				
				var thisClass = this;
				
				$('button.tocart').click(function(event){
					event.preventDefault();
					tag = $(this).parents('form:first');
					
					var data = tag.serializeArray();
					thisClass.initAjaxAddToCart(tag, 'catalog-add-to-cart-' + $.now(), tag.attr('action'), data);
					
					
				});
				
				this._create();
				
				
			}
			
		});

		return $.mage.productListToolbarForm;
	});
}