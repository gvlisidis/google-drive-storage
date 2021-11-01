@extends('welcome')


@section('content')
    <div class="container">
        <div>
            <a href="{{ route('folders.create') }}" class="btn btn-success">Create</a>
        </div>
        @if (session('status'))
            <div class="alert alert-success" style="margin: 10px 0">
                {{ session('status') }}
            </div>
        @endif
        <table class="table">
            <thead>
               <tr>
                   <th>ID</th>
                   <th>GOOGLE ID</th>
                   <th>Name</th>
                   <th>No. Of Files</th>
                   <th>Actions</th>
               </tr>
            </thead>
            <tbody>
                @forelse($folders as $folder)
                    <tr>
                        <td>{{ $folder->id }}</td>
                        <td>{{ $folder->google_folder_id }}</td>
                        <td>{{ $folder->name }}</td>
                        <td>{{ $folder->files_count }}</td>
                        <td style="display: flex">
                            <form action="{{ route('folders.download', $folder) }}" method="POST" style="margin-right: 10px">
                                @csrf
                                <button class="btn btn-success">Download</button>
                            </form>
                            <form action="{{ route('folders.delete', $folder) }}" method="POST" style="margin-right: 10px">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                            <a href="{{ route('folders.share', $folder) }}" class="btn btn-primary" style="margin-right: 10px">Share</a>
                            <a href="{{ route('folders.move', $folder) }}" class="btn btn-info" style="margin-right: 10px">Move</a>
                            <a href="{{ route('folders.copy', $folder) }}" class="btn btn-info" style="margin-right: 10px">Copy</a>
                            <a href="{{ route('folders.rename', $folder) }}" class="btn btn-info" style="margin-right: 10px">Rename</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No folders yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
