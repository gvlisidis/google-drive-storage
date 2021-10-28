@extends('welcome')


@section('content')
    <div class="container">
        <form action="{{ route('folders.store') }}" method="POST">
            @csrf
            <label for="parent_folder_id">Select Parent Folder</label>
            <select name="parent_folder_id" id="parent_folder_id" class="form-control">
                @foreach($folders as $folder)
                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                @endforeach
            </select>
            <label for="name">Folder name</label>
            <input type="text" id="name" name="name" class="form-control" />
            <br/>
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
