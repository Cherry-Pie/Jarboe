<?php

namespace Yaro\Jarboe\Tests\Filters;

use Illuminate\View\View;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Table\Filters\AbstractFilter;
use Yaro\Jarboe\Tests\AbstractBaseTest;
use Yaro\Jarboe\Tests\Helpers\QueryCollector;

abstract class AbstractFilterTest extends AbstractBaseTest
{
    abstract protected function filter(): AbstractFilter;

    protected function saveSearchValue($tableIdentifier, $name, $value)
    {
        $key = sprintf('jarboe.%s.search.%s', $tableIdentifier, $name);

        session([
            $key => $value
        ]);
    }

    protected function queryCollector()
    {
        return new QueryCollector();
    }

    /**
     * @test
     */
    public function check_render()
    {
        $filter = $this->filter();

        $this->assertInstanceOf(View::class, $filter->render());
    }

    /**
     * @test
     */
    public function check_valid_field()
    {
        $field = Text::make('title');
        $filter = $this->filter();

        $filter->field($field);

        $this->assertSame($field, $filter->field());
    }

    /**
     * @test
     */
    public function check_invalid_field()
    {
        $this->expectException(\TypeError::class);

        $this->filter()->field(new \stdClass());
    }

    /**
     * @test
     */
    public function check_value()
    {
        $filter = $this->filter();

        $this->assertNull($filter->value());

        $value = 'sasa lele';
        $filter->value($value);
        $this->assertSame($value, $filter->value());
    }

    /**
     * @test
     */
    public function check_sign()
    {
        $signProperty = "\x00*\x00sign";
        $filter = $this->filter();
        $data = (array) $filter;
        $this->assertSame('=', $data[$signProperty]);

        $filter->sign('>');
        $data = (array) $filter;
        $this->assertSame('>', $data[$signProperty]);
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

        $this->assertNull($filter->getValue($ident));

        $value = 'el hopaness romtic';
        $this->saveSearchValue($ident, $field->name(), $value);

        $this->assertEquals($value, $filter->getValue($ident));
    }
}
