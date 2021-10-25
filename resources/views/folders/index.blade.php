@extends('welcome')


@section('content')
    <div class="container">
        <div>
            <a href="{{ route('folders.create') }}" class="btn btn-success">Create</a>
        </div>
        <table class="table">
            <thead>
               <tr>
                   <th>ID</th>
                   <th>GOOGLE ID</th>
                   <th>Name</th>
                   <th>Root</th>
                   <th># Of Files</th>
               </tr>
            </thead>
            <tbody>
                @forelse($folders as $folder)
                    <tr>
                        <td>{{ $folder->id }}</td>
                        <td>{{ $folder->google_folder_id }}</td>
                        <td>{{ $folder->name }}</td>
                        <td>True</td>
                        <td>0</td>
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
