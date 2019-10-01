$(document).ready(function()
{
	var currency = $('.default_currency').val();
	
	$('.buttonEditOrder').on('click', function()
	{
		$('input').attr('aria-invalid', 'false');
		idOrder = $(this).attr('id').substr(15);

		$('.orderId').val(idOrder);
		$('.editOrderCustomerId').val($('.edit-customerId-' + idOrder).val());
		$('.editOrderPhone').val($('.edit-phone-' + idOrder).val());
		$('.editOrderAnotherPhone').val($('.edit-another_phone-' + idOrder).val());
		$('.noCheckEditOrderAnotherPhone').val($('.edit-another_phone-' + idOrder).val());
		$('.editOrderCustomer').val($('.edit-user_name-' + idOrder).val());
		$('.editOrderAddress').val($('.edit-adsress-' + idOrder).val());
		$('.editOrderAdditions').val($('.edit-user_comments-' + idOrder).val());
		$('.editOrderDelivery').val($('.edit-delivery-' + idOrder).val()).attr('selected', 'selected');
		$('.editOrderPay').val($('.edit-pay-' + idOrder).val()).attr('selected', 'selected');
		$('.editGetOrderList').html($('.edit-order_list-' + idOrder).val());
		$('.editAllOrderPrice').html($('.edit-all_order_price-' + idOrder).val());
		$('.editForPay').html($('.edit-pay_order_price-' + idOrder).val());
		$('.editDeliveryPrice').html($('.edit-delivery_order_price-' + idOrder).val());
		$('.editOrderDiscount').val($('.edit-discount-' + idOrder).val());
		$('.orderDiscountId').val($('.edit-discount_id-' + idOrder).val());
		$('.orderDiscountName').html($('.edit-discount_name-' + idOrder).val());
		$('.editOrderCoupons').html($('.edit-coupon_list-' + idOrder).val());
		$('.editCouponId').val($('.edit-coupon-' + idOrder).val());
		$('.editCouponId').attr('discount', $('.edit-coupon_discount-' + idOrder).val());
		$('.editCurentOrderStatus').val($('.labelStatus.active').attr('idStatus'));
		$('.editOrderDate').val($('.edit-order_date-' + idOrder).val());
		$('.edit-status input[value=' + $('.edit-order_status-' + idOrder).val() + ']').attr('checked', 'checked');
	});

	$(".editGetItem").change(function(e)
	{
	    id = $(this).val();
	    $('.card').hide(10);
	 	$('.item_' + id).show();   
	});

	$('.editSelectItem').click(function()
	{
		idItem = $(this).attr('itemId').substr(8);
		idOption = $(this).attr('optionid');
		img = $('.editImg' + idItem).attr('src');
		title = $('.editTitle' + idItem).html();
		size = $('.editOptionData' + idOption).attr('optionname');
		price = $('.editOptionData' + idOption).val();


		data = '<div class="editList editList' + idOption + '" idItem="' + idItem + '" idOption="' + idOption + '">' +
					'<img src="' + img + '" />' +
					'<div class="link">' +
						'<a href="#">' + title + '</a>' +
						'<p>' + size + '</p>' +
					'</div>' +
					'<div class="amount">' +
						'<div class="editDown" forOption="downItem' + idOption + '">-</div>' +
						'<div class="editNumber editAmountItem' + idOption + '">1</div>' +
						'<div class="editUp" forOption="upItem' + idOption + '">+</div>' +
					'</div>' +
					'<div class="editValue editSetPriceItem' + idOption + '">' + price + '</div>' +
					'<input type="hidden" value="' + price + '" class="editPriceItem editPriceItem' + idOption + '" />' +
					'<span class="currency"> ' + currency + '</span></div>' +
				'</div>';

		if($('div').is('.editList' + idOption) != true)
		{
			$('.editGetOrderList').append(data);

			var allPrice = 0;
			$.each($('.editValue'), function () { 
		        price = parseInt($(this).html());
		        allPrice = allPrice + price;
		    });

		    idCoupon = $('.editCouponId').val();
		    if(idCoupon == 0)
			{
				discount = 100 - parseInt($('.editOrderDiscount').val());

			}
			else
			{
				discount = 100 - parseInt($('.editCouponId').attr('discount'));
			}

			delivery = parseInt($('.editDeliveryPrice').html());

			$('.editAllOrderPrice').html((allPrice / 100 * discount).toFixed(2));
			$('.editForPay').html((allPrice / 100 * discount + delivery).toFixed(2));
		}
	});

	$(document).on('click', '.editUp', function()
	{
		idOption = $(this).attr('forOption').substr(6);
		count = parseInt($('.editAmountItem' + idOption).html());
		count = count + 1;
		$('.editAmountItem' + idOption).html(count);
		price = parseInt($('.editPriceItem' + idOption).val());
		price = price * count;
		$('.editSetPriceItem' + idOption).html(price);

		idCoupon = $('.editCouponId').val();

		var allPrice = 0;
		$.each($('.editValue'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price;
	    });

		if(idCoupon == 0)
		{
			discount = 100 - parseInt($('.editOrderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt($('.editCouponId').attr('discount'));
		}

		delivery = parseInt($('.editDeliveryPrice').html());

		$('.editAllOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.editForPay').html((allPrice / 100 * discount + delivery).toFixed(2));

	});

	$(document).on('click', '.editDown', function()
	{
		idOption = $(this).attr('forOption').substr(8);
		count = parseInt($('.editAmountItem' + idOption).html());
		count = count - 1;
		$('.editAmountItem' + idOption).html(count);
		price = parseInt($('.editPriceItem' + idOption).val());
		price = price * count;
		$('.editSetPriceItem' + idOption).html(price);
		if(count < 1)
		{
			$('.editList' + idOption).remove();
		}

		idCoupon = $('.editCouponId').val();

		var allPrice = 0;
		$.each($('.editValue'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price; 
	    });

		if(idCoupon == 0)
		{
			discount = 100 - parseInt($('.editOrderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt($('.editCouponId').attr('discount'));
		}

		delivery = parseInt($('.editDeliveryPrice').html());

		$('.editAllOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.editForPay').html((allPrice / 100 * discount + delivery).toFixed(2));
	});

	$('.saveEditOrder').on('click', function()
	{
		var list = '';
		$.each($('.editList'), function () { 
	        idItem = $(this).attr('idItem');
	        count = $('.editNumber', this).html();
	        price = $('.editPriceItem', this).val();
	        size = $(this).attr('idOption');

	        list = list + idItem + ';' + count  + ';' + price + ';' + size  + '&';
	    });
	    
	    $('.editOrderList').val(list);
	});

	$('.editOrderDiscount').on('focusout', function()
	{
		coupon = $('.editCouponId').val();
		
		if(coupon == 0)
		{
			var allPrice = 0;
			$.each($('.editValue'), function () { 
		        price = parseInt($(this).html());
		        allPrice = allPrice + price; 
		    });
	
			discount = 100 - parseInt($(this).val());
	
			delivery = parseInt($('.editDeliveryPrice').html());

			$('.editAllOrderPrice').html((allPrice / 100 * discount).toFixed(2));
			$('.editForPay').html((allPrice / 100 * discount + delivery).toFixed(2));
		}
	});

	$(document).on('click', '.editCoupon input', function()
	{
		id = $(this).attr('editCouponId');
		discount = $(this).attr('discount');
		$('.editCouponId').val(id);
		$('.editCouponId').attr('discount', discount);

		var allPrice = 0;
		$.each($('.editValue'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price; 
	    });

		if(id == 0)
		{	
			discount = 100 - parseInt($('.editOrderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt(discount);
		}

		delivery = parseInt($('.editDeliveryPrice').html());

		$('.editAllOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.editForPay').html((allPrice / 100 * discount + delivery).toFixed(2));
	});

	$(document).on('click', '.labelStatus', function()
	{
		$('.editCurentOrderStatus').val($(this).attr('idStatus'));

		if($(this).attr('cancelpromo') == 1)
		{
			$('.notUse').click();
		}
	});

	$(document).on('click', '.editOrderDelivery', function()
	{
		delivery = $(this).val();

		$.ajax({
			url: 'index.php?r=order/getdelivery',
			data: {id: delivery},
			type: 'POST',
			success: getEditDelivery,
		})
	});

	function getEditDelivery(data)
	{
		$('.editDeliveryPrice').html(data);

		idCoupon = $('.editCouponId').val();

		var allPrice = 0;
		$.each($('.editValue'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price; 
	    });

		if(idCoupon == 0)
		{
			discount = 100 - parseInt($('.editOrderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt($('.editCouponId').attr('discount'));
		}

		delivery = parseInt($('.editDeliveryPrice').html());

		$('.editAllOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.editForPay').html((allPrice / 100 * discount + delivery).toFixed(2));
	}
});