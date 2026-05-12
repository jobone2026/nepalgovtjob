@extends('layouts.app')

@section('title', 'Contact Us - JobOne.in')
@section('description', 'Get in touch with JobOne.in for any queries or feedback')

@section('content')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="glass-effect rounded-lg shadow-md p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">Contact Us</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Contact Information -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Get in Touch</h2>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Email</h3>
                    <p class="text-gray-700">
                        <a href="mailto:jobone2026@gmail.com" class="text-red-600 hover:text-red-700 font-semibold">jobone2026@gmail.com</a>
                    </p>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Website</h3>
                    <p class="text-gray-700">
                        <a href="https://jobone.in" class="text-red-600 hover:text-red-700 font-semibold">jobone.in</a>
                    </p>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Response Time</h3>
                    <p class="text-gray-700">
                        We typically respond to all inquiries within 24-48 hours.
                    </p>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Send us a Message</h2>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Name</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-600" placeholder="Your Name">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-600" placeholder="Your Email">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Subject</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-600" placeholder="Subject">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Message</label>
                        <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-600" rows="5" placeholder="Your Message"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-red-600 text-white font-semibold py-2 rounded-lg hover:bg-red-700">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
