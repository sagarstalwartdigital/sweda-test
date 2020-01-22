require(
    [
    'jquery',
    'mage/mage',
    'Magento_Ui/js/modal/modal',
    'daterangepickerjs'
    ],
    function (
        jQuery,
        modal
        ) {

        var loader_image = window.location.origin+"/pub/media/loading/preloader.gif";
        // console.log(BASE_URL)

        function bindDatePicker(){
            jQuery(function() {
                jQuery('input[name="event_date"]').datepicker();
            });
        }
        
        function bindDeleteActoins()
        {
            jQuery(".mini_sc_item_delete, .sc_delete").off('click',function() {});
            jQuery(".mini_sc_item_delete, .sc_delete").on('click',function () {
                var deleteUrl = jQuery(this).attr("href");
                var oriLocation = window.location.toString();
                jQuery.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    dataType: 'json',
                    success: function (respData) {
                        // if (respData.smartCartGridHtml) {
                        //     jQuery(".smartcarts-listing-grid").replaceWith(respData.smartCartGridHtml);
                        // }
                        if (respData.miniSmartCartHtml) {
                            jQuery("#mini-smartcart-items").replaceWith(respData.miniSmartCartHtml);
                        }
                        if (respData.smartCartItemCount >= 0) {
                            jQuery("#mini-cart-item-count").text(respData.smartCartItemCount);
                        }
                        bindDeleteActoins();
                        jQuery('#choosediffcart').click(function(){ openSmartCartList() });
                        window.location.href = oriLocation;
                    }
                });
                return false;
            });
        }


        function openSmartCartList() {
            var dataPass = {
                "isajax": true,
                "currenturl": window.location.toString(),
                "onlysmartcartlist": true
            };
            jQuery.ajax({
                url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/showpopup",
                data: dataPass,
                type: 'POST',
                dataType: 'json',
                success: function (data, status, xhr) {
                    if (data.logged) {
                        jQuery("#smartcart-popup-modal").remove();
                        jQuery("#mini-smartcart-items").html(data.popuphtml);
                        bindChooseDiffCartLinks();
                    } else {
                        if (data.redirectUrl)
                            window.location.href = data.redirectUrl;
                    }
                },
                error: function (xhr, status, errorThrown) {
                    console.log('Error happens. Try again.');
                    console.log(errorThrown);
                    alert("Something went wrong, Please try later.");
                }
            });
        }

        function openSmartCartListQuickView() {
            var dataPass = {
                "isajax": true,
                "currenturl": window.location.toString(),
                "choosediffcartquickview": true
            };
            jQuery.ajax({
                url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/showpopup",
                data: dataPass,
                type: 'POST',
                dataType: 'json',
                success: function (data, status, xhr) {
                    if (data.logged) {
                        jQuery(".display-active-smart-cart").remove();
                        jQuery('.chnage-smart-cart-btn').html(data.popuphtml);
                        bindChooseDiffCartLinksQuickView();
                    } else {
                        if (data.redirectUrl)
                            window.location.href = data.redirectUrl;
                    }
                },
                error: function (xhr, status, errorThrown) {
                    console.log('Error happens. Try again.');
                    console.log(errorThrown);
                    alert("Something went wrong, Please try later.");
                }
            });
        }

        function bindChooseDiffCartLinks()
        {
            jQuery("form#add-smartcart-form .choosed-diff-sc").click(function (fe) {
                fe.preventDefault();
                var fe_url = jQuery(this).attr('href');

                jQuery.ajax({
                    type: "POST",
                    url: fe_url,
                    success: function (respData) {
                        if (respData.logged) {
                            if (respData.smartCartItemCount >= 0) {
                                jQuery("#mini-cart-item-count").text(respData.smartCartItemCount);
                            }
                            if (respData.miniCartPopupHtml) {
                                appendMiniCart(respData.miniCartPopupHtml);
                                setTimeout(function() {
                                    jQuery('.sidenav.minicart').css('transform','translateX(100%)');
                                    jQuery("#myOverlay").fadeOut();
                                    jQuery(".minicart-content").remove();
                                }, 2000);
                                jQuery('#choosediffcart').click(function(){ openSmartCartList() });
                            }
                        } else {
                            if (respData.redirectUrl)
                                window.location.href = respData.redirectUrl;
                        }
                    }
                });
                return false;

            });

        }

        function bindChooseDiffCartLinksQuickView()
        {
            jQuery("form#add-smartcart-form .choosed-diff-sc").click(function (fe) {
                fe.preventDefault();
                var fe_url = jQuery(this).attr('href');

                jQuery.ajax({
                    type: "POST",
                    url: fe_url,
                    success: function (respData) {
                        if (respData.logged) {
                            jQuery(".chnage-smart-cart-btn").remove();
                            if (respData.smartCartItemCount >= 0) {
                                jQuery("#mini-cart-item-count",parent.document).text(respData.smartCartItemCount);
                            }
                            if (respData.quickviewchangecarthtml) {
                                jQuery(".change-cart-wrapper").append(respData.quickviewchangecarthtml);
                                jQuery('#choosediffcartquickview').click(function(){ openSmartCartListQuickView() });
                            }
                            jQuery("#create_new_smart_cart_from_mini_pdp").click(function(){ openNewSmartcart();});
                        } else {
                            if (respData.redirectUrl)
                                window.location.href = respData.redirectUrl;
                        }
                    }
                });
                return false;

            });

        }

        function bindAddToCartQuickViewButton() {
            var modaloption = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                clickableOverlay: true,
                title: 'Create a New Smart Cart',
                buttons: [{
                    text: jQuery.mage.__('Close'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }],
            };
            var currentClickedElemId = jQuery("#product-addtocart-quickview-button").attr("id");
            var isCurrentProductView = jQuery("#product-addtocart-quickview-button").hasClass("btn-tocart-pro-view");
            if (currentClickedElemId == "product-addtocart-quickview-button" && jQuery('#product_addtocart_form').valid()) {

                if (currentClickedElemId == "product-addtocart-quickview-button" || (currentClickedElemId == "product-addtocart-quickview-button" && isCurrentProductView)) {
                    if (currentClickedElemId == "product-addtocart-quickview-button" && !isCurrentProductView) {

                        var dataToPass = {
                            "isajax": true,
                            "currenturl": window.location.toString(),
                            "currentsmartcart": jQuery(".display-active-smart-cart > p").attr('id'),
                            "currentitemoption": jQuery("#product_addtocart_form").serialize()
                        };
                    } else {
                        var dataToPass = {
                            "isajax": true,
                            "currenturl": window.location.toString(),
                            "currentsmartcart": jQuery(".display-active-smart-cart > p").attr('id'),
                            "currentitemoption": jQuery("#product_addtocart_form").serialize(),
                            "onproview": true
                        };
                    }
                }
                jQuery('body').append("<div class='ajax-loader'><img src='" + loader_image + "' /></div>");
                jQuery.ajax({
                    url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/savecart",
                    data: dataToPass,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, status, xhr) {
                        jQuery('.ajax-loader').remove();
                        if (data.logged) {
                            if ((currentClickedElemId != "product-addtocart-quickview-button") || (currentClickedElemId == "product-addtocart-quickview-button" && jQuery(".display-active-smart-cart > p").attr('id') == "") || (jQuery("span").hasClass("smart_cart_items_list")) ) {
                                if (jQuery("span").hasClass("smart_cart_items_list")) {
                                    alert("Please Choose Any One Smart Cart");
                                } else {
                                    jQuery("#smartcart-popup-modal").remove();

                                    jQuery("body").append(data.popuphtml);

                                    jQuery(".reciepentdata_delete").click(function () {
                                        jQuery(this).parents('.row.apended').remove();
                                    });

                                    var smartCartModal = jQuery("#smartcart-popup-modal").modal(modaloption)
                                    smartCartModal.modal('openModal');

                                    jQuery("#smartcart-popup-modal #productid").val(currentClickedElemId);

                                    jQuery("#smartcart-popup-modal #productdata").val(jQuery("#product_addtocart_form").serialize());

                                    jQuery('.smart_cart_items_list > label').click(function () {
                                        if (jQuery('.smart_cart_items_list').hasClass("active")) {
                                            jQuery('.smart_cart_items_list').removeClass("active");
                                        }
                                        jQuery(this).parent().addClass("active");
                                    });

                                    jQuery("#smartcart-popup-modal a#add-more-recipients").click(function () {
                                        jQuery(".recipentdata").append('<div class="row apended"><div class="col-md-12"><div class="row"><div class="col-md"> <div class="cart-md-form"> <label for="name" class="">Recipient Name</label> <input class="input-text" name="recipientname[]" type="text"> </div></div><div class="col-md"> <div class="cart-md-form"> <label for="email" class="">Recipient Email</label> <input class="input-text" name="recipientemail[]" type="email"> </div></div><div class="col-md-1"> <div class="cart-md-form"> <label for="" class="">&nbsp;</label> <a href="javascript:void(0)" class="reciepentdata_delete"></a> </div></div></div></div></div>');
                                        jQuery(".reciepentdata_delete").click(function () {
                                            jQuery(this).parents('.row.apended').remove();
                                        });
                                    });

                                    var addSmartCartForm = jQuery('#add-smartcart-form');
                                    var ignore = null;

                                    addSmartCartForm.mage('validation', {
                                        ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
                                    }).find('input:text').attr('autocomplete', 'off');

                                    jQuery("#smartcart-popup-modal .smart-cart-list").click(function () {
                                        if (jQuery(this).hasClass("add-new")) {
                                            jQuery("div.open_addnew_form").show();
                                        } else {
                                            jQuery("div.open_addnew_form").hide();
                                        }
                                    });
                                    bindSubmitSmartCartForm(smartCartModal);
                                    bindDatePicker();
                                }
                            } else {
                                if( window.self !== window.top )
                                {
                                    if (data.logged) {
                                        if (data.smartCartItemCount >= 0) {
                                            jQuery("#mini-cart-item-count",parent.document).text(data.smartCartItemCount);
                                        }
                                        if (data.miniCartPopupHtml) {
                                            jQuery('.open-minicart',parent.document).click();
                                        }
                                    } else {
                                        if (data.redirectUrl)
                                            window.location.href = data.redirectUrl;
                                    }
                                } else {
                                    if (data.logged) {
                                        if (data.smartCartItemCount >= 0) {
                                            jQuery("#mini-cart-item-count").text(data.smartCartItemCount);
                                        }
                                        if (data.miniCartPopupHtml) {
                                            jQuery('.open-minicart').click();
                                        }
                                    } else {
                                        if (data.redirectUrl)
                                            window.location.href = data.redirectUrl;
                                    }
                                }
                            }

                        } else {
                            if (data.redirectUrl)
                                window.location.href = data.redirectUrl;
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('Error happens. Try again.');
                        console.log(errorThrown);
                        alert("Something went wrong, Please try later.");
                    }
                });
}

}

