<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Semantic UI CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css"
        integrity="sha512-KXol4x3sVoO+8ZsWPFI/r5KBVB/ssCGB5tsv2nVOKwLg33wTFP3fmnXa47FdSVIshVTgsYk/1734xSk9aFIa4A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Laravel Backups UI</title>
</head>

<body>

    <nav class="ui fixed inverted menu">
        <div class="ui container">
            <a href="{{ route('laravel-backups.index') }}" class="header item">Laravel Backups UI</a>
            <div class="right menu">
                <a href="https://github.com/Xatta-Trone/laravel-backup-ui" class="item" target="_blank">Github</a>
            </div>
            <a href="#" class="item" id="menu-toggle"><i class="sidebar icon"></i></a>
        </div>
    </nav>

    <div class="ui container" style="margin-top: 80px;">
        <div class="ui form">
            <div class="fields">
                <div class="field">
                    <label>Select Disk</label>
                    <select id="disks" class="ui dropdown">
                        @foreach ($disks as $disk)
                            <option value="{{ $disk }}" @if (request()->get('disk', 'local') == $disk) selected @endif>
                                {{ ucfirst($disk) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Sort Order</label>
                    <select id="sort" class="ui dropdown">
                        <option value="asc" @if (request()->get('sort', 'asc') == 'asc') selected @endif>Oldest First</option>
                        <option value="desc" @if (request()->get('sort', 'asc') == 'desc') selected @endif>Newest First</option>
                    </select>
                </div>
                <div class="field">
                    <label>Page Limit</label>
                    <select id="pagination" class="ui dropdown">
                        <option value="10" @if (request()->get('per_page', 10) == 10) selected @endif>10</option>
                        <option value="20" @if (request()->get('per_page', 10) == 20) selected @endif>20</option>
                        <option value="30" @if (request()->get('per_page', 10) == 30) selected @endif>30</option>
                        <option value="40" @if (request()->get('per_page', 10) == 40) selected @endif>40</option>
                        <option value="50" @if (request()->get('per_page', 10) == 50) selected @endif>50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="ui hidden divider"></div>

        {{-- Alerts start --}}
        @foreach (['success', 'danger', 'info'] as $alert)
            @if (Session::has($alert))
                <div class="ui message {{ $alert }}" role="alert">
                    <i class="close icon"></i>
                    <div class="header">
                        {{ $alert }}
                    </div>
                    <p>{{ Session::get($alert) }}</p>
                </div>
            @endif
        @endforeach
        {{-- Alerts end --}}

        {{-- Show total backups --}}
        <h3 class="mt-4 mb-2">Total Backups Count: {{ $paginate->total() }}</h3>
        {{-- Show total backups end --}}

        {{-- Table Start --}}
        <table class="ui celled table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                    @forelse ($paginate->items() as $index=>$file)
                        <tr>
                            <td>
                                {{ $index + 1 + (request()->get('page', 1) - 1) * request()->get('per_page', 10) }}
                            </td>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($file['last_modified'])->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <a href="{{ $file['download_url'] }}" class="ui button primary">Download</a>
                                <form class="form_delete d-inline-block" action="{{ $file['delete_url'] }}"
                                    method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="button d-inline-block" class="ui button negative">
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

        {{ $paginate->withQueryString()->links('pagination::semantic-ui') }}
        {{-- Table End --}}
    </div>

    <!-- Semantic UI JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js"
        integrity="sha512-Xo0Jh8MsOn72LGV8kU5LsclG7SUzJsWGhXbWcYs2MAmChkQzwiW/yTQwdJ8w6UA9C6EVG18GHb/TrYpYCjyAQw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // handle menu toggle
        $('#menu-toggle').click(function() {
            $('.ui.sidebar').sidebar('toggle');
        });

        // handle pagination
        $('#pagination').on('change', function(e) {
            handleQueryParamsChange('per_page', e.target.value);
        });

        // handle sorting
        $('#sort').on('change', function(e) {
            handleQueryParamsChange('sort', e.target.value);
        });

        // handle disks change
        $('#disks').on('change', function(e) {
            handleQueryParamsChange('disk', e.target.value);
        });

        // function to handle query parameter changes
        function handleQueryParamsChange(param, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(param, value);
            url.searchParams.set('page', 1);
            window.location.href = url;
        }

        $('.deletebutton').on('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete?')) {
                $(this).closest('form').submit();
            }
        });
    </script>

</body>

</html>
