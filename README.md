ICQDump (GNU GPL v3)
=======

ICQDump is a tool for dumping contacts and conversations from ICQ message databases as HTML. I've created this tool after I recovered my ICQ database from an old hard drive. Maybe someone out there finds it useful.

Dumping a database will create an index.html with a list of contacts, HTML files for each contact with their conversations and HTML files for each conversation containing all messages. Navigation between files is easily possible.

System requirements
======
* PHP 5.4.0 or newer
* PDO-ODBC extension for PHP (built into core on Windows)
* Windows NT 6.0 or newer or UNIX-compliant operating system (e. g. Linux, BSD, Apple Mac OS...)
* Tested with databases from official ICQ client version 7.0

Usage
=====
```
$ php icqdump.php --help
ICQDump 2014 Yussuf Khalil
GNU GPL v3
https://github.com/pp3345/ICQDump

--file=Messages.mdb                   ICQ database file path
--users=123456789,987654321,...       Only dump users with specified ICQ UIDs
--folder=dump                         Output folder for HTML dump
--help                                Show this help
--nogroup                             Disable dump of group conversations
```

### Donate Bitcoins to 12drX4MyvqzywuDuA7RdbCRHVdxog9QJbf
