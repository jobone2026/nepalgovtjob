@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full text-center">
        <!-- Rate Limit Icon -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-orange-100 to-red-100 rounded-full">
                <i class="fas fa-tachometer-alt text-6xl text-orange-600"></i>
            </div>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">429</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Too Many Requests</h2>
        <p class="text-gray-600 mb-4">
            You've made too many requests in a short period. Please slow down.
        </p>

        @php
            $retryAfter = request()->header('Retry-After') ?? 60;
        @endphp

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-8">
            <p class="text-orange-800 font-semibold">
                Please wait <span class="text-2xl font-bold">{{ $retryAfter }}</span> seconds before trying again.
            </p>
        </div>

        <!-- Countdown Timer -->
        <div x-data="{ countdown: {{ $retryAfter }} }" x-init="setInterval(() => { if (countdown > 0) countdown-- }, 1000)">
            <div class="mb-8">
                <div class="text-4xl font-bold text-blue-600" x-text="countdown"></div>
                <div class="text-sm text-gray-600">seconds remaining</div>
            </div>
            
            <button 
                @click="if (countdown === 0) window.location.reload()" 
                :disabled="countdown > 0"
                :class="countdown > 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 cursor-pointer'"
                class="px-6 py-3 text-white rounded-lg font-medium transition">
                <i class="fas fa-sync-alt"></i> <span x-text="countdown > 0 ? 'Please Wait...' : 'Try Again'"></span>
            </button>
        </div>

        <!-- Home Link -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium mt-4">
            <i class="fas fa-home"></i>
            Back to Homepage
        </a>
    </div>
</div>
@endsection
