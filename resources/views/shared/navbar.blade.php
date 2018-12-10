<header id="header" class="full-header">
    <div id="header-wrap">
        <div class="container clearfix">
            <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

            <div id="logo">
                <a href="index.html" class="standard-logo" data-dark-logo="images/logo-dark.png"><img src="public/images/logo.png" alt="Canvas Logo"></a>
            </div>

            <nav id="primary-menu">
                <ul>
                    <li><a href="{{ route('documents.index') }}"><div>Documents</div></a>
                    </li>
                    <li><a href="#"><div>Profile</div></a>
                        <ul>
                            <li><a href="#"><div><i class="icon-stack"></i>Edit</div></a></li>
                            <li><a href="{{ route('branch-cameras.index') }}"><div><i class="icon-stack"></i>Cameras</div></a></li>
                            <li><a href="#"><div><i class="icon-stack"></i>Logout</div></a></li>
                        </ul>
                    </li>
                </ul>
                <div id="top-search">
                    <a href="#" id="top-search-trigger"><i class="icon-search3"></i><i class="icon-line-cross"></i></a>
                    <form action="#" method="get">
                        <input type="text" name="q" class="form-control" value="" placeholder="Type &amp; Enter..">
                    </form>
                </div>
            </nav>
        </div>
    </div>
</header>
