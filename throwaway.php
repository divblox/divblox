<?php

require("divblox/divblox.php");


$CategoryArray = Category::QueryArray(
    dxQ::All()
);

foreach ($CategoryArray as $CategoryObj) {
    $TicketCount = Ticket::QueryCount(
        dxQ::Equal(
            dxQN::Ticket()->CategoryObject->Id,
            $CategoryObj->Id
        )
    );

    $CategoryObj->TicketCount = $TicketCount;
    $CategoryObj->Save();
}