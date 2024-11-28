<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="icon" type="image/x-icon" href="{{ asset('Images/dagat_logo.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Offices</title>
  @vite(['resources/css/sidebar.css', 'resources/css/office.css', 'resources/js/sidebar.js'])
</head>


<body>
@include('includes.sidebar')
  
<section class="home-section">
  <div class="home-content"></div>
  <div class="container bg-light rounded">
    <br>
    <h3>&nbsp;Offices</h3>
    <hr>
    <div class="d-grid gap-2 col-12 mx-auto">
      <button  class="floating-btn" type="button" data-bs-toggle="modal" data-bs-target="#addModal"> <i class='bx bx-plus'></i> </button> 
    </div>
    <br>
    <div class="table-responsive">
      <table id="example" class="table table-hover table-light table-borderless">
        <thead>
          <tr>
            <th>Office_ID</th>
            <th>Name</th>
            <th>Office Pins</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($offices as $office)
          <tr>
            <td>{{ $office->id }}</td>
            <td>{{ $office->Office_Name }}</td>
            <td>{{ $office->Office_Pin }}</td>
            <td>
              <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $office->id }}" data-name="{{ $office->Office_Name }}" data-pin="{{ $office->Office_pin }}"> <i class="bx bx-edit"></i> Update
               
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <br>
    </div>
  </div>
</section>

<!-- Modals -->
<!-- Add Offices -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Add Office</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addForm" method="POST" action="{{ route('offices.store') }}">
          @csrf
          <div class="mb-3">
            <label for="new-office-name" class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="new-office-name" name="name">
          </div>
          <div class="mb-3">
            <label for="new-office-pin" class="col-form-label">Pin:</label>
            <input type="text" class="form-control" id="new-office-pin" name="pin">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="addForm" class="btn btn-primary">Add Office</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Office Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Office</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Office Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="Pin" class="form-label">PIN</label>
                        <input type="text" class="form-control" id="Pin" name="Pin" required>
                    </div>
                    <input type="hidden" id="edit-id" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editForm" class="btn btn-primary">Save Changes</button>
            </div>
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

        // script for edit modal
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var pin = button.data('pin');
            var modal = $(this);
            modal.find('#edit-id').val(id);
            modal.find('#name').val(name);
            modal.find('#Pin').val(pin);
            var formAction = "{{ route('offices.update', ':id') }}".replace(':id', id);
            $('#editForm').attr('action', formAction);
        });
    });
</script>

</body>
</html>
