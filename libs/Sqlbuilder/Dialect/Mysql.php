<?php

/*
 * This file is part of the 'octris/sqlbuilder' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris\Sqlbuilder\Dialect;

/**
 * MySQL dialect.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Mysql extends \Octris\Sqlbuilder\Dialect
{
    /**
     * Constructor.
     * 
     * @param   array               $attributes             Optional additional attributes to identify database.
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct(['version' => ''] + $attributes);
    }
}
