$(document).ready(function()
{
	$('.searchById').on('change', function()
	{
		if($(this).val().length > 0)
		{
			$('.searching').hide();
			$('[orderId = ' + 'order-' + $(this).val() + ']').show();
		}
		else
		{
			$('.searching').show();
		}
	});

	$('.searchByCustomer').on('change', function()
	{
		if($(this).val().length > 1)
		{
			$('.searching').hide();
			$('[orderCustomer = ' + 'customer-' + $(this).val() + ']').show();
		}
		else
		{
			$('.searching').show();
		}
	});

	$('.searchByPhone').on('change', function()
	{
		if($(this).val().length > 9)
		{
			$('.searching').hide();
			$('[orderPhone = ' + $(this).val() + ']').show();
		}
		else
		{
			$('.searching').show();
		}
	});

	$('#myOrders').on('change', function()
	{
		if(this.checked)
		{
			$('.listOrder').hide();
			$('[myOrder="show"]').show();
		}
		else
		{
			$('tr').show();
		}
	})
});