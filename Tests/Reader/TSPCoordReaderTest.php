<?php

namespace Tests\Reader;

use PHPUnit\Framework\TestCase;
use Reader\TSPCoordReader;

class TSPCoordReaderTest extends TestCase
{
    public function testLoadVertexTypeFile(): void
    {
        $file = __DIR__ . '/../Files/TSP/ch150.tsp';
        $reader = new TSPCoordReader($file);
        $reader->load();
        self::assertEquals('ch150', $reader->name);
        self::assertEquals('TSP', $reader->type);
        self::assertEquals('150 city Problem (churritz)', $reader->comment);
        self::assertEquals('150', $reader->dimension);
        self::assertEquals('EUC_2D', $reader->edgeWeightType);
        self::assertCount(150, $reader->nodeCoords);
        self::assertEquals([37.4393516691, 541.2090699418], $reader->nodeCoords[0]);
        self::assertEquals([656.6943525585, 558.1109593509], $reader->nodeCoords[98]);
        self::assertEquals([91.6467647724, 166.3541158474], $reader->nodeCoords[149]);
    }

    public function testLoadEdgeTypeFile(): void
    {
        $file = __DIR__ . '/../Files/TSP/zeroPath.tsp';
        $reader = new TSPCoordReader($file);
        $reader->load();
        self::assertEquals('zeroPath', $reader->name);
        self::assertEquals('TSP', $reader->type);
        self::assertEquals('10 with one 0 route (Zbigniew Czarnecki)', $reader->comment);
        self::assertEquals('10', $reader->dimension);
        self::assertEquals('MATRIX', $reader->edgeWeightType);
        self::assertCount(10, $reader->nodeCoords);
        self::assertEquals([3, 0, 4, 72, 36, 25, 37, 120, 2, 15], $reader->nodeCoords[0]);
        self::assertEquals([33, 25, 49, 7, 3, 0, 7, 10, 2, 1], $reader->nodeCoords[4]);
        self::assertEquals([0, 53, 44, 17, 3, 2, 7, 10, 12, 15], $reader->nodeCoords[9]);
    }

    /**
     * @dataProvider loadLineProvider
     */
    public function testLoadLine(string $line, string $property, string $value): void
    {
        $reader = new TSPCoordReader('');
        $reader->loadLine($line);
        self::assertEquals($value, $reader->$property);
    }

    public function loadLineProvider(): array
    {
        return [
            [
                'line' => 'NAME: ch150',
                'property' => 'name',
                'value' => 'ch150',
            ],
            [
                'line' => 'NAME : ./TSPLIB/a280.tsp.optbc.tour',
                'property' => 'name',
                'value' => './TSPLIB/a280.tsp.optbc.tour',
            ],
            [
                'line' => 'TYPE: TSP',
                'property' => 'type',
                'value' => 'TSP',
            ],
            [
                'line' => 'COMMENT: 150 city Problem (churritz)',
                'property' => 'comment',
                'value' => '150 city Problem (churritz)',
            ],
            [
                'line' => 'DIMENSION: 150',
                'property' => 'dimension',
                'value' => '150',
            ],
            [
                'line' => 'EDGE_WEIGHT_TYPE: EUC_2D',
                'property' => 'edgeWeightType',
                'value' => 'EUC_2D',
            ],
            [
                'line' => 'NAME : att532',
                'property' => 'name',
                'value' => 'att532',
            ],
            [
                'line' => 'COMMENT: 532-city problem (Padberg/Rinaldi)',
                'property' => 'comment',
                'value' => '532-city problem (Padberg/Rinaldi)',
            ],
            [
                'line' => 'DIMENSION: 532',
                'property' => 'dimension',
                'value' => '532',
            ],
            [
                'line' => 'EDGE_WEIGHT_TYPE: ATT',
                'property' => 'edgeWeightType',
                'value' => 'ATT',
            ],
            [
                'line' => 'COMMENT: 29 Cities in Bavaria, geographical distances (Groetschel,Juenger,Reinelt)',
                'property' => 'comment',
                'value' => '29 Cities in Bavaria, geographical distances (Groetschel,Juenger,Reinelt)',
            ],
        ];
    }

    public function testLoadNodesVertexType(): void
    {
        $reader = new TSPCoordReader('');
        $reader->loadLine('EDGE_WEIGHT_TYPE: EUC_2D');
        $reader->loadLine(TSPCoordReader::NODE_COORD_SECTION);
        $reader->loadLine("5 143.0606355347 631.7200953923");
        $reader->loadLine("18 7890 5130");
        $reader->loadLine("  7 236 169");
        $reader->loadLine(" 22 148 169");
        $reader->loadLine("45 -16.11 -52.30");
        $reader->loadLine('EOF');
        self::assertEquals([
            [143.0606355347, 631.7200953923],
            [7890, 5130],
            [236, 169],
            [148, 169],
            [-16.11, -52.30],
            ], $reader->nodeCoords);
    }

    public function testLoadNodesEdgeType(): void
    {
        $reader = new TSPCoordReader('');
        $reader->loadLine('EDGE_WEIGHT_TYPE: MATRIX');
        $reader->loadLine(TSPCoordReader::EDGE_COORD_SECTION);
        $reader->loadLine("3 0 4 72 36 25 37 120 2 15");
        $reader->loadLine("31 5 0 71 3 2 7 -10 -12 15");
        $reader->loadLine("3 25 46 0 3 2 7 10 2.1 -1.14");
        $reader->loadLine('EOF');
        self::assertEquals([
            [3, 0, 4, 72, 36, 25, 37, 120, 2, 15],
            [31, 5, 0, 71, 3, 2, 7, -10, -12, 15],
            [3, 25, 46, 0, 3, 2, 7, 10, 2.1, -1.14],
        ], $reader->nodeCoords);
    }
}
