<?php

/*
 * This file is part of the 'octris/sqlbuilder' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris\Sqlbuilder;

/**
 * SQL builder clauses.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Clauses
{
    /**
     * String to use for joining multiple clauses.
     *
     * @type    string
     */
    protected $joiner;

    /**
     * Prefix string for joined clauses.
     *
     * @type    string
     */
    protected $prefix;

    /**
     * Postfix string for joined clauses.
     *
     * @type    string
     */
    protected $postfix;

    /**
     * Clauses.
     *
     * @type    array
     */
    protected $clauses;

    /**
     * Parameters.
     *
     * @type    array
     */
    protected $parameters = array();

    /**
     * Constructor.
     *
     * @param   string              $joiner                     String to use for joining multiple clauses.
     * @param   string              $prefix                     Prefix string for joined clauses.
     * @param   string              $postfix                    Postfix string for joined clauses.
     */
    public function __construct($joiner, $prefix, $postfix)
    {
        $this->joiner = $joiner;
        $this->prefix = $prefix;
        $this->postfix = $postfix;

        $this->clauses = [
            true => [],
            false => []
        ];
    }

    /**
     * Resolve clauses.
     *
     * @return  array                                           Array of resolved template snippet and parameters.
     */
    public function resolveClauses()
    {
        if (count($this->clauses[true]) > 0) {
            $snippet = $this->prefix . implode(
                $this->joiner,
                array_merge(
                    $this->clauses[false],
                    [
                        ' ( ' . implode(' OR ', $this->clauses[true]) . ' ) '
                    ]
                )
            ) . $this->postfix;
        } elseif (count($this->clauses[false]) > 0) {
            $snippet = $this->prefix . implode($this->joiner, $this->clauses[false]) . $this->postfix;
        } else {
            $snippet = '';
        }

        return [$snippet, $this->parameters];
    }

    /**
     * Add a clause to the list of clauses.
     *
     * @param   string              $sql                        SQL of clause to add.
     * @param   array               $parameters                 Parameters for clause.
     * @param   bool                $is_inclusive               Clause mode.
     */
    public function addClause($sql, array $parameters, $is_inclusive)
    {
        $this->clauses[$is_inclusive][] = $sql;

        $this->parameters = array_merge($this->parameters, $parameters);
    }
}
