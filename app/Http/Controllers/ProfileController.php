<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use PHPMailer\PHPMailer\PHPMailer;
use  PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // dd($request->user());
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
        // $user = Auth::user();
        // return view('user.profile', compact('user'));

    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function sendMail()
    {

        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 465;
        $phpmailer->Username = 'cd6dce04978bd4';
        $phpmailer->Password = 'a038b0cfe98af7';

        $phpmailer->setFrom('testmailz1tech@gmail.com', 'Mailtrap');
        //$mail->addReplyTo('testmailz1tech@gmail.com', 'Mailtrap');
        $phpmailer->addAddress('harsharahuwanshi094@gmail.com', 'Harsh');


        $phpmailer->Subject = 'Test Email via Mailtrap SMTP using PHPMailer';
        $phpmailer->isHTML(true);

        $mailContent = "<h1>Send HTML Email using SMTP in PHP</h1>
        <p>This is a test email I’m sending using SMTP mail server with PHPMailer.</p>";
        $phpmailer->Body = $mailContent;

        if ($phpmailer->send()) {
            echo 'Message has been sent';
        } else {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
        }
    }
}
