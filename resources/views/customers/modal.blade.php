<div id="modal-customer" class="modal fade">
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
					<div class="mb-3">
						<label for="email" class="form-label">{{ __('Email') }}</label>
						<input type="email" id="email" name="email" class="form-control email" placeholder="{{ __('exemplo@email.com.br') }}" required />
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
