<?php

class PEAR {
	function raiseError($message) {
		throw new Exception($message);
	}
}