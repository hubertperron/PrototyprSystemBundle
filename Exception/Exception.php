<?php

namespace Prototypr\SystemBundle\Exception;

class Exception extends \Exception
{
	public function __construct($message, $code, $previous)
	{
		return parent::__construct('Prototypr' . $message, $code, $previous);
	}
}
