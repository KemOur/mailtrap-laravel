<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\Contact;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(ContactFormRequest $request)
    {
        $params = [
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'msg' => $request->get('message'),
            'subject' => "Réservation confirmé 🥳"
        ];
        //md5(uniqid(true));

        DB::table('contacts')->insert([
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'email' => $params['email'],
            'message' => $params['msg']
        ]);

        Mail::to(Config::get('contact.email'), Config::get('contact.name'))->send(new Contact($params));

/*
        Mail::send('emails.contact', $params, function ($m) use ($params) {
            $m->from($params['email'], $params['first_name'] . ' ' . $params['last_name']);
            $m->to('julie@webstart.com', 'Julie de Webstart')->subject('Nouveau essage');
        });
*/
        return redirect('contact')
            ->with('status', 'Your message has been sent!');
    }
}
