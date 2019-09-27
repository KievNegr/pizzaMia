$(document).ready(function()
{
	$('.buttonEditOrder').on('click', function()
	{
		id = $(this).attr('id').substr(15);

		$('.orderName').val($('.edit-name-' + id).val());
		$('.orderColor').val($('.edit-color-' + id).val());

		value = $('.edit-color-' + id).val();
		$('.orderColor').val(value).attr('selected', 'selected');
		setBackground(value);

		$('.orderId').val(id);

		if($('.edit-cancel_promo-' + id).val() == 1)
		{
			$('.orderCancell').attr('checked', 'checked');
		}
		else
		{
			$('.orderCancell').removeAttr('checked');
		}

		if($('.edit-ratio-' + id).val() == 1)
		{
			$('.orderRatio').attr('checked', 'checked');
		}
		else
		{
			$('.orderRatio').removeAttr('checked');
		}

		if($('.edit-earned-' + id).val() == 1)
		{
			$('.orderEarned').attr('checked', 'checked');
		}
		else
		{
			$('.orderEarned').removeAttr('checked');
		}

		if($('.edit-expected-' + id).val() == 1)
		{
			$('.orderExpected').attr('checked', 'checked');
		}
		else
		{
			$('.orderExpected').removeAttr('checked');
		}

		if($('.edit-hoped-' + id).val() == 1)
		{
			$('.orderHoped').attr('checked', 'checked');
		}
		else
		{
			$('.orderHoped').removeAttr('checked');
		}
		
	});

	$('.buttonDeleteOrder').on('click', function()
	{
		id = $(this).attr('id').substr(17);

		$('.titleDeleteOrder').text($('.edit-name-' + id).val());
		$('.idDeleteOrder').val(id);
	});

	$('.buttonRestoreOrder').on('click', function()
	{
		id = $(this).attr('id').substr(18);

		$('.titleRestoreOrder').text($('.edit-name-' + id).val());
		$('.idRestoreOrder').val(id);
	});

	$('.getBackground').on('change', function()
	{
		setBackground($(this).val());
	});

	function setBackground(name)
	{
		$('.setBackground').removeClass('btn-primary');
		$('.setBackground').removeClass('btn-secondary');
		$('.setBackground').removeClass('btn-success');
		$('.setBackground').removeClass('btn-danger');
		$('.setBackground').removeClass('btn-warning');
		$('.setBackground').removeClass('btn-info');
		$('.setBackground').removeClass('btn-light');

		$('.setBackground').addClass(name);
	}
});