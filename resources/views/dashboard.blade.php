<!DOCTYPE html>

<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
  <link rel="stylesheet" href="css/sidebar.css">
  <!-- Boxiocns CDN Link -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Dashboard </title>
  @vite(['resources/css/app.css', 'resources/css/sidebar.css'], 'resources/js/sidebar.js')
  
</head>


<body>
  <!-- Images-->
  <div class="sidebar close">
    <div class="logo-details">
      <img src="{{ asset('images/dagat_logo.png') }}" alt="logo" class="logo-img">
      <span class="logo_name">&nbsp;&nbsp;&nbsp;DaGAT</span>
    </div>

    <!-- Links-->
    <ul class="nav-links">

    <!-- Admin-->
      <li id="dashboard" class="active">
        <a href="{{ url('/admin/dashboard') }} ">
          <i class='bx bx-grid-alt'></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ url('/admin') }}">Dashboard</a></li>
        </ul>
      </li>

      <!-- Archives-->
      <li id="archives">
        <a href="archives.html">
        <i class='bx bx-briefcase-alt-2'></i>
          <span class="link_name">Archives</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="archives.html">Archives</a></li>
        </ul>
      </li>

      <!-- Document Tracker-->
      <li id="documents">
        <a href="{{ route('documents.index') }}">
        <i class='bx bx-search-alt' ></i>
          <span class="link_name">Document Tracker</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('documents.index') }}">Document Tracker</a></li>
        </ul>
      </li>

      <!-- Activity Log-->
      <li id="activitylog">
        <a href="{{ url('/activitylog') }}">
        <i class='bx bx-list-ul'></i>
          <span class="link_name">Activity Log</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('/activitylog') }}">Activity Log</a></li>
        </ul>
      </li>


      <!-- Offices-->
<li id="office">
        <a href="{{ route('offices.show', ['id' => $office->id]) }}">
        <i class='bx bx-buildings' ></i>
          <span class="link_name">Offices</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('offices.show', ['id' => $office->id]) }}">Offices</a></li>
        </ul>
      </li>


      <!--Office Metrics-->

      <li id="metrics">
        <a href="{{ url('/metrics') }}">
        <i class='bx bx-bar-chart-alt'></i>
          <span class="link_name">Office Metrics</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('/offices') }}">Offices Metrics</a></li>
        </ul>
      </li>

      
      <!-- UserManagement -->
       
      <li id="usermanagement">
    <a href="{{ url('/usermanagement') }}">
        <i class='bx bx-user'></i>
        <span class="link_name">User Management</span>
    </a>
    <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ url('/usermanagement') }}">User Management</a></li>
        </ul>
</li>

<br>
<br>
<br>

      <!-- About Us-->
      <li id="aboutus">
        <a href="{{ url('/aboutus') }}">
          <i class='bx bx-info-circle'></i>
          <span class="link_name">About Us</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ url('/aboutus') }}">About Us</a></li>
        </ul>
      </li>

        <!-- Log Out-->
      <li>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class='bx bx-log-out'></i>
        <span class="link_name">Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <ul class="sub-menu blank">
        <li><a class="link_name" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
    </ul>
</li>
    </ul>

    <!-- End Link-->
  </div>

  


  
  <section class="home-section">
    <div class="home-content">

    </div>
    <div class="container bg-light rounded">
      <br>
      <h2>&nbsp;Dashboard</h2>
      <br>

      <br>
      <br>

      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
        Launch static backdrop modal
      </button>

      <!-- Modal -->
      <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Understood</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

  <script src="{{ asset('js/sidebar.js') }}"></script>
  
</body>
</html>
