$(document).ready(function()
{
	$('.buttonEditDelivery').on('click', function()
	{
		id = $(this).attr('id').substr(18);

		$('.editNameDelivery').val($('.edit-name-' + id).val());
		$('.editValueDelivery').val($('.edit-value-' + id).val());
		$('.editIdDelivery').val(id);
		
	});

	$('.buttonDeleteDelivery').on('click', function()
	{
		id = $(this).attr('id').substr(20);

		$('#titleDeleteDelivery').text($('.edit-name-' + id).val());
		$('.deleteIdDelivery').val(id);
	});

	$('.buttonRestoreDelivery').on('click', function()
	{
		id = $(this).attr('id').substr(21);

		$('#titleRestoreDelivery').text($('.edit-name-' + id).val());
		$('.restoreIdDelivery').val(id);
	});

	$('.buttonEditTime').on('click', function()
	{
		id = $(this).attr('id').substr(14);

		$('.editFromTime').val($('.edit-fromtime-' + id).val().substr(0, 5));
		$('.editTillTime').val($('.edit-tilltime-' + id).val().substr(0, 5));
		$('.editIdTime').val(id);
		
	});

	$('.buttonDeleteTime').on('click', function()
	{
		id = $(this).attr('id').substr(16);

		$('#titleDeleteTime').text('С ' + $('.edit-fromtime-' + id).val().substr(0, 5) + ' до ' + $('.edit-tilltime-' + id).val().substr(0, 5));
		$('.deleteIdTime').val(id);
	});

	$('.setSort').on('change', function()
	{
		sort = $(this).val();
		id = $(this).attr('sortId');

		$.ajax({
			url: 'index.php?r=setting/sorttime',
			data: {id: id, sort: sort},
			type: 'POST'
		});
	});
});