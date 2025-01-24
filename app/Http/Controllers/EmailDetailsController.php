<?php

namespace App\Http\Controllers;

use App\Models\IncomingEmail;
use App\Models\OutgoingEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class EmailDetailsController extends Controller
{

    public $allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];

    /**
     * Display a listing of the resource.
     */
    public function incomingEmailDetails(IncomingEmail $email)
    {

        if($email->user_id != Auth::id())
        {
            abort(404);
        }
        (new IncomingEmailsController)->setAsSeen($email);

        $attachments = Storage::allFiles('public/attachments/incoming/'.$email->id);
        $images = [];
        $files = [];
        foreach ($attachments as $attachment) {

            if(! in_array(mime_content_type(Storage::path($attachment)), $this->allowedMimeTypes) )
            {
                $files[] = $attachment;
            } else {
                $images[] = $attachment;
            }
        }

        view()->share('emailToReply', $email->from);
        view()->share('emailData', $email->toArray());
        view()->share('canBeReplied', true);

        return view('email_details.show',compact('email','files','images'));

    }

    /**
     * Display a listing of the resource.
     */
    public function outgoingEmailDetails(OutgoingEmail $email)
    {
        if($email->user_id != Auth::id())
        {
            abort(404);
        }

        $attachments = Storage::allFiles('public/attachments/outgoing/'.$email->id);
        $images = [];
        $files = [];
        foreach ($attachments as $attachment) {

            if(! in_array(mime_content_type(Storage::path($attachment)), $this->allowedMimeTypes) )
            {
                $files[] = $attachment;
            } else {
                $images[] = $attachment;
            }
        }

        view()->share('emailToReply', $email->to);
        view()->share('emailData', $email->toArray());
        view()->share('canBeReplied', true);
        return view('email_details.show',compact('email','files','images'));
    }
}
