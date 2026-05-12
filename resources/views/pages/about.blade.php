@extends('layouts.app')

@section('title', 'About Us - LokSewaAlert')
@section('description', 'Learn about LokSewaAlert - Your trusted Nepal government job portal')

@section('content')
    <style>
        .modern-page-content {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid #e9ecef;
        }
        .modern-page-content h1, .modern-page-content h2 {
            color: #1a202c;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .modern-page-content p {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 14px;
        }
        .modern-page-content li {
            color: #4a5568;
            font-size: 14px;
        }
    </style>

    <div class="modern-page-content rounded-lg shadow-md p-6 md:p-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-building"></i> About LokSewaAlert</h1>
        
        <div class="space-y-4 text-sm">
            <p>
                LokSewaAlert is Nepal's leading government job portal dedicated to helping job seekers find the best government employment opportunities across Nepal.
            </p>
            
            <h2 class="text-lg font-bold text-gray-800 mt-6 mb-3"><i class="fas fa-bullseye"></i> Our Mission</h2>
            <p>
                Our mission is to provide a comprehensive platform where candidates can easily access and apply for government jobs, view admit cards, check results, and access study materials all in one place.
            </p>
            
            <h2 class="text-lg font-bold text-gray-800 mt-6 mb-3"><i class="fas fa-star"></i> What We Offer</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>Latest Government Job Notifications</li>
                <li>Admit Cards for Various Exams</li>
                <li>Exam Results and Answer Keys</li>
                <li>Syllabus and Exam Patterns</li>
                <li>Expert Tips and Preparation Guides</li>
                <li>State-wise and Category-wise Filtering</li>
            </ul>
            
            <h2 class="text-lg font-bold text-gray-800 mt-6 mb-3"><i class="fas fa-check-circle"></i> Why Choose LokSewaAlert?</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>Comprehensive coverage of all Nepal government job sectors</li>
                <li>Real-time updates on Lok Sewa notifications and results</li>
                <li>User-friendly interface for easy navigation</li>
                <li>Reliable and accurate information</li>
                <li>Free access to all job listings</li>
            </ul>
            
            <h2 class="text-lg font-bold text-gray-800 mt-6 mb-3"><i class="fas fa-envelope"></i> Contact Us</h2>
            <p>
                For any queries or suggestions, feel free to reach out to us at <a href="mailto:loksewaalert@gmail.com" class="text-blue-600 hover:text-blue-800 font-semibold transition">loksewaalert@gmail.com</a>
            </p>
        </div>
    </div>
@endsection
