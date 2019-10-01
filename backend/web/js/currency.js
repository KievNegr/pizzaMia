$(document).ready(function()
{
	$('.buttonEditCurrency').on('click', function()
	{
		id = $(this).attr('id').substr(18);

		$('.editCurrencyName').val($('.edit-name-' + id).val());
		$('.nameNoCheckEditCurrency').val($('.edit-name-' + id).val());
		if($('.edit-default_view-' + id).val() == 1)
		{
			$('.editCurrencyDefault').attr('checked', 'checked');
		}
		else
		{
			$('.editCurrencyDefault').removeAttr('checked');
		}
		$('.editCurrencyId').val(id);
	});

	$('.buttonDeleteCurrency').on('click', function()
	{
		id = $(this).attr('id').substr(20);

		$('.titleDeleteCurrency').text($('.edit-name-' + id).val());
		$('.deleteIdCurrency').val(id);
	});

	$('.buttonRestoreCurrency').on('click', function()
	{
		id = $(this).attr('id').substr(21);

		$('.titleRestoreCurrency').text($('.edit-name-' + id).val());
		$('.restoreIdCurrency').val(id);
	});
});