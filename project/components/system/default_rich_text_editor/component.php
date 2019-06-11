<?php
require("../../../../divblox/divblox.php");
class DefaultRichTextEditorController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function getInitData() {
        // TODO: Override this with applicable data. For now, we just return Hello world
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Html",'<p><strong>Hello</strong> World!</p>');
        $this->presentOutput();
    }
    public function saveData() {
        $DataStr = $this->getInputValue("html");
        // TODO: Save this data as needed
        error_log("Data to save: ".$DataStr);
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Data saved");
        $this->presentOutput();
    }
}
$ComponentObj = new DefaultRichTextEditorController("default_rich_text_editor");
?>