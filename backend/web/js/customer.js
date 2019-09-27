$(document).ready(function()
{
	$('.buttonEditCustomer').on('click', function()
	{
		id = $(this).attr('id').substr(18);

		$('.username').html($('.edit-username-' + id).val());
		$('.email').val($('.edit-email-' + id).val());
		$('.phone').val($('.edit-phone-' + id).val());
		$('.another_phone').val($('.edit-another_phone-' + id).val());
		$('.userLevel').val($('.editLevel-' + id).val());
		$('.userLevelList').html($('.editLevelList-' + id).val());
		$('.userId').val(id);
	});

	$(document).on('click', '.setLevel', function()
	{
		$('.userLevel').val($(this).attr('level'));
	});
});