<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SmsTemplateRequest;
use App\Models\SmsTemplate;

class SmsTemplateController extends Controller
{
    /**
     * Display a list of all SMS templates.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function index()
    {
        $result = SmsTemplate::paginate(10);
        return view('backend.smstemplate.smstemp', compact('result'));
    }

    /**
     * Display the form for creating a new SMS template.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function create()
    {
        return view('backend.smstemplate.add');
    }

    /**
     * Store a newly created SMS template.
     * @param \App\Request\SmsTemplateRequest $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function store(SmsTemplateRequest $request)
    {
        $validatedData = $request->validated();

        $smstemplate = new SmsTemplate();
        $smstemplate->fill($validatedData);

        if ($smstemplate->save()) {
            return redirect()->route('sms.index')->with('success', 'Sms Template added successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to save Sms Template. Review information and try again!');
        }
    }

    /**
     * Display the form for updating an existing SMS template $id passed as param.
     * @param \App\Models\SmsTemplate $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function edit(SmsTemplate $smsTemplate)
    {
        return view('backend.smstemplate.edit', compact('smsTemplate'));
    }

    /**
     * Update an existing SMS template $request ad $id passed as param.
     * @param \App\Request\SmsTemplateRequest $request and \App\Models\SmsTemplate $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function update(SmsTemplate $smsTemplate, SmsTemplateRequest $request)
    {
        $smsTemplate->update($request->validated());

        if ($smsTemplate) {
            return redirect()->route('sms.index')->with('success', 'Sms Template updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to update Sms Template. Review information and try again!');
        }
    }

    /**
     * Find and delete an existing SMS template $id passed as param.
     * @param \App\Models\SmsTemplate $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function delete(SmsTemplate $smsTemplate)
    {
        $smsTemplate->delete();
        return redirect()->back()->with('success', 'Sms Template deleted successfully.');
    }
}
