<!doctype html>
<head>
	<style type="text/css"><?=$css?></style>

	<title>ICQDump - Contact Overview</title>
</head>
<body>
	<h1>ICQDump - Contact Overview</h1>

	<h3>Total of <?=$totalContacts?> contacts.</h3>

	<ul>
	<?php
		usort($users, function($a, $b) {
			if(count($a->conversations) == count($b->conversations))
				return 0;
			return count($a->conversations) < count($b->conversations) ? 1 : -1;
		});

		foreach($users as $user):

		if(isset($limitUsers) && !isset($limitUsers[$user->UID]))
			continue;
	?>
		<li><a href="user/<?=$user->UID?>.html"><?=$user->UID?> <?=$user->name?> (<?=count($user->conversations)?> conversations)</a>
	<?php
		endforeach;
	?>

	</ul>

	<hr>
	<a href="https://github.com/pp3345/ICQDump">ICQDump</a> 2014 Yussuf Khalil</a>
</body>
</html>
