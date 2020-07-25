<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();

        return view('companies.index')
            ->with('companies', $companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies'
        ]);
        Company::create($request->all());
        Session::flash('success', 'Company Added Successfully!');
        return redirect(route('companies.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $company_id = $request->company_id;
        $company = Company::findOrFail($company_id);

        $request->validate([
            'name' => 'required|unique:companies,name,' . $company->id
        ]);
        $company->update($request->all());
        Session::flash('success', 'Company Updated Successfully!');
        return redirect(route('companies.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $company_id = $request->company_id;
        $company = Company::findOrFail($company_id);

        if ($company->calls->count() > 0) {
            Session::flash('error', 'Cannot delete a company with calls!');
            return redirect(route('companies.index'));
        }
        $company->delete();
        Session::flash('success', 'Company Deleted Successfully!');
        return redirect(route('companies.index'));
    }
}