function bindSubmitSmartCartForm(smartCartModal)
{
    jQuery("form#add-smartcart-form").submit(function (fe) {

        var isFromWhere = jQuery(this).hasClass("is-from-product");
        if (jQuery('button[name="adding_pro_submit"]').length > 0 ) {
            var btnaddprovalue = jQuery('button[name="adding_pro_submit"]').val();
        }

        if (!jQuery(this).valid()) return false;


        fe.preventDefault();

        var fe_form = jQuery(this);
        var fe_url = fe_form.attr('action');
        jQuery.ajax({
            type: 'POST',
            url: fe_url,
            data: fe_form.serialize(),
            dataType: 'json',
            success: function (respData) {
                if( window.self !== window.top )
                {
                    if (respData.logged) {
                        if (respData.smartCartItemCount >= 0) {
                            jQuery("#mini-cart-item-count",parent.document).text(respData.smartCartItemCount);
                        }
                        if (respData.miniCartPopupHtml) {
                            jQuery('.open-minicart',parent.document).click();
                        }
                    } else {
                        if (respData.redirectUrl)
                            window.location.href = respData.redirectUrl;
                    }
                }else{
                    if (respData.logged) {
                        if (respData.smartCartItemCount >= 0) {
                            jQuery("#mini-cart-item-count").text(respData.smartCartItemCount);
                        }
                        if (respData.miniCartPopupHtml) {
                            appendMiniCart(respData.miniCartPopupHtml);
                            setTimeout(function() {
                                jQuery('.sidenav.minicart').css('transform','translateX(100%)');
                                jQuery("#myOverlay").fadeOut();
                                jQuery(".minicart-content").remove();
                            }, 2000);
                            jQuery('#choosediffcart').click(function(){ openSmartCartList() });
                        }
                        if (isFromWhere) {

                            if (respData.addtoSmartBtnHtml) {
                                jQuery('.pdp-smart-cart-button').remove();
                                jQuery('.change-cart-wrapper').remove();
                                jQuery('.product-info-main').append(respData.addtoSmartBtnHtml);
                            }
                            if (respData.changeSmartCartBtnHtml) {
                                jQuery('.product-add-btn-view').remove();
                                jQuery('.product-info-main').append(respData.changeSmartCartBtnHtml);
                            }
                            jQuery('#choosediffcartquickview').click(function () { openSmartCartListQuickView() });
                            jQuery("#product-addtocart-quickview-button").click(function(){ bindAddToCartQuickViewButton() });
                            jQuery("#create_new_smart_cart_from_mini_pdp").click(function(){ openNewSmartcart();});
                        }
                    } else {
                        if (respData.redirectUrl)
                            window.location.href = respData.redirectUrl;
                    }
                }
            },
            complete: function (respData) {
                smartCartModal.modal("closeModal");
                if(!(isFromWhere))
                {
                    if (btnaddprovalue.length > 0) {
                                // window.location.href = window.location.protocol + "//" + window.location.host + "#start_new_project";
                                location.reload();
                            }
                            // jQuery("#smartcart-popup-modal").remove();
                        }
                    }

                });

    });
}

