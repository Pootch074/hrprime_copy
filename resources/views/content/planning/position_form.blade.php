<div class="mb-3">
  <label>Position Name</label>
  <input type="text" name="position_name" class="form-control text-uppercase" required>
</div>

<div class="mb-3">
  <label>Abbreviation</label>
  <input type="text" name="abbreviation" class="form-control text-uppercase" required>
</div>


@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- ✅ jQuery added --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: "Select qualifications",
      allowClear: true,
      width: '100%'
    });
  });
</script>
@endpush