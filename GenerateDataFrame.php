<?php
require("divblox/divblox.php");

// Clear Database
Account::DeleteAll();
Category::DeleteAll();
Ticket::DeleteAll();
SubTask::DeleteAll();
Note::DeleteAll();

// Create Bobby Axelrod account
$AccountObj = new Account();
$AccountObj->FirstName = "Bobby";
$AccountObj->LastName = "Axelrod";
$AccountObj->FullName = $AccountObj->FirstName . " " . $AccountObj->LastName;
$AccountObj->EmailAddress = "bobby.axelrod@billions.com";
$AccountObj->Username = $AccountObj->EmailAddress;
$AccountObj->Password = "1";
$AccountObj->Save();

echo "Bobby Axelrod Created <br><br>";

// Create Category Frame
// Base Category keys and proportions
$BaseCategoryPropArr = ["Work" => 0.55, "Sport" => 0.3, "Leisure" => 0.15];
$WorkSubCategoryPropArr = ["DevOps" => 0.4, "Testing" => 0.2, "Project Management" => 0.27, "Other" => 0.13];
$SportSubCategoryPropArr = ["Tennis" => 0.8, "Golf" => 0.07, "Cricket" => 0.13];
$LeisureSubCategoryPropArr = ["Gardening" => 0.4, "Hiking" => 0.33, "History" => 0.27];

$BaseCategoryKeyArr = array_keys($BaseCategoryPropArr);
$WorkSubCategoryKeyArr = array_keys($WorkSubCategoryPropArr);
$SportSubCategoryKeyArr = array_keys($SportSubCategoryPropArr);
$LeisureSubCategoryKeyArr = array_keys($LeisureSubCategoryPropArr);

foreach ($BaseCategoryKeyArr as $Category) {
	echo "<br>";
	$CategoryObj = new Category();
	$CategoryObj->CategoryLabel = $Category;
	$CategoryObj->HierarchyPath = $Category;
	$CategoryObj->Save();
	$ParentId = $CategoryObj->Id;

	echo $CategoryObj->CategoryLabel . " created. <br>";

	if ($CategoryObj->CategoryLabel == "Work") {
		foreach ($WorkSubCategoryKeyArr as $SubCategory) {
			$CategoryObj = new Category();
			$CategoryObj->CategoryLabel = $SubCategory;
			$CategoryObj->CategoryParentId = $ParentId;
			$CategoryObj->HierarchyPath = "Work / " . $CategoryObj->CategoryLabel;
			$CategoryObj->Save();

			echo $CategoryObj->CategoryLabel . " subcategory created.<br>";
		}
	}
	if ($CategoryObj->CategoryLabel == "Sport") {
		foreach ($SportSubCategoryKeyArr as $SubCategory) {
			$CategoryObj = new Category();
			$CategoryObj->CategoryLabel = $SubCategory;
			$CategoryObj->CategoryParentId = $ParentId;
			$CategoryObj->HierarchyPath = "Sport / " . $CategoryObj->CategoryLabel;
			$CategoryObj->Save();

			echo $CategoryObj->CategoryLabel . " subcategory created.<br>";
		}
	}
	if ($CategoryObj->CategoryLabel == "Leisure") {
		foreach ($LeisureSubCategoryKeyArr as $SubCategory) {
			$CategoryObj = new Category();
			$CategoryObj->CategoryLabel = $SubCategory;
			$CategoryObj->CategoryParentId = $ParentId;
			$CategoryObj->HierarchyPath =  "Leisure / " . $CategoryObj->CategoryLabel;
			$CategoryObj->Save();

			echo $CategoryObj->CategoryLabel . " subcategory created.<br>";
		}
	}
}

echo "<br><br>";

// Add random accounts
for ($i = 0; $i < 15; $i++) {
	$AccountObj = new Account();
	$AccountObj->FirstName = ProjectFunctions::generateRandomString(8);
	$AccountObj->LastName = ProjectFunctions::generateRandomString(8);
	$AccountObj->FullName = $AccountObj->FirstName . " " . $AccountObj->LastName;
	$AccountObj->EmailAddress = ProjectFunctions::generateTimeBasedRandomString();
	$AccountObj->Username = $AccountObj->EmailAddress;
	$AccountObj->Save();
}

// Ticket Generation
$TicketStatusArray = ["New", "In Progress", "Due Soon", "Urgent", "Complete", "Overdue"];
$TicketStatusCumPropArray = [1, 51, 58, 67, 97, 100];
$PropArr = $TicketStatusCumPropArray;
for ($i = 0; $i < 150; $i++) {
	$TicketObj = new Ticket();
	$TicketObj->TicketName = ProjectFunctions::generateRandomString(8);
	$TicketObj->TicketDescription = ProjectFunctions::generateRandomString(15);

	// To get uneven proportions of statuses
	$Random = rand(0, 100);
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

	$TicketObj->TicketDueDate = dxDateTime::Now()->AddDays(rand(1, 40));
	$TicketObj->AccountObject = Account::Load(rand($AccountMinObj->Id, $AccountMaxObj->Id));
	$TicketObj->CategoryObject = Category::Load(rand($CategoryMinObj->Id, $CategoryMaxObj->Id));
	$TicketObj->Save();

	echo "<br>Ticket " . $TicketObj->TicketName . " created.<br>";
}

echo "<br><br>";

// SubTask Generation
$AllTicketsArr = Ticket::LoadAll();
foreach ($AllTicketsArr as $TicketObj) {
	echo $TicketObj->TicketName . " Subtasks: <br>";
	$NumberOfSubTasksInt = rand(1, 5);
	for ($i = 0; $i < $NumberOfSubTasksInt; $i++) {
		$SubTaskObj = new SubTask();
		$SubTaskObj->TicketObject = $TicketObj;
		$SubTaskObj->Description = ProjectFunctions::generateRandomString(15);

		// To get uneven proportions of statuses
		$Random = rand(0, 100);
		if (0 < $Random && $Random <= $PropArr[0]) {
			$SubTaskObj->SubTaskStatus = $TicketStatusArray[0];
		} else if ($PropArr[0] < $Random &&
			$Random <= $PropArr[1]) {
			$SubTaskObj->SubTaskStatus = $TicketStatusArray[1];
		} else if ($PropArr[1] < $Random &&
			$Random <= $PropArr[2]) {
			$SubTaskObj->SubTaskStatus = $TicketStatusArray[2];
		} else if ($PropArr[2] < $Random &&
			$Random <= $PropArr[3]) {
			$SubTaskObj->SubTaskStatus = $TicketStatusArray[3];
		} else if ($PropArr[3] < $Random &&
			$Random <= $PropArr[4]) {
			$SubTaskObj->SubTaskStatus = $TicketStatusArray[4];
		} else if ($PropArr[4] < $Random &&
			$Random <= $PropArr[5]) {
			$SubTaskObj->SubTaskStatus = $TicketStatusArray[5];
		}

		$SubTaskObj->SubTaskDueDate = dxDateTime::Now()->AddDays(rand(1, 40));
		$SubTaskObj->Save();

		echo "		SubTask " . $SubTaskObj->Description . " created. <br>";
	}

//	$TicketObj->Save();
	echo $TicketObj->TicketProgress . " % <br>";
	echo "<br><br>";
}

