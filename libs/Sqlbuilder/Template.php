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
 * SQL builder template class.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Template
{
    /**
     * SQL builder instance.
     *
     * @type    \Octris\Sqlbuilder
     */
    protected $builder;

    /**
     * SQL template.
     *
     * @type    string
     */
    protected $sql;

    /**
     * Constructor.
     *
     * @param   \Octris\Sqlbuilder                  $builder    SQL builder instance.
     * @param   string                              $sql        SQL template to add.
     */
    public function __construct(\Octris\Sqlbuilder $builder, $sql)
    {
        $this->builder = $builder;
        $this->sql = $sql;
    }

    /**
     * Resolve SQL statement.
     *
     * @param   array                       $parameters         Optional parameters for forming SQL statement.
     * @return  string                                          Resolved SQL statement.
     */
    public function resolveSql(array $parameters = array())
    {
        // sql statement from template
        $sql = preg_replace_callback('|/\*\*(.+?)\*\*/|', function($match) use (&$parameters) {
            $name = trim($match[1]);

            $snippet = $this->builder->resolveSnippet($name, $parameters);

            return $snippet;
        }, $this->sql);

        // resolve parameters
        $types = '';
        $values = [];

        $sql = preg_replace_callback('/@(?P<type>.):(?P<name>.+?)@/', function($match) use (&$types, &$values, $parameters) {
            $types .= $match['type'];
            $values[] = $parameters[$match['name']];

            return $this->builder->resolveParameter(count($values), $match['type'], $match['name']);
        }, $sql);

        return (object)['sql' => $sql, 'types' => $types, 'parameters' => $values];
    }
}
