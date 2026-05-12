@extends('layouts.admin')

@section('title', 'Create Post')

@section('content')
    <form action="{{ route('admin.posts.store') }}" method="POST">
        @csrf
        @include('admin.posts.form')
    </form>
@endsection
