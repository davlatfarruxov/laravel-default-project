@extends('layouts.app')

@section('title', '500 · Laravel Default')

@section('content')

<div class="bg-[#F8FAFC] dark:bg-gray-900 min-h-screen w-full flex items-center justify-center relative overflow-hidden">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[30rem] h-[30rem] bg-rose-600/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 text-center px-4 max-w-lg mx-auto flex flex-col items-center py-20">

        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white dark:bg-gray-800 border border-rose-100 dark:border-rose-900/30 shadow-xl shadow-rose-100/50 text-rose-600 mb-6">
            <x-lucide-server-crash class="w-10 h-10" />
        </div>

        <h1 class="text-7xl sm:text-9xl font-black leading-none tracking-tighter text-gray-900 dark:text-white mb-2">
            500
        </h1>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Server xatosi</h2>

        <p class="text-gray-500 dark:text-gray-400 text-base mb-8 font-medium">
            Serverda kutilmagan xatolik yuz berdi. Iltimos, keyinroq qayta urinib ko'ring.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 w-full sm:w-auto">
            <button onclick="window.location.reload()" class="w-full sm:w-auto px-6 py-3.5 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/30 hover:-translate-y-0.5 flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
                <x-lucide-refresh-cw class="w-5 h-5" /> Sahifani yangilash
            </button>
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-2xl font-bold border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                <x-lucide-home class="w-5 h-5" /> Bosh sahifa
            </a>
        </div>

    </div>
</div>

@endsection
