<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailTemplateRequest;
use App\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    /**
     * Display a list of all Email templates.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function index()
    {
        $result = EmailTemplate::paginate(10);
        return view('backend.emailtemplate.emailtemp', compact('result'));
    }

    /**
     * Display the form for creating a new Email template.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function create()
    {
        return view('backend.emailtemplate.add');
    }

    /**
     * Store a newly created Email template $request passed as param.
     * @param \App\Request\EmailTemplateRequest $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function store(EmailTemplateRequest $request)
    {
        $validatedData = $request->validated();

        $emailTemplate = new EmailTemplate();
        $emailTemplate->fill($validatedData);

        if ($emailTemplate->save()) {
            return redirect()->route('email.index')->with('success', 'Email Template added successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to save Email Template. Review information and try again!');
        }
    }

    /**
     * Display the form for updating an existing Email template.
     * @param \App\Models\EmailTemplate $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        // dd($emailTemplate);
        return view('backend.emailtemplate.edit', compact('emailTemplate'));
    }

    /**
     * Update an existing Email template .
     * @param \App\Request\EmailTemplateRequest $request and \App\Models\EmailTemplate $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {

        $emailTemplate->update($request->validated());

        if ($emailTemplate->wasChanged()) {
            return redirect()->route('email.index')->with('success', 'Email Template updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to update Email Template. Review information and try again!');
        }
    }

    /**
     * Find and delete an existing Email template .
     * @param \App\Models\EmailTemplate $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function delete(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        return redirect()->back()->with('success', 'Email Template deleted successfully.');
    }
}
