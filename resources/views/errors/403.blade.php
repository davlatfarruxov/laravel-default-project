@extends('layouts.app')

@section('title', '403 · Laravel Default')

@section('content')

<div class="bg-[#F8FAFC] dark:bg-gray-900 min-h-[calc(100vh-5.1rem)] w-full flex items-center justify-center relative overflow-hidden">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[30rem] h-[30rem] bg-gray-900/10 dark:bg-gray-100/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 text-center px-4 max-w-lg mx-auto flex flex-col items-center py-20">

        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl shadow-gray-200/50 text-gray-800 dark:text-gray-200 mb-6">
            <x-lucide-lock class="w-10 h-10" />
        </div>

        <h1 class="text-7xl sm:text-9xl font-black leading-none tracking-tighter text-gray-900 dark:text-white mb-2">
            403
        </h1>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Ruxsat yo'q</h2>

        <p class="text-gray-500 dark:text-gray-400 text-base mb-8 font-medium">
            Bu sahifaga kirish huquqingiz yo'q.
        </p>

        <div class="flex items-center justify-center w-full sm:w-auto">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3.5 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-bold hover:bg-black dark:hover:bg-gray-600 transition-all shadow-lg shadow-gray-900/30 hover:-translate-y-0.5 flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
                <x-lucide-home class="w-5 h-5" /> Bosh sahifa
            </a>
        </div>

    </div>
</div>

@endsection
