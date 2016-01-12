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
 * SQL builder clause.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Clause
{
    protected $instance;

    protected $joiner;

    protected $prefix;

    protected $postfix;

    /**
     * Constructor.
     */
    public function __construct($instance, $joiner, $prefix, $postfix)
    {
        $this->instance = $instance;
        $this->joiner = $joiner;
        $this->prefix = $prefix;
        $this->postfix = $postfix;
    }

    public function build()
    {
        return $this->prefix . (!is_null($this->instance) ? $this->instance->build() : '') . $this->postfix;
    }
}
