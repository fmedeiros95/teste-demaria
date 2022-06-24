<div id="modal-sale" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-uppercase"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                @csrf
				<input type="hidden" name="customer_id" id="customer_id" />
				<div class="modal-body p-4">
					<div class="mb-3">
						<label for="search-customer" class="form-label">{{ __('Cliente') }}</label>
						<input type="text" id="search-customer" class="form-control customer" placeholder="{{ __('John Doe') }}" required />
					</div>
					<div class="row">
                        <div class="col-md-6">
							<div class="mb-3">
								<label for="payment_method" class="form-label">{{ __('Método de Pagamento') }}</label>
								<select name="payment_method" id="payment_method" class="form-control payment_method" required>
									<option value="cash" selected>{{ __('Dinheiro') }}</option>
									<option value="credit_card">{{ __('Cartão de Crédito') }}</option>
									<option value="debit_card">{{ __('Cartão de Débito') }}</option>
									<option value="transfer">{{ __('Transferência') }}</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label for="sale_status" class="form-label">{{ __('Status') }}</label>
								<select name="status" id="sale_status" class="form-control status" required>
									<option value="pending" selected>{{ __('Pendente') }}</option>
									<option value="paid">{{ __('Pago') }}</option>
									<option value="canceled">{{ __('Cancelado') }}</option>
								</select>
							</div>
						</div>
					</div>

					<div class="mb-3">
						<input type="text" id="search-product" class="form-control" placeholder="{{ __('Procurar produto...') }}" />
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="poTable" class="table table-borderless table-nowrap table-centered m-0">
									<thead class="table-light">
										<tr>
											<th>{{ __('Produto') }}</th>
											<th class="col-2">{{ __('Quantidade') }}</th>
											<th class="col-2 text-end">{{ __('Preço Unitário') }}</th>
											<th class="col-2 text-end">{{ __('Subtotal') }}</th>
											<th style="width: 25px;"></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot class="table-light">
										<tr>
											<th>{{ __('Total') }}</th>
											<th></th>
											<th></th>
											<th class="text-end"><span id="gtotal">-</span></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
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
