<!DOCTYPE html>
<html>
<head>
	<title>Stock Quotes Form</title>
	<!-- Add Bootstrap CSS via CDN -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">
     <style>
    #form-overlay {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 9999;
    }
    #form-container {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
    }
  </style>
   <!-- Add the following two CDN links -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
</head>
<body>
<div id="form-overlay">
    <div id="form-container" class="col-md-6 col-sm-12">
        <form method="post" action="{{ route('submit') }}">
        @csrf
        <div class="form-group">
            <label for="company_symbol">Company Symbol</label>
            <select class="form-control" id="company_symbol" name="company_symbol" required>
            <option value="">Select a symbol</option>
            <?php 
            $symbols = \App\Http\Controllers\FormController::getCompanySymbolsOrName();
            // dd($symbols);
            foreach ($symbols as $symbol): ?>
                <option value="<?= $symbol ?>"><?= $symbol ?></option>
            <?php endforeach; ?>
            </select>
            <!-- Error message for symbol field -->
            @error('company_symbol')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control" id="start_date" name="start_date" placeholder="YYYY-MM-DD" required>
            <!-- Error message for start_date field -->
            @error('start_date')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control" id="end_date" name="end_date" placeholder="YYYY-MM-DD" required>
            <!-- Error message for end_date field -->
            @error('end_date')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            <!-- Error message for email field -->
            @error('email')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
</body>
  <!-- Your HTML code here -->
  
  <!-- Add the following JavaScript code -->
  <script>
    $(document).ready(function() {
      // Set the date format to yyyy-mm-dd
      $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd'
      });


      $('#start_date').datepicker({
        maxDate: 0, // Set the max date to today as required in doc
        onSelect: function(selectedDate) {
          // Set the End Date field's minimum date to the Start Date field's selected date
          $('#end_date').datepicker('option', 'minDate', selectedDate);
        }
      });

      // Initialize the datepicker for the End Date field
      $('#end_date').datepicker({
        maxDate: 0, // Set the minimum date to today
        onSelect: function(selectedDate) {
          // Set the Start Date field's maximum date to the End Date field's selected date
          $('#start_date').datepicker('option', 'maxDate', selectedDate);
        }
      });
    });
  </script>
