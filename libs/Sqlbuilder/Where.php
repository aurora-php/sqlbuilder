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
 * Class implementing where clause
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Where
{
    /**
     * Where condition groups.
     *
     * @type    array
     */
    protected $groups = array();

    /**
     * Clauses.
     *
     * @type    array
     */
    protected $clauses = array();

    /**
     * Parent group.
     *
     * @type    \Octris\Sqlbuilder\Group|null
     */
    protected $parent;

    /**
     * Type of group.
     *
     * @type    string
     */
    protected $type;

    /**
     * Constructor.
     */
    public function __construct($type, $parent = null)
    {
        $this->type = $type;
        $this->parent = $parent;
    }

    /**
     * Render "where" conditions to snippet.
     *
     * @return  string                              Rendered snippet.
     */
    public function build()
    {
        $return = '(' . implode(' ' . $this->type . ' ', $this->clauses) . ')';

        return $return;
    }

    /**
     * Add an 'AND' condition group.
     *
     * @return \Octris\Sqlbuilder\Where             New and nested instance of "where" condition group.
     */
    public function addAndWhere()
    {
        $instance = new static('AND', $this);

        $this->groups[] = $instance;

        return $instance;
    }

    /**
     * Add an 'OR' condition group.
     *
     * @return \Octris\Sqlbuilder\Where             New and nested instance of "where" condition group.
     */
    public function addOrWhere()
    {
        $instance = new static('OR', $this);

        $this->groups[] = $instance;

        return $instance;
    }

    /**
     * Add a where clause.
     *
     * @param   string          $name               Parameter name for where clause.
     * @param   string          $clause             Clause to add.
     * @return  \Octris\Sqlbuilder\Where            Instance of current "where" condition group.
     */
    public function addClause($name, $where)
    {
        $this->clauses[$name] = $where;

        return $this;
    }

    /**
     * Move up a group.
     *
     * @return \Octris\Sqlbuilder\Where             Instance of "where" condition group.
     */
    public function up()
    {
        if (!is_null($this->parent)) {
            return $this->parent;
        }

        throw new \Exception('Already at top group level');
    }
}
