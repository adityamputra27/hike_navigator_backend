<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hike Navigator | Admin @yield('title')</title>
    <link rel="stylesheet" href="{{asset('polished/polished.min.css')}}">
    <link rel="stylesheet" href="{{asset('polished/iconic/css/open-iconic-bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.4.3/mapbox-gl-draw.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.4.3/mapbox-gl-draw.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <style>
        .grid-highlight {
            padding-top: 1em;
            padding-bottom: 1em;
            background-color: #5c6ac4;
            border: 1px solid #202e78;
            color: #fff;
        }
        hr {
            margin: 6rem 0;
        }
        hr+.display-3, 
        hr+.display-2+.display-3 {
            margin-bottom: 2em;
        }
        @media (min-width:992px) {
            .modal-lg,.modal-xl {
                max-width:800px}
            }
        @media (min-width:1200px) {
            .modal-xl {
                max-width:1140px
            }
        }
        /* custom marker */
        .mountain_markers {
            background-image: url("{{ asset('images/mountain_marker.png') }}");
        }
        .waterfall_markers {
            background-image: url("{{ asset('images/waterfalls.png') }}");
        }
        .post_markers {
            background-image: url("{{ asset('images/posts.png') }}");
        }
        .waterspring_markers {
            background-image: url("{{ asset('images/watersprings.png') }}");
        }
        .river_markers {
            background-image: url("{{ asset('images/rivers.png') }}");
        }
        .mark_markers {
            background-image: url("{{ asset('images/marks.png') }}");
        }
        .cross_road_markers {
            background-image: url("{{ asset('images/cross_road_marker.png') }}");
        }
        .mountain_markers {
            background-size: cover;
            width: 50px;
            height: 50px;
            cursor: pointer;
        }
        .waterfall_markers,
        .river_markers,
        .waterspring_markers,
        .post_markers,
        .mark_markers,
        .cross_road_markers {
            background-size: cover;
            width: 35px;
            height: 35px;
            cursor: pointer;
        }
        .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .dropzone.dz-started .dz-message {
            display: block !important;
        }
        .dropzone {
            border: 2px dashed #5c6ac4 !important;
        }
        .dropzone .dz-preview.dz-complete .dz-success-mark {
            opacity: 1;
        }
        .dropzone .dz-preview.dz-error .dz-success-mark {
            opacity: 0;
        }
        .dropzone .dz-preview .dz-error-message {
            top: 144px;
        }
        /* end */
    </style>
    <script type="text/javascript">
        document.documentElement.className =
        document.documentElement.className.replace('no-js', 'js') +
        (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1") ? ' svg' : ' no-svg')
    </script>
</head>
<body>
    <nav class="navbar navbar-expand p-1">
        <a href="#" class="navbar-brand text-center col-xs-12 col-md-3 col-lg-2 mr-0">Hike Navigator | Admin</a>
        <button class="btn btn-link d-block d-md-none" data-toggle="collapse" data-target="#sidebar-nav" role="button">
            <span class="oi oi-menu"></span>
        </button>
        <input type="text" placeholder="Search" class="border-dark bg-primary-darkest form-control d-none d-md-block w-50 ml-3 mr-2">
        <div class="dropdown d-none d-md-block">
            @if(\Auth::user())
                <button class="btn btn-link btn-link-primary dropdown-toggle" id="navbar-dropdown" data-toggle="dropdown">{{Auth::user()->name}}</button>
            @endif
            <div class="dropdown-menu dropdown-menu-right" id="navbardropdown">
            <a href="#" class="dropdown-item">Profile</a>
            <a href="#" class="dropdown-item">Setting</a>
            <div class="dropdown-divider"></div>
                <li>
                    <form action="{{route("logout")}}" method="POST">
                        @csrf
                        <button class="dropdown-item" style="cursor:pointer">Sign Out</button>
                    </form>
                </li>
            </div>
        </div>
    </nav>
    <div class="container-fluid h-100 p-0">
        <div style="min-height: 100%" class="flex-row d-flex align-itemsstretch m-0">
            <div class="polished-sidebar bg-light col-12 col-md-3 col-lg-2 p-0 collapse d-md-inline" id="sidebar-nav">
                <ul class="polished-sidebar-menu ml-0 pt-4 p-0 d-md-block">
                    <input class="border-dark form-control d-block d-md-none mb-4" type="text" placeholder="Search" aria-label="Search" />
                    <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="/dashboard" style="font-weight: bold;"><span class="oi oi-home"></span>Dashboard</a>
                    </li>
                    <div class="pt-3">
                        <a href="#" class="pl-3 fs-smallest fw-bold text-muted">API MOBILE NAVIGATION </a>
                    </div>
                    <li class="
                        {{ request()->routeIs('peaks.*') 
                        ? 'active' : '' }}">
                        <a href="/peaks" style="font-weight: bold;"><span class="oi oi-menu"></span>Data Master</a>
                    </li>
                    <li class="{{ request()->routeIs('mountains.*') ? 'active' : '' }}">
                        <a href="/mountains" style="font-weight: bold;"><span class="oi oi-map"></span>Daftar Destinasi</a>
                    </li>
                    <li class="{{ request()->routeIs('climbing_plans.*') ? 'active' : '' }}">
                        <a href="/climbing_plans" style="font-weight: bold;"><span class="oi oi-location"></span>Rencana Pendakian</a>
                    </li>
                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="/users" style="font-weight: bold;"><span class="oi oi-people"></span>Manage Users</a>
                    </li>
                    <div class="pt-3">
                        <a href="#" class="pl-3 fs-smallest fw-bold text-muted">ADMOB NAVIGATION </a> 
                        <ul class="list-unstyled">
                            <li class="" style="font-weight: bold;"><a href="#"><span class="oi oi-bullhorn"></span>Admob Configuration</a></li>
                        </ul>
                    </div>
                    <div class="pt-3">
                        <a href="#" class="pl-3 fs-smallest fw-bold text-muted">OTHER NAVIGATION </a> 
                        <ul class="list-unstyled">
                            <li class=""><a href="/settings" style="font-weight: bold;"><span class="oi oi-cog"></span>Settings</a></li>
                            <li class=""><a href="#" style="font-weight: bold;"><span class="oi oi-pencil"></span>Edit Profile</a></li>
                        </ul>
                    </div>
                    <div class="d-block d-md-none">
                        <div class="dropdown-divider"></div>
                        <li><a href="#" style="font-weight: bold;"> Profile</a></li>
                        <li><a href="#" style="font-weight: bold;"> Setting</a></li>
                        <li>
                            <form action="{{route("logout")}}" method="POST">
                            @csrf
                            <button class="dropdown-item"
                                style="cursor:pointer">Sign Out</button>
                            </form>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="col-lg-10 col-md-9 p-4">
                <div class="row">
                    <div class="col-md-12 pl-3 pt-2">
                        <div class="pl-3">
                        <h3>@yield("title")</h3>
                        <br/>
                        </div>
                    </div>
                </div>
                @yield("content")
            </div>
        </div>
     </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    @stack('map-scripts')
    @yield('scripts')
</body>
</html>