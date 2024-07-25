<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRegeneratedDocsRequest;
use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    /**
     * Display a list of all leads.
     *  @param \Illuminate\Http\Request $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function dashboard(Request $request)
    {
        $dynamic_and_where = '';

        if ($request->isMethod('get')) {
            $start_date = Carbon::now()->format('m/d/Y');
            $end_date = Carbon::now()->format('m/d/Y');
            if ($start_date) {
                $start_date_ = $start_date . ' 00:00:00';
                $dynamic_and_where .= " AND primary_leads.createdAt > '$start_date_' ";
            }

            if ($end_date) {
                $end_date_ = $end_date . ' 23:59:59';
                $dynamic_and_where .= " AND primary_leads.createdAt < '$end_date_' ";
            }
            $result = Dashboard::whereRaw('1' . $dynamic_and_where)->paginate(10);

            return view('backend.pages.dashboard', compact('result'));
        }

        if ($request->isMethod('post')) {
            $requestData = $request->all();
            if (!empty($requestData)) {
                $dynamic_and_where = $this->buildQueryConditions($request);
                if (!empty($dynamic_and_where)) {
                    $result = Dashboard::whereRaw('1' . $dynamic_and_where)->paginate(10);
                    return view('backend.pages.dashboardfilter', compact('result'));
                }
            }
        }
    }

    /**
     * Download leads in csv format.
     *  @param \Illuminate\Http\Request $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    function downloadCSV(Request $request)
    {
        $file_name = 'admin-leads.csv';
        $requestData = $request->all();
        if (!empty($requestData)) {
            $dynamic_and_where = $this->buildQueryConditions($request);
            if (!empty($dynamic_and_where)) {
                $result = Dashboard::whereRaw('1' . $dynamic_and_where)->get();
            }
        }

        if (!empty($result)) {
            $delimiter = ",";
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $file_name . '";');
            $f = fopen("php://output", 'w');

            $fields = array('title', 'firstname', 'lastname', 'leadip', 'browser', 'device', 'phone', 'email', 'PostURL', 'createdAt', 'documentedDate', 'Classification');
            fputcsv($f, $fields, $delimiter);

            foreach ($result as $row) {
                $lineData = $this->formatlead($row);
                fputcsv($f, $lineData, $delimiter);
            }
            fclose($f);
        } else {
            echo '<h1>No Records Found</h1>';
        }
    }

    /**
     * Format the leads for download the leads as csv files.
     * @param $object
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    function formatlead($object)
    {
        $uk_mob_number = $object['phone'];
        $uk_mob_number = str_replace("+", "", $uk_mob_number);
        $phone1 = preg_replace('/^61/', '0', $uk_mob_number);
        $classification = "Completed Leads";


        if ($object['is_completed'] = 0 || $object['is_completed'] == "") {
            $classification = "Partial Lead";
        }
        $obj = (array)[
            'title' => $object['title'],
            'firstname' => $object['firstname'],
            'lastname' => $object['lastname'],
            'leadip' => $object['leadip'],
            'browser' => $object['browser'],
            'device' => $object['device'],
            'phone' => $phone1,
            'email' => $object['email'],
            'PostURL' => $object['PostURL'],
            'createdAt' => $object['createdAt'],
            'documentedDate' => $object['documentedDate'],
            'Classification' => $classification,
        ];
        return $obj;
    }

    /**
     * Display the view for the upload csv file.
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function uploadCsvIndex()
    {
        return view('backend.pages.uploadcsv');
    }

    /**
     * Upload comma separated csv file to save leads into database.
     * @param \Illuminate\Http\Request $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function uploadCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file));
            $headers = array_shift($csvData);

            foreach ($csvData as $index => $row) {
                $data = [];
                foreach ($headers as $index => $columnName) {
                    $data[$columnName] = $row[$index];
                }

                $existingRecord = Dashboard::where('phone', $data['phone'])
                    ->orWhere('firstname', $data['firstname'])
                    ->orWhere('email', $data['email'])
                    ->first();

                if (!$existingRecord) {
                    $model = new Dashboard();
                    $model->timestamps = false;
                    $model->fill($data);
                    $model->save();
                }
            }

            return redirect()->back()->with('success', 'CSV file uploaded, and selected records inserted.');
        }

        return redirect()->back()->with('error', 'Please select a CSV file to upload.');
    }

    /**
     * Query builder private function to make dynamic where conditions for the filters
     * @param \Illuminate\Http\Request $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    private function buildQueryConditions(Request $request)
    {
        $dynamic_and_where = '';
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $PostURL = $request->input('PostURL');
        $is_completed = $request->input('is_completed');
        $searchTerm = $request->input('search');

        if ($start_date) {
            $start_date_ = $start_date . ' 00:00:00';
            $dynamic_and_where .= " AND primary_leads.createdAt > '$start_date_' ";
        }

        if ($end_date) {
            $end_date_ = $end_date . ' 23:59:59';
            $dynamic_and_where .= " AND primary_leads.createdAt < '$end_date_' ";
        }

        if ($PostURL && $PostURL != 'all') {
            $dynamic_and_where .= " AND primary_leads.PostURL = '$PostURL' ";
        }

        if ($is_completed) {
            $dynamic_and_where .= " AND primary_leads.is_completed = '$is_completed' ";
        }

        if ($searchTerm) {
            $dynamic_and_where .= " AND (
            LOWER(primary_leads.firstname) LIKE '%" . strtolower($searchTerm) . "%' OR
            LOWER(primary_leads.lastname) LIKE '%" . strtolower($searchTerm) . "%' OR
            primary_leads.phone LIKE '%$searchTerm%' OR
            LOWER(primary_leads.email) LIKE '%" . strtolower($searchTerm) . "%'
        ) ";
        }

        return $dynamic_and_where;
    }

    /**
     * Display a list of all documnets.
     * @param \Illuminate\Http\Request $request
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function getRegenertedDocs(Request $request)
    {
        $dynamic_and_where = '';

        if ($request->isMethod('get')) {

            $dynamic_and_where .= " AND primary_leads.aml_pdf_url is not null AND primary_leads.is_completed = 1 ";

            $result = Dashboard::whereRaw('1' . $dynamic_and_where)->paginate(10);

            return view('backend.pages.regenratedocs', compact('result'));
        }

        if ($request->isMethod('post')) {
            $requestData = $request->all();
            if (!empty($requestData)) {
                $searchTerm = $request->input('search');
                $dynamic_and_where .= " AND primary_leads.aml_pdf_url is not null AND primary_leads.is_completed = 1 ";
                if ($searchTerm  != '' && $searchTerm != null) {
                    $dynamic_and_where .= " AND (
                    LOWER(primary_leads.firstname) LIKE '%" . strtolower($searchTerm) . "%' OR
                    LOWER(primary_leads.lastname) LIKE '%" . strtolower($searchTerm) . "%' OR
                    primary_leads.phone LIKE '%$searchTerm%' OR
                    LOWER(primary_leads.email) LIKE '%" . strtolower($searchTerm) . "%'
                ) ";
                }
                if (!empty($dynamic_and_where)) {
                    $result = Dashboard::whereRaw('1' . $dynamic_and_where)->paginate(10);
                    return view('backend.pages.regenratedocsfilter', compact('result'));
                }
            }
        }
    }

    /**
     * Display the form for updating an existing documnets.
     * @param \App\Models\Dashboard $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function editRegenertedDocs(Dashboard $dashboard)
    {
        return view('backend.pages.editregenratedocs', compact('dashboard'));
    }

    /**
     * Update an existing documnets.
     * @param \App\Request\UpdateRegeneratedDocsRequest $request and \App\Models\Dashboard $id
     * Author: Arman Saleem
     * Date: 15 Sep, 2023
     */
    public function updateRegenertedDocs(UpdateRegeneratedDocsRequest $request, Dashboard $dashboard)
    {
        $validatedData = $request->validated();
        $validatedData['is_completed'] = 0;
    
        if ($dashboard->update($validatedData)) {
            return redirect()->route('docs.index')->with('success', 'Document updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Unable to update Document. Review information and try again!');
        }
    }
}
