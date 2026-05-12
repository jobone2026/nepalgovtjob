@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('admin.posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.posts.form')
    </form>
@endsection
