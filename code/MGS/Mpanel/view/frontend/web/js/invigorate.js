require([
    'jquery',
    'mage/mage'
], function(jQuery){
    jQuery(document).ready(function(){
        jQuery("#add_item_smart_cart").click(function(){
            var dataToPass = {
                "isajax" : "true",
                "currenturl" : window.location.toString(),
            };
            jQuery.ajax({
                url: "http://swedausa.local/smartcart/cartindex/showpopup",
                data: dataToPass,
                type: 'POST',
                dataType: 'json',
                success: function(data, status, xhr) {
                    if(data.logged)
                    {
                        jQuery("#smartcart-popup-modal").remove();
                        jQuery("body").append(data.popuphtml);
                        jQuery("#smartcart-popup-modal").modal().modal('openModal');
                        if(currentClickedElemId != "create_new_smart_cart")
                        {
                            jQuery("#smartcart-popup-modal #productdata").val(jQuery("#product_addtocart_form").serialize());
                            jQuery('.smart_cart_items_list > label').click(function() {
                                if(jQuery('.smart_cart_items_list').hasClass("active")){
                                    jQuery('.smart_cart_items_list').removeClass("active");
                                }
                                jQuery(this).parent().addClass("active");
                            });
                        }
                        jQuery("#smartcart-popup-modal a#add-more-recipients").click(function(){
                            jQuery(".recipentdata").append('<div class="row apended"><div class="col-md-11"><div class="row"><div class="col-md-6">Recipient Name<br><input required="true" name="recipientname[]" type="text"></div><div class="col-md-6">Recipient Email<br><input required="true" name="recipientemail[]" type="email"></div></div></div><div class="col-md-1"><div class="row"><a href="javascript:void(0)" class="reciepentdata_delete"></a></div></div></div>');
                            jQuery(".reciepentdata_delete").click(function(){
                                jQuery(this).parent().parent().parent().remove();
                            });
                        });
                        var addSmartCartForm = jQuery('#add-smartcart-form');
                        var ignore = null;

                        addSmartCartForm.mage('validation', {
                            ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
                        }).find('input:text').attr('autocomplete', 'off');

                        jQuery("#smartcart-popup-modal .smart-cart-list").click(function(){
                            if(jQuery(this).hasClass("add-new"))
                            {
                                jQuery("div.open_addnew_form").show();
                            }
                            else{
                                jQuery("div.open_addnew_form").hide();
                            }
                        });
                    }else{
                        if(data.redirectUrl)
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
    });
});