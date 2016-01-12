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
    protected $clauses = array();

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
    }

    /**
     * Build string representation of clauses.
     * 
     * @return  string                                          String.
     */
    public function __toString()
    {
    }

    /**
     * Add a clause to the list of clauses.
     * 
     * @param   string              $sql                        SQL of clause to add.
     */
    public function add($sql)
    {
        $this->clauses[] = [
            'sql' => $sql
        ];
    }
}
