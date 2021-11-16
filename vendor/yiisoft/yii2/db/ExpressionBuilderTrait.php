<?php
/**
 */

namespace yii\db;

/**
 * Trait ExpressionBuilderTrait provides common constructor for classes that
 * should implement [[ExpressionBuilderInterface]]
 *
 * @since 2.0.14
 */
trait ExpressionBuilderTrait
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * ExpressionBuilderTrait constructor.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
}
