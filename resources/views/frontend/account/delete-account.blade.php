@extends('layouts.store', ['title' => __('Delete Account')])

@section('content')
<section class="section-b-space">
    <div class="container">
        <div class="row justify-content-center my-md-3 mt-5 pt-4">
            <div class="col-lg-8">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>{{ __('Delete Account') }}</h2>
                        </div>

                        <div class="card-box">

                            <p>{{ __('You can request deletion of your Restocare account from this page.') }}</p>

                            <p>{{ __('If you are logged in, use the button below to permanently delete your account and all associated personal data from our active systems.') }}</p>

                            <p>{{ __('You can also delete your account from the website: Login > Profile > Delete Account.') }}</p>

                            <p>{{ __('You can also delete your account from the mobile app: Account > Settings > Delete Account.') }}</p>

                            <div class="mt-4">
                                @if(auth()->check())
                                    <form id="deleteAccountForm" action="{{ route('user.deleteAccount') }}" method="POST"
                                        data-swal-title="{{ __('Delete account?') }}"
                                        data-swal-text="{{ __('Are you sure you want to delete your account? This action cannot be undone.') }}"
                                        data-swal-confirm="{{ __('Yes, delete it') }}"
                                        data-swal-cancel="{{ __('Cancel') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-solid">
                                            {{ __('Delete My Account') }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('customer.login') }}" class="btn btn-solid">
                                        {{ __('Login To Delete Account') }}
                                    </a>
                                @endif
                            </div>

                            <hr>

                            <h5>{{ __('Request By Contact') }}</h5>
                            <p class="mb-0">
                                {{ __('You can also request deletion by contacting:') }}
                                <a href="mailto:support@restocare.in">
                                    support@restocare.in
                                </a>
                            </p>

                            <hr>

                            <h5>{{ __('Data Deletion Policy') }}</h5>
                            <ul class="mb-0 pl-3">
                                <li>{{ __('All personal data including name, email, phone number, and account details will be permanently deleted from active systems.') }}</li>
                                <li>{{ __('Some transaction, tax, fraud-prevention, or legal records may be retained where required for compliance purposes.') }}</li>
                                <li>{{ __('Account deletion requests made via support are processed within 3–5 working days.') }}</li>
                                <li>{{ __('If you delete your account while logged in through the website or app, access is removed immediately.') }}</li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript">
    $(document).on('submit', '#deleteAccountForm', function(e) {
        e.preventDefault();
        var form = this;
        var swalTitle = $(form).data('swal-title');
        var swalText = $(form).data('swal-text');
        var confirmText = $(form).data('swal-confirm');
        var cancelText = $(form).data('swal-cancel');

        Swal.fire({
            title: swalTitle,
            text: swalText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true
        }).then(function(result) {
            if (result && (result.isConfirmed || result.value === true)) {
                form.submit();
            }
        });
    });
</script>
@endsection