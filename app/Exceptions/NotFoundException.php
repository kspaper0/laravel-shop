<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class NotFoundException extends Exception
{
	protected $msgForUser;

    public function __construct(string $message = "", string $msgForUser = 'Whoops! The page cannot be found', int $code = 404)
    {
        parent::__construct($message, $code);
        $this->msgForUser = $msgForUser;

    }

    public function render(Request $request)
    {
    	if ($request->expectsJson()) {
    		return response()->json(['msg' => $this->msgForUser], $this->code);
    	}
    
    	return view('pages.error', ['msg' => $this->msgForUser]);
    }

}
