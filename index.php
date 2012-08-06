<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width">

	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<a href="https://github.com/hongaar/php-masonry"><img style="position: fixed; z-index: 2; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_white_ffffff.png" alt="Fork me on GitHub"></a>

<div id="main">
	<?php

	require_once 'lib/Masonry.php';

	// create images array
	$images = glob('images/*');
	shuffle($images);

	$masonry = new Masonry();

	if (isset($_REQUEST['small'])) {
		$masonry->minWidth = 5;
		$masonry->maxWidth = 15;
		$masonry->minHeight = 10;
		$masonry->maxHeight = 30;
		$masonry->fillTopProbability = 5;
		$masonry->fillHalfwayProbability = 2;
	} else {
		$masonry->multiplier = 10;
		$masonry->ysize = 10;
		$masonry->minWidth = 1;
		$masonry->maxWidth = 4;
		$masonry->minHeight = 3;
		$masonry->maxHeight = 7;
	}
	// set xsize to allow for maximum width/height of all images
	$masonry->xsize = count($images) * ($masonry->maxWidth / ($masonry->ysize / $masonry->maxHeight));

	try {
		$position = $masonry->create($images);
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	foreach($images as $k => $v) : ?>

		<div style='top: <?php echo $position[$k]['y']; ?>%; left: <?php echo $position[$k]['x']; ?>%; width: <?php echo $position[$k]['w']; ?>%; height: <?php echo $position[$k]['h']; ?>%; background-image: url(<?php echo $images[$k]; ?>);'></div>

	<?php endforeach; ?>

</div>

<footer>
	<strong>php-masonry</strong> - hit F5 for new grid - <a href='?large'>large</a> | <a href='?small'>small</a><br/>
	&copy; photo's from various authors on <a href='http://www.flickr.com/'>flickr.com</a> - <a href='https://github.com/hongaar/php-masonry'>source on github</a>
</footer>

</body>
</html>