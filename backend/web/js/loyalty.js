$(document).ready(function()
{
	$('.buttonEditLoyalty').on('click', function()
	{
		id = $(this).attr('id').substr(17);

		$('.editFromSumLoyalty').val($('.edit-from-' + id).val());
		$('.editToSumLoyalty').val($('.edit-to-' + id).val());
		$('.editDiscountLoyalty').val($('.edit-value-' + id).val());
		$('.editIdLoyalty').val(id);
		
	});

	$('.buttonDeleteLoyalty').on('click', function()
	{
		id = $(this).attr('id').substr(19);
		from = $('.edit-from-' + id).val();
		to = $('.edit-to-' + id).val();

		$('#titleDeleteLoyalty').text('От ' + from + ' до ' + to);
		$('.deleteIdLoyalty').val(id);
	});

	$('.buttonRestoreLoyalty').on('click', function()
	{
		id = $(this).attr('id').substr(20);
		from = $('.edit-from-' + id).val();
		to = $('.edit-to-' + id).val();

		$('#titleRestoreLoyalty').text('От ' + from + ' до ' + to);
		$('.restoreIdLoyalty').val(id);
	});
});