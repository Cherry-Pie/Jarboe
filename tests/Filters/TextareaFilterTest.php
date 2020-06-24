<?php

namespace Yaro\Jarboe\Tests\Filters;

use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Table\Filters\AbstractFilter;
use Yaro\Jarboe\Table\Filters\TextareaFilter;

class TextareaFilterTest extends AbstractFilterTest
{
    protected function filter(): AbstractFilter
    {
        return TextareaFilter::make();
    }

    /**
     * @test
     */
    public function check_like()
    {
        $likeProperty = "\x00*\x00like";
        $filter = $this->filter();
        $data = (array) $filter;
        $this->assertSame([
            'left'  => true,
            'right' => true,
        ], $data[$likeProperty]);

        $filter->like(false);
        $data = (array) $filter;
        $this->assertSame([
            'left'  => false,
            'right' => true,
        ], $data[$likeProperty]);

        $filter->like(true, false);
        $data = (array) $filter;
        $this->assertSame([
            'left'  => true,
            'right' => false,
        ], $data[$likeProperty]);

        $filter->like(false, false);
        $data = (array) $filter;
        $this->assertSame([
            'left'  => false,
            'right' => false,
        ], $data[$likeProperty]);
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
                'name' => 'where',
                'arguments' => [
                    'title',
                    'like',
                    '%'. $value .'%',
                ],
            ],
        ], $queryCollector->calls());


        $filter->like(true, false);
        $filter->value($value);
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    'like',
                    '%'. $value,
                ],
            ],
        ], $queryCollector->calls());


        $filter->like(false, true);
        $filter->value($value);
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    'like',
                    $value .'%',
                ],
            ],
        ], $queryCollector->calls());


        $filter->like(false, false);
        $filter->value($value);
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    '=',
                    $value,
                ],
            ],
        ], $queryCollector->calls());


        $filter->sign('>');
        $filter->like(false, false);
        $filter->value($value);
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    '>',
                    $value,
                ],
            ],
        ], $queryCollector->calls());
    }
}
