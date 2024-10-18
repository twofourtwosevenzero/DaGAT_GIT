<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  @vite(['resources/css/about.css', 'resources/css/sidebar.css', 'resources/css/usermanagement.css', 'resources/js/sidebar.js'])
  <!-- Boxiocns CDN Link -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <!-- DataTables CSS -->
  <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet'>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title> User Management </title>
  <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    #text1{
      font-family: 'Inter', sans-serif;
      margin-left:5px;
    }
  </style>
</head>

<body>
@include('includes.sidebar')

<section class="home-section">
  <div class="home-content"></div>
  <div class="container bg-light rounded">
    <br>
    <h3>&nbsp;User Management</h3>
    <hr>
    <br>
    <div class="table-responsive">
      <table id="example" class="table table-hover table-light table-borderless">
        <thead>
          <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->position ? $user->position->position_name : 'N/A' }}</td>
            <td>
              <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                      data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-position="{{ $user->position ? $user->position->Position_Name : 'N/A' }}">
                <i class='bx bx-edit'><span id="text1">Update User</span></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <br>
  </div>
</section>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm" form action="{{ route('user.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="edit-name" class="form-label">Name</label>
            <input type="text" class="form-control" id="edit-name" name="name" required>
          </div>

          <!--
          <div class="mb-3">
            <label for="edit-position" class="form-label">Position</label>
            <input type="text" class="form-control" id="edit-position" name="position" required>
          </div>
          -->
          
          <button type="submit" class="btn btn-primary">Update User</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="col-form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="position" class="col-form-label">Position:</label>
            <input type="text" class="form-control" id="position" name="Position" required>
          </div>
          <div class="mb-3">
            <label for="UserType" class="col-form-label">User Type:</label>
            <input type="text" class="form-control" id="UserType" name="usertype" required>
          </div>
          <div class="mb-3">
            <label for="password" class="col-form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add User</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Edit Modal
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var name = button.getAttribute('data-name');
      var position = button.getAttribute('data-position');

      var modalTitle = editModal.querySelector('.modal-title');
      var modalBodyInputName = editModal.querySelector('#edit-name');
      var modalBodyInputPosition = editModal.querySelector('#edit-position');
      var editForm = editModal.querySelector('#editForm');

      modalTitle.textContent = 'Edit User: ' + name;
      modalBodyInputName.value = name;
      modalBodyInputPosition.value = position;
      editForm.action = '{{ route('user.update', '') }}/' + id;
    });

  });
</script>
</body>
</html>