function appendMiniCart(miniCartHtml)
{
            //jQuery('.sidenav.minicart').css("right", "0");
            jQuery('.sidenav.minicart').css('transform','translateX(0px)');
            jQuery(".minicart-content").remove();
            jQuery("#myOverlay").fadeIn();
            jQuery(".sidenav.minicart").append(miniCartHtml);
            jQuery('a.closebtn#minicartclose').click(function(){
                jQuery('.sidenav.minicart').css('transform','translateX(100%)');
                jQuery("#myOverlay").fadeOut();
                jQuery(".minicart-content").remove();
            })
            jQuery('#myOverlay').click(function(){
                jQuery('.sidenav.minicart').css('transform','translateX(100%)');
                jQuery("#myOverlay").fadeOut();
                jQuery(".minicart-content").remove();
            })
            bindDeleteActoins();
        }

        function openNewSmartcart(){
            var modaloption = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'open-smartcart-wrapper-from-mini',
                clickableOverlay: true,
                buttons: [{
                    text: jQuery.mage.__('Close'),
                    click: function () {
                        this.closeModal();
                    }
                }],
            };
            var currentClickedElemId = jQuery(this).attr("id");
            var dataToPass = {
                "isajax": true,
                "currenturl": window.location.toString(),
                "onlyform": true
            };


            jQuery('body').append("<div class='ajax-loader'><img src='" + loader_image + "' /></div>");
            jQuery.ajax({
                url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/showpopup",
                data: dataToPass,
                type: 'POST',
                dataType: 'json',
                success: function (data, status, xhr) {
                    jQuery('.ajax-loader').remove();
                    if (data.logged) {
                        jQuery("#smartcart-popup-modal").remove();
                        jQuery("body").append(data.popuphtml);
                        jQuery(".reciepentdata_delete").click(function () {
                            jQuery(this).parents('.row.apended').remove();
                        });
                        var smartCartModal = jQuery("#smartcart-popup-modal").modal(modaloption)
                        smartCartModal.modal('openModal');
                        bindDatePicker();

                        jQuery("#smartcart-popup-modal #productid").val('create_new_smart_cart');

                        jQuery("#smartcart-popup-modal a#add-more-recipients").click(function () {
                            jQuery(".recipentdata").append('<div class="row apended"><div class="col-md-12"><div class="row"><div class="col-md"> <div class="cart-md-form"> <label for="name" class="">Recipient Name</label> <input class="input-text" name="recipientname[]" type="text"> </div></div><div class="col-md"> <div class="cart-md-form"> <label for="email" class="">Recipient Email</label> <input class="input-text" name="recipientemail[]" type="email"> </div></div><div class="col-md-1"> <div class="cart-md-form"> <label for="" class="">&nbsp;</label> <a href="javascript:void(0)" class="reciepentdata_delete"></a> </div></div></div></div></div>');
                            jQuery(".reciepentdata_delete").click(function () {
                                jQuery(this).parents('.row.apended').remove();
                            });
                        });
                        jQuery('.back-overlay').css('display','block');
                        jQuery('.action-close').click(function () {
                            jQuery('.back-overlay').css('display','none'); 
                        });
                        var addSmartCartForm = jQuery('#add-smartcart-form');
                        var ignore = null;

                        addSmartCartForm.mage('validation', {
                            ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
                        }).find('input:text').attr('autocomplete', 'off');

                        bindSubmitSmartCartForm(smartCartModal);
                    } else {
                        if (data.redirectUrl)
                            window.location.href = data.redirectUrl;
                    }
                },
                error: function (xhr, status, errorThrown) {
                    jQuery('.ajax-loader').remove();
                    console.log('Error happens. Try again.');
                    console.log(errorThrown);
                    alert("Something went wrong, Please try later.");
                }
            });
        }
        function appendMiniCartCreateNewPopup()
        {
            jQuery("#create_new_smart_cart_from_mini").click(function () {
                console.log(jQuery(this));
                openNewSmartcart();
            });
        }


        jQuery(document).ready(function () {

            jQuery('.mgs-quickview-notbtob').click(function (e) {

                e.preventDefault();

                var modaloption = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'non-btob-modal',
                    clickableOverlay: true,
                    buttons: [{
                        text: jQuery.mage.__('Close'),
                        click: function () {
                            this.closeModal();
                        }
                    }],
                };
                var dataToPass = {
                    "isajax": true,
                    "currenturl": window.location.toString()
                };

                jQuery('body').append("<div class='ajax-loader'><img src='" + loader_image + "' /></div>");

                jQuery.ajax({
                    url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/Shownonbtobpopup",
                    data: dataToPass,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, status, xhr) {
                        jQuery('.ajax-loader').remove();
                        if (data.logged) {
                            jQuery('.non-btob-popup-content').remove();
                            jQuery("body").append(data.popuphtml);
                            var nonbtobmodal = jQuery(".non-btob-popup-content").modal(modaloption);
                            nonbtobmodal.modal('openModal');
                        } else {
                            if (data.redirectUrl)
                                window.location.href = data.redirectUrl;
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('Error happens. Try again.');
                        console.log(errorThrown);
                        alert("Something went wrong, Please try later.");
                    }
                });
            });

            var smartCartModal = jQuery("#smartcart-popup-modal").modal();
            jQuery('body').on('modalclosed',smartCartModal, function () {
                console.log('Do some action when modal closed')
                jQuery('.sidenav').removeClass('add-new-cart')
            });

            var smartCartModal = jQuery("#smartcart-popup-modal").modal();
            jQuery('body').on('opened',smartCartModal, function () {
                console.log('Do some action when modal closed')
                jQuery('.sidenav').addClass('add-new-cart')
            });

            jQuery('.send-mail').click(function (e) {

                e.preventDefault();

                var currenClickeId = jQuery(this).attr("id");

                var modaloption = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'send-mail-modal-wrapper',
                    clickableOverlay: true,
                    buttons: [{
                        text: jQuery.mage.__('Close'),
                        click: function () {
                            this.closeModal();
                        }
                    }],
                };
                var dataToPass = {
                    "isajax": true,
                    "currenturl": window.location.toString(),
                    "smartcartid": currenClickeId
                };

                jQuery.ajax({
                    url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/sendsmartcart",
                    data: dataToPass,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, status, xhr) {
                        if (data.logged) {
                            jQuery(".send-mail-content").remove();
                            jQuery("body").append(data.popuphtml);
                            var smartCartModal = jQuery(".send-mail-content").modal(modaloption);
                            smartCartModal.modal('openModal');
                        } else {
                            if (data.redirectUrl)
                                window.location.href = data.redirectUrl;
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('Error happens. Try again.');
                        console.log(errorThrown);
                        alert("Something went wrong, Please try later.");
                    }
                });
            });

            jQuery('.add-more-pro').click(function (e) {

                e.preventDefault();

                var currenClickeSmartCartId = jQuery(this).attr("id");
                sessionStorage.setItem("smartcartid",currenClickeSmartCartId);

                var modaloption = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: 'Start Adding Products',
                    modalClass: 'adding-product-modal-wrapper',
                    clickableOverlay: true,
                    buttons: [{
                        text: jQuery.mage.__('Close'),
                        click: function () {
                            this.closeModal();
                        }
                    }],
                };
                var dataToPass = {
                    "isajax": true,
                    "currenturl": window.location.toString()
                };

                jQuery.ajax({
                    url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/setactivecart",
                    data: dataToPass,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, status, xhr) {
                        if (data.logged) {
                            jQuery(".add-more-product-menu").remove();
                            jQuery("body").append(data.popuphtml);
                            var smartCartModal = jQuery(".add-more-product-menu").modal(modaloption);
                            smartCartModal.modal('openModal');
                            bindcatclicklink();
                        } else {
                            if (data.redirectUrl)
                                window.location.href = data.redirectUrl;
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('Error happens. Try again.');
                        console.log(errorThrown);
                        alert("Something went wrong, Please try later.");
                    }
                });
            });

            function bindcatclicklink()
            {
                jQuery('.add-more-product-menu .nav-megamenu.mgs-menu nav.navigation ul#mainMenu li > ul.dropdown-menu .custom-megamenu .col ul li > a, .adding-product-modal-wrapper .shopall-btn a').click(function (e) {

                    e.preventDefault();

                    var currenClickeUrl = jQuery(this).attr("href");

                    var dataToPass = {
                        "isajax": true,
                        "currenturl": window.location.toString(),
                        "locationtosend": currenClickeUrl,
                        "smartcartid": sessionStorage.getItem("smartcartid")
                    };

                    jQuery.ajax({
                        url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/setactivecart",
                        data: dataToPass,
                        type: 'POST',
                        dataType: 'json',
                        success: function (data, status, xhr) {
                            if (data.logged) {
                                window.location.href = data.catUrl;
                                sessionStorage.removeItem("smartcartid");
                            } else {
                                if (data.redirectUrl)
                                    window.location.href = data.redirectUrl;
                            }
                        },
                        error: function (xhr, status, errorThrown) {
                            console.log('Error happens. Try again.');
                            console.log(errorThrown);
                            alert("Something went wrong, Please try later.");
                        }
                    });
                });
            }


            /* slide some area on ac page in mobile view */
            jQuery(document).ready(function(){
                jQuery(document).on('click','.account-overview-title .your-profile-section a',function(){
                    if (jQuery(this).find('h2').hasClass('open')) {
                        jQuery(this).find('h2').removeClass("open");
                        jQuery(this).siblings(".Profile-detail").hide();
                    }
                    else{
                        jQuery(this).find('h2').addClass("open");
                        jQuery(this).siblings(".Profile-detail").show();
                    }
                });

                jQuery(document).on('click','.account-overview-title .recent-orders-section h2',function(){
                    if (jQuery(this).hasClass('open')) {
                        jQuery(this).removeClass("open");
                        jQuery(this).siblings(".recent-detail").hide();
                    }
                    else{
                        jQuery(this).addClass("open");
                        jQuery(this).siblings(".recent-detail").show();
                    }
                        // jQuery(this).toggleClass("open");
                        // jQuery(".recent-detail").slideToggle("slow");
                    });
                jQuery(document).on('click','.account-overview-section h2',function(){
                    if (jQuery(this).hasClass('open')) {
                        jQuery(this).removeClass("open");
                        jQuery(this).siblings(".recent-detail").hide();
                    }
                    else{
                        jQuery(this).addClass("open");
                        jQuery(this).siblings(".recent-detail").show();
                    }

                        // jQuery(this).toggleClass("open");
                        // jQuery(".account-overview-smartcart-table").slideToggle("slow");
                    });
            })
            jQuery(window).resize(function() {
                var mobwindowwidth = jQuery(window).width();
                if (mobwindowwidth < 769) {
                    jQuery(".comment-box label").click(function(){
                        jQuery(this).toggleClass("open");
                        jQuery(".comment-box textarea").slideToggle("slow");
                    });
                }
            }).resize();

            /* end slide textarea on moxy cart */

            var modaloption = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                clickableOverlay: true,
                title: 'Create a New Smart Cart',
                buttons: [{
                    text: jQuery.mage.__('Close'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }],
            };
            var modaloptioneditcart = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                clickableOverlay: true,
                title: 'Edit Your Smart Cart',
                buttons: [{
                    text: jQuery.mage.__('Close'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }],
            };

            bindDeleteActoins();
            /*** on page load - do a ajax call to get Smart Cart Items count and display in header - starts ***/
            jQuery.ajax({
                url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/Minicartcounts",
                type: 'POST',
                dataType: 'json',
                success: function (respData) {
                    if (respData.smartCartItemCount >= 0) {
                        jQuery("#mini-cart-item-count").text(respData.smartCartItemCount);
                    }
                }
            });
            /*** on page load - do a ajax call to get Smart Cart Items count and display in header - ends ***/

            jQuery('.editsmartcartsubmit, .editallsmartcartitem').click(function(){
                var currentClickedClassForEdit = jQuery(this).attr("class");

                if (currentClickedClassForEdit != "editallsmartcartitem") {
                    var smartcartcurrentitemid = jQuery(this).attr("id");
                    var currentcongigurablepro = jQuery(this).attr("data-id");
                }

                var editcartitemurl = window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/editcartitem";

                if (currentClickedClassForEdit == "editallsmartcartitem"){
                    var forFirstDisplayMsgCounter = 0;
                    jQuery(".editsmartcartsubmit").each(function(){
                        forFirstDisplayMsgCounter++;
                        var displayMsgFlag = 0;
                        if(jQuery(".editsmartcartsubmit").length == forFirstDisplayMsgCounter)
                            displayMsgFlag = 1;
                        var smartcartcurrentitemid = jQuery(this).attr("id");
                        var currentcongigurablepro = jQuery(this).attr("data-id");
                        if(smartcartcurrentitemid && currentcongigurablepro)
                        {
                            if (!jQuery("#product_addtosmartcart_form-"+smartcartcurrentitemid).valid()) return false;
                            jQuery.ajax({
                                url: editcartitemurl,
                                type: "POST",
                                data: {
                                    dodisplaysuccessmsg: displayMsgFlag,
                                    configurablepro: currentcongigurablepro,
                                    smartcartitemid: smartcartcurrentitemid,
                                    data: jQuery("#product_addtosmartcart_form-"+smartcartcurrentitemid).serialize(),
                                    datacomments: jQuery(".usecomments").serialize()
                                },
                                dataType: "JSON",
                                success: function (data) {
                                    location.reload();
                                }
                            });
                        }
                    });
                } else {
                    if (!jQuery("#product_addtosmartcart_form-"+smartcartcurrentitemid).valid()) return false;
                    jQuery.ajax({
                        url: editcartitemurl,
                        type: "POST",
                        data: {
                            dodisplaysuccessmsg: 1,
                            configurablepro: currentcongigurablepro,
                            smartcartitemid: smartcartcurrentitemid,
                            data: jQuery("#product_addtosmartcart_form-"+smartcartcurrentitemid).serialize(),
                            datacomments: jQuery(".usecomments").serialize()
                        },
                        dataType: "JSON",
                        success: function (data) {
                            location.reload();
                        }
                    });
                }
            });

            /*** get fresh mini cart popup-sidebar on click on cart icon - starts ***/
            jQuery('.open-minicart').click(function () {
                jQuery(".mfp-close").click();
                var dataToPass = {
                    "isajax": true,
                    "currenturl": window.location.toString(),
                };

                jQuery.ajax({
                    url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/Showminicart",
                    data: dataToPass,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, status, xhr) {
                        if (data.logged) {
                            jQuery(".minicart .message.info.empty").remove();
                            jQuery("#create_new_smart_cart_from_mini").remove();
                            appendMiniCart(data.popuphtml);
                            jQuery('#choosediffcart').click(function(){ openSmartCartList() });
                            appendMiniCartCreateNewPopup();
                        } else {
                            if (data.redirectUrl)
                                window.location.href = data.redirectUrl;
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('Error happens. Try again.');
                        console.log(errorThrown);
                        alert("Something went wrong, Please try later.");
                    }
                });
            });

            jQuery('#choosediffcartquickview').click(function () { openSmartCartListQuickView() });
            jQuery("#product-addtocart-quickview-button").click(function(){ bindAddToCartQuickViewButton() });

            jQuery("#create_new_smart_cart_from_mini_pdp").click(function(){ openNewSmartcart();});


            /*** get fresh mini cart popup-sidebar on click on cart icon - ends ***/

            jQuery("form#product_addtocart_form .field.qty").hide();
            jQuery("form#product_addtocart_form #product-addtocart-button").attr("type", "button");

            jQuery("form#product_addtocart_form #product-addtocart-button").attr("title", "Add to smart cart");
            jQuery("form#product_addtocart_form #product-addtocart-button span.text").text("Add to smart cart");

            jQuery("#product-addtocart-button, #create_new_smart_cart, .add-to-smart-cart-plus, #edit_cart_info").click(function () {
                var currentClickedElemId = jQuery(this).attr("id");
                var currentClickedElemHasClass = jQuery(this).hasClass("add-to-smart-cart-plus");
                var currentClickedClass = jQuery(this).attr("class");
                var isCurrentProductView = jQuery(this).hasClass("btn-tocart-pro-view");
                if ((currentClickedElemHasClass || currentClickedElemId == "create_new_smart_cart" || currentClickedElemId == "edit_cart_info") || (currentClickedElemId == "product-addtocart-button" && jQuery('#product_addtocart_form').valid()) || (currentClickedElemId == "product-addtocart-quickview-button" && jQuery('#product_addtocart_form').valid())) {
                    if (currentClickedElemId == "create_new_smart_cart" || currentClickedElemId == "edit_cart_info") {
                        if (currentClickedElemId == "create_new_smart_cart") {
                            var dataToPass = {
                                "isajax": true,
                                "currenturl": window.location.toString(),
                                "onlyform": true
                            };
                        } else {
                            var dataToPass = {
                                "isajax": true,
                                "currenturl": window.location.toString(),
                                "editcart": true,
                                "editcartclass": currentClickedClass
                            };
                        }
                    } else {
                        var dataToPass = {
                            "isajax": true,
                            "currenturl": window.location.toString(),
                        };
                    }

                    jQuery.ajax({
                        url: window.location.protocol + "//" + window.location.host + "/" + "smartcart/cartindex/showpopup",
                        data: dataToPass,
                        type: 'POST',
                        dataType: 'json',
                        success: function (data, status, xhr) {
                            if (data.logged) {
                                jQuery("#smartcart-popup-modal").remove();
                                jQuery("body").append(data.popuphtml);
                                jQuery(".reciepentdata_delete").click(function () {
                                    jQuery(this).parents('.row.apended').remove();
                                });
                                if (currentClickedElemId == "edit_cart_info"){
                                    var smartCartModal = jQuery("#smartcart-popup-modal").modal(modaloptioneditcart)
                                } else {
                                    var smartCartModal = jQuery("#smartcart-popup-modal").modal(modaloption)
                                }
                                smartCartModal.modal('openModal');
                                bindDatePicker();
                                jQuery("#smartcart-popup-modal #productid").val(currentClickedElemId);
                                if (currentClickedElemId != "create_new_smart_cart") {
                                    jQuery("#smartcart-popup-modal #productdata").val(jQuery("#product_addtocart_form").serialize());
                                    jQuery('.smart_cart_items_list > label').click(function () {
                                        if (jQuery('.smart_cart_items_list').hasClass("active")) {
                                            jQuery('.smart_cart_items_list').removeClass("active");
                                        }
                                        jQuery(this).parent().addClass("active");
                                    });
                                }
                                jQuery("#smartcart-popup-modal a#add-more-recipients").click(function () {
                                    jQuery(".recipentdata").append('<div class="row apended"><div class="col-md-12"><div class="row"><div class="col-md"> <div class="cart-md-form"> <label for="name" class="">Recipient Name</label> <input class="input-text" name="recipientname[]" type="text"> </div></div><div class="col-md"> <div class="cart-md-form"> <label for="email" class="">Recipient Email</label> <input class="input-text" name="recipientemail[]" type="email"> </div></div><div class="col-md-1"> <div class="cart-md-form"> <label for="" class="">&nbsp;</label> <a href="javascript:void(0)" class="reciepentdata_delete"></a> </div></div></div></div></div>');
                                    jQuery(".reciepentdata_delete").click(function () {
                                        jQuery(this).parents('.row.apended').remove();
                                    });
                                });
                                var addSmartCartForm = jQuery('#add-smartcart-form');
                                var ignore = null;

                                addSmartCartForm.mage('validation', {
                                    ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
                                }).find('input:text').attr('autocomplete', 'off');

                                jQuery("#smartcart-popup-modal .smart-cart-list").click(function () {
                                    if (jQuery(this).hasClass("add-new")) {
                                        jQuery("div.open_addnew_form").show();
                                    } else {
                                        jQuery("div.open_addnew_form").hide();
                                    }
                                });
                                bindSubmitSmartCartForm(smartCartModal);
                            } else {
                                if (data.redirectUrl)
                                    window.location.href = data.redirectUrl;
                            }
                        },
                        error: function (xhr, status, errorThrown) {
                            console.log('Error happens. Try again.');
                            console.log(errorThrown);
                            alert("Something went wrong, Please try later.");
                        }
                    });
}

});


});
});