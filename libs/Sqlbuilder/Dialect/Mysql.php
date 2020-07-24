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

namespace Octris\Sqlbuilder\Dialect;

/**
 * MySQL dialect.
 *
 * @copyright   copyright (c) 2016-present by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Mysql extends \Octris\Sqlbuilder\AbstractDialect
{
    /**
     * {@inheritDoc}
     */
    public function getLimitString(int $limit, int $offset = 0): string
    {
        return sprintf('LIMIT %d, %d', $offset, $limit);
    }
    
    /**
     * {@inheritDoc}
     */
    public function resolveParameter(int $idx, string $type, string $name): string
    {
        return '?';
    }
}
