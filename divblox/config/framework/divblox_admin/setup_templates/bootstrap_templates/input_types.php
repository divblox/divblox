<?php
require("../../../../../divblox.php");
if (!isset($_SESSION["divblox_admin_access"])) {
    die(json_encode(array("Result" => "Failed", "Message" => "Not authorized")));
}
$SelectedId = -1;
if (isset($_POST['selected'])) {
    $SelectedId = $_POST['selected'];
}
$Options = [];

// Form Controls Inputs
$Options['FormControlText'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Text</label>
    <input id="[element_id]_FormControlInput" type="text" class="form-control" placeholder="Text Input"/>
</div>';
$Options['FormControlEmail'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Email Address</label>
    <input id="[element_id]_FormControlInput" type="email" class="form-control" placeholder="name@example.com"/>
</div>';
$Options['FormControlPassword'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Password</label>
    <input id="[element_id]_FormControlInput" type="password" class="form-control" placeholder="Password Input"/>
</div>';
$Options['FormControlColor'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Color</label>
    <input id="[element_id]_FormControlInput" type="color" class="form-control" placeholder="Color Input"/>
</div>';
$Options['FormControlDate'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Date</label>
    <input id="[element_id]_FormControlInput" type="date" class="form-control" placeholder="Date Input"/>
</div>';
$Options['FormControlDateTimeLocal'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Date Time Local</label>
    <input id="[element_id]_FormControlInput" type="datetime-local" class="form-control" placeholder="Date Time Input"/>
</div>';
$Options['FormControlMonth'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Month</label>
    <input id="[element_id]_FormControlInput" type="month" class="form-control" placeholder="Month Input"/>
</div>';
$Options['FormControlWeek'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Week</label>
    <input id="[element_id]_FormControlInput" type="week" class="form-control" placeholder="Week Input"/>
</div>';
$Options['FormControlNumber'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Number</label>
    <input id="[element_id]_FormControlInput" type="number" class="form-control" placeholder="Number Input"/>
</div>';
$Options['FormControlTel'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Telephone</label>
    <input id="[element_id]_FormControlInput" type="tel" class="form-control" placeholder="Telephone Input"/>
</div>';
$Options['FormControlTime'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Time</label>
    <input id="[element_id]_FormControlInput" type="tel" class="form-control" placeholder="Time Input"/>
</div>';
$Options['FormControlUrl'] = '<div class="form-group">
    <label for="[element_id]_FormControlInput">Input Url</label>
    <input id="[element_id]_FormControlInput" type="url" class="form-control" placeholder="URL Input"/>
</div>';
// Form Control Group
$Options['FormControlGroup'] = '<div class="form-group">
    <label for="[element_id]_FormControlSelect">Example select</label>
    <select class="form-control" id="[element_id]_FormControlSelect">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
   </div>';
// Form Control Group Multiple
$Options['FormControlGroupMultiple'] = '<div class="form-group">
    <label for="[element_id]_FormControlSelect">Example multiple select</label>
    <select multiple="true" class="form-control" id="[element_id]_FormControlSelect">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
</div>';
// Form Control Text Area
$Options['FormControlTextArea'] = '<div class="form-group">
    <label for="[element_id]_FormControlTextArea">Example textarea</label>
    <textarea class="form-control" id="[element_id]_FormControlTextArea" rows="3"></textarea>
</div>';
// Range Input / Slider
$Options['RangeInput'] = '<div class="form-group">
    <label for="[element_id]_FormControlRange">Example Range input</label>
    <input type="range" class="form-control-range" id="[element_id]_FormControlRange"/>
</div>';
// Check Boxes
$Options['CheckBoxes'] = '<div>
    <div class="form-check">
        <input id="[element_id]_Check1" class="form-check-input" type="checkbox" value=""/>
        <label class="form-check-label" for="[element_id]_Check1">
            Default checkbox
        </label>
    </div>
    <div class="form-check">
        <input id="[element_id]_Check2" class="form-check-input" type="checkbox" value="" disabled="true"/>
        <label class="form-check-label" for="[element_id]_Check2">
            Disabled checkbox
        </label>
    </div>
</div>';
// Radio Buttons
$Options['RadioButtons'] = '<div>
    <div class="form-check">
        <input id="[element_id]_Radio1" class="form-check-input" type="radio" name="Radios" value="option1" checked="true"/>
        <label class="form-check-label" for="[element_id]_Radio1">
            Default radio
        </label>
    </div>
    <div class="form-check">
        <input id="[element_id]_Radio2" class="form-check-input" type="radio" name="Radios" value="option2"/>
        <label class="form-check-label" for="[element_id]_Radio2">
            Second default radio
        </label>
    </div>
    <div class="form-check">
        <input id="[element_id]_Radio3" class="form-check-input" type="radio" name="Radios" value="option3" disabled="true"/>
        <label class="form-check-label" for="[element_id]_Radio3">
            Disabled radio
        </label>
    </div>
</div>';
// Inline Check Boxes
$Options['InlineCheckBoxes'] = '<div>
    <div class="form-check form-check-inline">
        <input id="[element_id]_inlineCheckbox1" class="form-check-input" type="checkbox" value="option1"/>
        <label class="form-check-label" for="[element_id]_inlineCheckbox1">1</label>
    </div>
    <div class="form-check form-check-inline">
        <input id="[element_id]_inlineCheckbox2" class="form-check-input" type="checkbox" value="option2"/>
        <label class="form-check-label" for="[element_id]_inlineCheckbox2">2</label>
    </div>
    <div class="form-check form-check-inline">
        <input id="[element_id]_inlineCheckbox3" class="form-check-input" type="checkbox" value="option3" disabled="true"/>
        <label class="form-check-label" for="[element_id]_inlineCheckbox3">3 (disabled)</label>
    </div>
</div>';
// Inline Radio Buttons
$Options['InlineRadioButtons'] = '<div>
    <div class="form-check form-check-inline">
        <input id="[element_id]_inlineRadio1" class="form-check-input" type="radio" name="[element_id]_inlineRadio1" value="option1"/>
        <label class="form-check-label" for="[element_id]_inlineRadio1">1</label>
    </div>
    <div class="form-check form-check-inline">
        <input id="[element_id]_inlineRadio2" class="form-check-input" type="radio" name="[element_id]_inlineRadio2" value="option2"/>
        <label class="form-check-label" for="[element_id]_inlineRadio2">2</label>
    </div>
    <div class="form-check form-check-inline">
        <input id="[element_id]_inlineRadio3" class="form-check-input" type="radio" name="[element_id]_inlineRadio3" value="option3" disabled="true"/>
        <label class="form-check-label" for="[element_id]_inlineRadio3">3 (disabled)</label>
    </div>
</div>';
// Basic Input Groups Icon
$Options['BasicInputGroupsIcon'] = '<div>
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text" id="[element_id]_FormControlInputGroup">@</span>
        </div>
        <input id="[element_id]_FormControl" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="[element_id]_FormControlInputGroup"/>
    </div>
</div>';
// Basic Input Groups Username
$Options['BasicInputGroupsUsername'] = '<div>
    <div class="input-group mb-2">
        <input id="[element_id]_FormControl" type="text" class="form-control" placeholder="Recipient\'s username" aria-label="Recipient\'s username" aria-describedby="[element_id]_FormControlInputGroup"/>
        <div class="input-group-append">
           <span class="input-group-text" id="[element_id]_FormControlInputGroup">@example.com</span>
        </div>
    </div>
</div>';
// Basic Input Groups URL
$Options['BasicInputGroupsURL'] = '<div><label for="[element_id]_basic-url">Your vanity URL</label>
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text" id="[element_id]_FormControlInputGroup">https://example.com/users/</span>
        </div>
        <input id="[element_id]_FormControl" type="text" class="form-control" aria-describedby="[element_id]_FormControlInputGroup"/>
    </div>
</div>';

if ($SelectedId == -1) {
    die(json_encode(array("Result" => "Success", "Options" => $Options)));
} else {
    die(json_encode(array("Result" => "Success", "SelectedItem" => $Options[$SelectedId])));
}
?>