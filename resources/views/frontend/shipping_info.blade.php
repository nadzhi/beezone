@extends('frontend.layouts.app')

@section('content')

<div id="page-content">
	<section class="slice-xs sct-color-2 border-bottom">
		<div class="container container-sm">
			<div class="row cols-delimited">
				<div class="col-4">
					<div class="icon-block icon-block--style-1-v5 text-center">
						<div class="block-icon c-gray-light mb-0">
							<i class="la la-shopping-cart"></i>
						</div>
						<div class="block-content d-none d-md-block">
							<h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
						</div>
					</div>
				</div>

				<div class="col-4">
					<div class="icon-block icon-block--style-1-v5 text-center active">
						<div class="block-icon mb-0">
							<i class="la la-truck"></i>
						</div>
						<div class="block-content d-none d-md-block">
							<h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
						</div>
					</div>
				</div>

				<div class="col-4">
					<div class="icon-block icon-block--style-1-v5 text-center">
						<div class="block-icon c-gray-light mb-0">
							<i class="la la-credit-card"></i>
						</div>
						<div class="block-content d-none d-md-block">
							<h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Payment')}}</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="py-4 gry-bg">
		<div class="container cart-wrapper">
			<div class="row cols-xs-space cols-sm-space cols-md-space">
				<div class="col-lg-8">
					<form class="form-default" data-toggle="validator" action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">
						@csrf
						<div class="card">
							@if(Auth::check())
							@php
							$user = Auth::user();
							@endphp
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">{{__('Name')}}</label>
											<input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">{{__('Email')}}</label>
											<input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">{{__('Address')}}</label>
											<div class="clear"></div>
											<div class="address-input-text">
												<input type="text" id="address"  value="{{ $user->address }}" class="form-control" name="address" placeholder="{{__('Address')}}" required  autocomplete="off">
												<input type="hidden" name="geo" id="geo" value="" />
											</div>
											<div class="address-input-button">
												<span class="btn btn-primary" onclick="showMap()">Показать на карте</span>
											</div>
											<div class="clear"></div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{__('Select your country')}}</label>
											<select class="form-control selectpicker" data-live-search="true" name="country">
												@foreach (\App\Country::all() as $key => $country)
												<option value="{{ $country->name }}" @if ($country->code == $user->country) selected @endif>{{ $country->name }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group has-feedback">
											<label class="control-label">{{__('City')}}</label>
											<select class="form-control custome-control" data-live-search="true" name="city" required>
												@foreach (\App\City::all() as $key => $city)
												<option @php
														if(
														Session::has('city_id') 
														AND 
														Session::get('city_id',1) == $city->id
													)
													{
													echo " selected ";
													}
													@endphp
													value="{{ $city->id }}">
													{{ $city->name }}
												</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group has-feedback">
											<label class="control-label">{{__('Postal code')}}</label>
											<input type="number" min="0" class="form-control" value="{{ $user->postal_code }}" name="postal_code" required autocomplete="off">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group has-feedback">
											<label class="control-label">{{__('Phone')}}</label>
											<input type="text" class="form-control" value="{{ $user->phone }}" name="phone" required autocomplete="off">
										</div>
									</div>
								</div>

								<input type="hidden" name="checkout_type" value="logged">
							</div>
							@endif
						</div>

						<div class="row align-items-center pt-4 cart-buttons">
							<div class="col-6">
								<a href="{{ route('home') }}" class="link link--style-3">
									<i class="ion-android-arrow-back"></i>
									{{__('Return to shop')}}
								</a>
							</div>
							<div class="col-6 text-right">
								<button type="submit" class="btn btn-styled btn-base-1">{{__('Continue to Payment')}}</a>
							</div>
						</div>

					</form>
				</div>

				<div class="col-lg-4 ml-lg-auto">
					@include('frontend.partials.cart_summary')
				</div>
			</div>
		</div>
	</section>
</div>
	
	

<div class="modal fade" id="showMap" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-zoom" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="exampleModalLabel">Выбрать на карте</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Кликните по карте, чтобы узнать адрес</p>
				<div style="height: 400px;" id="map"></div>
				<div class=" py-2 text-center">
					<button type="button" class="btn btn-primary" onclick="closeMapModal()">Выбрать</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
    function showMap(){
        $('#showMap').modal();
    }
	function closeMapModal(){
		if($("#address").val() == ""){
			alert("Укажите адрес!");
		}
		else{
			 $('#showMap').modal('hide');
		}
    }
    </script>
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=5e3d5db5-5b87-4dfd-91a7-9b68d24b61b8" type="text/javascript"></script>

	<script type="text/javascript">
		ymaps.ready(init);

		function init() {
			var myPlacemark,
				myMap = new ymaps.Map('map', {
					center: [
						{{ \App\City::where('id',Session::get('city_id',1))->first()->lat }}, 
						{{ \App\City::where('id',Session::get('city_id',1))->first()->lng }}
					],
					zoom: 12
				}, {
					searchControlProvider: 'yandex#search'
				});

			// Слушаем клик на карте.
			myMap.events.add('click', function (e) {
				var coords = e.get('coords');

				// Если метка уже создана – просто передвигаем ее.
				if (myPlacemark) {
					myPlacemark.geometry.setCoordinates(coords);
				}
				// Если нет – создаем.
				else {
					myPlacemark = createPlacemark(coords);
					myMap.geoObjects.add(myPlacemark);
					// Слушаем событие окончания перетаскивания на метке.
					myPlacemark.events.add('dragend', function () {
						getAddress(myPlacemark.geometry.getCoordinates());
					});
				}
				getAddress(coords);
			});

			// Создание метки.
			function createPlacemark(coords) {
				return new ymaps.Placemark(coords, {
					iconCaption: 'поиск...'
				}, {
					preset: 'islands#violetDotIconWithCaption',
					draggable: true
				});
			}

			// Определяем адрес по координатам (обратное геокодирование).
			function getAddress(coords) {
				myPlacemark.properties.set('iconCaption', 'поиск...');
				ymaps.geocode(coords).then(function (res) {
					var firstGeoObject = res.geoObjects.get(0);

					myPlacemark.properties
						.set({
							// Формируем строку с данными об объекте.
							iconCaption: [
								// Название населенного пункта или вышестоящее административно-территориальное образование.
								firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
								// Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
								firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
							].filter(Boolean).join(', '),
							// В качестве контента балуна задаем строку с адресом объекта.
							balloonContent: firstGeoObject.getAddressLine()
						});
						document.getElementById("address").value = firstGeoObject.getAddressLine();
						document.getElementById("geo").value = coords;
				});
			}
			
			
		}

</script>
@endsection

