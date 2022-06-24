@extends('partials.master')
@section('content')
	<div class="row">
		<div class="col-md-12 col-xl-4">
			<div class="widget-rounded-circle card">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<div class="avatar-lg rounded bg-soft-primary">
								<i class="dripicons-wallet font-24 avatar-title text-primary"></i>
							</div>
						</div>
						<div class="col-6">
							<div class="text-end">
								<h3 class="text-dark mt-1">R$ {{ number_format($todaySales, 2, ',', '.') }}</h3>
								<p class="text-muted mb-1 text-truncate">{{ __('Vendas Hoje') }}</p>
							</div>
						</div>
					</div> <!-- end row-->
				</div>
			</div> <!-- end widget-rounded-circle-->
		</div> <!-- end col-->
		<div class="col-md-12 col-xl-4">
			<div class="widget-rounded-circle card">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<div class="avatar-lg rounded bg-soft-success">
								<i class="dripicons-wallet font-24 avatar-title text-success"></i>
							</div>
						</div>
						<div class="col-6">
							<div class="text-end">
								<h3 class="text-dark mt-1">R$ {{ number_format($totalSalesAmountPaid, 2, ',', '.') }}</h3>
								<p class="text-muted mb-1 text-truncate">{{ __('Vendas') }}</p>
							</div>
						</div>
					</div> <!-- end row-->
				</div>
			</div> <!-- end widget-rounded-circle-->
		</div> <!-- end col-->
		<div class="col-md-12 col-xl-4">
			<div class="widget-rounded-circle card">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<div class="avatar-lg rounded bg-soft-warning">
								<i class="dripicons-wallet font-24 avatar-title text-warning"></i>
							</div>
						</div>
						<div class="col-6">
							<div class="text-end">
								<h3 class="text-dark mt-1">R$ {{ number_format($totalSalesAmountPending, 2, ',', '.') }}</h3>
								<p class="text-muted mb-1 text-truncate">{{ __('Vendas Pendentes') }}</p>
							</div>
						</div>
					</div> <!-- end row-->
				</div>
			</div> <!-- end widget-rounded-circle-->
		</div> <!-- end col-->

		<div class="col-md-12 col-xl-4">
			<div class="widget-rounded-circle card">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<div class="avatar-lg rounded bg-soft-success">
								<i class="dripicons-basket font-24 avatar-title text-success"></i>
							</div>
						</div>
						<div class="col-6">
							<div class="text-end">
								<h3 class="text-dark mt-1">{{ $totalSales }}</h3>
								<p class="text-muted mb-1 text-truncate">{{ __('Vendas Realizadas') }}</p>
							</div>
						</div>
					</div> <!-- end row-->
				</div>
			</div> <!-- end widget-rounded-circle-->
		</div> <!-- end col-->

		<div class="col-md-12 col-xl-4">
			<div class="widget-rounded-circle card">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<div class="avatar-lg rounded bg-soft-info">
								<i class="dripicons-user-group font-24 avatar-title text-info"></i>
							</div>
						</div>
						<div class="col-6">
							<div class="text-end">
								<h3 class="text-dark mt-1">{{ $totalCustomers }}</h3>
								<p class="text-muted mb-1 text-truncate">{{ __('Clientes Cadastrados') }}</p>
							</div>
						</div>
					</div> <!-- end row-->
				</div>
			</div> <!-- end widget-rounded-circle-->
		</div> <!-- end col-->

		<div class="col-md-12 col-xl-4">
			<div class="widget-rounded-circle card">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<div class="avatar-lg rounded bg-soft-warning">
								<i class="dripicons-list font-24 avatar-title text-warning"></i>
							</div>
						</div>
						<div class="col-6">
							<div class="text-end">
								<h3 class="text-dark mt-1">{{ $totalProducts }}</h3>
								<p class="text-muted mb-1 text-truncate">{{ __('Produtos Cadastrados') }}</p>
							</div>
						</div>
					</div> <!-- end row-->
				</div>
			</div> <!-- end widget-rounded-circle-->
		</div> <!-- end col-->
	</div>
	<!-- end row -->

	@if (auth()->user()->role == 'admin')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="header-title d-flex w-100 justify-content-between">
							<div>
								<h4>{{ __('Log de Ações') }}</h4>
								<p class="text-muted font-13 mb-4">
									{{ __('Aqui você pode ver todos as ações realizadas no sistema.') }}
								</p>
							</div>
						</div>
						<table class="data-table dt-responsive table table-borderles nowrap table-centered m-0 w-100">
							<thead>
								<tr class="table-light">
									<th class="col-2">{{ __('Data') }}</th>
									<th class="text-center">{{ __('Mensagem') }}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	@endif
@endsection

@section('script-bottom')
	@if (auth()->user()->role == 'admin')
		<script type="text/javascript">
			$(function () {
				const table = $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					ajax: "{{ url('panel/dashboard/logs') }}",
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
						{ data: 'created_at', name: 'created_at' },
						{ data: 'message', name: 'message' }
					],
					drawCallback: function() {
						$(".dataTables_paginate > .pagination").addClass("pagination-rounded")
					}
				});

				table.on('draw', function () {
					$('[data-bs-toggle="tooltip"]').tooltip();

					$('.dataTable tr').each(function () {
						$(this).find('td:eq(0)').addClass('text-center');
					});

					$(".dataTables_length select").addClass("form-select form-select-sm");
					$(".dataTables_length select").removeClass("custom-select custom-select-sm");
					$(".dataTables_length label").addClass("form-label");
				});
			});
		</script>
	@endif
@endsection

@section('css')
	@if (auth()->user()->role == 'admin')
		<!-- third party css -->
		<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
		<!-- third party css end -->
	@endif
@endsection

@section('script')
	@if (auth()->user()->role == 'admin')
		<!-- third party js -->
		<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
		<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
		<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
		<!-- third party js ends -->
	@endif
@endsection
