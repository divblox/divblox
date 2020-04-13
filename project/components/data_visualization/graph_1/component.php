<?php
// JGL: Documentation can be found here: https://www.chartjs.org/
require("../../../../divblox/divblox.php");
class Graph1Controller extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function getData() {
        $ReturnData = array(
            "labels" => ["Value 1", "Value 2", "Value 3", "Value 4", "Value 5", "Value 6"],
            "datasets" =>
                array(["label" => "Dataset 1 label",
                        "data" => [rand(1,20), rand(1,20), rand(1,20), rand(1,20), rand(1,20), rand(1,20)],
                        "backgroundColor" => [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                                'rgba(255, 206, 86, 0.2)',
                                                'rgba(75, 192, 192, 0.2)',
                                                'rgba(153, 102, 255, 0.2)',
                                                'rgba(255, 159, 64, 0.2)'],
                        "borderColor" => [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'],
                        "borderWidth" => 1],
                    ["label" => "Dataset 2 label",
                        "data" => [rand(1,20), rand(1,20), rand(1,20), rand(1,20), rand(1,20), rand(1,20)],
                        "type" => "line",
                        "backgroundColor" => ['rgba(75, 192, 192, 0.2)'],
                        "borderColor" => ['rgba(75, 192, 192, 1)'],
                        "borderWidth" => 1]));
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Data",$ReturnData);
        $this->presentOutput();
    }
}
$ComponentObj = new Graph1Controller("graph_1");
?>