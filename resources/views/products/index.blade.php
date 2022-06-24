@extends('partials.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="header-title d-flex w-100 justify-content-between">
                        <div>
                            <h4>{{ __('Lista de produtos') }}</h4>
                            <p class="text-muted font-13 mb-4">
                                {{ __('Aqui você pode ver todos os produtos cadastrados no sistema.') }}
                            </p>
                        </div>
                        <div class="float-end">
                            <button type="button" class="add-product btn btn--md btn-primary waves-effect waves-light">
                                {{ __('Novo') }}
                            </button>
                        </div>
                    </div>

                    <table class="data-table dt-responsive table table-borderles nowrap table-centered m-0 w-100">
                        <thead>
                            <tr class="table-light">
                                <th>{{ __('Nome') }}</th>
                                <th class="text-center">{{ __('Estoque') }}</th>
                                <th class="text-center">{{ __('Custo') }}</th>
								<th class="text-center">{{ __('Preço') }}</th>
                                <th>{{ __('Criado em') }}</th>
                                <th>{{ __('Última edição') }}</th>
                                <th class="text-center">{{ __('Ação') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('products.modal')
@endsection

@section('script-bottom')
    <script type="text/javascript">
        $(function () {
            const table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('panel/products') }}",
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
                    { data: 'quantity', name: 'quantity', },
                    { data: 'cost', name: 'cost' },
					{ data: 'price', name: 'price' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                columnDefs: [{
                    targets: 1,
                    render: (data, type, row) => {
                        const quantity = row.quantity.replace(/[^\d]/g, '');

                        let classe = "primary";
                        if (quantity <= 10) {
                            classe = "danger";
                        } else if (quantity <= 20) {
                            classe = "warning";
                        }
                        return `<div class="badge badge-outline-${classe}">${row?.quantity}</div>`;
                    }
                }, {
                    targets: 2,
                    render: (data, type, row) => {
                        return `<div class="badge badge-outline-secondary">${row?.cost}</div>`;
                    }
                }, {
                    targets: 3,
                    render: (data, type, row) => {
                        return `<div class="badge badge-outline-info">${row?.price}</div>`;
                    }
                }, {
                    targets: 4,
                    render: (data, type, row) => {
                        const [ date, time ] = row.created_at.split(' ');
                        return `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${time}">${date}</span>`;
                    }
                }, {
                    targets: 5,
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
                    $(this).find('td:eq(1)').addClass('text-center');
                    $(this).find('td:eq(2)').addClass('text-center');
					$(this).find('td:eq(3)').addClass('text-center');
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
                        url: "{{ url('panel/products') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            console.log(data);
                            Swal.fire("{{ __('Deletado!') }}", data.message, "success");
                            table.ajax.reload();
                        },
                        error: function({ responseJSON }) {
                            Swal.fire("{{ __('Erro!') }}", responseJSON.message, "error");
                        }
                    });
                });
            });

            const modalProduct = $('#modal-product');
			const modalBody = $('.modal-body', modalProduct);

            $('.data-table').on('click', '.edit', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('panel/products') }}/" + id,
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function({ data }) {
                        modalProduct.modal('show');
                        $('.modal-title', modalProduct).text('{{ __("Editar Produto") }}');
                        $('form', modalProduct).attr('action', "{{ url('panel/products') }}/" + id)
                            .attr('method', 'PUT')
							.trigger('reset');

                        const modalBody = $('.modal-body', modalProduct);
                        $('.name', modalBody).val(data.name);
                        $('.quantity', modalBody).val(data.quantity);
                        $('.cost', modalBody).val(data.cost);
						$('.price', modalBody).val(data.price);
                    },
                    error: function({ responseJSON }) {
                        const { message } = responseJSON;
                        Swal.fire("{{ __('Erro') }}", message, "error");
                    }
                });
            });

            $('.add-product').on('click', function () {
                modalProduct.modal('show');
                $('.modal-title', modalProduct).text('{{ __("Adicionar Produto") }}');
                $('form', modalProduct).attr('action', "{{ url('panel/products') }}")
                    .attr('method', 'POST')
					.trigger('reset');
            });

            $('form', modalProduct).on('submit', function(e) {
                e.preventDefault();

                // Form data to array
                const formData = $(this).serializeArray();

                // replace cost format
                const cost = formData.find(item => item.name === 'cost');
                cost.value = cost.value.replace(/[^\d\,]/g, '').replace(/\,/g, '.');

                // replace price format
                const price = formData.find(item => item.name === 'price');
                price.value = price.value.replace(/[^\d\,]/g, '').replace(/\,/g, '.');

                // replace quantity format
                const quantity = formData.find(item => item.name === 'quantity');
                quantity.value = quantity.value.replace(/[^\d]/g, '');

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    success: function({ message, errors }) {
                        Swal.fire("{{ __('Sucesso') }}", message, "success");

                        table.ajax.reload();
                        modalProduct.modal('hide');
                    },
                    error: function({ responseJSON }) {
                        const { message } = responseJSON;
                        Swal.fire("{{ __('Erro') }}", message, "error");
                    }
                })
            });

            $('.money', modalProduct).priceFormat({
                prefix: false,
                centsSeparator: ',',
                thousandsSeparator: '.',
                limit: 10,
                clearOnEmpty: true
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
