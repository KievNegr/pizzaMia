$(document).ready(function()
{
	$('.makeseo').on('focusout', function()
	{
		text = $(this).val();
		if(text.length > 0)
		{
			$.ajax({
				url: 'index.php?r=site/seo',
				data: {text: text},
				type: 'GET',
				success: makelink
			});
		}
	});

	function makelink(data)
	{
		$('.setseo').val(data);
	}
});