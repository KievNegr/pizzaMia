<?php
	echo '<ul>';
	foreach($getFromDb as $data)
	{
		echo '<li>' . $data['id'] . '. ' . $data['name'];
			echo '<ul>' . 
					'<li>' . $data['email'] . '</li>' .
					'<li>' . $data['text'] . '</li>' .
				 '</ul>';
		echo '</li>';
	}
	echo '</ul>';
?>

<pre>
<?php
	print_r($getFromDb);
?>
</pre>