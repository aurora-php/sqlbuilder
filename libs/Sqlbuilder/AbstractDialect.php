<?php

declare(strict_types=1);

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
 * SQL Dialects base class.
 *
 * @copyright   copyright (c) 2016-present by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
abstract class AbstractDialect
{
    /**
     * Return string for limiting result.
     * 
     * @param   int                     $limit              Limit result.
     * @param   int                     $offset             Optional offset to start result at.
     * @return  string                                      SQL snippet for limiting result.
     */
    abstract public function getLimitString(int $limit, int $offset = 0): string;

    /**
     * Resolve query parameter.
     *
     * @param   int                     $idx                Position of the parameter in the query.
     * @param   string                  $type               Type of the parameter.
     * @param   string                  $name               Name of the parameter.
     * @return  string
     */
    abstract public function resolveParameter(int $idx, string $type, string $name): string;
}
