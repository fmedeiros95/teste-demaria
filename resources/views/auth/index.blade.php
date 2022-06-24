@extends('partials.master-without-nav')

@section('body')
    <body class="loading authentication-bg authentication-bg-pattern">
@endsection

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card bg-pattern">
                        <div class="card-body p-4">
                            <form id="form-login" method="POST" action="{{ url('auth') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('E-mail') }}</label>
                                    <input class="form-control" type="email" id="email" name="email" required placeholder="{{ __('email@exemplo.com.br') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Senha') }}</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••" />
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center d-grid">
                                    <button class="btn btn-primary" type="submit"> {{ __('Acessar') }} </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            const formLogin = $('#form-login');
            formLogin.submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
					success: function({ message, redirect }) {
						Swal.fire("{{ __('Sucesso') }}", message, "success");
						setTimeout(function() {
							window.location.href = redirect;
						}, 1250);
					},
					error: function({ responseJSON }) {
						const { message } = responseJSON;
						Swal.fire("{{ __('Erro') }}", message, "error");
					}
                });
            });
        })
    </script>
@endsection
