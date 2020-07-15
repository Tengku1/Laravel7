<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $data = Branch::paginate(5);
        return view("Master.index", compact("data"));
    }
    public function create()
    {
        return view("Master.BranchCreate");
    }
    public function store()
    {
        $attr = request()->all();
        $attr['address_name'] = request()->address;
        Branch::create($attr);
        session()->flash('success', 'The Data Was Added');
        return redirect()->to("/branch");
    }
    public function edit(Branch $branch)
    {
        return view("Master.BranchEdit", compact("branch"));
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
        return view("Master.BranchShow", compact("branch"));
    }
    public function destroy(Branch $branch)
    {
        $branch->delete();
        session()->flash('success', 'The Data Was Deleted');
        return redirect()->to('/branch');
    }
}
