<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $data = User::where('roles', 'like', '%Admin%')->paginate(7);
        return view('Master.User.index', compact('data'));
    }

    public function destroy()
    {
        User::where('id', '=', request('id'))->delete();
        return redirect()->to('/user');
    }

    public function excel()
    {
        $name = "User.xlsx";
        return Excel::download(new UserExport, $name);
    }

    public function search()
    {
        $attr = request()->all();
        $data = User::where('name', 'like', '%' . $attr['by'] . '%')
            ->orWhere('name', 'like', '%' . $attr['by'] . '%')
            ->orWhere('status', 'like', '%' . $attr['by'] . '%')
            ->orWhere('created_at', 'like', '%' . $attr['by'] . '%')
            ->paginate(7);
        return view('Master.User.index', compact('data'));
    }
}
