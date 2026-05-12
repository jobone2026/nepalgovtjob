@php
    // Redirect back with flash message
    if (!request()->ajax()) {
        session()->flash('error', 'Your session expired. Please try again.');
        header('Location: ' . url()->previous());
        exit;
    }
@endphp

@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full text-center">
        <!-- Session Icon -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-full">
                <i class="fas fa-clock text-6xl text-yellow-600"></i>
            </div>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">419</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Session Expired</h2>
        <p class="text-gray-600 mb-8">
            Your session has expired due to inactivity. Please refresh the page and try again.
        </p>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center">
            <button onclick="window.location.reload()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-sync-alt"></i> Refresh Page
            </button>
            <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                <i class="fas fa-home"></i> Go Home
            </a>
        </div>
    </div>
</div>
@endsection
