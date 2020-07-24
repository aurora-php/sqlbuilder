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
 * @copyright   copyright (c) 2016-2018 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Mysql extends \Octris\Sqlbuilder\AbstractDialect
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
    
    /**
     * Resolve query parameter.
     *
     * @param   int                     $idx                Position of the parameter in the query.
     * @param   string                  $type               Type of the parameter.
     * @param   string                  $name               Name of the parameter.
     */
    public function resolveParameter($idx, $type, $name)
    {
        return '?';
    }
}
