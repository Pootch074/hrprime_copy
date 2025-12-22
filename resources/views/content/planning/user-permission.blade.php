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
                            @php
                                $permissionName = $module->slug . '.' . $action;
                                $isDisabled = !in_array($permissionName, $assignablePermissions) || !auth()->user()->hasRole('HR-PLANNING');
                            @endphp
                            <td class="text-center">
                                <input type="checkbox" class="permission-checkbox"
                                       data-permission-name="{{ $permissionName }}"
                                       {{ $isDisabled ? 'disabled' : '' }}
                                       title="{{ $isDisabled ? 'You cannot assign this permission' : '' }}">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Update Permissions Button --}}
        <div class="mb-3 d-flex justify-content-end mt-3">
        <button id="updatePermissionsBtn mb-3 d-flex justify-content-end mt-3"  class="btn btn-primary"
                {{ auth()->user()->section !== 'HR-PLANNING' ? 'disabled' : '' }}>
            Update Permissions
        </button>
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

    $('#selectUser').change(function() {
        userId = $(this).val();
        $('#permissionsContainer').toggle(!!userId);

        // Enable/disable update button based on user selection and section
        const canAssign = userId && "{{ auth()->user()->section }}" === 'HR-PLANNING';
        $('#updatePermissionsBtn').prop('disabled', !canAssign);

        if (!userId) return;

        // Reset checkboxes
        $('.permission-checkbox').prop('checked', false);

        // Load current user permissions
        $.get(`/planning/user-permission/${userId}`, function(userPermissions){
            $('.permission-checkbox').each(function(){
                const permName = $(this).data('permission-name');
                $(this).prop('checked', userPermissions.includes(permName));
            });
        });
    });

    $('#updatePermissionsBtn').click(function() {
        $('#permissionConfirmText').text('Are you sure you want to update permissions for this user?');
        new bootstrap.Modal(document.getElementById('permissionConfirmModal')).show();
    });

    $('#confirmPermissionBtn').click(function() {
        const permissions = [];
        $('.permission-checkbox:checked').each(function() {
            permissions.push($(this).data('permission-name'));
        });

        $.ajax({
            url: "{{ route('user-permission.update') }}",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: userId,
                permissions: permissions
            },
            success: function(res) {
                toastr.success(res.success);
                bootstrap.Modal.getInstance(document.getElementById('permissionConfirmModal')).hide();
            },
            error: function(err) {
                toastr.error('Failed to update permissions');
                console.log(err.responseText);
            }
        });
    });
});

</script>
@endsection
