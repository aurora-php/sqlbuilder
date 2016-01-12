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
     * Parameters for prepared SQL statement.
     *
     * @type    array
     */
    protected $data;

    /**
     * Template snippets.
     *
     * @type    array
     */
    protected $snippets = array();

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
     * @param   array                               $data       Optional parameters for prepared SQL statement.
     */
    public function __construct(\Octris\Core\Db\Device\IConnection $cn, array $data = array())
    {
        $this->cn = $cn;
        $this->data = $data;
    }

    /**
     * Build template snippet.
     *
     * @param   string                              $name       Name of snippet to return.
     * @return  string                                          Template snippet.
     */
    public function build($name)
    {
        if (isset($this->clauses[$name])) {
            $return = $this->clauses[$name]->build();
        } elseif (isset($this->snippets[$name])) {
            $return = $this->snippets[$name];
        } else {
            $return = '';
        }

        return $return;
    }

    /**
     * Add a textual snippet.
     *
     * @param   string                              $name       Name of snippet to add.
     * @param   string                              $snippet    Snippet content to add.
     */
    public function addSnippet($name, $snippet)
    {
        $this->snippets[$name] = $snippet;
    }

    /**
     * Add a template to the builder instance.
     *
     * @param   string                              $sql        SQL template to add.
     * @return  \Octris\Sqlbuilder\Template                     Instance of template class.
     */
    public function addTemplate($sql)
    {
        $instance = new \Octris\Sqlbuilder\Template($this, $sql);

        return $instance;
    }

    /**
     * Add clause.
     *
     * @param   string              $name                       Name of clause to add.
     */
    protected function addClause($name, $instance, $joiner, $prefix, $postfix)
    {
        $this->clauses[$name] = new \Octris\Sqlbuilder\Clause($instance, $joiner, $prefix, $postfix);
    }

    /**
     * Add where clauses, alias for addAndWhere.
     *
     * @return  \Octris\Sqlbuilder\Where                        Instance of "where" group.
     */
    public function addWhere()
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

        $this->addClause('WHERE', $instance, '', 'WHERE ', '');

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

        $this->addClause('WHERE', $instance, '', 'WHERE ', '');

        return $instance;
    }

    /**
     * Add paging clause.
     *
     * @param   int             $page               Page to start querying.
     * @param   int             $limit              Limit rows to return.
     */
    public function addPaging($page, $limit)
    {
        $this->addClause('PAGING', null, '', sprintf('LIMIT %d, %d', ($page - 1) * $limit, $limit), '');
    }

    /**
     * Execute SQL statement.
     */
    public function execute(\Octris\Sqlbuilder\Template $tpl)
    {
        $sql = (string)$tpl;

        $types = '';
        $values = [];

        $sql = preg_replace_callback('/@(?P<type>.):(?P<name>.+?)@/', function($match) use (&$types, &$values) {
            $types .= $match['type'];
            $values[] = $this->data[$match['name']];

            return '?';
        }, $sql);

        $stmt = $this->cn->prepare($sql);
        $stmt->bindParam($types, $values);

        return $stmt->execute();
    }
}
