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
                   <th>Type</th>
               </tr>
            </thead>
            <tbody>
                @forelse($files as $file)
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->google_file_id }}</td>
                        <td>{{ $file->name }}</td>
                        <td>{{ $file->type }}</td>
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
