<?php declare(strict_types=1);

namespace Elastic\ScoutDriverPlus\Tests\Integration\Queries;

use Elastic\ScoutDriverPlus\Support\Query;
use Elastic\ScoutDriverPlus\Tests\App\Book;
use Elastic\ScoutDriverPlus\Tests\Integration\TestCase;

/**
 * @covers \Elastic\ScoutDriverPlus\Builders\AbstractParameterizedQueryBuilder
 * @covers \Elastic\ScoutDriverPlus\Builders\MultiMatchQueryBuilder
 * @covers \Elastic\ScoutDriverPlus\Engine
 * @covers \Elastic\ScoutDriverPlus\Factories\LazyModelFactory
 * @covers \Elastic\ScoutDriverPlus\Support\Query
 *
 * @uses   \Elastic\ScoutDriverPlus\Builders\SearchParametersBuilder
 * @uses   \Elastic\ScoutDriverPlus\Decorators\Hit
 * @uses   \Elastic\ScoutDriverPlus\Decorators\SearchResult
 * @uses   \Elastic\ScoutDriverPlus\Factories\DocumentFactory
 * @uses   \Elastic\ScoutDriverPlus\Factories\ParameterFactory
 * @uses   \Elastic\ScoutDriverPlus\Factories\RoutingFactory
 * @uses   \Elastic\ScoutDriverPlus\QueryParameters\ParameterCollection
 * @uses   \Elastic\ScoutDriverPlus\QueryParameters\Transformers\FlatArrayTransformer
 * @uses   \Elastic\ScoutDriverPlus\QueryParameters\Validators\AllOfValidator
 * @uses   \Elastic\ScoutDriverPlus\Searchable
 * @uses   \Elastic\ScoutDriverPlus\Support\ModelScope
 */
final class MultiMatchQueryTest extends TestCase
{
    public function test_models_can_be_found_using_fields_and_text(): void
    {
        // additional mixin
        factory(Book::class)
            ->state('belongs_to_author')
            ->create([
                'title' => 'mixin title',
                'description' => 'mixin description',
            ]);

        $target = factory(Book::class)
            ->state('belongs_to_author')
            ->create([
                'title' => 'foo',
                'description' => 'bar',
            ]);

        $query = Query::multiMatch()
            ->fields(['title', 'description'])
            ->query('foo bar');

        $found = Book::searchQuery($query)->execute();

        $this->assertFoundModel($target, $found);
    }
}
