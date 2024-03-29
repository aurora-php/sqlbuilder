<?php

declare(strict_types=1);

/*
 * This file is part of the 'octris/sqlbuilder' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris;

use \Octris\Sqlbuilder\AbstractDialect;
use \Octris\Sqlbuilder\Clauses;
use \Octris\Sqlbuilder\Template;

/**
 * SQL builder.
 *
 * @copyright   copyright (c) 2016-present by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Sqlbuilder
{
    /**
     * Instance of SQL dialect class.
     *
     * @var     AbstractDialect
     */
    protected AbstractDialect $dialect;

    /**
     * Template snippets.
     *
     * @var     array
     */
    protected array $snippets = [];

    /**
     * Clauses.
     *
     * @var     array
     */
    protected array $clauses = [];

    /**
     * Constructor.
     *
     * @param   \Octris\Sqlbuilder\AbstractDialect          $dialect    SQL dialect to use.
     */
    public function __construct(AbstractDialect $dialect)
    {
        $this->dialect = $dialect;
    }

    /**
     * Resolve template snippet.
     *
     * @param   string                      $name               Name of snippet to resolve.
     * @param   array                       $parameters         Parameters for resolving snippet.
     * @return  array                                           Resolved template snippet.
     */
    public function resolveSnippet(string $name, array &$parameters): string
    {
        $name = strtoupper($name);

        if (isset($this->clauses[$name])) {
            $snippet = $this->clauses[$name]->resolveClauses($parameters);
        } elseif (isset($this->snippets[$name])) {
            $snippet = $this->snippets[$name];
        } else {
            $snippet = '';
        }

        return $snippet;
    }

    /**
     * Resolve query parameter.
     *
     * @param   int                                 $idx        Position of the parameter in the query.
     * @param   string                              $type       Type of the parameter.
     * @param   string                              $name       Name of the parameter.
     * @return  string
     */
    public function resolveParameter(int $idx, string $type, string $name): string
    {
        return $this->dialect->resolveParameter($idx, $type, $name);
    }

    /**
     * Add a textual template snippet.
     *
     * @param   string                              $name       Name of snippet to add.
     * @param   string                              $snippet    Snippet content to add.
     * @return  \Octris\Sqlbuilder                  This instance for method chaining.
     */
    public function addSnippet(string $name, string $snippet): self
    {
        $this->snippets[strtoupper($name)] = $snippet;

        return $this;
    }

    /**
     * Add a template to the builder instance.
     *
     * @param   string                              $sql        SQL template to add.
     * @return  \Octris\Sqlbuilder\Template                     Instance of template class.
     */
    public function addTemplate(string $sql): Template
    {
        $instance = new Template($this, $sql);

        return $instance;
    }

    /**
     * Add clause.
     *
     * @param   string              $name                       Name of clause to add.
     * @param   string              $sql                        SQL snippet of clause.
     * @param   array               $parameters                 Parameters for clause.
     * @param   string              $joiner                     String to use for joining multiple clauses of the same name.
     * @param   string              $prefix                     Optional prefix string for joined clauses.
     * @param   string              $postfix                    Optional postfix string for joined clauses.
     * @param   bool                $is_inclusive               Optional clause mode.
     */
    protected function addClause(string $name, string $sql, array $parameters, string $joiner, string $prefix = '', string $postfix = '', bool $is_inclusive = false)
    {
        $name = strtoupper($name);

        if (!isset($this->clauses[$name])) {
            $this->clauses[$name] = new Clauses($joiner, $prefix, $postfix);
        }

        $this->clauses[$name]->addClause($sql, $parameters, $is_inclusive);
    }

    /**
     * Columns for eg.: INSERT/UPDATE
     *
     * @param   string                      $sql            SQL snippet of column.
     * @param   array                       $parameters     Optional parameters for 'where' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addColumn(string $sql, array $parameters = array()): self
    {
        $this->addClause('COLUMN', $sql, $parameters, ', ', '', "\n", false);

        return $this;
    }

    /**
     * Add an 'inner join' clause.
     *
     * @param   string                      $sql            SQL snippet for 'inner join'.
     * @param   array                       $parameters     Optional parameters for 'inner join' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addInnerJoin(string $sql, array $parameters = array()): self
    {
        $this->addClause('INNERJOIN', $sql, $parameters, "\nINNER JOIN ", "\nINNER JOIN ", "\n", false);

        return $this;
    }

    /**
     * Add an 'join' clause (alias for 'inner join').
     *
     * @param   string                      $sql            SQL snippet for 'join'.
     * @param   array                       $parameters     Optional parameters for 'join' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addJoin(string $sql, array $parameters = array()): self
    {
        $this->addClause('JOIN', $sql, $parameters, "\nJOIN ", "\nJOIN ", "\n", false);

        return $this;
    }

    /**
     * Add a 'left join' clause.
     *
     * @param   string                      $sql            SQL snippet for 'left join'.
     * @param   array                       $parameters     Optional parameters for 'left join' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addLeftJoin(string $sql, array $parameters = array()): self
    {
        $this->addClause('LEFTJOIN', $sql, $parameters, "\nLEFT JOIN ", "\nLEFT JOIN ", "\n", false);

        return $this;
    }

    /**
     * Add a 'right join' clause.
     *
     * @param   string                      $sql            SQL snippet for 'right join'.
     * @param   array                       $parameters     Optional parameters for 'right' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addRightJoin(string $sql, array $parameters = array()): self
    {
        $this->addClause('RIGHTJOIN', $sql, $parameters, "\nRIGHT JOIN ", "\nRIGHT JOIN ", "\n", false);

        return $this;
    }

    /**
     * Add an 'AND' condition. This method is an alias for "addAndWhere".
     *
     * @param   string                      $sql            SQL snippet for 'where' condition.
     * @param   array                       $parameters     Optional parameters for 'where' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addWhere(string $sql, array $parameters = array()): self
    {
        return $this->addAndWhere($sql, $parameters);
    }

    /**
     * Add an 'AND' condition.
     *
     * @param   string                      $sql            SQL snippet for 'where' condition.
     * @param   array                       $parameters     Optional parameters for 'where' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addAndWhere(string $sql, array $parameters = array()): self
    {
        $this->addClause('WHERE', $sql, $parameters, ' AND ', 'WHERE ', "\n", false);

        return $this;
    }

    /**
     * Add an 'OR' condition.
     *
     * @param   string                      $sql            SQL snippet for 'where' condition.
     * @param   array                       $parameters     Optional parameters for 'where' clause.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addOrWhere(string $sql, array $parameters = array()): self
    {
        $this->addClause('WHERE', $sql, $parameters, ' AND ', 'WHERE ', "\n", true);

        return $this;
    }

    /**
     * Add an 'order by' clause.
     *
     * @param   string                      $sql            SQL snippet for 'order by'.
     * @param   array                       $parameters     Optional parameters for 'order by'.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addOrderBy(string $sql, array $parameters = array()): self
    {
        $this->addClause('ORDERBY', $sql, $parameters, ', ', 'ORDER BY ', "\n", false);

        return $this;
    }

    /**
     * Add a 'group by' clause.
     *
     * @param   string                      $sql            SQL snippet for 'group by'.
     * @param   array                       $parameters     Optional parameters for 'group by'.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addGroupBy(string $sql, array $parameters = array()): self
    {
        $this->addClause('GROUPBY', $sql, $parameters, ', ', 'GROUP BY ', "\n", false);

        return $this;
    }

    /**
     * Add a 'having' clause.
     *
     * @param   string                      $sql            SQL snippet for 'having'.
     * @param   array                       $parameters     Optional parameters for 'having'.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addHaving(string $sql, array $parameters = array()): self
    {
        $this->addClause('HAVING', $sql, $parameters, "\nAND ", 'HAVING ', "\n", false);

        return $this;
    }

    /**
     * Add paging.
     *
     * @param   int                         $limit          Limit rows to return.
     * @param   int                         $page           Optional page to start querying at.
     * @return  \Octris\Sqlbuilder                          This instance for method chaining.
     */
    public function addPaging(int $limit, int $page = 1): self
    {
        $this->addClause('PAGING', $this->dialect->getLimitString($limit, ($page - 1) * $limit), [], '', '', "\n", false);

        return $this;
    }
}
