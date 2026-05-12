@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Icon -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-red-100 to-orange-100 rounded-full">
                <i class="fas fa-exclamation-triangle text-6xl text-red-600"></i>
            </div>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">500</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Internal Server Error</h2>
        <p class="text-gray-600 mb-8">
            Oops! Something went wrong on our end. Our team has been notified and we're working to fix it.
        </p>

        @if(config('app.debug') && isset($exception))
        <!-- Debug Information (Development Only) -->
        <div class="bg-gray-900 text-left rounded-lg p-6 mb-8 overflow-auto max-h-96">
            <div class="text-red-400 font-mono text-sm mb-4">
                <strong>{{ get_class($exception) }}</strong>: {{ $exception->getMessage() }}
            </div>
            <div class="text-gray-300 font-mono text-xs">
                <strong>File:</strong> {{ $exception->getFile() }}<br>
                <strong>Line:</strong> {{ $exception->getLine() }}
            </div>
            <div class="mt-4 text-gray-400 font-mono text-xs">
                <strong>Stack Trace:</strong>
                <pre class="mt-2 whitespace-pre-wrap">{{ $exception->getTraceAsString() }}</pre>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center">
            <button onclick="window.history.back()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                <i class="fas fa-arrow-left"></i> Go Back
            </button>
            <a href="{{ url('/') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-home"></i> Go Home
            </a>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-sm text-gray-500">
            If this problem persists, please contact us at 
            <a href="mailto:jobone2026@gmail.com" class="text-blue-600 hover:text-blue-700">jobone2026@gmail.com</a>
        </div>
    </div>
</div>
@endsection
