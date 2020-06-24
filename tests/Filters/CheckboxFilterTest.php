<?php

namespace Yaro\Jarboe\Tests\Filters;

use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Table\Filters\AbstractFilter;
use Yaro\Jarboe\Table\Filters\CheckboxFilter;

class CheckboxFilterTest extends AbstractFilterTest
{
    protected function filter(): AbstractFilter
    {
        return CheckboxFilter::make();
    }

    /**
     * @test
     */
    public function check_get_value()
    {
        $ident = 'table_identifier';
        $field = Text::make('title');
        $filter = $this->filter();
        $filter->field($field);

        $this->assertSame(CheckboxFilter::NO_INPUT_APPLIED, $filter->getValue($ident));

        $value = 'el hopaness romtic';
        $this->saveSearchValue($ident, $field->name(), $value);

        $this->assertEquals($value, $filter->getValue($ident));
    }

    /**
     * @test
     */
    public function check_get_checked_title()
    {
        /** @var CheckboxFilter $filter */
        $filter = $this->filter();

        $this->assertNotEmpty($filter->getCheckedTitle());
        $this->assertIsString($filter->getCheckedTitle());

        $title = 'some-random-title';
        $filter = $filter->titles($title);

        $this->assertInstanceOf(CheckboxFilter::class, $filter);
        $this->assertSame($title, $filter->getCheckedTitle());
    }

    /**
     * @test
     */
    public function check_get_unchecked_title()
    {
        /** @var CheckboxFilter $filter */
        $filter = $this->filter();

        $this->assertNotEmpty($filter->getUncheckedTitle());
        $this->assertIsString($filter->getUncheckedTitle());

        $title = 'some-random-title';
        $filter = $filter->titles(null, $title);

        $this->assertInstanceOf(CheckboxFilter::class, $filter);
        $this->assertSame($title, $filter->getUncheckedTitle());
    }

    /**
     * @test
     */
    public function check_get_desearch_title()
    {
        /** @var CheckboxFilter $filter */
        $filter = $this->filter();

        $this->assertNotEmpty($filter->getDesearchTitle());
        $this->assertIsString($filter->getDesearchTitle());

        $title = 'some-random-title';
        $filter = $filter->titles(null, null, $title);

        $this->assertInstanceOf(CheckboxFilter::class, $filter);
        $this->assertSame($title, $filter->getDesearchTitle());
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

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    '=',
                    null,
                ],
            ],
        ], $queryCollector->calls());


        $filter->value(CheckboxFilter::NO_INPUT_APPLIED);
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
                    '=',
                    $value,
                ],
            ],
        ], $queryCollector->calls());


        $filter->value(false);
        $filter->field(Text::make('title')->nullable());
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    '=',
                    null,
                ],
            ],
        ], $queryCollector->calls());


        $filter->value(false);
        $filter->field(Text::make('title'));
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    '=',
                    false,
                ],
            ],
        ], $queryCollector->calls());


        $filter->value(0);
        $filter->field(Text::make('title'));
        $queryCollector = $this->queryCollector();
        $filter->apply($queryCollector);

        $this->assertSame([
            [
                'name' => 'where',
                'arguments' => [
                    'title',
                    '=',
                    0,
                ],
            ],
        ], $queryCollector->calls());
    }
}
