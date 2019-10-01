$(document).ready(function()
{
	$('.buttonEditPay').on('click', function()
	{
		id = $(this).attr('id').substr(13);

		$('.editNamePay').val($('.edit-name-' + id).val());
		$('.editIdPay').val(id);

		if($('.edit-card-' + id).val() == 1)
		{
			$('.useCard').attr('checked', 'checked');
		}
		else
		{
			$('.useCard').removeAttr('checked');
		}
		
	});

	$('.buttonDeletePay').on('click', function()
	{
		id = $(this).attr('id').substr(15);

		$('#titleDeletePay').text($('.edit-name-' + id).val());
		$('#idDeletePay').val(id);
	});

	$('.buttonRestorePay').on('click', function()
	{
		id = $(this).attr('id').substr(16);

		$('#titleRestorePay').text($('.edit-name-' + id).val());
		$('#idRestorePay').val(id);
	});
});