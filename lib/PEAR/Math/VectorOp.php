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
// $Id: VectorOp.php 304045 2010-10-05 00:16:53Z clockwerx $
//

/**
 * Vector operation class. 
 * A static class implementing methods to operate on Vector objects.
 * Originally this class was part of NumPHP (Numeric PHP package)
 *
 * @author	Jesus M. Castagnetto <jmcastagnetto@php.net>
 * @version	1.0
 * @access	public
 * @package	Math_Vector
 */
class Math_VectorOp {

	/**
	 * Checks if object is of Math_Vector class (or a subclass of Math_Vector)
	 *
	 * @access	public
	 * @param	object	$obj
	 * @return	boolean	true on success
	 */
	function isVector($obj) /*{{{*/
	{
		if (function_exists("is_a"))
			return (is_object($obj) && is_a($obj, "Math_Vector"));
		else
			return (is_object($obj) && (strtolower(get_class($obj)) == "math_vector" || 
						is_subclass_of($obj, "Math_Vector")));
	}/*}}}*/

	/**
	 * Checks if object is of Math_Vector2 class (or a subclass of Math_Vector2)
	 *
	 * @access	public
	 * @param	object	$obj
	 * @return	boolean	true on success
	 */
	function isVector2($obj) /*{{{*/
	{
		if (function_exists("is_a"))
			return (is_object($obj) && is_a($obj, "Math_Vector2"));
		else
			return (is_object($obj) && (strtolower(get_class($obj)) == "math_vector2" ||
				is_subclass_of($obj, "Math_Vector2")));
	}/*}}}*/

	/**
	 * Checks if object is of Math_Vector3 class (or a subclass of Math_Vector3)
	 *
	 * @access	public
	 * @param	object	$obj
	 * @return	boolean	true on success
	 */
	function isVector3($obj) /*{{{*/
	{
		if (function_exists("is_a"))
			return (is_object($obj) && is_a($obj, "Math_Vector3"));
		else
			return (is_object($obj) && (strtolower(get_class($obj)) == "math_vector3" ||
					is_subclass_of($obj, "Math_Vector3")) );
	}/*}}}*/

	/**
	 * Creates a vector of a given size in which all elements have the same value
	 *
	 * @access	public
	 * @param	int	$size	vector size
	 * @param	numeric	$value	value to assign to the elements
	 * @return	object	if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector
	 */
	function create ($size, $value) /*{{{*/
	{
		if ($size == 2)
			$VClass = "Math_Vector2";
		elseif ($size == 3)
			$VClass = "Math_Vector3";
		else
			$VClass = "Math_Vector";
		return new $VClass(Math_VectorOp::_fill(0, $size, $value));
	}/*}}}*/

	/**
	 * Creates a zero-filled vector of the given size
	 *
	 * @access	public
	 * @param	int	$size	vector size
	 * @return	object	if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector
	 *
	 * @see	create()
	 */
	function createZero ($size) 
	{
		return Math_VectorOp::create ($size, 0);
	}

	/**
	 * Creates a one-filled vector of the given size
	 *
	 * @access	public
	 * @param	int	$size	vector size
	 * @return	object	if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector
	 *
	 * @see	create()
	 */
	function createOne ($size) /*{{{*/
	{
		return Math_VectorOp::create ($size, 1);
	}/*}}}*/


	/**
	 * Creates a basis vector of the given size
	 * A basis vector of size n, has n - 1 elements equal to 0
	 * and one element equal to 1
	 *
	 * @access	public
	 * @param	int	$size	vector size
	 * @param	int	$index	element to be set at 1
	 * @return	object	if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector, on error PEAR_Error
	 *
	 * @see	createZero()
	 */
	function createBasis ($size, $index) /*{{{*/
	{
		if ($index >= $size)
			return PEAR::raiseError("Incorrect index for size: $index >= $size");
		$v = Math_VectorOp::createZero($size);
		$res =$v->set($index, 1);
		if (PEAR::isError($res))
			return $res;
		else
			return $v;
	}/*}}}*/

