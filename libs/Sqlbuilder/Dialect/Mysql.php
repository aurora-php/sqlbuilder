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
     * Return string for limiting result.
     * 
     * @param   int                     $limit              Limit result.
     * @param   int                     $offset             Optional offset to start result at.
     * @return  string                                      SQL snippet for limiting result.
     */
    public function getLimitString($limit, $offset = 0)
    {
        return sprintf('LIMIT %d, %d', $offset, $limit);
    }
}
