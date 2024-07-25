<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;

class CampaignController extends Controller
{
    /**
     * Display a list of all Campaigns.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function index()
    {
        $result = Campaign::with([
            'emailTemplate' => function ($query) {
                $query->select('id', 'title');
            },
            'smsTemplate' => function ($query) {
                $query->select('id', 'title');
            }
        ])->paginate(10);
        return view('backend.campaign.index', compact('result'));
    }

    /**
     * Display the form for creating a new Campaigns.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function create()
    {
        $emailTemplate = EmailTemplate::where('status', 'active')->get();
        $smsTemplate = SmsTemplate::where('status', 'active')->get();
        return view('backend.campaign.add', compact('emailTemplate', 'smsTemplate'));
    }

    /**
     * Store a newly created Campaigns.
     * @param \App\Request\CampaignRequest $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function store(CampaignRequest $request)
    {
        $validatedData = $request->validated();

        $campaign = new Campaign();
        $campaign->fill($validatedData);

        if ($campaign->save()) {
            return redirect()->route('campaign.index')->with('success', 'Campaign added successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to save Campaign. Review information and try again!');
        }
    }

    /**
     * Display the form for updating an existing Campaigns.
     * @param \App\Models\Campaign $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
        public function edit(Campaign $campaign)
        {
            $emailTemplate = EmailTemplate::where('status', 'active')->get();
            $smsTemplate = SmsTemplate::where('status', 'active')->get();
            return view('backend.campaign.edit', compact('campaign', 'emailTemplate', 'smsTemplate'));
        }

    /**
     * Update an existing Campaigns.
     * @param \App\Request\CampaignRequest $request and \App\Models\Campaign $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function update(CampaignRequest $request, Campaign $campaign)
    {
       $campaign->update($request->validated());

        if ($campaign->wasChanged()) {
            return redirect()->route('campaign.index')->with('success', 'Campaign updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to update Campaign. Review information and try again!');
        }
    }

    /**
     * Find and delete an existing Campaigns.
     * @param \App\Models\Campaign $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function delete(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->back()->with('success', 'Campaign deleted successfully.');
    }
}
