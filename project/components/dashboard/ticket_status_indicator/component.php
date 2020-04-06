<?php
require("../../../../divblox/divblox.php");
class TicketStatusIndicatorController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }

    public function loadStatusTotals() {
        $StatusStr = $this->getInputValue("ticket_status");

        $StatusTicketCountInt = Ticket::QueryCount(
            dxQ::Equal(
                dxQN::Ticket()->TicketStatus,
                $StatusStr
            )
        );

        $TotalTicketCountInt = Ticket::QueryCount(
            dxQ::All()
        );
        $StatusPercentage = $StatusTicketCountInt/$TotalTicketCountInt;

        $this->setReturnValue("Result", "Success");
        $this->setReturnValue("Count", $StatusTicketCountInt);
        $this->setReturnValue("Percentage", $StatusPercentage);
        $this->presentOutput();
    }
}
$ComponentObj = new TicketStatusIndicatorController("ticket_status_indicator");
?>