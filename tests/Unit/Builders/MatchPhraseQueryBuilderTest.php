<?php declare(strict_types=1);

namespace Elastic\ScoutDriverPlus\Tests\Unit\Builders;

use Elastic\ScoutDriverPlus\Builders\MatchPhraseQueryBuilder;
use Elastic\ScoutDriverPlus\Exceptions\QueryBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Elastic\ScoutDriverPlus\Builders\AbstractParameterizedQueryBuilder
 * @covers \Elastic\ScoutDriverPlus\Builders\MatchPhraseQueryBuilder
 *
 * @uses   \Elastic\ScoutDriverPlus\QueryParameters\ParameterCollection
 * @uses   \Elastic\ScoutDriverPlus\QueryParameters\Transformers\GroupedArrayTransformer
 * @uses   \Elastic\ScoutDriverPlus\QueryParameters\Validators\AllOfValidator
 */
final class MatchPhraseQueryBuilderTest extends TestCase
{
    private MatchPhraseQueryBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new MatchPhraseQueryBuilder();
    }

    public function test_exception_is_thrown_when_field_is_not_specified(): void
    {
        $this->expectException(QueryBuilderException::class);

        $this->builder
            ->query('this is a test')
            ->buildQuery();
    }

    public function test_exception_is_thrown_when_text_is_not_specified(): void
    {
        $this->expectException(QueryBuilderException::class);

        $this->builder
            ->field('message')
            ->buildQuery();
    }

    public function test_query_with_field_and_text_can_be_built(): void
    {
        $expected = [
            'match_phrase' => [
                'message' => [
                    'query' => 'this is a test',
                ],
            ],
        ];

        $actual = $this->builder
            ->field('message')
            ->query('this is a test')
            ->buildQuery();

        $this->assertSame($expected, $actual);
    }

    public function test_query_with_field_and_text_and_slop_can_be_built(): void
    {
        $expected = [
            'match_phrase' => [
                'message' => [
                    'query' => 'this is a test',
                    'slop' => 0,
                ],
            ],
        ];

        $actual = $this->builder
            ->field('message')
            ->query('this is a test')
            ->slop(0)
            ->buildQuery();

        $this->assertSame($expected, $actual);
    }

    public function test_query_with_field_and_text_and_analyzer_can_be_built(): void
    {
        $expected = [
            'match_phrase' => [
                'message' => [
                    'query' => 'this is a test',
                    'analyzer' => 'english',
                ],
            ],
        ];

        $actual = $this->builder
            ->field('message')
            ->query('this is a test')
            ->analyzer('english')
            ->buildQuery();

        $this->assertSame($expected, $actual);
    }

    public function test_query_with_field_and_text_and_zero_terms_query_can_be_built(): void
    {
        $expected = [
            'match_phrase' => [
                'message' => [
                    'query' => 'this is a test',
                    'zero_terms_query' => 'none',
                ],
            ],
        ];

        $actual = $this->builder
            ->field('message')
            ->query('this is a test')
            ->zeroTermsQuery('none')
            ->buildQuery();

        $this->assertSame($expected, $actual);
    }
}
