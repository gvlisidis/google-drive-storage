@extends('welcome')


@section('content')
    <div class="container">
        <form action="{{ route('folders.store') }}" method="POST">
            @csrf
            <label for="folder_name">Folder name</label>
            <input type="text" id="folder_name" name="folder_name" class="form-control" />
            <br/>
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
