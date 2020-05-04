<?php
require("divblox/divblox.php");

echo "Initiated.";

$AllTicketsArr = Ticket::LoadAll();
foreach ($AllTicketsArr as $TicketObj) {
	$TicketObj->TicketName = ProjectFunctions::generateRandomString(10);
	echo $TicketObj->TicketName . " updated <br>";
	$TicketObj->Save();
}

echo "Finished";