@extends('welcome')


@section('content')
    <div class="container">
        <div>
            <a href="{{ route('files.create') }}" class="btn btn-success">Create</a>
        </div>
        <table class="table">
            <thead>
               <tr>
                   <th>ID</th>
                   <th>GOOGLE ID</th>
                   <th>Name</th>
                   <th>Actions</th>
               </tr>
            </thead>
            <tbody>
                @forelse($files as $file)
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->google_file_id }}</td>
                        <td>{{ $file->name }}</td>
                        <td class="" style="display: flex">
                            <form action="{{ route('files.download', $file) }}" method="POST" style="margin-right: 10px">
                                @csrf
                                <button class="btn btn-success">Download</button>
                            </form>
                            <form action="{{ route('files.delete', $file) }}" method="POST" style="margin-right: 10px">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                            <a href="{{ route('files.share', $file) }}" class="btn btn-primary" style="margin-right: 10px">Share</a>
                            <a href="{{ route('files.move', $file) }}" class="btn btn-info">Move</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No files yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
