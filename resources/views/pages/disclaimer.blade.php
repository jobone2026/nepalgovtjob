@extends('layouts.app')

@section('title', 'Disclaimer - JobOne.in')
@section('description', 'Disclaimer for JobOne.in')

@section('content')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="glass-effect rounded-lg shadow-md p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">Disclaimer</h1>
        
        <div class="prose prose-lg max-w-none text-gray-700">
            <p class="mb-4">
                <strong>Last Updated:</strong> March 2026
            </p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">1. General Disclaimer</h2>
            <p class="mb-4">
                The information provided on JobOne.in is for general informational purposes only. While we strive to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability, or availability with respect to the website or the information, products, services, or related graphics contained on the website.
            </p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">2. External Links</h2>
            <p class="mb-4">
                JobOne.in may contain links to external websites. We are not responsible for the content, accuracy, or practices of these external sites. Your use of external websites is at your own risk and subject to the terms and conditions of those websites.
            </p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">3. Job Information</h2>
            <p class="mb-4">
                All job notifications, admit cards, results, and other information published on JobOne.in are sourced from official government websites and organizations. We do not guarantee the accuracy or completeness of this information. Candidates are advised to verify all information from official sources before applying.
            </p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">4. Limitation of Liability</h2>
            <p class="mb-4">
                In no event shall JobOne.in, its directors, employees, or agents be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the website or the information contained therein.
            </p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">5. Changes to Disclaimer</h2>
            <p class="mb-4">
                We reserve the right to modify this disclaimer at any time. Changes will be effective immediately upon posting to the website.
            </p>
            
            <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">6. Contact Us</h2>
            <p class="mb-4">
                If you have any questions about this Disclaimer, please contact us at:
            </p>
            <p class="mb-4">
                Email: <a href="mailto:jobone2026@gmail.com" class="text-red-600 hover:text-red-700 font-semibold">jobone2026@gmail.com</a>
            </p>
        </div>
    </div>
@endsection
