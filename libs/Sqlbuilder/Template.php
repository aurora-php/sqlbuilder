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
     * Prepare template.
     */
    public function prepare()
    {
        $sql = preg_replace_callback('|/\*\*(.+?)\*\*/|', function($match) {
        }, $this->sql);
    }
}
