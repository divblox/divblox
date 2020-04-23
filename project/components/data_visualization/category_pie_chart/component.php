<?php
// JGL: Documentation can be found here: https://www.chartjs.org/
require("../../../../divblox/divblox.php");
class CategoryPieChartController extends ProjectComponentController {
    public function __construct($ComponentNameStr = 'Component') {
        parent::__construct($ComponentNameStr);
    }
    public function getData() {
        $CategoryLabelCountArray = [];
        $CategoryLabelArray = ["Sport", "Leisure", "Work"];
        foreach($CategoryLabelArray as $Category) {
            $CategoryLabelCountArray[] = Ticket::QueryCount(
                dxQ::Equal(
                    dxQN::Ticket()->CategoryObject->CategoryLabel,
                    $Category
                )
            );
        }



        $ReturnData = array(
            "labels" => $CategoryLabelArray,
            "datasets" =>
                array(["label" => "Categories",
                        "data" => $CategoryLabelCountArray,
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
                        "borderWidth" => 1],));
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Data",$ReturnData);
        $this->setReturnValue("DataArray",$CategoryLabelCountArray);
        $this->presentOutput();
    }
}
$ComponentObj = new CategoryPieChartController("category_pie_chart");
?>