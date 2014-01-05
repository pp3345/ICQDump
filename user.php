<!doctype html>
<head>
	<style type="text/css"><?=$css?></style>

	<title>ICQDump - <?=$user->UID?> <?=$user->name?></title>
</head>
<body>
	<h1>ICQDump - <?=$user->UID?> <?=$user->name?></h1>

	<?php
		if(!$dumpGroupConversations) {
			$nConversations = 0;

			foreach($user->conversations as $conv) {
				if(count($conv->users) == 1)
					$nConversations++;
			}
		} else {
			$nConversations = count($user->conversations);
		}
	?>

	<h3>Total of <?=$nConversations?> conversations.</h3>

	<ul>
	<?php
		foreach($user->conversations as $conv):

		if(count($conv->users) > 1 && !$dumpGroupConversations)
			continue;
	?>
		<li>
			<a href="../conversation/<?=$conv->id?>.html"><?=$conv->id?></a>
			<ul>
				<li><?=count($conv->messages)?> messages</li>
				<?php
				if(count($conv->messages)):
				?>
				<li>First message: <?=date('Y-m-d H:i:s', $conv->messages[0]->date)?></li>
				<li>Last message: <?=date('Y-m-d H:i:s', end($conv->messages)->date)?></li>
				<?php
				endif;
				?>

				<?php
				if(count($conv->users) > 1):
				?>
				<li>Group conversation members:
					<ul>
						<?php
						foreach($conv->users as $cUser):
						?>
						<li><a href="../user/<?=$cUser->UID?>.html"><?=$cUser->UID?> <?=$cUser->name?></a></li>
						<?php
						endforeach;
						?>
					</ul>
				</li>
				<?php
				endif;
				?>
			</ul>
		</li>
	<?php
		endforeach;
	?>
	</ul>

	<a href="../index.html">Back to overview</a>

	<hr/>
	<a href="https://github.com/pp3345/ICQDump">ICQDump</a> 2014 Yussuf Khalil</a>
</body>
</html>
