<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new AdminController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    	$this->middleware(['auth:api', 'role:admin']);
    }

    /**
     * Get the admin home page.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
    	// if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException)
    	// {
     //    	return response()->json(['Error' => 'Redirect to Home Page'], 403);
    	// }

        return response()->json(['success' => 'Admin Panel'], 200);
    }
}
