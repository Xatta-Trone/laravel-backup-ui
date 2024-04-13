<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Laravel Backups UI</title>
</head>

<body class="bg-gray-100">

    <nav class="bg-gray-800 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a class="text-white text-2xl font-bold" href="{{ route('laravel-backups.index') }}">Laravel Backups
                    UI</a>
                <button class="text-white focus:outline-none md:hidden" id="menu-toggle">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path v-if="!isOpen" fill-rule="evenodd" clip-rule="evenodd"
                            d="M4 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zM4 12h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zM4 18h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                        <path v-else fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.293 6.293a1 1 0 0 1 1.414 0l4.243 4.243 4.243-4.243a1 1 0 0 1 1.414 1.414l-4.95 4.95a1 1 0 0 1-1.414 0l-4.95-4.95a1 1 0 0 1 0-1.414z" />
                    </svg>
                </button>
                <div class="hidden md:flex items-center" id="navbarNav">
                    <ul class="ml-auto flex">
                        <li class="nav-item">
                            <a class="nav-link active text-white" target="_blank"
                                href="https://github.com/Xatta-Trone/laravel-backup-ui">Github</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8">
        <div class="md:flex md:justify-between">
            <div class="mb-4 md:mb-0 md:w-1/3 mr-2">
                <label for="disks" class="block mb-1">Select Disk</label>
                <select id="disks"
                    class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2">
                    @foreach ($disks as $disk)
                        <option value="{{ $disk }}" @if (request()->get('disk', 'local') == $disk) selected @endif>
                            {{ ucfirst($disk) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4 md:mb-0 md:w-1/3 mr-2">
                <label for="sort" class="block mb-1">Sort Order</label>
                <select id="sort"
                    class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2">
                    <option value="asc" @if (request()->get('sort', 'asc') == 'asc') selected @endif>Oldest First</option>
                    <option value="desc" @if (request()->get('sort', 'asc') == 'desc') selected @endif>Newest First</option>
                </select>
            </div>
            <div class="mb-4 md:mb-0 md:w-1/3 mr-2">
                <label for="pagination" class="block mb-1">Page Limit</label>
                <select id="pagination"
                    class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2">
                    <option value="10" @if (request()->get('per_page', 10) == 10) selected @endif>10</option>
                    <option value="20" @if (request()->get('per_page', 10) == 20) selected @endif>20</option>
                    <option value="30" @if (request()->get('per_page', 10) == 30) selected @endif>30</option>
                    <option value="40" @if (request()->get('per_page', 10) == 40) selected @endif>40</option>
                    <option value="50" @if (request()->get('per_page', 10) == 50) selected @endif>50</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            {{-- Alerts start --}}
            @foreach (['success', 'danger', 'info'] as $alert)
                @if (Session::has($alert))
                    <div class="alert alert-{{ $alert }} text-center" role="alert">
                        {{ Session::get($alert) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            @endforeach
            {{-- Alerts end --}}
        </div>

        {{-- Show total backups --}}
        <h3 class="mt-4 mb-2">Total Backups Count: {{ $paginate->total() }}</h3>
        {{-- Show total backups end --}}

        {{-- Table Start --}}
        <div class="mt-4 table-responsive">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">File Name</th>
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2">Last Modified</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paginate->items() as $index=>$file)
                        <tr>
                            <td class="border px-4 py-2">
                                {{ $index + 1 + (request()->get('page', 1) - 1) * request()->get('per_page', 10) }}
                            </td>
                            <td class="border px-4 py-2">{{ $file['name'] }}</td>
                            <td class="border px-4 py-2">{{ $file['size'] }}</td>
                            <td class="border px-4 py-2">
                                {{ \Carbon\Carbon::parse($file['last_modified'])->format('Y-m-d H:i:s') }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ $file['download_url'] }}"
                                    class="btn btn-primary inline-block py-1 px-2 rounded-md bg-blue-500 text-white">Download</a>
                                <form class="form_delete inline-block" action="{{ $file['delete_url'] }}"
                                    method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="button"
                                        class="btn btn-danger inline-block py-1 px-2 rounded-md bg-red-500 text-white deletebutton">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="border px-4 py-2 text-center" colspan="5">No files found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $paginate->withQueryString()->links('pagination::tailwind') }}
            </div>
        </div>
        {{-- Table End --}}
    </div>

    <!-- jQuery first -->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    </script>
    <script type="text/javascript">
        // handle menu toggle
        $('#menu-toggle').click(function() {
            $('#navbarNav').toggleClass('hidden');
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
