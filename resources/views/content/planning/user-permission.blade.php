@extends('layouts/contentNavbarLayout')
@section('title', 'Assigned Permissions')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card p-3">

    {{-- User Selection --}}
    <div class="mb-3">
        <select id="selectUser" class="form-control w-50">
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Permissions Table --}}
    <div class="table-responsive" id="permissionsContainer" style="display:none;">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Module</th>
                    @foreach(['view', 'create', 'edit'] as $action)
                        <th>{{ ucfirst($action) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $module)
                <tr>
                    <td>{{ $module->name }}</td>
                    @foreach(['view', 'create', 'edit'] as $action)
                        @php $permissionName = $module->slug . '.' . $action; @endphp
                        <td class="text-center">
                            <input type="checkbox" class="permission-checkbox" data-permission-name="{{ $permissionName }}">
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
            <div class="mb-3 d-flex justify-content-end mt-3">
                <button id="updatePermissionsBtn" class="btn btn-primary" disabled>Update Permissions</button>
            </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="modal fade" id="permissionConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Permission Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="permissionConfirmText"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmPermissionBtn" class="btn btn-success">Yes, Update</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    let userId = null;
    let pendingPermissions = [];

    // User selection
    $('#selectUser').change(function() {
        userId = $(this).val();
        if(!userId) {
            $('#permissionsContainer').hide();
            $('#updatePermissionsBtn').prop('disabled', true);
            return;
        }

        $('#permissionsContainer').show();
        $('#updatePermissionsBtn').prop('disabled', false);
        $('.permission-checkbox').prop('checked', false);

        // Load existing permissions
        $.get(`/planning/user-permission/${userId}`, function(userPermissions){
            $('.permission-checkbox').each(function(){
                const permName = $(this).data('permission-name');
                $(this).prop('checked', userPermissions.includes(permName));
            });
        });
    });

    // Update button click -> show confirmation modal
    $('#updatePermissionsBtn').click(function() {
        pendingPermissions = [];
        $('.permission-checkbox:checked').each(function() {
            pendingPermissions.push($(this).data('permission-name'));
        });

        $('#permissionConfirmText').text(`Are you sure you want to update permissions for the selected user?`);
        new bootstrap.Modal(document.getElementById('permissionConfirmModal')).show();
    });

    // Confirm button click -> send AJAX
    $('#confirmPermissionBtn').click(function() {
        if(!userId) return;

        $.ajax({
            url: "{{ route('user-permission.update') }}",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: userId,
                permissions: pendingPermissions
            },
            success: function(res) {
                toastr.success(res.success || 'Permissions updated!');
                bootstrap.Modal.getInstance(document.getElementById('permissionConfirmModal')).hide();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                toastr.error('Failed to update permissions');
            }
        });
    });
});
</script>

@endsection
