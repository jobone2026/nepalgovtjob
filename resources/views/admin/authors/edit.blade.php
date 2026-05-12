@extends('layouts.admin')

@section('title', 'Edit Author')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Author</h2>
            
            <form action="{{ route('admin.authors.update', $author) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $author->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $author->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-gray-700 font-bold mb-2">New Password (leave blank to keep current)</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="bio" class="block text-gray-700 font-bold mb-2">Bio</label>
                    <textarea id="bio" name="bio" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">{{ old('bio', $author->bio) }}</textarea>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $author->is_active ? 'checked' : '' }} class="mr-2">
                    <label for="is_active" class="text-gray-700 font-bold">Active</label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Update Author
                    </button>
                    <a href="{{ route('admin.authors.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
