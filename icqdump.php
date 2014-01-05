<?php

	/**
	* ICQDump
	* 2014 Yussuf Khalil
	*
	* https://github.com/pp3345/ICQDump
	*
	* License: GNU GPL v3
	*/

	// Initialize vars
	$users = $conversations = [];
	$totalContacts = 0;

	// Get command line options
	$options = getopt("", ['file:', 'users:', 'folder:', 'help', 'nogroup']);

	// Show help
	if(isset($options['help'])) {
		echo "ICQDump 2014 Yussuf Khalil" . "\r\n";
		echo "GNU GPL v3" . "\r\n";
		echo "https://github.com/pp3345/ICQDump" . "\r\n";

		echo "\r\n";

		echo '--file=Messages.mdb                   ICQ database file path' . "\r\n";
		echo '--users=123456789,987654321,...       Only dump users with specified ICQ UIDs' . "\r\n";
		echo '--folder=dump                         Output folder for HTML dump' . "\r\n";
		echo '--help                                Show this help' . "\r\n";
		echo '--nogroup                             Disable dump of group conversations' . "\r\n";

		return;
	}

	$file = isset($options['file']) ? $options['file'] : "Messages.mdb";
	$dumpFolder = isset($options['folder']) ? $options['folder'] : "dump";

	if(isset($options['users'])) {
		$limitUsers = array_flip(explode(',', $options['users']));

		foreach($limitUsers as &$val)
			$val = true;
	}

	$dumpGroupConversations = !isset($options['nogroup']);

	function htmlspecialchars_ascii($str) {
		return str_replace(['&', '"', "'", '<', '>'], ['&amp;', '&quot;', '&#039;', '&lt;', '&gt;'], $str);
	}

	echo "Connecting to database...\r\n";

	// Connect
	$pdo = new PDO('odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=' . $file . ';Uid=Admin');

	echo "Fetching contacts...\r\n";

	// Fetch data
	foreach($pdo->query('SELECT * FROM Users')->fetchAll() as $row) {
		$user = new stdClass;
		$user->name = $row["name"];
		$user->conversations = [];
		$user->UID = $row["UID"];

		$users[$row["UID"]] = $user;
	}

	echo "Fetching conversations...\r\n";

	foreach($pdo->query('SELECT * FROM ChatHistory')->fetchAll() as $row) {
		if(isset($conversations[$row["to"]])) {
			// Group conversation
			$conversations[$row["to"]]->users[] = $users[$row["UID"]];

			if(!isset($users[$row["UID"]]->conversations[$row["to"]]))
				$users[$row["UID"]]->conversations[$row["to"]]  = $conversations[$row["to"]];

			continue;
		}

		$conv = new stdClass;
		$conv->id = $row["to"];
		$conv->messages = [];
		$conv->users[] = $users[$row["UID"]];

		$conversations[$row["to"]] = $conv;
		$users[$row["UID"]]->conversations[$row["to"]] = $conv;
	}

	echo "Fetching messages...\r\n";

	foreach($pdo->query('SELECT * FROM Messages')->fetchAll() as $row) {
		$message = new stdClass;
		$message->conv = $conversations[$row["to"]];
		$message->type = $row["type"];
		$message->subType = $row["subType"];
		$message->from = $row["from"] ? $users[$row["from"]] : null;
		$message->date = strtotime($row["date"]);
		$message->message = $row["subject"];
		$message->data = $row["data"]; // ?

		$message->conv->messages[] = $message;
	}

	// Create directory
	if(!is_dir($dumpFolder))
		mkdir($dumpFolder);

	$css = file_get_contents('icqdump.css');

	if(!is_dir($dumpFolder . '/user'))
		mkdir($dumpFolder . '/user');

	// Write user files
	foreach($users as $user) {
		if(isset($limitUsers) && !isset($limitUsers[$user->UID]))
			continue;

		echo "Creating user/" . $user->UID . ".html...\r\n";

		$totalContacts++;

		ob_start();

		require 'user.php';

		file_put_contents($dumpFolder . '/user/' . $user->UID . '.html', ob_get_contents());
		ob_end_clean();
	}

	if(!is_dir($dumpFolder . '/conversation'))
		mkdir($dumpFolder . '/conversation');

	// Write user files
	foreach($conversations as $conv) {
		if(count($conv->users) > 1 && !$dumpGroupConversations)
			continue;

		if(isset($limitUsers)) {
			do {
				foreach($conv->users as $user) {
					if(isset($limitUsers[$user->UID]))
						break 2;
				}

				continue 2;
			} while(false);
		}

		echo "Creating conversation/" . $conv->id . ".html...\r\n";

		ob_start();

		require 'conversation.php';

		file_put_contents($dumpFolder . '/conversation/' . $conv->id . '.html', ob_get_contents());
		ob_end_clean();
	}

	// Write index file
	echo "Creating index.html...\r\n";

	ob_start();

	require_once 'overview.php';

	file_put_contents($dumpFolder . '/index.html', ob_get_contents());
	ob_end_clean();

	echo "Done\r\n";
?>
