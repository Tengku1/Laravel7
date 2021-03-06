<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Exports\UserExport;
use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $data = User::where('status', '=', 'active')->paginate(7);
        return view('Master.User.index', compact('data'));
    }

    public function create()
    {
        $branch = Branch::select("name", "code")->get();
        return view("Master.User.create", compact("branch"));
    }

    public function destroy()
    {
        User::where('id', '=', request('id'))->update([
            'status' => 'inactive',
        ]);
        return redirect()->to('/user');
    }

    public function edit($email)
    {
        $data = User::select("name", "full_name", "email")->where('email', '=', $email)->get();
        $branch = Branch::select('name')->get();
        return view('Master.User.edit', compact('data', 'branch'));
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

    public function store()
    {
        $attr = request()->all();
        User::create([
            'name' => $attr['name'],
            'password' => Hash::make($attr['password']),
            'full_name' => $attr['full_name'],
            'email' => $attr['email'],
            'status' => 'active',
            'branch_code' => $attr['branch_code'],
            'roles' => '[' . $attr['roles'] . ']',
        ]);
        return redirect()->to("/user");
    }

    public function update()
    {
        $attr = request()->all();
        if ($attr['branch'] != "default") {
            $branch = Branch::select('code')->where('name', '=', $attr['branch'])->get();
            User::where("email", "=", $attr['email'])->update([
                'name' => $attr['name'],
                'full_name' => $attr['fullname'],
                'branch_code' => $branch[0]->code,
                'email' => $attr['email'],
            ]);
        } else {
            User::where("email", "=", $attr['email'])->update([
                'name' => $attr['name'],
                'full_name' => $attr['fullname'],
                'email' => $attr['email'],
            ]);
        }
        return redirect()->to("/user");
    }
}
