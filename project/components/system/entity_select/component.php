<?php
require("../../../../divblox/divblox.php");
class EntitySelectController extends ProjectComponentController {
    protected $RestrictedEntityArray = [//Anything in this list will require its own dedicated select component
        "AuditLogEntry",
        "BackgroundProcess",
        "BackgroundProcessUpdate",
        "ClientAuthenticationToken",
        "ClientConnection","EmailMessage",
        "FileDocument",
        "PageView",
        "PasswordReset",
        "PushRegistration"];
    protected $RestrictedAttributeArray = [// This list is used to block access to sensitive attributes for entities not in $RestrictedEntityArray
        "EmailAddress",
        "Username",
        "Password"];
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function updateDatalist() {
        $EntityTypeStr = $this->getInputValue('entity');
        if (is_null($EntityTypeStr)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Entity not provided");
            $this->presentOutput();
        }
        if (in_array($EntityTypeStr, $this->RestrictedEntityArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Access denied");
            $this->presentOutput();
        }
        $EntityDisplayAttrStr = $this->getInputValue('display_attr');
        if (is_null($EntityDisplayAttrStr)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Entity display attribute not provided");
            $this->presentOutput();
        }
        if (in_array($EntityDisplayAttrStr, $this->RestrictedAttributeArray)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Access denied");
            $this->presentOutput();
        }
        $SearchTextStr = $this->getInputValue('input');
        $QueryCondition = dxQ::Like(dxQN::$EntityTypeStr()->$EntityDisplayAttrStr, "%$SearchTextStr%");
        if (strlen(trim($SearchTextStr)) == 0) {
            $QueryCondition = dxQ::All();
        }
        $ResultArray = $EntityTypeStr::QueryArray($QueryCondition,
            dxQ::Clause(
                dxQ::OrderBy(dxQN::$EntityTypeStr()->$EntityDisplayAttrStr),
                dxQ::LimitInfo(50),
                dxQ::Select(dxQN::$EntityTypeStr()->$EntityDisplayAttrStr)));
        $ReturnArray = [];
        if (ProjectFunctions::getDataSetSize($ResultArray) > 0) {
            foreach ($ResultArray as $item) {
                $ReturnArray[$item->Id] = $item->$EntityDisplayAttrStr;
            }
        }
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("ResultArray",$ReturnArray);
        $this->presentOutput();
    }
}
$ComponentObj = new EntitySelectController("entity_select");
?>