@extends('layouts.app')

@section('content')
    <h1>Category Details</h1>

    <p><strong>Name:</strong> {{ $category->name }}</p>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
@endsection