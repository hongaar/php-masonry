<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Jesus M. Castagnetto <jmcastagnetto@php.net>                |
// +----------------------------------------------------------------------+
//
// $Id: Vector2.php 304047 2010-10-05 00:25:33Z clockwerx $
//

require_once "Vector.php";

/**
 * 2D Vector class
 * Originally this class was part of NumPHP (Numeric PHP package)
 *
 * @author	Jesus M. Castagnetto <jmcastagnetto@php.net>
 * @version	1.0
 * @access	public
 * @package	Math_Vector
 */
class Math_Vector2 extends Math_Vector {

	/**
	 * Constructor for Math_Vector2
	 *
	 * @access	public
	 * @param	mixed	$arg	an array of values, a Math_Tuple object or a Math_Vector2 object
	 */
	function Math_Vector2($arg) /*{{{*/
	{
		if ( is_array($arg) && count($arg) != 2 )
			$this->tuple = null;
		elseif ( is_object($arg) && (strtolower(get_class($arg)) != "math_vector2"
					&& strtolower(get_class($arg)) != "math_tuple") )
			$this->tuple = null;
		elseif ( is_object($arg) && strtolower(get_class($arg)) == "math_tuple"
				&& $arg->getSize() != 2 )
			$this->tuple = null;
		else
			$this->Math_Vector($arg);
	}/*}}}*/

	/**
	 * Returns the X component of the vector
	 *
	 * @access	public
	 * @return	numeric
	 */
	function getX()/*{{{*/
	{
		return $this->get(0);
	}/*}}}*/

	/**
	 * Sets the X component of the vector
	 *
	 * @access	public
	 * @param	numeric	$val	the value for the Y component
	 * @return	mixed	true on success, PEAR_Error object otherwise
	 */
	function setX($val)/*{{{*/
	{
		return $this->set(0, $val);
	}/*}}}*/

	/**
	 * Returns the Y component of the vector
	 *
	 * @access	public
	 * @return	numeric
	 */
	function getY()/*{{{*/
	{
		return $this->get(1);
	}/*}}}*/

	/**
	 * Sets the Y component of the vector
	 *
	 * @access	public
	 * @param	numeric	$val	the value for the Y component
	 * @return	mixed	true on success, PEAR_Error object otherwise
	 */
	function setY($val)/*{{{*/
	{
		return $this->set(1, $val);
	}/*}}}*/

}

?>
