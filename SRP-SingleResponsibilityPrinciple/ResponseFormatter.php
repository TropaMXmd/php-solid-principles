<?php
class ResponseFormatter {

    private $areaCalculator;
    public function __construct(AreaCalculator $areaCalculator)
    {
        $this->areaCalculator = $areaCalculator;
    }

    public function JSON()
    {
        return json_encode(['total_area' => $this->areaCalculator->sum()]);
    }

    public function XML()
    {
        $totalArea = $this->areaCalculator->sum();
        return "<response><total_area>{$totalArea}</total_area></response>";
    }

    public function HTML()
    {
        $totalArea = $this->areaCalculator->sum();
        return "<html><body><h1>Total Area: {$totalArea}</h1></body></html>";
    }
}
?>