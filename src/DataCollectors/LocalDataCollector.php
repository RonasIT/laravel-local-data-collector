<?php

namespace  RonasIT\Support\DataCollectors;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use RonasIT\Support\Interfaces\DataCollectorInterface;
use RonasIT\Support\DataCollectors\Exceptions\MissedProductionFilePathException;

class LocalDataCollector implements DataCollectorInterface
{
    public $prodFilePath;
    public $tempFilePath;

    protected static $data;

    public function __construct()
    {
        $this->prodFilePath = config('local-data-collector.production_path');

        if (empty($this->prodFilePath)) {
            throw new MissedProductionFilePathException();
        }
    }

    public function saveTmpData($tempData) {
        self::$data = $tempData;
    }

    public function getTmpData() {
        return self::$data;
    }

    public function saveData(){
        $content = json_encode(self::$data);

        file_put_contents($this->prodFilePath, $content);

        self::$data = [];
    }

    public function getDocumentation() {
        if (!file_exists($this->prodFilePath)) {
            throw new FileNotFoundException();
        }

        $fileContent = file_get_contents($this->prodFilePath);

        return json_decode($fileContent);
    }
}
