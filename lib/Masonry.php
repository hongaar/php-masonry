<?php

require_once 'PEAR/Math/Matrix.php';

class Masonry
{
	public $multiplier = 1; // speedup

	public $xsize = 100;
	public $ysize = 100;

	public $minWidth = 15;
	public $maxWidth = 40;

	public $minHeight = 30;
	public $maxHeight = 75;

	public $minArea = 2; // fraction of minimum possible area
	public $maxArea = 0.5; // fraction of maximum possible area

	public $fillTopProbability = 2;
	public $fillHalfwayProbability = 3;

	public $debug = false;

	/**
	 *
	 * @var Math_Matrix
	 */
	private $matrix;

	public function create($array)
	{
		// test conditions

		// TODO: test min/max

		// test area
		if ( ($this->minArea * ($this->minWidth * $this->minHeight)) >= ($this->maxArea * ($this->maxWidth * $this->maxHeight))) {
			throw new Exception('Min/max area overlap, please adjust settings');
		}

		// create matrix
		$this->matrix = Math_Matrix::makeMatrix($this->ysize, $this->xsize, -1);

		// variable to define whether filling is active
		$fill = false;

		// return array
		$positions = array();

		foreach($array as $k => $v) {

			// get next empty space
			$pos = $this->searchNextPosition();

			// fill horizontal?
			if ($pos[1] == 0) { // are we at the top?
				if ($pos[0] != 0) { // are we not at [0,0] ?
					if (rand(1, $this->fillTopProbability) == 1) { // roll a dice for top position fill
						// calculate fill position
						$fill = $this->searchXBoundFrom($pos[0]) + $this->minWidth;
						if ($this->debug) {
							echo 'fill from top x='. $fill . '<br/>';
						}
					} else {
						$fill = false;
					}
				}
			} elseif ($fill === false) { // fill from halfway?
				if (rand(1, $this->fillHalfwayProbability) == 1) { // roll a dice halfway position fill
					// calculate fill position
					$fill = $this->searchXBoundFrom($pos[0]) + $this->minWidth;
					if ($this->debug) {
						echo 'fill x='. $fill . '<br/>';
					}
				}
			}

			// get dimensions
			$rand = $this->randomDimensions();
			$randWidth = $rand['width'];
			$randHeight = $rand['height'];

			if ($fill !== false) {
				$randWidth = $fill - $pos[0];
			}

			// height available?
			$heightAvailable = $this->availableHeightFrom($pos[0], $pos[1]);
			if ($heightAvailable <= $this->minHeight) {
				$height = $heightAvailable;
			} else if ($heightAvailable <= $this->minHeight * 2) {
				$height = $heightAvailable;
			} else {
				$height = min($randHeight, $heightAvailable - $this->minHeight);
			}
			// random width
			$width = $randWidth;

			// debug
			if ($this->debug) {
				echo $k . ': ' . $height  . '(' . $heightAvailable . ')x' . $width . ' @ [' . $pos[0] . ', ' . $pos[1] . ']<br/>';
			}

			// set ids
			for($x = $pos[0]; $x < $width + $pos[0]; $x++) {
				for($y = $pos[1]; $y < $height + $pos[1]; $y++) {
					$this->matrix->setElement($y, $x, $k);
				}
			}

			// set item position
			$positions[$k] = array(
				'x' => $pos[0] * $this->multiplier,
				'y' => $pos[1] * $this->multiplier,
				'w' => $width * $this->multiplier,
				'h' => $height * $this->multiplier
			);
		}

		// debug: print matrix
		if ($this->debug) {
			echo '<pre>'.$this->matrix->toString('%3.0f').'</pre>';
			die();
		}

		// return array
		return $positions;
	}

	public function searchNextPosition() {
		for($x = 0; $x < $this->xsize; $x++) {
			$col = $this->matrix->getCol($x);
			if (($y = array_search('-1', $col)) !== false) {
				return array($x, $y);
			}
		}
		throw new Exception('Grid X size too small, please enlarge');
	}

	public function searchXBoundFrom($x) {
		for($i = $x; $i < $this->xsize; $i++) {
			$col = $this->matrix->getCol($i);
			if (max($col) == -1) {
				return $i;
			}
		}
	}

	public function availableHeightFrom($x, $y) {
		// get the column
		$col = $this->matrix->getCol($x);
		// make array start at correct y pos
		for($i = 0; $i < $y; $i++) {
			array_shift($col);
		}
		foreach($col as $k => $v) {
			if ($v > -1) {
				return $k;
			}
		}
		// entire height available
		return $this->ysize - $y;
	}

	public function randomHeight()
	{
		return rand($this->minHeight, $this->maxHeight);
	}

	public function randomWidth()
	{
		return rand($this->minWidth, $this->maxWidth);
	}

	public function randomDimensions()
	{
		$min = ($this->minArea * ($this->minHeight * $this->minWidth));
		$max = ($this->maxArea * ($this->maxHeight * $this->maxWidth));
		do {
			$width = $this->randomWidth();
			$height = $this->randomHeight();
			$area = $width * $height;
		} while ($area < $min || $area > $max);
		return array(
			"width" => $width,
			"height" => $height
		);
	}
}