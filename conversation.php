<!doctype html>
<head>
	<style type="text/css"><?=$css?></style>

	<title>ICQDump - Conversation <?=$conv->id?></title>
</head>
<body>
	<h1>ICQDump - Conversation <?=$conv->id?></h1>

	<h3>Total of <?=count($conv->messages)?> messages.</h3>

	Conversation members:

	<ul>
	<?php
		foreach($conv->users as $user):
	?>
		<li><a href="../user/<?=$user->UID?>.html"><?=$user->UID?> <?=$user->name?></a></li>
	<?php
		endforeach;
	?>
	</ul>

	<a href="../index.html">Back to overview</a>
	<br/><br/>

	<?php
		foreach($conv->messages as $message):
	?>
		<span class="<?=$message->from ? "foreign" : "self"?>">
			<?=$message->type?> <?=$message->subType?>
			<?=date('Y-m-d H:i:s', $message->date)?>
			<?=$message->from ? ($message->from->UID . " " . $message->from->name) : "Self" ?>: <?=nl2br(htmlspecialchars_ascii($message->message))?>
		</span>
		<br/>
	<?php
		endforeach;
	?>

	<br/>
	<a href="../index.html">Back to overview</a>

	<hr/>
	<a href="https://github.com/pp3345/ICQDump">ICQDump</a> 2014 Yussuf Khalil</a>
</body>
</html>
