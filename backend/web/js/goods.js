$(document).ready(function()
{
	$('.buttonEditItem').on('click', function()
	{
		$('.gotImages').html('');
		$('.checkIngredient').prop('checked', false);
		id = $(this).attr('id').substr(14);

		$('#editItemTitle').val($('.edit-title-' + id).val());
		$('#editItemSef').val($('.edit-sef-' + id).val());
		$('#editNoCheckItemSef').val($('.edit-sef-' + id).val());
		$('#editItemDescription').val($('.edit-description-' + id).val());
		$('#editText').val($('.edit-text-' + id).val());
		$('#editCategory').val($('.edit-category-' + id).val()).attr('selected', 'selected');
		$('#editCategoryNoCheck').val($('.edit-category-' + id).val());
		$('.itemPrices').html($('.edit-prices-' + id).val());

		ingredients = $('.edit-ingredients-' + id).val().split(',');
		for(i = 0; i < ingredients.length; i++)
		{
			$('#editCheck' + ingredients[i]).prop('checked', true);
		}

		if($('.edit-popular-' + id).val() == 1)
		{
			$('.checkPop').attr('checked', 'checked');
		}
		else
		{
			$('.checkPop').removeAttr('checked');
		}

		if($('.edit-new-' + id).val() == 1)
		{
			$('.checkNew').attr('checked', 'checked');
		}
		else
		{
			$('.checkNew').removeAttr('checked');
		}

		if($('.edit-online_order-' + id).val() == 1)
		{
			$('.checkOnline').attr('checked', 'checked');
		}
		else
		{
			$('.checkOnline').removeAttr('checked');
		}

		$('.listImage').val($('.edit-image-' + id).val());
		$('.listImageNoCheck').val($('.edit-image-' + id).val());
		$('.editMainImage').val($('.edit-imageCheck-' + id).val());
		$('.editImages').html($('.edit-imageList-' + id).val());
		$('#editCategoryNoCheck').val($('.edit-category-' + id).val());
		$('#editItemId').val(id);
	});

	$('.buttonDeleteItem').on('click', function()
	{
		id = $(this).attr('id').substr(16);

		$('#titleDeleteItem').text($('.edit-title-' + id).val());
		$('#idDeleteItem').val(id);
	});

	$('.buttonRestoreItem').on('click', function()
	{
		id = $(this).attr('id').substr(17);

		$('#titleRestoreItem').text($('.edit-title-' + id).val());
		$('#idRestoreItem').val(id);
	});

	
	$(".getOptionsAjax").change(function(e)
	{
	    id = $(this).val();
	    edit = 0;
	    if($(this).attr('id') == 'editCategory')
	    	edit = 1;

	    $.ajax({
	        type: "POST",
	        url: "index.php?r=goods/getoptions",
	        data: {categoryId: id, edit: edit},
	        success: getOptions
	    });

	    e.preventDefault();
	});

	function getOptions(data)
	{
	    $(".itemPrices").html(data);
	}

	$('.clearFields').on('click', function()
	{
		$('.gotImages').html('');
		$('.itemPrices').html('');
	});
});