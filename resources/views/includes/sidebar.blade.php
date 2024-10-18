<div class="sidebar close">
  <div class="logo-details">
    <img src="{{ asset('images/dagat_logo.png') }}" alt="logo" class="logo-img">
    <span class="logo_name">&nbsp;&nbsp;&nbsp;DaGAT</span>
  </div>

  <!-- Links-->
  <ul class="nav-links">

    <!-- Admin-->
    <li id="dashboard" class="{{ Request::is('dashboard') ? 'active' : '' }}">
      <a href="{{ url('/dashboard') }}" target="_self">
        <i class='bx bx-grid-alt'></i>
        <span class="link_name">Dashboard</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ url('/dashboard') }}" target="_self">Dashboard</a></li>
      </ul>
    </li>

    <!-- Archives-->
    <li id="archives" class="{{ Request::is('archives') ? 'active' : '' }}">
      <a href="{{ url('/archives') }}" target="_self">
        <i class='bx bx-briefcase-alt-2'></i>
        <span class="link_name">Archives</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ url('/archives') }}" target="_self">Archives</a></li>
      </ul>
    </li>

    <!-- Document Tracker-->
    <li id="documents" class="{{ Request::is('documents*') ? 'active' : '' }}">
      <a href="{{ route('documents.index') }}" target="_self">
        <i class='bx bx-search-alt'></i>
        <span class="link_name">Document Tracker</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ route('documents.index') }}" target="_self">Document Tracker</a></li>
      </ul>
    </li>

    <!-- Activity Log-->
    <li id="activitylog" class="{{ Request::is('activitylog') ? 'active' : '' }}">
      <a href="{{ url('/activitylog') }}" target="_self">
        <i class='bx bx-list-ul'></i>
        <span class="link_name">Activity Log</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ url('/activitylog') }}" target="_self">Activity Log</a></li>
      </ul>
    </li>

    <!-- Offices-->
    <li id="office" class="{{ Request::is('offices') ? 'active' : '' }}">
      <a href="{{ url('/offices') }}" target="_self">
        <i class='bx bx-buildings'></i>
        <span class="link_name">Offices</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ url('/offices') }}" target="_self">Offices</a></li>
      </ul>
    </li>

    <!-- Office Metrics-->
    <li id="metrics" class="{{ Request::is('analytics') ? 'active' : '' }}">
      <a href="{{ url('/analytics') }}" target="_self">
        <i class='bx bx-bar-chart-alt'></i>
        <span class="link_name">Office Metrics</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ url('/analytics') }}" target="_self">Office Metrics</a></li>
      </ul>
    </li>

    <!-- User Management -->
    <li id="usermanagement" class="{{ Request::is('usermanagement') ? 'active' : '' }}">
      <a href="{{ url('/usermanagement') }}" target="_self">
        <i class='bx bx-user'></i>
        <span class="link_name">User Management</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ url('/usermanagement') }}" target="_self">User Management</a></li>
      </ul>
    </li>

    <br>
    <br>
    <br>

    <!-- About Us-->
    <li id="aboutus" class="{{ Request::is('aboutus') ? 'active' : '' }}">
      <a href="{{ url('/aboutus') }}" target="_self">
        <i class='bx bx-info-circle'></i>
        <span class="link_name">About Us</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="{{ url('/aboutus') }}" target="_self">About Us</a></li>
      </ul>
    </li>

    <!-- Log Out-->
    <li>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" target="_self">
        <i class='bx bx-log-out'></i>
        <span class="link_name">Logout</span>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" target="_self">Logout</a></li>
      </ul>
    </li>
  </ul>
  <!-- End Link-->
</div>
