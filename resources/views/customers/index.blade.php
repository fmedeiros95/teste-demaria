@extends('partials.master')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="header-title d-flex w-100 justify-content-between">
						<div>
							<h4>{{ __('Lista de clientes') }}</h4>
							<p class="text-muted font-13 mb-4">
								{{ __('Aqui você pode ver todos os clientes cadastrados no sistema.') }}
							</p>
						</div>
						<div class="float-end">
							<button type="button" class="add-customer btn btn--md btn-primary waves-effect waves-light">
								{{ __('Novo') }}
							</button>
						</div>
					</div>

					<table class="data-table dt-responsive table table-borderles nowrap table-centered m-0 w-100">
                        <thead>
                            <tr class="table-light">
								<th>{{ __('Nome') }}</th>
								<th>{{ __('E-mail') }}</th>
								<th>{{ __('Criado em') }}</th>
								<th>{{ __('Última Alteração') }}</th>
								<th class="text-center">{{ __('Ação') }}</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

	@include('customers.modal')
@endsection

@section('script-bottom')
	<script type="text/javascript">
		$(function () {
			const table = $('.data-table').DataTable({
				processing: true,
				serverSide: true,
				ajax: "{{ url('panel/customers') }}",
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
					{ data: 'name', name: 'name' },
					{ data: 'email', name: 'email', },
					{ data: 'created_at', name: 'created_at' },
					{ data: 'updated_at', name: 'updated_at' },
					{ data: 'action', name: 'action', orderable: false, searchable: false }
				],
				columnDefs: [{
					targets: 2,
					render: (data, type, row) => {
						const [ date, time ] = row.created_at.split(' ');
						return `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${time}">${date}</span>`;
					}
				}, {
					targets: 3,
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
					$(this).find('td:eq(2)').addClass('text-center');
					$(this).find('td:eq(5)').addClass('text-center');
				});

				$(".dataTables_length select").addClass("form-select form-select-sm");
				$(".dataTables_length select").removeClass("custom-select custom-select-sm");
				$(".dataTables_length label").addClass("form-label");
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
						url: "{{ url('panel/customers') }}/" + id,
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

			const modalUser = $('#modal-customer');

			$('.data-table').on('click', '.edit', function () {
				var id = $(this).data('id');
				$.ajax({
					url: "{{ url('panel/customers') }}/" + id,
					type: "GET",

					success: function({ data }) {
						modalUser.modal('show');
						$('.modal-title', modalUser).text('{{ __("Editar Cliente") }}');
						$('form', modalUser).attr('action', "{{ url('panel/customers') }}/" + id)
							.attr('method', 'PUT');

						const { name, email, role } = data;

						const modalBody = $('.modal-body', modalUser);
						$('.name', modalBody).val(name);
						$('.email', modalBody).val(email);
					},
					error: function({ responseJSON }) {
						const { message } = responseJSON;
						Swal.fire("{{ __('Erro') }}", message, "error");
					}
				});
			});

			$('.add-customer').on('click', function () {
				modalUser.modal('show');
				$('.modal-title', modalUser).text('{{ __("Adicionar Cliente") }}');
				$('form', modalUser).attr('action', "{{ url('panel/customers') }}")
					.attr('method', 'POST');

				const modalBody = $('.modal-body', modalUser);
				$('.name', modalBody).val('');
				$('.email', modalBody).val('');
			});

			$('form', modalUser).on('submit', function(e) {
				e.preventDefault();

				$.ajax({
					url: $(this).attr('action'),
					type: $(this).attr('method'),
					data: $(this).serialize(),
					success: function({ message }) {
						Swal.fire("{{ __('Sucesso') }}", message, "success");

						table.ajax.reload();
						modalUser.modal('hide');
					},
					error: function({ responseJSON }) {
						const { message } = responseJSON;
						Swal.fire("{{ __('Erro') }}", message, "error");
					}
				})
			});
		});
	</script>
@endsection

@section('css')
	<!-- third party css -->
	<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- third party css end -->
@endsection

@section('script')
	<!-- third party js -->
	<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
	<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
	<!-- third party js ends -->
@endsection
