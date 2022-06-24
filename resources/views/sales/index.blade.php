@extends('partials.master')
@section('content')
	<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="header-title d-flex w-100 justify-content-between">
                        <div>
                            <h4>{{ __('Lista de vendas') }}</h4>
                            <p class="text-muted font-13 mb-4">
                                {{ __('Aqui você pode ver todas as vendas cadastradas no sistema.') }}
                            </p>
                        </div>
                        <div class="float-end">
                            <button type="button" class="add-sale btn btn--md btn-primary waves-effect waves-light">
                                {{ __('Nova') }}
                            </button>
                        </div>
                    </div>

                    <table class="data-table dt-responsive table table-borderles nowrap table-centered m-0 w-100">
                        <thead>
                            <tr class="table-light">
                                <th class="text-center">{{ __('Venda Nº') }}</th>
								<th>{{ __('Cliente') }}</th>
                                <th>{{ __('Vendedor') }}</th>
								<th class="text-center">{{ __('Método de Pagamento') }}</th>
                                <th class="text-center">{{ __('Total') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
								<th>{{ __('Criado em') }}</th>
                                <th>{{ __('Modificado em') }}</th>
                                <th class="text-center">{{ __('Ação') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
	{{-- <input type="text" name="country" id="autocomplete-ajax" class="form-control" style=" z-index: 2; background: transparent;" /> --}}

	@include('sales.modal')
@endsection

@section('script-bottom')
	<script type="text/javascript">
		$(function () {
			const modalSale = $('#modal-sale');
			const modalBody = $('.modal-body', modalSale);

			const store = (key, data) => {
				localStorage.setItem(key, JSON.stringify(data));
			}

			const get = (key) => {
				return JSON.parse(localStorage.getItem(key));
			}

			let saleProducts = get('products') || {};

			const addProduct = (item) => {
				if (saleProducts[item.id]) {
					saleProducts[item.id].quantity += 1;
				} else {
					saleProducts[item.id] = {
						id: item.id,
						name: item.name,
						price: item.price,
						quantity: item?.quantity || 1,
						inventory: item.inventory
					};
				}
				store('products', saleProducts);
				loadProducts();

				return true;
			}
			const removeProduct = (itemId) => {
				Swal.fire({
					title: "{{ __('Você tem certeza?') }}",
					text: "{{ __('Você não poderá reverter isso!') }}",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#28bb4b",
					cancelButtonColor: "#f34e4e",
					confirmButtonText: "{{ __('Sim, apague isso!') }}",
					cancelButtonText: "{{ __('Cancelar') }}",
					allowOutsideClick: false,
				}).then(function(e) {
					if (!e.isConfirmed) return;

					const product = saleProducts[itemId];
					delete saleProducts[itemId];
					store('products', saleProducts);
					loadProducts();
				});
				return true;
			}
			const removeAllProducts = () => {
				saleProducts = {};
				store('products', saleProducts);
				loadProducts();
				return true;
			}
			const displayMoney = (value) => {
				return `R$ ${value.toFixed(2).replace('.', ',')}`;
			}
			const loadProducts = () => {
				saleProducts = get('products') || {};
				let total = 0;
				const html = Object.keys(saleProducts).map(itemId => {
					const product = saleProducts[itemId];

					total += parseFloat(product.price * product.quantity);

					return `<tr id="item-${product.id}">
						<td style="min-width: 100px;">
							<input name="product[]" type="hidden" value="${product.id}" />
							<span>${product.name}</span>
						</td>
						<td>
							<input class="form-control product-quantity text-center" name="quantity[${product.id}]" data-id="${product.id}" type="number" value="${product.quantity}" min="1" />
						</td>
						<td class="text-end">${displayMoney(product.price)}</td>
						<td class="text-end">${displayMoney(product.price * product.quantity)}</td>
						<td class="text-center">
							<i class="fas fa-trash pointer remove-product" data-id="${product.id}" style="cursor: pointer;"></i>
						</td>
					</tr>`;
				}).join('');
				$('#poTable tbody', modalSale).html(html);
				$('#gtotal', modalSale).html(displayMoney(total));
			}

			const table = $('.data-table').DataTable({
				processing: true,
				serverSide: true,
				ajax: "{{ url('panel/sales') }}",
				language: {
					paginate: {
						previous: "<i class='mdi mdi-chevron-left'>",
						next: "<i class='mdi mdi-chevron-right'>"
					},
					search: "_INPUT_",
					searchPlaceholder: "{{ __('Procurar...') }}",
					lengthMenu: "_MENU_",
					info: "{{ __('Mostrando de _START_ até _END_ de _TOTAL_ registros') }}",
					infoEmpty: "{{ __('Mostrando 0 até 0 de 0 registros') }}",
					infoFiltered: "{{ __('(Filtrado de _MAX_ total registros)') }}",
					loadingRecords: "{{ __('Carregando...') }}",
					processing: "{{ __('Processando...') }}",
					zeroRecords: "{{ __('Nenhum registro encontrado') }}",
					emptyTable: "{{ __('Nenhum registro encontrado') }}"
				},
				columns: [
					{ data: 'id', name: 'id' },
					{ data: 'customer_name', name: 'customers.name', },
					{ data: 'user_name', name: 'users.name' },
					{ data: 'payment_method', name: 'payment_method' },
					{ data: 'total', name: 'total' },
					{ data: 'status', name: 'status' },
					{ data: 'created_at', name: 'created_at' },
					{ data: 'updated_at', name: 'updated_at' },
					{ data: 'action', name: 'action', orderable: false, searchable: false }
				],
				columnDefs: [{
					targets: 0,
					render: (data, type, row) => {
						return `<div class="badge bg-info text-uppercase">${row?.id}</div>`;
					}
				}, {
					targets: 3,
					render: (data, type, row) => {
						let method = "{{ __('Desconhecido') }}";
						switch (row.payment_method) {
							case 'cash': method = "{{ __('Dinheiro') }}"; break;
							case 'credit_card': method = "{{ __('Cartão de Crédito') }}"; break;
							case 'debit_card': method = "{{ __('Cartão de Débito') }}"; break;
							case 'transfer': method = "{{ __('Transferência') }}"; break;
						}

						return `<div class="badge bg-primary text-uppercase">${method}</div>`;
					}
				}, {
					targets: 4,
					render: (data, type, row) => {
						return `<div class="badge badge-outline-primary text-uppercase">${row?.total ?? 'R$ 0,00'}</div>`;
					}
				}, {
					targets: 5,
					render: (data, type, row) => {
						let msg = '???'
						let status = 'secondary';
						switch (row.status) {
							case 'pending':
								msg = "{{ __('Em Aberto') }}"
								status = 'warning';
								break;
							case 'paid':
								msg = "{{ __('Paga') }}"
								status = 'success';
								break;
							case 'canceled':
								msg = "{{ __('Cancelada') }}"
								status = 'danger';
								break;
						}
						return `<div class="badge badge-outline-${status} text-uppercase">${msg}</div>`;
					}
				}, {
					targets: 6,
					render: (data, type, row) => {
						const [ date, time ] = row.created_at.split(' ');
						return `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${time}">${date}</span>`;
					}
				}, {
					targets: 7,
					render: (data, type, row) => {
						const [ date, time ] = row.updated_at.split(' ');
						return `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${time}">${date}</span>`;
					}
				}],
				drawCallback: function() {
					$(".dataTables_paginate > .pagination").addClass("pagination-rounded")
				}
			});

			table.on('draw', function () {
				$('[data-bs-toggle="tooltip"]').tooltip();

				$('.dataTable tr').each(function () {
					$(this).find('td:eq(0)').addClass('text-center');
					$(this).find('td:eq(3)').addClass('text-center');
					$(this).find('td:eq(4)').addClass('text-center');
					$(this).find('td:eq(5)').addClass('text-center');
				});

				$(".dataTables_length select").addClass("form-select form-select-sm");
				$(".dataTables_length select").removeClass("custom-select custom-select-sm");
				$(".dataTables_length label").addClass("form-label");
			});

			$('.data-table').on('click', '.edit', function () {
				var id = $(this).data('id');
				$.ajax({
					url: "{{ url('panel/sales') }}/" + id,
					type: "GET",

					success: function({ data }) {
						modalSale.modal('show');
						$('.modal-title', modalSale).text('{{ __("Editar Venda") }}');
						$('form', modalSale).attr('action', "{{ url('panel/sales') }}/" + id)
							.attr('method', 'PUT')
							.trigger('reset');

						const { status, payment_method, customer, products, total } = data;
						$('#customer_id').val(customer.id);
						$('.customer', modalBody).val(customer.name);
						$('.status', modalBody).val(status);
						$('.payment_method', modalBody).val(payment_method)

						// Clear products list
						removeAllProducts();

						// Add products
						products.forEach(product => addProduct(product));

						// List products
						loadProducts();
					},
					error: function({ responseJSON }) {
						const { message } = responseJSON;
						Swal.fire("{{ __('Erro') }}", message, "error");
					}
				});
			});

			$('.data-table').on('click', '.delete', function () {
				var id = $(this).data('id');
				Swal.fire({
					title: "{{ __('Você tem certeza?') }}",
					text: "{{ __('Você não poderá reverter isso!') }}",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#28bb4b",
					cancelButtonColor: "#f34e4e",
					confirmButtonText: "{{ __('Sim, apague isso!') }}",
					cancelButtonText: "{{ __('Cancelar') }}",
					allowOutsideClick: false,
				}).then(function(e) {
					if (!e.isConfirmed) return;

					$.ajax({
						url: "{{ url('panel/sales') }}/" + id,
						type: "DELETE",
						data: {
							_token: "{{ csrf_token() }}"
						},
						success: function({ message }) {
							Swal.fire("{{ __('Deletado!') }}", message, "success");
							table.ajax.reload();
						},
						error: function({ responseJSON }) {
							const { message } = responseJSON
							Swal.fire("{{ __('Erro!') }}", message, "error");
						}
					});
				});
			});

			$('.add-sale').on('click', function () {
				modalSale.modal('show');
				$('.modal-title', modalSale).text('{{ __("Nova Venda") }}');
				$('form', modalSale).attr('action', "{{ url('panel/sales') }}")
					.attr('method', 'POST')
					.trigger('reset');

				const modalBody = $('.modal-body', modalSale);
				removeAllProducts();
				loadProducts();
			});

			// Create remove-product event
			modalBody.on('click', '.remove-product', function () {
				const itemId = $(this).data('id');
				removeProduct(itemId);
			});

			modalBody.on('change', '.product-quantity', function () {
				const itemId = $(this).data('id');
				const quantity = parseInt($(this).val());
				if (quantity >= 1) {
					saleProducts[itemId].quantity = quantity;
					store('products', saleProducts);
					loadProducts();
				}
			});

			$('form', modalSale).on('submit', function(e) {
				e.preventDefault();

				$.ajax({
					url: $(this).attr('action'),
					type: $(this).attr('method'),
					data: $(this).serialize(),
					success: function({ message }) {
						Swal.fire("{{ __('Sucesso') }}", message, "success");

						table.ajax.reload();
						modalSale.modal('hide');
					},
					error: function({ responseJSON }) {
						const { message } = responseJSON;
						Swal.fire("{{ __('Erro') }}", message, "error");
					}
				})
			});

			$('#search-customer').autocomplete({
				noCache: true,
				serviceUrl: "{{ url('/panel/customers/autocomplete') }}",
				onSelect: (suggestion) => {
					$('#customer_id').val(suggestion.data.id);
				}
			});

			$('#search-product').autocomplete({
				noCache: true,
				serviceUrl: "{{ url('/panel/products/autocomplete') }}",
				onSelect: (suggestion) => {
					addProduct(suggestion.data);
					loadProducts();
					$('#search-product').val('');
				}
			});
		});
	</script>
@endsection

@section('script')
	<!-- third party js -->
	<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
	<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

	<script src="{{ asset('assets/libs/devbridge-autocomplete/jquery.autocomplete.min.js') }}"></script>
	<!-- third party js ends -->
@endsection

@section('css')
	<!-- third party css -->
	<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- third party css end -->
@endsection
