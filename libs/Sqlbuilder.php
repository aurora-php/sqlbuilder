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
     * Instance of SQL dialect class.
     *
     * @type    \Octris\Sqlbuilder\Dialect
     */
    protected $dialect;

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
     */
    public function __construct(\Octris\Core\Db\Device\IConnection $cn)
    {
        if (!($cn instanceof \Octris\Core\Db\Device\IDialect)) {
            throw new \InvalidArgumentException(get_class($cn) . ' must be a member of "\Octris\Core\Db\Device\IDialect"');
        }

        $this->cn = $cn;
    }

    /**
     * Resolve template snippet.
     *
     * @param   string                              $name       Name of snippet to resolve.
     * @param   array                               $param      Optional query parameters.
     * @return  string                                          Resolved template snippet.
     */
    public function resolveSnippet($name, array $param = array())
    {
        $name = strtoupper($name);

        if (isset($this->clauses[$name])) {
            $return = $this->clauses[$name]->resolveClauses($param);
        } elseif (isset($this->snippets[$name])) {
            $return = $this->snippets[$name];
        } else {
            $return = '';
        }

        return $return;
    }

    /**
     * Add a textual template snippet.
     *
     * @param   string                              $name       Name of snippet to add.
     * @param   string                              $snippet    Snippet content to add.
     * @return  \Octris\Sqlbuilder                  This instance for method chaining.
     */
    public function addSnippet($name, $snippet)
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
    public function addTemplate($sql)
    {
        $instance = new \Octris\Sqlbuilder\Template($this, $sql);

        return $instance;
    }

    /**
     * Add clause.
     *
     * @param   string              $name                       Name of clause to add.
     * @param   string              $sql                        SQL snippet of clause.
     * @param   string              $joiner                     String to use for joining multiple clauses of the same name.
     * @param   string              $prefix                     Prefix string for joined clauses.
     * @param   string              $postfix                    Postfix string for joined clauses.
     * @param   bool                $is_inclusive               Clause mode.
     */
    protected function addClause($name, $sql, $joiner, $prefix, $postfix, $is_inclusive)
    {
        $name = strtoupper($name);

        if (!isset($this->clauses[$name])) {
            $this->clauses[$name] = new \Octris\Sqlbuilder\Clauses($joiner, $prefix, $postfix);
        }

        $this->clauses[$name]->addClause($sql, $is_inclusive);
    }

    /**
     * Columns for eg.: INSERT/UPDATE
     *
     * @param   string                      $sql
     * @return  \Octris\Sqlbuilder                  This instance for method chaining.
     */
    public function addColumn($sql)
    {
        $this->addClause('COLUMN', $sql, ', ', '', "\n", false);

        return $this;
    }

    /**
     * Add an 'AND' condition. This method is an alias for "addAndWhere".
     *
     * @param   string                      $sql    SQL snippet for where condition.
     * @return  \Octris\Sqlbuilder                  This instance for method chaining.
     */
    public function addWhere($sql)
    {
        return $this->addAndWhere($sql);
    }

    /**
     * Add an 'AND' condition.
     *
     * @param   string                      $sql    SQL snippet for where condition.
     * @return  \Octris\Sqlbuilder                  This instance for method chaining.
     */
    public function addAndWhere($sql)
    {
        $this->addClause('WHERE', $sql, ' AND ', 'WHERE ', "\n", false);

        return $this;
    }

    /**
     * Add an 'OR' condition.
     *
     * @param   string                      $sql    SQL snippet for where condition.
     * @return  \Octris\Sqlbuilder                  This instance for method chaining.
     */
    public function addOrWhere($sql)
    {
        $this->addClause('WHERE', $sql, ' AND ', 'WHERE ', "\n", true);

        return $this;
    }

    /**
     * Add paging clause.
     *
     * @param   int             $page               Page to start querying.
     * @param   int             $limit              Limit rows to return.
     * @return  \Octris\Sqlbuilder                  This instance for method chaining.
     */
    public function addPaging($page, $limit)
    {
        if (isset($this->clauses['PAGING'])) {
            throw new \Exception('Only one paging can be defined');
        }

        $this->addClause('PAGING', $this->cn->getLimitString(($page - 1) * $limit, $limit), '', '', "\n", false);

        return $this;
    }

    /**
     * Build and execute SQL statement.
     *
     * @param   \Octris\Sqlbuilder\Template $tpl        $tpl        Template instance to transform into a SQL statement and execute.
     * @param   array                                   $param      Optional query parameters.
     * @return  \Octris\Core\Db\Device\IResult|null                 Result or null if no result set was produced by query.
     */
    public function execute(\Octris\Sqlbuilder\Template $tpl, array $param = array())
    {
        $sql = $tpl->resolveSql($param);

        $types = '';
        $values = [];

        $sql = preg_replace_callback('/@(?P<type>.):(?P<name>.+?)@/', function($match) use (&$types, &$values, $param) {
            $types .= $match['type'];
            $values[] = $param[$match['name']];

            return '?';
        }, $sql);

        $stmt = $this->cn->prepare($sql);

        if (count($values) > 0) {
            $stmt->bindParam($types, $values);
        }

        return $stmt->execute();
    }
}
