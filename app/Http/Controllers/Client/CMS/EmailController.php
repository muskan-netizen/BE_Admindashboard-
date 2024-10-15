<?php

namespace App\Http\Controllers\Client\CMS;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $email_templates = EmailTemplate::withInactive()->get();

        return view('backend.cms.email.index', compact('email_templates'));
    }

    public function show(Request $request, $domain = '', $id)
    {
        $email_template =  EmailTemplate::withInactive()->find($id);

        return $this->successResponse($email_template);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'subject' => 'required',
            'content' => 'required',
            'status'  => 'numeric',
        ]);

        $email_template = EmailTemplate::withInactive()->findOrFail($request->email_template_id);
        $email_template->update($validated);

        return $this->successResponse($email_template, 'Email Template Updated Successfully.');
    }
}
