<?php

use Yaro\Jarboe\Tests\AbstractBaseTest;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\Breadcrumbs;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\BreadcrumbsInterface;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\Crumb;

class BreadcrumbsTest extends AbstractBaseTest
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__ . '/../database/migrations'),
        ]);

        $this->breadcrumbs = new Breadcrumbs();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'testing');
    }

    /**
     * @test
     */
    public function check_breadcrumbs_creation()
    {
        $this->assertInstanceOf(BreadcrumbsInterface::class, $this->breadcrumbs);
    }

    /**
     * @test
     */
    public function check_breadcrumbs_default_crumbs()
    {
        $this->assertTrue($this->breadcrumbs->isEmptyForListPage());
        $this->assertTrue($this->breadcrumbs->isEmptyForEditPage());
        $this->assertTrue($this->breadcrumbs->isEmptyForCreatePage());
    }

    /**
     * @test
     */
    public function check_list_page_crumbs()
    {
        $this->breadcrumbs->add(Crumb::make()->showOnlyOnListPage());

        $this->assertFalse($this->breadcrumbs->isEmptyForListPage());
        $this->assertTrue($this->breadcrumbs->isEmptyForCreatePage());
        $this->assertTrue($this->breadcrumbs->isEmptyForEditPage());
    }

    /**
     * @test
     */
    public function check_create_page_crumbs()
    {
        $this->breadcrumbs->add(Crumb::make()->showOnlyOnCreatePage());

        $this->assertTrue($this->breadcrumbs->isEmptyForListPage());
        $this->assertFalse($this->breadcrumbs->isEmptyForCreatePage());
        $this->assertTrue($this->breadcrumbs->isEmptyForEditPage());
    }

    /**
     * @test
     */
    public function check_edit_page_crumbs()
    {
        $this->breadcrumbs->add(Crumb::make()->showOnlyOnEditPage());

        $this->assertTrue($this->breadcrumbs->isEmptyForListPage());
        $this->assertTrue($this->breadcrumbs->isEmptyForCreatePage());
        $this->assertFalse($this->breadcrumbs->isEmptyForEditPage());
    }

    /**
     * @test
     */
    public function check_crumb_show_everywhere_by_default()
    {
        $this->breadcrumbs->add(Crumb::make());

        $this->assertFalse($this->breadcrumbs->isEmptyForListPage());
        $this->assertFalse($this->breadcrumbs->isEmptyForCreatePage());
        $this->assertFalse($this->breadcrumbs->isEmptyForEditPage());
    }

    /**
     * @test
     */
    public function check_crumb()
    {
        $crumb = Crumb::make();

        $crumb->showOnListPage(false);
        $crumb->showOnCreatePage(false);
        $crumb->showOnEditPage(false);

        $this->assertFalse($crumb->shouldBeShownOnListPage());
        $this->assertFalse($crumb->shouldBeShownOnCreatePage());
        $this->assertFalse($crumb->shouldBeShownOnEditPage());


        $crumb->showOnListPage(true);
        $crumb->showOnCreatePage(true);
        $crumb->showOnEditPage(true);

        $this->assertTrue($crumb->shouldBeShownOnListPage());
        $this->assertTrue($crumb->shouldBeShownOnCreatePage());
        $this->assertTrue($crumb->shouldBeShownOnEditPage());


        $crumb->showOnlyOnListPage();

        $this->assertTrue($crumb->shouldBeShownOnListPage());
        $this->assertFalse($crumb->shouldBeShownOnCreatePage());
        $this->assertFalse($crumb->shouldBeShownOnEditPage());


        $crumb->showOnlyOnCreatePage();

        $this->assertFalse($crumb->shouldBeShownOnListPage());
        $this->assertTrue($crumb->shouldBeShownOnCreatePage());
        $this->assertFalse($crumb->shouldBeShownOnEditPage());


        $crumb->showOnlyOnEditPage();

        $this->assertFalse($crumb->shouldBeShownOnListPage());
        $this->assertFalse($crumb->shouldBeShownOnCreatePage());
        $this->assertTrue($crumb->shouldBeShownOnEditPage());
    }

    /**
     * @test
     */
    public function check_crumb_title()
    {
        $crumb = Crumb::make()->title('hai');
        $this->assertEquals('hai', $crumb->getTitle());

        $crumb = Crumb::make('hai');
        $this->assertEquals('hai', $crumb->getTitle());

        $crumb = Crumb::make()->title(function () {
            return 'oh hai';
        });
        $this->assertEquals('oh hai', $crumb->getTitle());

        $model = \Yaro\Jarboe\Tests\Models\Model::first();
        $crumb = Crumb::make('hello')->title(function ($model) {
            return $model->title;
        });
        $this->assertEquals($model->title, $crumb->getTitle($model));
    }

    /**
     * @test
     */
    public function check_crumb_url()
    {
        $crumb = Crumb::make()->url('hai');
        $this->assertEquals('hai', $crumb->getUrl());

        $crumb = Crumb::make('title', 'hai');
        $this->assertEquals('hai', $crumb->getUrl());

        $crumb = Crumb::make()->url(function () {
            return 'oh hai';
        });
        $this->assertEquals('oh hai', $crumb->getUrl());

        $model = \Yaro\Jarboe\Tests\Models\Model::first();
        $crumb = Crumb::make('hello', 'hello')->url(function ($model) {
            return $model->title;
        });
        $this->assertEquals($model->title, $crumb->getUrl($model));
    }

    /**
     * @test
     */
    public function check_breadcrumbs_iterator()
    {
        $first = Crumb::make('first');
        $second = Crumb::make('second');
        $this->breadcrumbs->add($first)->add($second);

        $this->assertEquals(0, $this->breadcrumbs->key());
        $this->assertEquals($first, $this->breadcrumbs->current());
        $this->assertTrue($this->breadcrumbs->valid());
        $this->breadcrumbs->next();
        $this->assertEquals(1, $this->breadcrumbs->key());
        $this->assertEquals($second, $this->breadcrumbs->current());
        $this->assertTrue($this->breadcrumbs->valid());
        $this->breadcrumbs->next();
        $this->assertEquals(2, $this->breadcrumbs->key());
        $this->assertFalse($this->breadcrumbs->valid());
        $this->breadcrumbs->rewind();
        $this->assertEquals(0, $this->breadcrumbs->key());
        $this->assertEquals($first, $this->breadcrumbs->current());
        $this->assertTrue($this->breadcrumbs->valid());
    }
}
