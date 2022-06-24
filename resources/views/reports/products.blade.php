@extends('partials.master')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<table class="data-table dt-responsive table table-borderles nowrap table-centered m-0 w-100">
						<thead>
							<tr class="table-light">
								<th>{{ __('Nome') }}</th>
								<th class="text-center">{{ __('Vendidos') }}</th>
								<th class="text-center">{{ __('Custo') }}</th>
								<th class="text-center">{{ __('Venda') }}</th>
								<th class="text-center">{{ __('Lucro') }}</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script-bottom')
    <script type="text/javascript">
        $(function () {
            const table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('panel/reports/products') }}",
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
                    { data: 'product', name: 'products.name' },
                    { data: 'quantity', name: 'quantity', searchable: false, },
                    { data: 'expense', name: 'expense', searchable: false },
					{ data: 'sold', name: 'sold', searchable: false },
					{ data: 'profit', name: 'profit', searchable: false, orderable: false },
                ],
                columnDefs: [{
					targets: 1,
					render: (data, type, row) => {
						return parseInt(row.quantity).toLocaleString('pt-br');
					}
				}, {
					targets: 2,
					render: (data, type, row) => {
						return parseFloat(row.expense).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
					}
				}, {
					targets: 3,
					render: (data, type, row) => {
						return parseFloat(row.sold).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
					}
				}, {
					targets: 4,
					render: (data, type, row) => {
						return parseFloat(row.profit).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
					}
				}],
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });

            table.on('draw', function () {
                $('[data-bs-toggle="tooltip"]').tooltip();

                $('.dataTable tr').each(function () {
                    $(this).find('td:eq(1)').addClass('text-center');
                    $(this).find('td:eq(2)').addClass('text-end');
					$(this).find('td:eq(3)').addClass('text-end');
                    $(this).find('td:eq(4)').addClass('text-end');
                });

                $(".dataTables_length select").addClass("form-select form-select-sm");
                $(".dataTables_length select").removeClass("custom-select custom-select-sm");
                $(".dataTables_length label").addClass("form-label");
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

