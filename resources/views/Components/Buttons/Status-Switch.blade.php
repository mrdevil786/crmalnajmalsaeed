@props(['userId', 'status', 'updateUrl', 'csrfToken'])

<label class="custom-switch form-switch mb-0">
    <input type="checkbox" name="custom-switch-radio"
        class="custom-switch-input status-switch"
        data-user-id="{{ $userId }}"
        {{ $status == 'active' ? 'checked' : '' }}>
    <span class="custom-switch-indicator"></span>
</label>

@once
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.status-switch').change(function() {
                const userId = $(this).data('user-id');
                const status = $(this).prop('checked') ? 'active' : 'blocked';
                const $switch = $(this);

                $.ajax({
                    url: "{{ $updateUrl }}",
                    method: "PUT",
                    data: {
                        id: userId,
                        status: status,
                        _token: "{{ $csrfToken }}"
                    },
                    success: function(response) {
                        if (response.warning) {
                            $.growl.warning1({
                                title: 'Warning',
                                message: response.warning
                            });
                            // Revert switch state if there's a warning
                            $switch.prop('checked', !$switch.prop('checked'));
                        } else {
                            $.growl.notice1({
                                title: 'Success',
                                message: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Revert switch state on error
                        $switch.prop('checked', !$switch.prop('checked'));
                        $.growl.error1({
                            title: 'Error',
                            message: 'An error occurred while updating user status.'
                        });
                    }
                });
            });
        });
    </script>
    @endpush
@endonce
