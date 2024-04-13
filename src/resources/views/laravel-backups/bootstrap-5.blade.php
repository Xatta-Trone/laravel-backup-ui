<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Laravel Backups UI</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('laravel-backups.index') }}">Laravel Backups UI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" target="_blank"
                        href="https://github.com/Xatta-Trone/laravel-backup-ui">Github</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row mt-2">
            <div class="col-12 col-md-4">
                <label for="disks">Select Disk</label>
                <select id="disks" class="form-control">
                    @foreach ($disks as $disk)
                        <option value="{{ $disk }}" @if (request()->get('disk', 'local') == $disk) selected @endif>
                            {{ ucfirst($disk) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="sort">Sort Order</label>
                <select id="sort" class="form-control">
                    <option value="asc" @if (request()->get('sort', 'asc') == 'asc') selected @endif>Oldest First</option>
                    <option value="desc" @if (request()->get('sort', 'asc') == 'desc') selected @endif>Newest First</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="pagination">Page Limit</label>
                <select id="pagination" class="form-control">
                    <option value="10" @if (request()->get('per_page', 10) == 10) selected @endif>10</option>
                    <option value="20" @if (request()->get('per_page', 10) == 20) selected @endif>20</option>
                    <option value="30" @if (request()->get('per_page', 10) == 30) selected @endif>30</option>
                    <option value="40" @if (request()->get('per_page', 10) == 40) selected @endif>40</option>
                    <option value="50" @if (request()->get('per_page', 10) == 50) selected @endif>50</option>
                </select>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-12">
                {{-- Alerts start --}}
                @foreach (['success', 'danger', 'info'] as $alert)
                    @if (Session::has($alert))
                        <div class="alert alert-{{ $alert }}" role="alert">
                            {{ Session::get($alert) }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                @endforeach
                {{-- Alerts end --}}
            </div>
        </div>

        {{-- Show total backups --}}
        <h3 class="my-2">Total Backups Count: {{ $paginate->total() }}</h3>
        {{-- Show total backups end --}}

        {{-- Table Start --}}
        <div class="row table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">File Name</th>
                        <th scope="col">Size</th>
                        <th scope="col">Last Modified</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paginate->items() as $index=>$file)
                        <tr>
                            <th scope="row">
                                {{ $index + 1 + (request()->get('page', 1) - 1) * request()->get('per_page', 10) }}
                            </th>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($file['last_modified'])->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <a href="{{ $file['download_url'] }}" class="btn btn-primary">Download</a>
                                <form class="form_delete d-inline-block" action="{{ $file['delete_url'] }}"
                                    method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="button d-inline-block" class="btn btn-danger deletebutton">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No files found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $paginate->withQueryString()->links() }}
        </div>
        {{-- Table End --}}


    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>


    <script type="text/javascript">
        // handle pagination
        document.getElementById('pagination').onchange = function(e) {
            console.log(e.target.value)
            // Get the current query parameters
            const queryParams = new URLSearchParams(window.location.search);
            const perPage = e.target.value ?? 10;
            queryParams.set('per_page', perPage);
            queryParams.set('page', 1);
            window.history.replaceState({}, '', `${window.location.pathname}?${queryParams.toString()}`);
            // Reload the page with the updated URL
            window.location.reload();
        };
        // handle sorting
        document.getElementById('sort').onchange = function(e) {
            console.log(e.target.value)
            // Get the current query parameters
            const queryParams = new URLSearchParams(window.location.search);
            const perPage = e.target.value ?? 10;
            queryParams.set('sort', perPage);
            queryParams.set('page', 1);
            window.history.replaceState({}, '', `${window.location.pathname}?${queryParams.toString()}`);
            // Reload the page with the updated URL
            window.location.reload();
        };
        // handle disks change
        document.getElementById('disks').onchange = function(e) {
            console.log(e.target.value)
            // Get the current query parameters
            const queryParams = new URLSearchParams(window.location.search);
            const disk = e.target.value ?? 'local';
            queryParams.set('disk', disk);
            queryParams.set('page', 1);
            window.history.replaceState({}, '', `${window.location.pathname}?${queryParams.toString()}`);
            // Reload the page with the updated URL
            window.location.reload();
        };

        $('.deletebutton').on('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete?')) {
                $(this).closest('form').submit();
            }
        });
    </script>
</body>

</html>
