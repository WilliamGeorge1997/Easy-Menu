<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Services\AdminService;

class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    public function dashboard()
    {
        return view('admin::dashboard');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $admins = $this->adminService->findAll($request->all(), ['roles:display']);
        return view('admin::admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
