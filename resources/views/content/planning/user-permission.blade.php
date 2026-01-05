@extends('layouts/contentNavbarLayout')
@section('title', 'User Permissions')

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
                    @foreach($actions as $action)
                        <th>{{ ucfirst($action) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $module)
                    <tr>
                        <td>{{ $module->name }}</td>
                        @foreach($actions as $action)
                            @php $permissionName = $module->slug . '.' . $action; @endphp
                            <td class="text-center">
                                <input type="checkbox" class="permission-checkbox"
                                       data-permission-name="{{ $permissionName }}">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Update Button --}}
        <div class="mb-3 d-flex justify-content-end mt-3">
            <button id="updatePermissionsBtn" class="btn btn-primary">Update Permissions</button>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="modal fade" id="permissionConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Permission Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
$(function() {

    let userId = null;
    const modalEl = document.getElementById('permissionConfirmModal');
    const permissionModal = new bootstrap.Modal(modalEl);

    // CSRF setup
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Load user permissions when a user is selected
    $('#selectUser').on('change', function() {
        userId = $(this).val();
        $('#permissionsContainer').toggle(!!userId);

        $('.permission-checkbox').prop('checked', false);

        if(!userId) return;

        $.get(`/planning/user-permission/${userId}`, function(userPermissions) {
            $('.permission-checkbox').each(function() {
                const perm = $(this).data('permission-name');
                $(this).prop('checked', userPermissions.includes(perm));
            });
        });
    });

    // Show confirmation modal
    $('#updatePermissionsBtn').on('click', function() {
        if(!userId) return toastr.error('Please select a user first!');
        $('#permissionConfirmText').text('Are you sure you want to update permissions for this user?');
        permissionModal.show();
    });

    // Confirm and save permissions
    $('#confirmPermissionBtn').on('click', function() {
        if(!userId) return toastr.error('No user selected!');

        const permissions = $('.permission-checkbox:checked').map(function() {
            return $(this).data('permission-name');
        }).get();

        console.log('Sending permissions:', permissions); // Debug

        $(this).prop('disabled', true);

        $.post("{{ route('user-permission.update') }}", { user_id: userId, permissions })
            .done(function(res) {
                toastr.success(`${res.synced_permissions.length} permissions updated successfully!`);
                permissionModal.hide();
            })
            .fail(function(err) {
                toastr.error(err.responseJSON?.error || 'Failed to update permissions');
                console.error(err);
            })
            .always(function() {
                $('#confirmPermissionBtn').prop('disabled', false);
            });
    });

});
</script>
@endsection
