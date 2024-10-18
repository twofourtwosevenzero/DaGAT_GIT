<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
  @vite(['resources/css/app.css', 'resources/css/custom.css'])
</head>
<body>
  
  <section class="home-section">
    <div class="home-content">
    </div>
    <div class="container bg-light rounded">
      <br>
      <h2>&nbsp;Edit User</h2>
      <br>
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
          <label for="Position" class="form-label">Position</label>
          <input type="text" class="form-control" id="Position" name="Position" value="{{ old('Position', $user->Position) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
      </form>
      <br>
      <br>
    </div>
  </section>

  <footer>
    <div class="copyright">
      <p> © 2024, CIC-LC OJT Team, All rights Reserved </p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-wEmeIV1mKuiNpK7+VsJ0LhpgFQ0JLHcEGthTmrpB4zYk4iZlQMaqlPcsIW5Sn9ja" crossorigin="anonymous"></script>
  <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>