<?php

include 'config.php';

$coll = 'images_of_india';

$c = Dase_DBO_Collection::get($db,$coll);

print "$c->collection_name ($c->item_count)\n\n";

foreach ($c->getItems() as $item) {
	print "deleting ".$item->serial_number."\n";
	print $item->expunge();
}
