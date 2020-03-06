<?php
require("../../../../divblox/divblox.php");
class FurtherExamplesController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }

    // This function will generate a bunch of categories and accounts.
    // It will then generate a bunch of tickets, each linked to a random
    // category and account with a random status and due date.
    public function Function1() {
        $AccountDataSize = 50;
        $CategoryDataSize = 8;
        $TicketDataSize = 500;
        $TicketStatusArray = ["New", "In Progress", "Backlog", "Urgent", "Completed"];

        // Note that you need initial account and category
        for ($i = 0; $i < $TicketDataSize; $i++) {
            $TicketObj = new Ticket();
            $TicketObj->TicketName = ProjectFunctions::generateRandomString(8);
            $TicketObj->TicketDescription = ProjectFunctions::generateRandomString(100);
            $TicketObj->TicketStatus = $TicketStatusArray[rand(0,4)];
            $TicketObj->TicketDueDate = dxDateTime::Now()->AddDays(rand(1,20));
            $TicketObj->AccountObject = Account::Load(rand(0,Account::CountAll()-1));
            $TicketObj->CategoryObject = Category::Load(rand(0,Category::CountAll()-1));
            $TicketObj->Save();

            if ($i >= $AccountDataSize) {
                continue;
            }
            $AccountObj = new Account();
            $AccountObj->FirstName = ProjectFunctions::generateRandomString(8);
            $AccountObj->LastName = ProjectFunctions::generateRandomString(8);
            $AccountObj->FullName = $AccountObj->FirstName." ".$AccountObj->LastName;
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

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", "$TicketDataSize Tickets created");
        $this->presentOutput();
    }

    // Return all tickets in the category specified in the input box
    public function Function2() {
        $CategoryInputStr = $this->getInputValue("additional_input");
        if (is_null($CategoryInputStr)) {
            $CategoryInputStr = "Personal";
        }

        $TicketArray = Ticket::QueryArray(
            dxQ::Equal(
                dxQN::Ticket()->CategoryObject->CategoryLabel,
                $CategoryInputStr
            )
        );

        $ReturnArray = [];
        foreach($TicketArray as $TicketObj) {
            $ReturnArray[] = json_decode($TicketObj->getJson());
        }

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", $ReturnArray);
        $this->presentOutput();
    }

    // Return all tickets where the account's first name is specified in the input box and
    // the ticket's status is <em>In Progress</em>
    public function Function3() {
        $FirstNameStr = $this->getInputValue("additional_input");

        if (is_null($FirstNameStr)) {
            $FirstNameStr = "John";
        }

        $TicketArray = Ticket::QueryArray(
            dxQ::AndCondition(
                dxq::Equal(
                    dxqN::Ticket()->AccountObject->FirstName,
                    $FirstNameStr
                ),
                dxq::Equal(
                    dxqN::Ticket()->TicketStatus,
                    "In Progress"
                )
            )
        );

        $ReturnArray = [];
        foreach($TicketArray as $TicketObj) {
            $ReturnArray[] = json_decode($TicketObj->getJson());
        }

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", $ReturnArray);
        $this->presentOutput();
    }

    // Return all tickets that have a status <em>Completed</em> where
    // the due date is in the current month, orderd by Ticket Due Date aascending
    public function Function4() {
        $StartDateObj = dxDateTime::Now()->setDate(dxDateTime::Now()->format("Y"), dxDateTime::Now()->format("m"), 1)->setTime(0,0,0);
        $EndDateObj = dxDateTime::Now()->setDate(dxDateTime::Now()->format("Y"), dxDateTime::Now()->format("m"), 1)->setTime(0,0,0);
        $EndDateObj->addMonths(1);
        $EndDateObj->addDays(-1);

        $TicketArray = Ticket::QueryArray(
            dxQ::AndCondition(
                dxq::Equal(
                    dxqN::Ticket()->TicketStatus,
                    "Completed"
                ),
                dxQ::GreaterOrEqual(
                    dxqN::Ticket()->TicketDueDate,
                    $StartDateObj
                ),
                dxQ::LessOrEqual(
                    dxqN::Ticket()->TicketDueDate,
                    $EndDateObj
                )
            ),
            dxQ::Clause(
                dxQ::OrderBy(
                    dxqN::Ticket()->TicketDueDate,
                    true
                )
            )
        );

        $ReturnArray = [];
        foreach($TicketArray as $TicketObj) {
            $ReturnArray[] = json_decode($TicketObj->getJson());
        }

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", $ReturnArray);
        $this->presentOutput();
    }

    // Return a list of account full names with a count of tickets that
    // they each currently have <em>In Progress</em>.
    public function Function5() {
        $AccountArray = Account::QueryArray(
            dxQ::All()
        );

        // $AccountArray = Account::LoadAll();

        $ReturnArray = [];

        foreach ($AccountArray as $AccountObj) {
            $ReturnArray[$AccountObj->FullName] = Ticket::QueryCount(
                dxQ::Equal(
                    dxQN::Ticket()->AccountObject->Id,
                    $AccountObj->Id
                )
            );
        }

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", $ReturnArray);
        $this->presentOutput();
    }

    // Return a list of account email addresses. For each account,
    // show an array of categories. For each category in the array,
    // show the total count for all tickets in that category,
    // as well as the count for the specific account.
    public function Function6() {
        $AccountArray = Account::QueryArray(
            dxQ::All()
        );

        $CategoryArray = Category::QueryArray(
            dxQ::All()
        );

        $ReturnArray = [];

        foreach ($AccountArray as $AccountObj) {
            $ReturnArray[$AccountObj->EmailAddress] = [];
            foreach ($CategoryArray as $CategoryObj) {

                $TotalCountInt = Ticket::QueryCount(
                    dxQ::Equal(
                        dxQN::Ticket()->CategoryObject->CategoryLabel,
                        $CategoryObj->CategoryLabel
                    )
                );

                $AccountCountInt = Ticket::QueryCount(
                    dxQ::AndCondition(
                        dxQ::Equal(
                            dxQN::Ticket()->CategoryObject->CategoryLabel,
                            $CategoryObj->CategoryLabel
                        ),
                        dxQ::Equal(
                            dxQN::Ticket()->AccountObject->Id,
                            $AccountObj->Id
                        )
                    )
                );

                $ReturnArray[$AccountObj->EmailAddress][$CategoryObj->CategoryLabel] = ["GrandTotal"=> $TotalCountInt, "AccountTotal"=> $AccountCountInt];
            }
        }

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", $ReturnArray);
        $this->presentOutput();
    }

    // Optimises function 6 to return its query result quicker
    public function Function7() {
        $AccountArray = Account::QueryArray(
            dxQ::All(),
            dxQ::Clause(
                dxQ::Select(
                    dxQN::Account()->EmailAddress
                )
            )
        );

        $CategoryArray = Category::QueryArray(
            dxQ::All()
        );

        $ReturnArray = [];
//        $TotalCountArray = [];

        foreach ($AccountArray as $AccountObj) {
            $ReturnArray[$AccountObj->EmailAddress] = [];
            foreach ($CategoryArray as $CategoryObj) {

//                if (!isset($TotalCountArray[$CategoryObj->Id])) {
//                    $TotalCountArray[$CategoryObj->Id] = Ticket::QueryCount(
//                        dxQ::Equal(
//                            dxQN::Ticket()->CategoryObject->Id,
//                            $CategoryObj->Id
//                        )
//                    );
//                }

                $AccountCountInt = Ticket::QueryCount(
                    dxQ::AndCondition(
                        dxQ::Equal(
                            dxQN::Ticket()->CategoryObject->Id,
                            $CategoryObj->Id
                        ),
                        dxQ::Equal(
                            dxQN::Ticket()->AccountObject->Id,
                            $AccountObj->Id
                        )
                    ),
                    dxQ::Clause(
                        dxQ::Select(
                            dxqN::Ticket()->Id
                        )
                    )
                );

                $ReturnArray[$AccountObj->EmailAddress][$CategoryObj->CategoryLabel] = ["GrandTotal"=> $CategoryObj->TicketCount, "AccountTotal"=> $AccountCountInt];
            }
        }

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("ReturnData", $ReturnArray);
        $this->presentOutput();
    }

}
$ComponentObj = new FurtherExamplesController("further_examples");
?>