<?php
	/*foreach($user as $someOne)
	{
		echo '<ul><li>';
		echo $someOne['name'];
		if(count($someOne['action']) != 0)
		{
			echo '<ul>';
			foreach($someOne['action'] as $action)
			{
				echo '<li>' . $action['action'] . '</li>';
			}
			echo '</ul>';
		}
		echo '</li></ul>';
	}*/
?>
<pre>
<p style="color: brown;">print_r($user);</p>
<?php
	print_r($user);
?>
<hr />
<!--
<p style="color: brown;">count($user->action);</p>
<?php
	//echo count($user[0]->action);
?>
<hr />

<p style="color: aqua;">print_r($user);</p>
<?php
	//print_r($user);
?>
<hr />
<p style="color: aqua;">print_r($action);</p>
<?php
	//print_r($action);
?>
<hr />
<p style="color: aqua;">print_r($action->test);</p>
<?php
	//print_r($action->test);
?>
<hr />
<p style="color: aqua;">print_r($action);</p>
<?php
	//print_r($action);
?>-->
</pre>