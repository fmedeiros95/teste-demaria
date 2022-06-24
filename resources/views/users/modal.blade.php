<div id="modal-user" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-uppercase"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Nome') }}</label>
                        <input type="text" id="name" name="name" class="form-control name" placeholder="{{ __('John Doe') }}" required />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" id="email" name="email" class="form-control email" placeholder="{{ __('exemplo@email.com.br') }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">{{ __('Cargo') }}</label>
                                <select name="role" id="role" class="form-control role" placeholder="sadasda" required>
									<option value="" disabled selected>{{ __('Selecione') }}</option>
                                    @foreach ($roles as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row form-password">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Senha') }}</label>
                                <input type="password" id="password" name="password" class="form-control password" placeholder="••••••••" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }}</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control password_confirmation" placeholder="••••••••" />
                            </div>
                        </div>
                        <div class="col-md-12 text-center alert-pw">
                            <div class="alert alert-info">
                                {{ __('Só preencha o campo de senha se desejar alterar a senha do usuário.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">{{ __('Fechar') }}</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">{{ __('Salvar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
