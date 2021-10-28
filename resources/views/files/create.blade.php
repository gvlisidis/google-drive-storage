@extends('welcome')


@section('content')
    <div class="container">
        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="folder_id">Select Folder</label>
            <select name="folder_id" id="folder_id" class="form-control">
                @foreach($folders as $folder)
                    <option value="{{ $folder->google_folder_id }}">{{ $folder->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="name">Choose File name</label>
            <input type="text" id="name" name="name" class="form-control" />
            <br>
            <label for="the_file">File For Upload</label>
            <input type="file" id="the_file" name="the_file" class="form-control" />
            <br/>
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
