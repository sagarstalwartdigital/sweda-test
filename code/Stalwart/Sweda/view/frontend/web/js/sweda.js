require([
    'jquery',
    'mage/mage',
    'magnificPopup',
    'Magento_Ui/js/modal/modal'
], function(jQuery,modal){
    jQuery(document).ready(function(){

        jQuery('.pro-print-btn').click(function(e) {

            e.preventDefault();

            var url = jQuery(this).attr('href');

            jQuery.magnificPopup.open({
                items: {
                    src: url,
                    title : "<p><i class='fa fa-print' aria-hidden='true'></i> Print Preview</p>"
                },
                type: 'iframe',
                removalDelay: 300,
                mainClass: 'print_pro iframe',
                closeOnBgClick: false,
                preloader: true,
                tLoading: ''
            });
        });
        
        jQuery('#download-img, .download-img').click(function (e) {

            var proId = jQuery(this).attr('data-id');

            e.preventDefault();

            var imageName = "/pub/media/loading/preloader.gif";
            var image = window.location.protocol + "//" + window.location.host + imageName;

            jQuery('body').append("<div class='ajax-loader'><img src='"+image+"' /></div>");

            var modaloption = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'download-images-popup',
                title: '<p> Download Hi-Res Image(s)</p>',
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
                "productid": proId
            };
            
            jQuery.ajax({
                url: window.location.protocol + "//" + window.location.host + "/" + "sweda/downloadimages/showpopup",
                data: dataToPass,
                type: 'POST',
                dataType: 'json',
                success: function (data, status, xhr) {
                    jQuery('.ajax-loader').remove();
                    jQuery(".download-images-content").remove();
                    jQuery("body").append(data.popuphtml);
                    var smartCartModal = jQuery(".download-images-content").modal(modaloption);
                    smartCartModal.modal('openModal');
                },
                error: function (xhr, status, errorThrown) {
                    console.log('Error happens. Try again.');
                    console.log(errorThrown);
                    alert("Something went wrong, Please try later.");
                }
            });
        });

        
        jQuery("a.order-number-frame").click(function(a){
            a.preventDefault();
            var b = jQuery(this);
            var url = jQuery(this).data("url");
            var orderNo = jQuery(this).data("ordernumber");
            var orderFrame = '<iframe src="https://orders.swedausa.com/apex/f?p=ORDERS:5:::::P5_ORDER_NUMBER:' + orderNo + '" height="900px" width="100%"></iframe>';
            
            jQuery.magnificPopup.open({
                items: {
                    src: 'https://orders.swedausa.com/apex/f?p=ORDERS:5:::::P5_ORDER_NUMBER:' + orderNo,
                    title : "ORDER DETAILS"
                },
                type: 'iframe',
                removalDelay: 300,
                mainClass: 'mfp-fade mfp-mgs-orderview iframe',
                closeOnBgClick: false,
                preloader: true,
                tLoading: ''
            });
        })




        jQuery("form.form-address-edit").submit(function (fe) {

            if (!jQuery(".form-edit-account").valid()) return false;
            if (!jQuery(this).valid()) return false;
            
            fe.preventDefault();
            var fe_form_account = jQuery(".form-edit-account");
            var fe_form_address = jQuery(this);

            var urls = [fe_form_account.attr('action'),fe_form_address.attr('action')];

            jQuery.each(urls, function(i,u){ 
                if (i == 0) {
                    jQuery.ajax({
                        url: u,
                        type: "POST",
                        data: fe_form_account.serialize(),
                        success: function (data) {
                            
                        }

                    });
                } else {
                    jQuery.ajax({
                        url: u,
                        type: "POST",
                        data: fe_form_address.serialize(),
                        success: function (data) {
                            
                        }

                    });
                }
				
			});
        });

        jQuery(".clear a").click(function(fe){
            jQuery("form#refine-search-form input").val('');
            jQuery("form#refine-search-form select").val('ALL');
            jQuery("form#refine-search-form").submit();
        });


    });
});