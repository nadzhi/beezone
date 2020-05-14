@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-lg-block">
                    @include('frontend.inc.supplier_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        Методы оплаты
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="#">Методы оплаты</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    
						<div class="card no-border mt-4">
							<div class="payment-methods my-4">
								@if(isset($user_payments))
									@foreach($user_payments as $user_payment)
									<div class="row px-4 py-2">
										<div class="col-md-2"><b>{{ ucfirst($user_payment->name) }}</b></div>
										<div class="col-md-4">{{ $user_payment->user_name }}</div>
										<div class="col-md-4"><b>{{ $user_payment->value }}</b></div>
										<div class="col-md-2 text-right"><span class="delete-row" data-id="{{ $user_payment->id }}" style="color: #f44336;font-size:20px;margin-top:-5px;float:right;cursor:pointer;">&times;</span></div>
									</div>
									@endforeach
								@else
									<p class="pt-3 text-center">Методы оплаты не найдены</p>
								@endif
							</div>
								
							<div class="form-group mt-4">
								<div class="col-lg-12 text-center">
									<div class="btn-group">
									  <button type="button" class="btn btn-styled btn-base-1 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Добавить
									  </button>
									  <div class="dropdown-menu">
										<div class="dropdown-item" data-toggle="modal" data-target="#kaspi">Kaspi</div>
										<div class="dropdown-item" data-toggle="modal" data-target="#card">Карту</div>
									  </div>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
	
<div class="modal fade" id="kaspi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="exampleModalLabel">Добавить kaspi</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ route('payment_method.add') }}">
				@csrf
				<div class="modal-body">
					<div class="credit-card-style">
						<div class="card-number">
							<input type="text" class="phone_number" name="card_number" placeholder="Телефон" required/>
						</div>
						<div class="card-username">
							<input type="text" name="card_name" placeholder="ИМЯ" required/>
							<input type="text" name="card_surname" placeholder="ФАМИЛИЯ" required/>
							<input type="hidden" name="type" value="kaspi" />
							<div class="clear"></div>
						</div>
						
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
					<button type="submit" class="btn btn-primary" >Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="card" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="exampleModalLabel">Добавить карту</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ route('payment_method.add') }}" >
				@csrf
				<div class="modal-body">
					<div class="credit-card-style">
						<div class="card-number">
							<input type="text" class="card_number" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX" required/>
						</div>
						<div class="card-username">
							<input type="text" name="card_name" placeholder="ИМЯ" required/>
							<input type="text" name="card_surname" placeholder="ФАМИЛИЯ" required/>
							<input type="hidden" name="type" value="card" />
							<div class="clear"></div>
						</div>
						
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
					<button type="submit" class="btn btn-primary" >Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection


@section('script')
	<script src="{{ asset('frontend/js/jquery_mask.js') }}"></script>
	<script type="text/javascript">
		$(function($){
			$(".card_number").mask("9999-9999-9999-9999");
			$(".phone_number").mask("+7 (999) 999-9999");
		});
		$(document).ready(function(){
			$(".delete-row").on("click",function(e){
				e.preventDefault();
				let id = $(this).attr("data-id");
				let _this = $(this);
				$.post("{{ route('payment_method.delete') }}",{_token:'{{ csrf_token() }}',id:id},function(data){
					swal({
            			type: "success",
						position: 'top-end',
						title: "Данные успешно удалены!",
						showConfirmButton: false,
						timer: 1500
					});
					_this.closest('.row').remove();
				});
			});
		});
	</script>
@endsection

