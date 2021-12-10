@extends('layouts.app')

@section('content')
<div class="container">
    @foreach ($product as $a)
    <div class="alert alert-success" role="alert">
        {{$a->name}}
        <a href="#">Edit</a>
        @can('delete', \App\Models\Product::class)
        <a href="{{$a->id}}/delete-product">Delete</a>
        @endcan
    </div>    
    @endforeach

</div>
@endsection