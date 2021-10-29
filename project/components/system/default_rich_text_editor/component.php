<?php
require("../../../../divblox/divblox.php");

class DefaultRichTextEditorController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
}

$ComponentObj = new DefaultRichTextEditorController("default_rich_text_editor");
?>