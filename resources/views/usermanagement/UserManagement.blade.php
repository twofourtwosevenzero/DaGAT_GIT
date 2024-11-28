<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  @vite(['resources/css/about.css', 'resources/css/sidebar.css', 'resources/css/usermanagement.css', 'resources/js/sidebar.js'])
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title> User Management </title>
  <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    #text1 {
      font-family: 'Inter', sans-serif;
      margin-left: 5px;
    }
    .form-select {
    width: 100%;
    min-width: 180px; /* Set your desired minimum width */
    max-width: 350px; /* Set your desired maximum width */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

</style>
</head>

<body>
@include('includes.sidebar')

<section class="home-section">
  <div class="home-content"></div>
  <div class="container bg-light rounded">
    <br>
    <div class="d-flex justify-content-between align-items-center">
      <h3>&nbsp;User Management</h3>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bx bx-plus-circle"></i> Add User
      </button>
    </div>
    <hr>
    <br>
    <div class="table-responsive">
      <table id="example" class="table table-hover table-light table-borderless">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Position</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->position->position_name ?? 'N/A' }}</td>
            <td>
              <!-- Edit Button -->
              <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                data-id="{{ $user->id }}"
                data-name="{{ $user->name }}"
                data-email="{{ $user->email }}"
                data-position="{{ $user->position_id }}"
                <i class="bx bx-edit"></i> Edit
              </button>
              <!-- Delete Button -->
              <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                  <i class="bx bx-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <br>
  </div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{ route('user.store') }}" method="POST">
              @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="addModalLabel">Add New User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="name" class="form-label">Name</label>
                      <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="mb-3">
                      <label for="position_id" class="form-label">Position</label>
                      <select class="form-select" id="position_id" name="position_id" required>
                          <option value="" disabled selected>Select Position</option>
                          @foreach($positions as $position)
                              <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                    <label for="add-PRI_ID" class="form-label">Privilege</label>
                    <select class="form-select form-control" id="privilege_id" name="PRI_ID" required>
                      <option value="" selected disabled>Select Privilege</option>
                      @foreach($privileges as $privilege)
                          <option value="{{ $privilege->id }}">{{ $privilege->Privilege_Level }}</option>
                      @endforeach
                  </select>
                  
                </div>
                
                  <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
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


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="editForm" method="POST">
              @csrf
              @method('PUT')
              <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="edit-name" class="form-label">Name</label>
                      <input type="text" class="form-control" id="edit-name" name="name" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit-email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="edit-email" name="email" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit-position_id" class="form-label">Position</label>
                      <select class="form-select" id="edit-position_id" name="position_id" required>
                          @foreach($positions as $position)
                              <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                    <label for="edit-PRI_ID" class="form-label">Privilege</label>
                    <select class="form-select form-control" id="privilege_id" name="PRI_ID" required>
                      <option value="" selected disabled>Select Privilege</option>
                      @foreach($privileges as $privilege)
                          <option value="{{ $privilege->id }}">{{ $privilege->Privilege_Level }}</option>
                      @endforeach
                  </select>
                  
                </div>   
              </div>
          
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
          </form>
      </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });

  document.addEventListener('DOMContentLoaded', function () {
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var email = button.getAttribute('data-email');
        var positionId = button.getAttribute('data-position-id');
        var privilege = button.getAttribute('data-pri-id'); // Fetch privilege

        var nameInput = editModal.querySelector('#edit-name');
        var emailInput = editModal.querySelector('#edit-email');
        var positionSelect = editModal.querySelector('#edit-position_id');
        var privilegeSelect = editModal.querySelector('#edit-PRI_ID'); // Select privilege dropdown
        var form = editModal.querySelector('form');

        nameInput.value = name;
        emailInput.value = email;
        positionSelect.value = positionId;
        privilegeSelect.value = privilege; // Set selected privilege

        form.action = `/usermanagement/${id}`;
    });
});

</script>
</body>
</html>
