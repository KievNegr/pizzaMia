$(document).ready(function()
{
	$('.buttonEditPromo').on('click', function()
	{
		id = $(this).attr('id').substr(15);

		$('.promoCode').val($('.edit-code-' + id).val());
		$('.promoExpire').val($('.edit-expiration-' + id).val());
		$('.promoUser').val($('.edit-user-' + id).val());
		$('.promoDiscount').val($('.edit-discount-' + id).val());
		$('.editCustomerName').html($('.edit-name-' + id).val());
		if($('.edit-applying-' + id).val() == 1)
		{
			$('.promoApplying').attr('checked', 'checked');
		}
		else
		{
			$('.promoApplying').removeAttr('checked');
		}
		
		$('.promoId').val(id);
	});

	$('.buttonDeletePromo').on('click', function()
	{
		id = $(this).attr('id').substr(17);

		$('.titleDeletePromo').text($('.edit-code-' + id).val());
		$('.promoId').val(id);
	});

	$('.buttonRestorePromo').on('click', function()
	{
		id = $(this).attr('id').substr(18);

		$('.titleRestorePromo').text($('.edit-code-' + id).val());
		$('.promoId').val(id);
	});

	$('.getCustomer').on('change', function()
	{
		phone = $(this).val();

		$.ajax({
			url: 'index.php?r=setting/getcustomer',
			data: {phone: phone},
			type: 'POST',
			success: getCustomer
		});
	});

	function getCustomer(data)
	{
		customer = $.parseJSON(data);
		$('.setCustomerId').val(customer.user_id);
		$('.setCustomerName').html(customer.username);
	}

	$(document).on('click', '.promoApplying input', function()
	{
		alert($(this).val());
	})
});