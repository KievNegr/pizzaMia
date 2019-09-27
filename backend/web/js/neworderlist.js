$(document).ready(function()
{	
	var currency = $('.default_currency').val();

	$(".getItem").change(function(e)
	{
	    id = $(this).val();
	    $('.card').hide(10);
	 	$('.item_' + id).show();   
	});

	$('.selectItem').click(function()
	{
		idItem = $(this).attr('itemId').substr(8);
		idOption = $(this).attr('optionid');
		img = $('.img' + idItem).attr('src');
		title = $('.title' + idItem).html();
		size = $('.optionData' + idOption).attr('optionname');
		price = $('.optionData' + idOption).val();


		data = '<div class="list list' + idOption + '" idItem="' + idItem + '" idOption="' + idOption + '">' +
					'<img src="' + img + '" />' +
					'<div class="link">' +
						'<a href="#">' + title + '</a>' +
						'<p>' + size + '</p>' +
					'</div>' +
					'<div class="amount">' +
						'<div class="down" forOption="downItem' + idOption + '">-</div>' +
						'<div class="number amountItem' + idOption + '">1</div>' +
						'<div class="up" forOption="upItem' + idOption + '">+</div>' +
					'</div>' +
					'<div class="value setPriceItem' + idOption + '">' + price + '</div>' +
					'<input type="hidden" value="' + price + '" class="priceItem priceItem' + idOption + '" />' +
					'<span class="currency"> ' + currency + '</span></div>' +
				'</div>';

		if($('div').is('.list' + idOption) != true)
		{
			$('.getOrderList').append(data);

			var allPrice = 0;

			$.each($('.value'), function () { 
		        price = parseInt($(this).html());
		        allPrice = allPrice + price;
		    });

		    idCoupon = $('.couponId').val();

		    if(idCoupon == 0)
			{
				discount = 100 - parseInt($('.orderDiscount').val());

			}
			else
			{
				discount = 100 - parseInt($('.couponId').attr('discount'));
			}

			delivery = parseInt($('.deliveryPrice').html());

			$('.allOrderPrice').html((allPrice / 100 * discount).toFixed(2));
			$('.forPay').html((allPrice / 100 * discount + delivery).toFixed(2));
		}
	});

	$('.sel').on('change', function()
	{
		alert($(this).html());
	});

	$(document).on('click', '.up', function()
	{
		idOption = $(this).attr('forOption').substr(6);
		count = parseInt($('.amountItem' + idOption).html());
		count = count + 1;
		$('.amountItem' + idOption).html(count);
		price = parseInt($('.priceItem' + idOption).val());
		price = price * count;
		$('.setPriceItem' + idOption).html(price);

		idCoupon = $('.couponId').val();

		var allPrice = 0;
		$.each($('.value'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price;
	    });

		if(idCoupon == 0)
		{
			discount = 100 - parseInt($('.orderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt($('.couponId').attr('discount'));
		}

		delivery = parseInt($('.deliveryPrice').html());

		$('.allOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.forPay').html((allPrice / 100 * discount + delivery).toFixed(2));

	});

	$(document).on('click', '.down', function()
	{
		idOption = $(this).attr('forOption').substr(8);
		count = parseInt($('.amountItem' + idOption).html());
		count = count - 1;
		$('.amountItem' + idOption).html(count);
		price = parseInt($('.priceItem' + idOption).val());
		price = price * count;
		$('.setPriceItem' + idOption).html(price);
		if(count < 1)
		{
			$('.list' + idOption).remove();
		}

		idCoupon = $('.couponId').val();

		var allPrice = 0;
		$.each($('.value'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price; 
	    });


		if(idCoupon == 0)
		{
			discount = 100 - parseInt($('.orderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt($('.couponId').attr('discount'));
		}

		delivery = parseInt($('.deliveryPrice').html());

		$('.allOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.forPay').html((allPrice / 100 * discount + delivery).toFixed(2));
	});

	$('.saveNewOrder').on('click', function()
	{
		var list = '';
		$.each($('.list'), function () { 
	        idItem = $(this).attr('idItem');
	        count = $('.number', this).html();
	        price = $('.priceItem', this).val();
	        size = $(this).attr('idOption');

	        list = list + idItem + ';' + count  + ';' + price + ';' + size  + '&';
	    });
	    
	    $('.orderList').val(list);
	});

	$('.orderDiscount').on('focusout', function()
	{
		coupon = $('.couponId').val();
		if(coupon == 0)
		{
			var allPrice = 0;
			$.each($('.value'), function () { 
		        price = parseInt($(this).html());
		        allPrice = allPrice + price; 
		    });
	
			discount = 100 - parseInt($(this).val());
	
			delivery = parseInt($('.deliveryPrice').html());

			$('.allOrderPrice').html((allPrice / 100 * discount).toFixed(2));
			$('.forPay').html((allPrice / 100 * discount + delivery).toFixed(2));
		}
	});

	$('.getCustomer').on('change', function()
	{
		phone = $(this).val();

		if(phone.length > 9)
		{
			$.ajax({
				url: 'index.php?r=order/getcustomer',
				data: {phone: phone},
				type: 'POST',
				success: getCustomer
			});
		}
	});

	function getCustomer(data)
	{
		customer = $.parseJSON(data);
		$('.setCustomerName').val(customer.username);
		$('.setCustomerId').val(customer.id);
		$('.setCustomerAnotherPhone').val(customer.another_phone);
		$('.noCheckOrderAnotherPhone').val(customer.another_phone);
		$('.setCustomerEmail').val(customer.email);
		$('.setCustomerAddress').val(customer.address);
		$('.setCustomerAdditions').val(customer.additional);

		coupons = customer.coupons;

		var availableCoupon = '';
		$.each(coupons, function(key, item) {
			availableCoupon = availableCoupon + '<div class="custom-control coupon custom-radio">' +
			  				'<input type="radio" discount="' + item.discount + '" couponId="' + item.id + '" id="customRadio' + item.id + '" name="customRadio" class="custom-control-input">' +
			  				'<label class="custom-control-label" for="customRadio' + item.id + '">' + item.code + ' (до ' + item.expiration + ', скидка ' + item.discount +' %)</label>' +
							'</div>';
		});

		if(availableCoupon != '')
		{
			availableCoupon = availableCoupon + '<div class="custom-control coupon custom-radio">' +
			  				'<input type="radio" couponId="0" id="customRadioRefuse" name="customRadio" class="custom-control-input">' +
			  				'<label class="custom-control-label" for="customRadioRefuse">Не использовать</label>' +
							'</div>';
		}

		$('.orderCoupons').html(availableCoupon);
	}

	$(document).on('click', '.coupon input', function()
	{
		id = $(this).attr('couponId');
		discount = $(this).attr('discount');
		$('.couponId').val(id);
		$('.couponId').attr('discount', discount);

		var allPrice = 0;
		$.each($('.value'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price; 
	    });

		if(id == 0)
		{	
			discount = 100 - parseInt($('.orderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt(discount);
		}

		delivery = parseInt($('.deliveryPrice').html());

		$('.allOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.forPay').html((allPrice / 100 * discount + delivery).toFixed(2));
	});

	$(document).on('click', '.getDelivery', function()
	{
		delivery = $(this).val();

		$.ajax({
			url: 'index.php?r=order/getdelivery',
			data: {id: delivery},
			type: 'POST',
			success: getDelivery,
		})
	});

	function getDelivery(data)
	{
		$('.deliveryPrice').html(data);

		idCoupon = $('.couponId').val();

		var allPrice = 0;
		$.each($('.value'), function () { 
	        price = parseInt($(this).html());
	        allPrice = allPrice + price;
	    });

		if(idCoupon == 0)
		{
			discount = 100 - parseInt($('.orderDiscount').val());
		}
		else
		{
			discount = 100 - parseInt($('.couponId').attr('discount'));
		}

		delivery = parseInt($('.deliveryPrice').html());

		$('.allOrderPrice').html((allPrice / 100 * discount).toFixed(2));
		$('.forPay').html((allPrice / 100 * discount + delivery).toFixed(2));
	}
});