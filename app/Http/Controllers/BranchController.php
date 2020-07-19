<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        $data = Branch::where('status', '=', 'active')->paginate(5);
        return view("Master.Branch.index", compact("data"));
    }
    public function create()
    {
        return view("Master.Branch.create");
    }
    public function store()
    {
        $attr = request()->all();
        $attr['address_name'] = request()->address;
        $attr['status'] = 'active';
        $attr['slug'] = Str::slug($attr['name']);
        Branch::create($attr);
        session()->flash('success', 'The Data Was Added');
        return redirect()->to("/branch");
    }
    public function edit(Branch $branch)
    {
        return view("Master.Branch.edit", compact("branch"));
    }
    public function update(Branch $branch)
    {
        $attr = request()->all();
        $branch->update($attr);
        session()->flash('success', 'The Data Was Updated');
        return redirect()->to('/branch');
    }
    public function show(Branch $branch)
    {
        return view("Master.Branch.show", compact("branch"));
    }
    public function destroy(Branch $branch)
    {
        Branch::where('code', '=', $branch['code'])->update(['status' => 'inactive']);
        session()->flash('success', 'The Selected Branch Has Been Deactivated');
        return redirect()->to('/branch');
    }
}
