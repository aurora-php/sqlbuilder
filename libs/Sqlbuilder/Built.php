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
 * SQL builder built result.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Built
{
    /**
     * SQL Statement.
     *
     * @type    string
     */
    protected $sql;

    /**
     * Types.
     *
     * @type    string
     */
    protected $types;

    /**
     * Parameters.
     *
     * @type    array
     */
    protected $parameters;

    /**
     * Constructor.
     *
     * @param   string              $sql                Built sql statement.
     * @param   string              $types              Types (for forming prepared statement).
     * @param   string              $parameters         Parameters for sql statement.
     */
    public function __construct($sql, $types, array $parameters)
    {
        $this->sql = $sql;
        $this->types = $types;
        $this->parameters = $parameters;
    }

    /**
     * Getter.
     *
     * @param   string              $name               Name of property to return.
     * @return  mixed                                   Value stored in property.
     */
    public function __get($name)
    {
        return $this->{$name};
    }
}