	/**
	 * Vector addition
	 * v + w = <v1 + w1, v2 + w2, ..., vk + wk>
	 *
	 * @access	public
	 * @param	object	Math_Vector (or subclass)	$v1
	 * @param	object	Math_Vector (or subclass)	$v2
	 * @return	object	Math_Vector (or subclass) on success, PEAR_Error otherwise
	 *
	 * @see 	isVector()
	 */
	function add ($v1, $v2) /*{{{*/
	{
		if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
			$n = $v1->size();
			if ($v2->size() != $n)
				return PEAR::raiseError("Vectors must of the same size");
			for ($i=0; $i < $n; $i++)
				$arr[$i] = $v1->get($i) + $v2->get($i);
			return new Math_Vector($arr);
		} else {
			return PEAR::raiseError("V1 and V2 must be Math_Vector objects");
		}
	}/*}}}*/

	/**
	 * Vector substraction
	 * v - w = <v1 - w1, v2 - w2, ..., vk - wk>
	 *
	 * @access	public
	 * @param	object	Math_Vector (or subclass)	$v1
	 * @param	object	Math_Vector (or subclass)	$v2
	 * @return	object	Math_Vector (or subclass) on success, PEAR_Error otherwise
	 *
	 * @see 	isVector()
	 */
	function substract ($v1, $v2) /*{{{*/
	{
		if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
			$n = $v1->size();
			if ($v2->size() != $n)
				return PEAR::raiseError("Vectors must of the same size");
			for ($i=0; $i < $n; $i++)
				$arr[$i] = $v1->get($i) - $v2->get($i);
			return new Math_Vector($arr);
		} else {
			return PEAR::raiseError("V1 and V2 must be Math_Vector objects");
		}
	}/*}}}*/

	/**
	 * Vector multiplication
	 * v * w = <v1 * w1, v2 * w2, ..., vk * wk>
	 *
	 * @access	public
	 * @param	object	Math_Vector (or subclass)	$v1
	 * @param	object	Math_Vector (or subclass)	$v2
	 * @return	object	Math_Vector (or subclass) on success, PEAR_Error otherwise
	 *
	 * @see 	isVector()
	 */
	function multiply ($v1, $v2) /*{{{*/
	{
		if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
			$n = $v1->size();
			if ($v2->size() != $n)
				return PEAR::raiseError("Vectors must of the same size");
			for ($i=0; $i < $n; $i++)
				$arr[$i] = $v1->get($i) * $v2->get($i);
			return new Math_Vector($arr);
		} else {
			return PEAR::raiseError("V1 and V2 must be Math_Vector objects");
		}
	}/*}}}*/

	/**
	 * Vector scaling
	 * f * w = <f * w1, f * w2, ..., f * wk>
	 *
	 * @access	public
	 * @param	numeric	$f	scaling factor
	 * @param	object	Math_Vector (or subclass)	$v
	 * @return	object	Math_Vector (or subclass) on success, PEAR_Error otherwise
	 *
	 * @see 	isVector()
	 */
	function scale ($f, $v) /*{{{*/
	{
		if (is_numeric($f) && Math_VectorOp::isVector($v)) {
			$n = $v->size();
			for ($i=0; $i < $n; $i++)
				$arr[$i] = $v->get($i) * $f;
			return new Math_Vector($arr);
		} else {
			return PEAR::raiseError("Requires a numeric factor and a Math_Vector object");
		}
	}/*}}}*/

	/**
	 * Vector division
	 * v / w = <v1 / w1, v2 / w2, ..., vk / wk>
	 *
	 * @access	public
	 * @param	object	Math_Vector (or subclass)	$v1
	 * @param	object	Math_Vector (or subclass)	$v2
	 * @return	object	Math_Vector (or subclass) on success, PEAR_Error otherwise
	 *
	 * @see 	isVector()
	 */
	function divide ($v1, $v2) /*{{{*/
	{
		if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
			$n = $v1->size();
			if ($v2->size() != $n)
				return PEAR::raiseError("Vectors must of the same size");
			for ($i=0; $i < $n; $i++) {
				$d = $v2->get($i);
				if ($d == 0)
					return PEAR::raiseError("Division by zero: Element $i in V2 is zero");
				$arr[$i] = $v1->get($i) / $d;
			}
			return new Math_Vector($arr);
		} else {
			return PEAR::raiseError("V1 and V2 must be Math_Vector objects");
		}
	}/*}}}*/

	/**
	 * Vector dot product = v . w = |v| |w| cos(theta)
	 *
	 * @access	public
	 * @param	object	Math_Vector2 or MathVector3 (or subclass)	$v1
	 * @param	object	Math_Vector2 or MathVector3 (or subclass)	$v2
	 * @return	mixed	the dot product (float) on success, a PEAR_Error object otherwise
	 *
	 * @see 	isVector2()
	 * @see		isVector3()
	 */
	function dotProduct ($v1, $v2)/*{{{*/
	{
		if (Math_VectorOp::isVector2($v1) && Math_VectorOp::isVector2($v2))
			return ( $v1->getX() * $v2->getX() +
					 $v1->getY() * $v2->getY() );
		elseif (Math_VectorOp::isVector3($v1) && Math_VectorOp::isVector3($v2))
			return ( $v1->getX() * $v2->getX() +
					 $v1->getY() * $v2->getY() +
					 $v1->getZ() * $v2->getZ() );
		else
			return PEAR::raiseError("Vectors must be both of the same type");
	}/*}}}*/

	/**
	 * Vector cross product = v x w 
	 *
	 * @access	public
	 * @param	object	Math_Vector3 (or subclass)	$v1
	 * @param	object	Math_Vector3 (or subclass)	$v2
	 * @return	object	the cross product vector (Math_Vector3) on success, a PEAR_Error object otherwise
	 *
	 * @see		isVector3()
	 */
	function crossProduct ($v1, $v2)
	{
		if (Math_VectorOp::isVector3($v1) && Math_VectorOp::isVector3($v2)) {
			$arr[0] = $v1->getY() * $v2->getZ() - $v1->getZ() * $v2->getY();
			$arr[1] = $v1->getZ() * $v2->getX() - $v1->getX() * $v2->getZ();
			$arr[2] = $v1->getX() * $v2->getY() - $v1->getY() * $v2->getX();
			return new Math_Vector3($arr);
		} else {
			return PEAR::raiseError("Vectors must be both of the same type");
		}
	}

	/**
	 * Vector triple scalar product =  v1 . (v2 x v3) 
	 *
	 * @access	public
	 * @param	object	Math_Vector3 (or subclass)	$v1
	 * @param	object	Math_Vector3 (or subclass)	$v2
	 * @param	object	Math_Vector3 (or subclass)	$v3
	 * @return	mixed	the triple scalar product (float) on success, a PEAR_Error object otherwise
	 *
	 * @see		isVector3()
	 * @see		dotProduct()
	 * @see		crossProduct()
	 */
	function tripleScalarProduct ($v1, $v2, $v3) /*{{{*/
	{
		if (Math_VectorOp::isVector3($v1)
			&& Math_VectorOp::isVector3($v2)
			&& Math_VectorOp::isVector3($v3))
			return Math_VectorOp::dotProduct($v1,Math_VectorOp::crossProduct($v2, $v3));
		else
			return PEAR_Error("All three vectors must be of the same type");
	}/*}}}*/

	/**
	 * Angle between vectors, using the equation: v . w = |v| |w| cos(theta)
	 *
	 * @access	public
	 * @param	object	Math_Vector2 or MathVector3 (or subclass)	$v1
	 * @param	object	Math_Vector2 or MathVector3 (or subclass)	$v2
	 * @return	mixed	the angle between vectors (float, in radians) on success, a PEAR_Error object otherwise
	 *
	 * @see 	isVector2()
	 * @see		isVector3()
	 * @see		dotProduct()
	 */
	function angleBetween($v1, $v2) /*{{{*/
	{
		if ( (Math_VectorOp::isVector2($v1) && Math_VectorOp::isVector2($v2)) || 
			 (Math_VectorOp::isVector3($v1) && Math_VectorOp::isVector3($v2)) ) {
			$v1->normalize();
			$v2->normalize();
			return acos( Math_VectorOp::dotProduct($v1,$v2) );
		} else {
			return PEAR::raiseError("Vectors must be both of the same type");
		}
	}/*}}}*/

	/**
	 * To generate an array of a given size filled with a single value
	 * If available uses array_fill()
	 * 
	 * @access	private
	 * @param	int	$index	starting index
	 * @param	int	$size	size of the array
	 * @param	numeric	$value	value to use for filling the array
	 * @return	array
	 */
	function _fill($index, $size, $value)/*{{{*/
	{
		if (function_exists("array_fill"))
			return array_fill($index, $size, $value);

		for ($i=$index; $i < ($index + $size); $i++)
			$arr[$i] = $value;
		return $arr;
	}/*}}}*/
}


?>
