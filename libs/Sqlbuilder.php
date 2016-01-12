<?php

/*
 * This file is part of the 'octris/sqlbuilder' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris;

/**
 * SQL builder.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Sqlbuilder
{
    /**
     * Database connection.
     *
     * @type    \Octris\Core\Db\Device\IConnection
     */
    protected $cn;

    /**
     * Clauses.
     *
     * @type    array
     */
    protected $clauses = array();

    /**
     * Constructor.
     *
     * @param   \Octris\Core\Db\Device\IConnection  $cn         Database connection to use with generated sql statement.
     */
    public function __construct(\Octris\Core\Db\Device\IConnection $cn)
    {
        $this->cn = $cn;
    }

    /**
     * Add a template to the builder instance.
     *
     * @param   string                              $sql        SQL template to add.
     * @return  \Octris\Sqlbuilder\Template                     Instance of template class.
     */
    public function addTemplate($sql)
    {
        return new \Octris\Sqlbuilder\Template($this, $sql);
    }

    /**
     * Add where clauses, alias for addAndWhere.
     *
     * @return  \Octris\Sqlbuilder\Where                        Instance of "where" group.
     */
    public function addWhere();
    {
        return $this->addAndWhere();
    }

    /**
     * Add an 'AND' condition group.
     *
     * @return \Octris\Sqlbuilder\Where             New and nested instance of "where" condition group.
     */
    public function addAndWhere()
    {
        $instance = new \Octris\Sqlbuilder\Where('AND');

        $this->clauses[] = $instance;

        return $instance;
    }

    /**
     * Add an 'OR' condition group.
     *
     * @return \Octris\Sqlbuilder\Where             New and nested instance of "where" condition group.
     */
    public function addOrWhere()
    {
        $instance = new \Octris\Sqlbuilder\Where('OR');

        $this->clauses[] = $instance;

        return $instance;
    }
}
