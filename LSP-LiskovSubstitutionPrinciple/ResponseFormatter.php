<?php
class ResponseFormatter {

    private $calculator;
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function JSON()
    {
        return json_encode(['total_area' => $this->calculator->sum()]);
    }

    public function XML()
    {
        $totalArea = $this->calculator->sum();
        return "<response><total_area>{$totalArea}</total_area></response>";
    }

    public function HTML()
    {
        $totalArea = $this->calculator->sum();
        return "<html><body><h1>Total Area: {$totalArea}</h1></body></html>";
    }
}
?>