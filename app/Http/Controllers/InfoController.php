<?php

namespace App\Http\Controllers;

class InfoController extends Controller
{
    /** Marketing maslahatlari — barcha autentifikatsiyalangan foydalanuvchilar uchun. */
    public function tips()
    {
        return view('info.tips', ['tips' => __('info.tips')]);
    }

    /** Savol-javob (FAQ). */
    public function faq()
    {
        return view('info.faq', ['faqs' => __('info.faq')]);
    }

    /** E'lonlar / yangiliklar. */
    public function news()
    {
        return view('info.news', ['news' => __('info.news')]);
    }
}
