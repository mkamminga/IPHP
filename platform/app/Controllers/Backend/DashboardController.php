<?php

namespace App\Controllers\Backend;

use App\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->view('cms::dashboard.php');
    }
}
