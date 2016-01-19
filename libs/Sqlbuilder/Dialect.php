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
 * SQL Dialects base class.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
abstract class Dialect extends \Octris\Sqlbuilder
{
    /**
     * Database attributes.
     *
     * @type    array
     */
    protected $attributes;
    
    /**
     * Constructor.
     * 
     * @param   array               $attributes             Optional additional attributes to identify database.
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes = $attributes;
    }
    
    /**
     * Return string for limiting result.
     * 
     * @return  string                                      SQL snippet for limiting result.
     */
    abstract public function getLimitString();

    /**
     * Return SQL snippet for counting result.
     * 
     * @return  string                                      SQL snippet for counting result.
     */
    abstract public function getCountString(); 
}
