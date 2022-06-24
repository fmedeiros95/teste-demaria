<div id="modal-product" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-uppercase"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-3" class="form-label">{{ __('Nome') }}</label>
                                <input type="text" name="name" class="form-control name" placeholder="Ex: Skol Lata 350ml" required />
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-5" class="form-label">{{ __('Estoque') }}</label>
                                <input type="text" name="quantity" class="form-control quantity" placeholder="Ex: 100" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-6" class="form-label">{{ __('Custo') }}</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-text">R$</div>
                                    <input type="text" name="cost" class="form-control money cost" placeholder="Ex: 5,00" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-6" class="form-label">{{ __('Preço') }}</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-text">R$</div>
                                    <input type="text" name="price" class="form-control money price" placeholder="Ex: 10,00" required />
                                </div>
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
