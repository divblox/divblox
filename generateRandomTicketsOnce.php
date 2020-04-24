<?php
require("divblox/divblox.php");

Account::DeleteAll();
Category::DeleteAll();

if (!Account::LoadAll()) {
    $AccountObj = new Account();
    $AccountObj->FirstName = ProjectFunctions::generateRandomString(8);
    $AccountObj->LastName = ProjectFunctions::generateRandomString(8);
    $AccountObj->FullName = $AccountObj->FirstName . " " . $AccountObj->LastName;
    $AccountObj->EmailAddress = ProjectFunctions::generateTimeBasedRandomString();
    $AccountObj->Username = $AccountObj->EmailAddress;
    $AccountObj->Save();
}
if (!Category::LoadAll()) {
    $CategoryObj = new Category();
    $CategoryObj->CategoryLabel = ProjectFunctions::generateTimeBasedRandomString();
    $CategoryObj->Save();
}



$AccountDataSize = 50;
$CategoryDataSize = 8;
$TicketDataSize = 500;
$TicketStatusArray = ["New", "In Progress", "Due Soon", "Urgent", "Complete", "Overdue"];
$TicketStatusCumPropArray = [1, 51, 58, 67, 97, 100];
$PropArr = $TicketStatusCumPropArray;

// Note that you need initial account and category
for ($i = 0; $i < $TicketDataSize; $i++) {
    $TicketObj = new Ticket();
    $TicketObj->TicketName = ProjectFunctions::generateRandomString(8);
    $TicketObj->TicketDescription = ProjectFunctions::generateRandomString(100);

    // To get uneven proportions of statuses
    $Random = rand(0,100);
    if (0 < $Random && $Random <= $PropArr[0]) {
        $TicketObj->TicketStatus = $TicketStatusArray[0];
    } else if ($PropArr[0] < $Random &&
                $Random <= $PropArr[1]) {
        $TicketObj->TicketStatus = $TicketStatusArray[1];
    } else if ($PropArr[1] < $Random &&
                $Random <= $PropArr[2]) {
        $TicketObj->TicketStatus = $TicketStatusArray[2];
    } else if ($PropArr[2] < $Random &&
                $Random <= $PropArr[3]) {
        $TicketObj->TicketStatus = $TicketStatusArray[3];
    } else if ($PropArr[3] < $Random &&
                $Random <= $PropArr[4]) {
        $TicketObj->TicketStatus = $TicketStatusArray[4];
    } else if ($PropArr[4] < $Random &&
                $Random <= $PropArr[5]) {
        $TicketObj->TicketStatus = $TicketStatusArray[5];
    }


    $AccountMinObj = Account::QuerySingle(
        dxQ::All(),
        dxQ::Clause(
            dxQ::OrderBy(
                dxQN::Account()->Id,
                true
            )
        )
    );
    $AccountMaxObj = Account::QuerySingle(
        dxQ::All(),
        dxQ::Clause(
            dxQ::OrderBy(
                dxQN::Account()->Id,
                false
            )
        )
    );
    $CategoryMinObj = Category::QuerySingle(
        dxQ::All(),
        dxQ::Clause(
            dxQ::OrderBy(
                dxQN::Category()->Id,
                true
            )
        )
    );
    $CategoryMaxObj = Category::QuerySingle(
        dxQ::All(),
        dxQ::Clause(
            dxQ::OrderBy(
                dxQN::Category()->Id,
                false
            )
        )
    );


    $TicketObj->TicketDueDate = dxDateTime::Now()->AddDays(rand(1, 20));
    $TicketObj->AccountObject = Account::Load(rand($AccountMinObj->Id, $AccountMaxObj->Id));
    $Category = Category::Load(rand($CategoryMinObj->Id, $CategoryMaxObj->Id));
    $TicketObj->CategoryObject = $Category;
//    $TicketObj->Category = $Category;

    foreach($TicketObj as $key => $val) {
        if (!is_null($val)) {
            echo $key . ":" . $val . "<br>";
        }
        echo "<br>";
    }
    $TicketObj->Save();

    if ($i >= $AccountDataSize) {
        continue;
    }
    $AccountObj = new Account();
    $AccountObj->FirstName = ProjectFunctions::generateRandomString(8);
    $AccountObj->LastName = ProjectFunctions::generateRandomString(8);
    $AccountObj->FullName = $AccountObj->FirstName . " " . $AccountObj->LastName;
    $AccountObj->EmailAddress = ProjectFunctions::generateTimeBasedRandomString();
    $AccountObj->Username = $AccountObj->EmailAddress;
    $AccountObj->Save();

    if ($i >= $CategoryDataSize) {
        continue;
    }

    $CategoryObj = new Category();
    $CategoryObj->CategoryLabel = ProjectFunctions::generateTimeBasedRandomString();
    $CategoryObj->Save();
}
