JDB
===
This class helps you to make JSON Databases, and if requested that databases will be encrypted with a custom key.

How to use it
=============
Create a new instance of JDB class ([this is an optional parameter]):
$db = new JDB($dbName, [$encrypted = false], [$encryptKey = ""]);

Add a group of things:
$db->createGroup("groupName");
-- or --
$db->insert("groupName", array());

Insert things into group:
$db->insertInto("groupName", "key", "value");

Insert things into root:
$db->insert("key", "value");

Delete things from root:
$db->delete("key");

Delete things from a group:
$db->deleteInto("groupName", "key");

If no one of these functions can help you use the array: $db->currArr; examples:
unset($db->currArr["key"]);
$db->currArr["key"] = array();
$db->currArr["str"] = "";
etc...

Credits
=======
Only me and my brain!

Finally...
==========
Thanks for reading, report any bug to me!
