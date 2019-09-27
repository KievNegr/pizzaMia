$(document).ready(function(){
	
	var showOrderClick = 0;
	var showHeadMenu = 0;
    var logged = 0;
    var currency = $('.default_currency').val();

	if( $(document).scrollTop() > 50 )
	{
		$('.empty-space').css('display', 'block');
		$('header').css({'position': 'fixed', 'top': '0', 'left': '5%', 'width': '90%', 'background-color': '#FFF'});
		$('main ul.filter').css({'position': 'fixed', 'top': '40px', 'right': '5%', 'background-color': '#fff', 'z-index': '3', 'padding': '10px', 'border-radius': '0 0 0 10px', 'box-shadow': '-1px 2px 3px #353535'});

        if( $(document).width() < 1190 )
        {
            $('header').css({'width': '100%', 'left': '0'});
        }
	}
	
	$(document).scroll(function(e)
	{			
		if( $(this).scrollTop() > 50 )
		{
			$('.empty-space').css('display', 'block');
			$('header').css({'position': 'fixed', 'top': '0', 'left': '5%', 'width': '90%', 'background-color': '#FFF'});
			$('main ul.filter').css({'position': 'fixed', 'top': '40px', 'right': '5%', 'background-color': '#fff', 'z-index': '3', 'padding': '10px', 'border-radius': '0 0 0 10px', 'box-shadow': '-1px 2px 3px #353535'});
		}
		else
		{
			$('.empty-space').css('display', 'none');
			$('header').css({'position': 'relative', 'background-color': 'transparent', 'width': '100%', 'left': '0'});
			$('main ul.filter').css({'position': 'static', 'top': 'unset', 'right': 'unset', 'background-color': 'transparent', 'padding': '0', 'border-radius': 'unset', 'box-shadow': 'unset'});
		}

        if( $(document).width() < 1190 )
        {
            $('header').css({'width': '100%', 'left': '0'});
        }
	});

	$('.filter a').on('click', function() { 
		var scroll_el = $(this).attr('href');
        if ($(scroll_el).length != 0) {
	    	$('html, body').animate({ scrollTop: $(scroll_el).position().top}, 500);
        }

        $('.filter a').removeClass('active');
        $(this).addClass('active');
	    return false; // выключаем стандартное действие
    });

    $('.sidebar-menu').on('click', function()
    {
        if(showHeadMenu == 0)
        {
            $('.sidebar-menu .header-menu .slash').css({'transform': 'rotate(135deg)', 'margin':'4px 0 0 0'});
            $('.sidebar-menu .header-menu .backslash').css({'transform': 'rotate(-135deg)', 'margin':'-2px 0 0 0'});
            $('.sidebar-menu .header-menu .middle').fadeOut(300);
            $('.sidebar').animate({left: 0, top: $(document).scrollTop() + 70}, 300);
            showHeadMenu = 1;
        }
        else
        {
            $('.sidebar-menu .header-menu .slash').css({'transform': 'rotate(0)', 'margin':'0 0 5px 0'});
            $('.sidebar-menu .header-menu .backslash').css({'transform': 'rotate(0)', 'margin':'0 0 5px 0'});
            $('.sidebar-menu .header-menu .middle').fadeIn(300);
            $('.sidebar').animate({left: '-100%'}, 300);
            showHeadMenu = 0;
        }
    });

    $('.logged').on('click', function()
    {
        if( $(document).width() < 969 )
        {
            if(logged == 0)
            {
                $('header .logged .show-profile').slideDown();
                $('header .logged .show-profile').css('display', 'flex');
                logged = 1;
            }
            else
            {
                $('header .logged .show-profile').slideUp();
                logged = 0;
            }
        }
    });

    $('.login').on('click', function()
    {
    	$('.login-form').animate({top: '100px'}, 300);
    	$('.fadeall').fadeIn(200);
    });

    $('.login-form-close').on('click', function()
    {
		$('.login-form').animate({top: '-1000px'}, 300);
    	$('.fadeall').fadeOut(200);
    });

    $('.profile-order').on('click', function()
    {
    	$('.show-details', this).slideDown();
    });

    $('.checked_size').on('click', function()
    {
    	$('.cost').html($(this).attr('price'));
        $('.noempty').fadeOut();
    });

    $('.fast-value').on('click', function()
    {
    	itemId = $(this).attr('itemid');
    	optionId = $(this).attr('optionid');

    	addItem(itemId, optionId);

    	$('.added-to-cart').show();
        $('.added-to-cart').fadeOut(4000);
		
		$('.checkout').animate({'opacity': .1}, 700);
		$('.checkout').animate({'opacity': 1}, 300);
    });

    $('.add-item-to-cart').on('click', function()
    {
    	itemId = $(this).attr('itemid');
    	optionId = $('.checked_size:checked').val();
        if(!optionId)
        {
            $('.noempty').fadeIn();
            return false;
        }
        $('.added-to-cart').show();
        $('.added-to-cart').fadeOut(4000);
    	addItem(itemId, optionId);
    });

    $(document).on('click', '.up', function()
    {
    	itemId = $(this).attr('product');
    	optionId = $(this).attr('option');
    	
    	addItem(itemId, optionId);
    });

    $(document).on('click', '.down', function()
    {
    	itemId = $(this).attr('product');
    	optionId = $(this).attr('option');
    	
    	addItem(itemId, optionId, 0);
    });

    function addItem(itemId, optionId, qty = 1)
    {
    	$.ajax({
    		url: '/cart/add.html',
    		data: {itemId: itemId, optionId: optionId, qty: qty},
    		type: 'POST',
    		success: addToList,
    	});
    }

    function addToList(data)
    {
    	if(data)
    	{
    		$('.checkout').fadeIn();
    		list = $.parseJSON(data);
	    	$('.checkout .count').text(list.qty);
	    	newList = '';
	    	price = 0;
            delivery = parseInt(list.delivery.value);
	    	$.each(list.list, function(key, value) {
	    		price = price + parseInt(value.price) * parseInt(value.qty);
				newList = newList + '<div class="list list' + value.id_option + '">' +
					                       '<div class="img" style="background-image: url(' + value.product_image + ');"></div>' +
					                       '<div class="link">' +
					                           '<a href="/goods/' + value.product_sef + '.html">' + value.product_name + '</a>' +
					                           '<p>' + value.option_name + '</p>' +
					                       '</div>' +
					                       '<div class="amount">' +
					                           '<div class="down" option="' + value.id_option + '" product="' + value.id_product + '">-</div>' +
					                           '<div class="number">' + value.qty + '</div>' +
					                           '<div class="up" option="' + value.id_option + '" product="' + value.id_product + '">+</div>' +
					                       '</div>' +
					                       '<div class="value">' +
                                                '<span class="cost">' + value.price + '</span> ' +
                                                '<span class="currency">' + currency + '</span></div>' +
					                   '</div>';
			});
	
			$('.orderlist').html(newList);
            $('.orderSumWithoutDiscount').html(price);
            price = price * (100 - parseInt(list.discount.value)) / 100;
            $('.orderDelivery').html(delivery);
            $('.orderAll').html(price + delivery);
			$('.orderSum').html(price);
    	}
    	else
    	{
    		$('.checkout').fadeOut();
    		$('.closeOrderList').trigger('click');
    	}
    }

    $('.selectDelivery').on('click', function()
    {
        id = $(this).attr('iddelivery');
        $('.orderDeliveryId').val(id);

        $.ajax({
            url: '/cart/selectdelivery.html',
            data: {idDelivery: id},
            type: 'POST',
            success: addToList,
        });
    });

    $(document).on('click', '.selectCoupon', function()
    {
        idDiscount = $(this).attr('idcoupon');

        $.ajax({
            url: '/cart/selectdiscount.html',
            data: {idDiscount: idDiscount},
            type: 'POST',
            success: addToList,
        });
    });

    $('.ratio').on('click', function()
    {
        stars = $(this).attr('stars');
        item = $(this).attr('item');

        $.ajax({
            url: '/myorder/setratio.html',
            data: {stars: stars, item: item},
            type: 'POST',
            success: setRatio,
        });
    });

    function setRatio(data)
    {
        got = $.parseJSON(data);
        stars = parseInt(got.stars);
        ratio = '';
        for (i = 0; i < stars; i++) {
            ratio = ratio + '<i class="fas fa-star"></i>';
        }
        for (e = 0; e < 5 - i; e++) {
            ratio = ratio + '<i class="far fa-star"></i>';
        }

        $('.foritem' + got.item).html(ratio);
        $('.ratiolist'  + got.item).remove();
    }

    $(function($) {
        $.mask.definitions['~']='[+-]';
        //$('#name_buy').mask("");
        $('.mask-phone').mask('+38(999)999-99-99');
        //$('#email_buy').mask("*?@?*");
        //$("#tin").mask("99-9999999");
        //$("#ssn").mask("999-99-9999");
        //$("#product").mask("a*-999-a999");
        //$("#eyescript").mask("~9.99 ~9.99 999");
    });

    var disabledDays = [0, 6];

    var today = new Date();
    var week = new Date();
    week.setDate(today.getDate() + 7);

    $('.datepicker-here').datepicker({
        onRenderCell: function (date, cellType) {
            if (cellType == 'day') {
                var day = date.getDay(),
                    isDisabled = disabledDays.indexOf(day) != -1;

                return {
                    disabled: isDisabled
                }
            }
        },
        minDate: today,
        maxDate: week,
        minHours: 9,
        maxHours: 18
    })
});