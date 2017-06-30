{{-- ADMIN MAIN NAVBAR --}}

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button"
                    class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#navbar"
                    aria-expanded="false"
                    aria-controls="navbar"
                ><span class="sr-only">Toggle navigation</span
                ><span class="icon-bar"></span
                ><span class="icon-bar"></span
                ><span class="icon-bar"></span></button
            >
            <a class="navbar-brand" href="{{ route('admin::dashboard') }}">Sam Kitano</a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="{{ $pagename == 'admin::dashboard' ? 'active' : '' }} hint hint--bottom-left"
                    aria-label="Dashboard">
                    <a href="{{ route('admin::dashboard') }}"><i class="fa fa-dashboard"></i></a>
                </li>

                <li class="dropdown{{ in_array($pagename, [
                    'admin::articles.index',
                    'admin::articles.create',
                    'admin::articles.edit',
                    'admin::articles.show'
                ]) ? ' active' : '' }}">

                    <a href="#"
                       class="dropdown-toggle hint hint--bottom-left"
                       data-toggle="dropdown"
                       aria-label="Articles"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false"><i class="fa fa-newspaper-o"></i> <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="{{ $pagename == 'admin::articles.index' ? 'active' : '' }}">
                            <a href="{{ route('admin::articles.index') }}"><i class="fa fa-reorder"></i> Index</a>
                        </li>

                        <li class="{{ $pagename == 'admin::articles.create' ? ' active' : '' }}">
                            <a href="{{ route('admin::articles.create') }}"><i class="fa fa-plus-square-o"></i> Create</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown{{ in_array($pagename, [
                    'admin::tags.index',
                    'admin::tags.create',
                    'admin::tags.edit',
                    'admin::tags.show'
                ]) ? ' active' : '' }}">

                    <a href="#"
                       class="dropdown-toggle hint hint--bottom-left"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-label="Tags"
                       aria-expanded="false"><i class="fa fa-tags"></i> <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="{{ $pagename == 'admin::tags.index' ? 'active' : '' }}">
                            <a href="{{ route('admin::tags.index') }}"><i class="fa fa-reorder"></i> Index</a>
                        </li>
                        <li class="{{ $pagename == 'admin::tags.create' ? ' active' : '' }}">
                            <a href="{{ route('admin::tags.create') }}"><i class="fa fa-plus-square-o"></i> Create</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown{{ in_array($pagename, [
                    'admin::media.index',
                    'admin::media.create',
                    'admin::media.edit',
                    'admin::media.show'
                ]) ? ' active' : '' }}">

                    <a href="#"
                       class="dropdown-toggle hint hint--bottom-left"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-label="Media"
                       aria-expanded="false"><i class="fa fa-picture-o"></i> <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="{{ $pagename == 'admin::media.index' ? 'active' : '' }}">
                            <a href="{{ route('admin::media.index') }}"><i class="fa fa-reorder"></i> Index</a>
                        </li>
                        <li class="{{ $pagename == 'admin::media.create' ? ' active' : '' }}">
                            <a href="{{ route('admin::media.create') }}"><i class="fa fa-plus-square-o"></i> Create</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown{{ in_array($pagename, [
                    'admin::albums.index',
                    'admin::albums.create',
                    'admin::albums.edit',
                    'admin::albums.show'
                ]) ? ' active' : '' }}">

                    <a href="#"
                       class="dropdown-toggle hint hint--bottom-left"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-label="Albums"
                       aria-expanded="false"><i class="fa fa-folder-o"></i> <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="{{ $pagename == 'admin::albums.index' ? 'active' : '' }}">
                            <a href="{{ route('admin::albums.index') }}"><i class="fa fa-reorder"></i> Index</a>
                        </li>
                        <li class="{{ $pagename == 'admin::albums.create' ? ' active' : '' }}">
                            <a href="{{ route('admin::albums.create') }}"><i class="fa fa-plus-square-o"></i> Create</a>
                        </li>
                    </ul>
                </li>

                <li class="{{ in_array($pagename, [
                    'admin::comments.index',
                    'admin::comments.edit',
                    'admin::comments.show'
                ]) ? ' active' : '' }} hint hint--bottom-left"
                    aria-label="Comments">
                    <a href="{{ route('admin::comments.index') }}"><i class="fa fa-comments-o"></i> </a>
                </li>

                <li class="{{ in_array($pagename, [
                    'admin::users.index',
                    'admin::users.edit',
                    'admin::users.show'
                ]) ? ' active' : '' }} hint hint--bottom-left"
                    aria-label="Users">
                    <a href="{{ route('admin::users.index') }}"><i class="fa fa-users"></i> </a>
                </li>

                @if ($isBoss)
                    <li class="{{ in_array($pagename, [
                        'admin::contacts.index',
                        'admin::contacts.show'
                    ]) ? ' active' : '' }} hint hint--bottom-left"
                        aria-label="Contacts">
                        <a href="{{ route('admin::contacts.index') }}"><i class="fa fa-envelope-o"></i> </a>
                    </li>
                @endif

                @if ($isBoss)
                    <li class="{{ in_array($pagename, [
                        'admin::statics.index',
                        'admin::statics.show'
                    ]) ? ' active' : '' }} hint hint--bottom-left"
                        aria-label="Static Pages">
                        <a href="{{ route('admin::statics.index') }}"><i class="fa fa-file-code-o"></i> </a>
                    </li>
                @endif

                <li class="{{ in_array($pagename, [
                    'admin::accounts.index',
                    'admin::accounts.show'
                ]) ? ' active' : '' }} hint hint--bottom-left"
                    aria-label="Admins">
                    <a href="{{ route('admin::accounts.index') }}"><i class="fa fa-user-secret"></i> </a>
                </li>
            </ul>

            @if (auth()->check())
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown hint hint--left" aria-label="{{ auth()->user()->name }}: Options">
                        <a href="#"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           role="button"
                           aria-haspopup="true"
                           aria-expanded="false"><i class="fa fa-cog"></i> <span class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <li><a id="clear_cache" href="#"><i class="fa fa-recycle"></i> Clear Cache</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('admin::change-password') }}"><i class="fa fa-lock"></i> Change Password</a
                                ></li
                            >
                            <li role="separator" class="divider"></li>
                            <li><a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   href="#"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>

<form id="logout-form"
      action="{{ route('admin::logout') }}"
      method="POST"
      style="display: none;">{{ csrf_field() }}</form>
