<?php

namespace Yaro\Jarboe\Tests\Filters;

use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Table\Filters\AbstractFilter;
use Yaro\Jarboe\Table\Filters\DateFilter;
use Yaro\Jarboe\Table\Filters\TextFilter;

class DateFilterTest extends AbstractFilterTest
{
    protected function filter(): AbstractFilter
    {
        return DateFilter::make();
    }

    /**
     * @test
     */
    public function check_range()
    {
        $filter = $this->filter();
        $filter->field(Text::make('title'));

        $this->assertFalse($filter->isRange());

        $filter->range();
        $this->assertTrue($filter->isRange());

        $filter->range(false);
        $this->assertFalse($filter->isRange());

        $filter->range(true);
        $this->assertTrue($filter->isRange());
    }

    /**
     * @test
     */
    public function check_apply_without_value()
    {
        $filter = $this->filter();
        $filter->field(Text::make('title'));

        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([], $queryCollector->calls());
    }

    /**
     * @test
     */
    public function check_apply_with_value()
    {
        $value = 'sasa lele';
        $filter = $this->filter();
        $filter->field(Text::make('title'));

        $filter->value($value);
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'whereDate',
                'arguments' => [
                    'title',
                    '=',
                    $value,
                ],
            ],
        ], $queryCollector->calls());


        $value = [
            'from' => '1111-11-11',
            'to' => '1212-12-12',
        ];
        $filter->range();
        $filter->value($value);
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $calls = $queryCollector->calls();
        $fromCall = $calls[0];
        $toCall = $calls[1];

        $this->assertSame('when', $fromCall['name']);
        $this->assertSame($value['from'], $fromCall['arguments'][0]);

        $this->assertSame('when', $toCall['name']);
        $this->assertSame($value['to'], $toCall['arguments'][0]);


        $queryCollector = $this->queryCollector();
        $closure = $fromCall['arguments'][1];
        $closure($queryCollector, $value['from']);

        $this->assertSame([
            [
                'name' => 'whereDate',
                'arguments' => [
                    'title',
                    '>=',
                    $value['from'],
                ],
            ],
        ], $queryCollector->calls());

        $queryCollector = $this->queryCollector();
        $closure = $toCall['arguments'][1];
        $closure($queryCollector, $value['to']);

        $this->assertSame([
            [
                'name' => 'whereDate',
                'arguments' => [
                    'title',
                    '<=',
                    $value['to'],
                ],
            ],
        ], $queryCollector->calls());
    }
}
