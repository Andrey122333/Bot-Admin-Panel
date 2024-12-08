<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFormAnswer;
use App\Models\ApplicationForm;
use Illuminate\Http\Request;

class ApplicationFormsEditController extends Controller
{
    public function view($id)
    {
        $application_form_answers = ApplicationFormAnswer::where("application_form_id", $id)->get();
        $application_form = ApplicationForm::firstWhere("id", $id, ["name"]);

        if (!$application_form) {
            return redirect(substr(url()->current(), 0, strrpos(url()->current(), '/')));
        }

        return view("application-forms-edit", compact('application_form_answers', 'application_form'));
    }

    public function save($id, Request $req)
    {
        $req->validate([
            'status' => 'required',
        ]);

        ApplicationForm::where("id", $id)->update(['status' => $req->input('status')]);

        return back();
    }
}
