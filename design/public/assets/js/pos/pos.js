$("#saleSubmit").hide();
    $(document).ready(function() {
        $('.left-sidebar').addClass('aside-margin');
        $('.wrapper-page').addClass('wrapper-margin');
        $('.footer').addClass('wrapper-margin');
        $(document).on('click', "#superpro", function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var mrp = $(this).attr('data-mrp');
            var qty = $(this).attr('data-qty');
            $('#proid').val(iid);
            $('#proname').val(name);
            $('#mrp').val(mrp);
            $('.stock').val(qty);
            $('#qty').attr('tabindex', 3).focus();
            // console.log(qty);
        });
    });
    /*Value submit for invoice*/
    $(document).ready(function() {
        $("#saleSubmit").on('click', function(event) {
            event.preventDefault();
            var cid = $("#cid").val();
            console.log(cid);
            if(cid.length > 0){               
            var formval = $('#SalesFormConfirm')[0];
            var data = new FormData(formval);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "createInvoice",
                dataType: 'html',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function(response) {
                    $('#invoicedom').html(response);
                    // console.log(response);
                    if (response.status == 'error') {
                        $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                        // console.log(response);
                    } else if (response.status == 'success') {
                        $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                        // console.log(response);
                        window.setTimeout(function() {
                            window.location = response.curl;
                        }, 3000);
                    }
                },
                error: function(response) {
                    // console.error();
                }
            });
        } else {
            alert("Please Select Customer");
            $('#cusid').attr('tabindex', 1).focus();
            return false;             
        }
        });

    });
    /*Customer information insert*/
    $(document).ready(function() {
        $("#formid").on('submit', function(event) {
            event.preventDefault();
            var name = $(".name").val();
            var cid = $(".cid").val();
            var formval = $('#formid');
            var data = new FormData(this);
            //console.log(baseurl);
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                url: baseurl+"customer/save_Customer",
                dataType: 'json',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,

                success: function(response) {
                    if (response.status == 'error') {
                        $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                        // console.log(response);
                    } else if (response.status == 'success') {
                        $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                        $('#customer_name').val(name);
                        $('#customer_id').val(cid);
                        $('#cid').val(cid);
                        // console.log(response);
                        $('#myModal').modal('hide');
                    }
                },
                error: function(response) {
                    // console.error();
                }
            });
        });

    });
    $(document).ready(function() {
        $(document).on('click', "#superpro", function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-id');
            //console.log(iid);
        });
    });
    //Product value add
    $("#qty").keypress(function(e) {
        if (e.which == 13 || e.keycode == '13') {
            var iid = $('#customer_id').val();
            var qty = parseFloat($('#qty').val());
            var mrp = parseFloat($('.mrp').val());
            var pid = $('#proid').val();
            var stock = parseFloat($('.stock').val());
            var pname = $('.proname').val();
            //console.log(pname);
            if (qty > stock) {
                alert("Stock Is Unavilable!!!");
                $('#qty').val('');
                return false;
            }
            var total = qty * mrp;
            if (isNaN(iid) == false) {
                var iid = $('#customer_id').val();
            }
            var formval = $('#SalesForm')[0];
            var data = new FormData(formval);
            // Filter the same product
            var tableRow = $(".premove .pid").filter(function() {
                return $(this).data('id') == pid;
            }).closest('tr');
            if (tableRow.length > 0) {
                //console.log('duplicate');
                var oldQty = tableRow.find('.qty').val();
                var oldAmount = tableRow.find('.totall').val();

                var final_qty = +oldQty + +qty;                  
                if(final_qty > +stock) {
                    alert("Stock fewer than asked for");
                    //$('#qty').val('');
                    return false;
                } else {
                tableRow.find('.qty').val(+oldQty + +qty);
                tableRow.find('.totall').val(+oldAmount + +total.toFixed(2));                     
                tableRow.find('[data-total="total"]').val(+oldAmount + +total.toFixed(2));  
                tableRow.find('#tremove').attr('data-total', +oldAmount + +total.toFixed(2));
                calc_total();
                }
            } else {
                var newtr = '<tr class="premove"><td><input type="hidden" class="pid" data-id=' + pid + ' value=' + pid + ' name="pid[]"><input type="text" class="valid" name="pname[]" readonly="" value="' + pname + '" ></td><td><input type="text" class="qty" value=' + qty + ' name="qty[]" readonly=""><input type="hidden" class="mrpval" value=' + mrp + ' name="mrpval[]"></td><td><input type="text" class="totall" value=' + total + ' name="total[]" readonly=""></td><td class="text-nowrap"><a href="" id="tremove" class="tremove" data-total=' + total + ' data-original-title="Close"> <i class="fa fa-close text-danger"></i></a></td></tr>';
                /*Append Table Row*/
                $('#posinfo').append(newtr);
                calc_total();
            }

            function calc_total() {
                var sum = 0;
                $(".totall").each(function() {
                    sum += parseFloat($(this).val());
                });
                $('.grandtotal').val(sum.toFixed(2));
                var pay = 0;
                $(".totall").each(function() {
                    pay += parseFloat($(this).val());
                });

                $('.payable').val(pay.toFixed(2));
            }

            function calc_discount() {
                var discount = 0;
                $(".discount").each(function() {
                    discount += parseFloat($(this).val());
                });
                $('.gdiscount').val(discount);
            }
            $('#qty').val("");
            $('.mrp').val("");
            $('.stock').val("");
            $('.pname').val("");
            $('#proname').val("");
            $('.discount').html("");
            $('#running').html("");
            $('#product_name').attr('tabindex', 2).focus();
        }
    });
    //Remove Table value
    $(document).ready(function() {
        $(document).on('click', '#tremove', function(e) {
            e.preventDefault();
            var rows = this.closest('#SalesFormConfirm tr');
            var total = parseFloat($(".grandtotal").val());
            var pay = parseFloat($(".payable").val());
            var t = parseFloat($(this).attr('data-total'));
            var atotal = parseFloat(total - t);
            var ptotal = parseFloat(pay - t);
            $('.grandtotal').val(atotal);
            $('.payable').val(ptotal.toFixed(2));
            $(this).closest('tr').remove();
        });
    });
    /*POS calculation*/
    $(document).ready(function() {
        $("#gdiscount").keyup(function () { 
            var gtotal = $('.grandtotal').val();
            var discount = $("#gdiscount").val();
            var totalTax = $("#totalTax").val();
            if (discount > 0) {
                var pvalue = parseFloat(gtotal - discount);
                if(totalTax > 0){
                    pvalue += +totalTax;
                }
                $(".payable").val(Math.abs(pvalue).toFixed(2));
                var paid = parseFloat($('.pay').val());                
                var returnval = paid - pvalue;
                if (returnval <= 0) {
                $(".due").val(Math.abs(returnval).toFixed(2));
                $(".return").val('0');    
                } else if (returnval > 0) {
                $('.return').val(returnval.toFixed(2));    
                $(".due").val('0');
                }                
            }      
        });
        $("#tax").keyup(function () {
            var rows = this.closest('#SalesFormConfirm div');
            var tax = $(rows).find(".tax");
            var gtotal = $('.grandtotal').val();
            var discount = $("#gdiscount").val();
            var finalTotal = gtotal - discount;
            var taxValue = parseInt($(tax).val());
            var totalTaxval = Math.abs(finalTotal*(taxValue/100));
            //console.log(totalTaxval);
            var pvalued = parseFloat(finalTotal*(taxValue/100)) + +finalTotal;
            var paid = parseFloat($('.pay').val());
            if (paid > 0) {
                var returnval = paid - pvalued;
                if (returnval <= 0) {
                $(".due").val(Math.abs(returnval).toFixed(2));
                $(".return").val('0');    
                } else if (returnval > 0) {
                $('.return').val(returnval.toFixed(2));    
                $(".due").val('0');
                }
            }
            $(".payable").val(Math.abs(pvalued).toFixed(2));
            $(".totalTax").val(Math.abs(totalTaxval).toFixed(2));
        });    
        $(".pay").keyup(function () { 
            var payval = $('.pay').val();
            if (payval > 0) {
                var payablevalue = $('.payable').val();
                //console.log(pvalue);
                $("#saleSubmit").show();
                returnval = payval - payablevalue;
                if (returnval <= 0) {
                $(".due").val(Math.abs(returnval).toFixed(2));
                $(".return").val('0');    
                } else if (returnval > 0) {
                $(".return").val(returnval.toFixed(2));    
                $(".due").val('0');
                }
            } else {
                $("#saleSubmit").hide();
            }   
        });        
    });
    /*Print invoice*/
    $(document).ready(function() {
        $(".invoiceClose").click(function() {
            location.reload();
        });
    });
    /*Print invoice*/
    $(document).ready(function() {
        $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div#invoicedom").printArea(options);
        });
    });