@extends('layouts.app')

@section('title', '401 · Laravel Default')

@section('content')

<div class="bg-[#F8FAFC] dark:bg-gray-900 min-h-screen w-full flex items-center justify-center relative overflow-hidden">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[30rem] h-[30rem] bg-orange-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 text-center px-4 max-w-lg mx-auto flex flex-col items-center py-20">

        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white dark:bg-gray-800 border border-orange-100 dark:border-orange-900/30 shadow-xl shadow-orange-100/50 text-orange-500 mb-6">
            <x-lucide-key class="w-10 h-10" />
        </div>

        <h1 class="text-7xl sm:text-9xl font-black leading-none tracking-tighter text-gray-900 dark:text-white mb-2">
            401
        </h1>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Kirish taqiqlangan</h2>

        <p class="text-gray-500 dark:text-gray-400 text-base mb-8 font-medium">
            Bu sahifaga kirish uchun avval tizimga kirishingiz kerak.
        </p>

        <div class="flex items-center justify-center w-full sm:w-auto">
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3.5 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/30 hover:-translate-y-0.5 flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
                <x-lucide-log-in class="w-5 h-5" /> Tizimga kirish
            </a>
        </div>

    </div>
</div>

@endsection
