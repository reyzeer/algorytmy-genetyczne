<?php

namespace Reader;

use RuntimeException;

class TSPCoordReader
{
    private const INFO_REGEX = "/^([A-Z_]+)[\s]*:[\s]*([a-zA-Z0-9\s_\-.,\(\)\/]+)$/";
    private const NAME = 'NAME';
    private const TYPE = 'TYPE';
    private const COMMENT = 'COMMENT';
    private const DIMENSION = 'DIMENSION';
    private const EDGE_WEIGHT_TYPE = 'EDGE_WEIGHT_TYPE';
    public const NODE_COORD_SECTION = 'NODE_COORD_SECTION';
    public const EDGE_COORD_SECTION = 'EDGE_COORD_SECTION';
    private const EOF = 'EOF';

    private const VERTEX_TYPE = 'VERTEX';
    private const EDGE_TYPE = 'EDGE';

    private const AVAILABLE_TYPES = ['TSP'];
    private const AVAILABLE_VERTEX_TYPES = ['EUC_2D', 'ATT', 'EUC_2D'];
    private const AVAILABLE_EDGE_TYPES = ['MATRIX'];

    public string $name;
    public string $type;
    public string $comment;
    public string $dimension;
    public string $edgeWeightType;
    public array $nodeCoords = [];

    private bool $coordSection = false;

    public function __construct(
        private string $filepath
    ) {
    }

    public function load(): void
    {
        $file = fopen($this->filepath, 'rb');
        $this->nodeCoords = [];
        while (!feof($file)) {
            $line  = fgets($file);
            $this->loadLine($line);
        }
        fclose($file);
    }

    public function loadLine(string $line): void
    {
        $line = trim($line);
        if (!$this->coordSection) {
            if (preg_match(self::INFO_REGEX, $line, $matches)) {
                $this->setInfo($matches);
            } elseif ($line === self::NODE_COORD_SECTION || $line === self::EDGE_COORD_SECTION) {
                $this->coordSection = true;
            }
        } elseif ($line === self::EOF) {
            $this->coordSection = false;
        } else {
            $this->loadNodeCoord($line);
        }
    }

    private function setInfo(array $matches): void
    {
        switch ($matches[1]) {
            case self::NAME: $this->name = $matches[2]; break;
            case self::TYPE: $this->type = $matches[2]; break;
            case self::COMMENT: $this->comment = $matches[2]; break;
            case self::DIMENSION: $this->dimension = $matches[2]; break;
            case self::EDGE_WEIGHT_TYPE: $this->edgeWeightType = $matches[2]; break;
        }
    }

    private function loadNodeCoord(string $line): void
    {
        $elements = match ($this->getEdgeWeightDataType()) {
            self::VERTEX_TYPE => array_slice(explode(" ", $line), 1),
            self::EDGE_TYPE => explode(" ", $line)
        };
        $coords = [];
        foreach ($elements as $coord) {
            $coords[] = (float) $coord;
        }
        $this->nodeCoords[] = $coords;
    }

    private function getEdgeWeightDataType(): string
    {
        if (in_array($this->edgeWeightType, self::AVAILABLE_VERTEX_TYPES)) {
            return self::VERTEX_TYPE;
        }
        if (in_array($this->edgeWeightType, self::AVAILABLE_EDGE_TYPES, true)) {
            return self::EDGE_TYPE;
        }
        throw new RuntimeException("Unexpected graph type.");
    }
}
