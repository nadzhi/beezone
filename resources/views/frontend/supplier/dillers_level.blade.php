
<div class="modal-header">
    <h5 class="modal-title strong-600 heading-5">
		{{ $user->name }}
	</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="p-4">
	<form class="form-horizontal" action="{{ route('diller.edit', $user->id) }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
				@if(isset($supplier_levels))
					@foreach($supplier_levels as $supplier_level)
						<div class="form-group row">
							<label class="col-sm-1 py-2">Бренд</label>
							<div class="col-sm-4">
								<select name="brand[]" class="form-control">
									@if(isset($brands))
										<option disabled selected>Выберите брэнд</option>
										@foreach($brands as $brand)
											<option <?php if($brand->id == $supplier_level->brand_id): ?> selected <?php endif; ?> value="{{ $brand->id }}">{{ $brand->name }}</option>
										@endforeach
									@endif
								</select>
							</div>
							<label class="col-sm-1 py-2">Уровень</label>
							<div class="col-sm-4">
								<input type="text" placeholder="Уровень" name="level[]" value="{{ $supplier_level->level }}" class="form-control" required>
							</div>
							<div class="col-sm-2"> <button onclick="delete_row(this)" class="btn btn-danger btn-icon"> <i class="fa fa-trash"></i> </button></div>
						</div>
					@endforeach
				@endif
				<div class="product_price" id="product_price">

				</div>
				<br>

				<div class="form-group row">
					<div class="col-lg-12 text-left">
						<button type="button" class="btn btn-primary" onclick="add_product_price()">
							+
						</button>
					</div>
				</div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-primary" type="submit">{{__('Save')}}</button>
            </div>
        </form>
</div>

<script>
	
		function add_product_price(){
		$('#product_price').append('<div class="form-group row"><label class="col-sm-1 py-2">Бренд</label><div class="col-sm-4"><select name="brand[]" class="form-control"><option disabled selected>Выберите брэнд</option>'+
								   '<?php if(isset($brands)): foreach($brands as $brand): ?>'+
								   '<option value="<?=$brand->id?>"><?=$brand->name?></option>'+
								   '<?php endforeach;endif; ?>'+
								   '</select></div><label class="col-sm-1 py-2">Уровень</label><div class="col-sm-4"><input type="text" placeholder="Уровень" name="level[]" class="form-control" required></div><div class="col-lg-2"> <button onclick="delete_row(this)" class="btn btn-danger btn-icon"> <i class="fa fa-trash"></i> </button></div></div>');
	}
	
	function delete_row(em){
		$(em).closest('.form-group').remove();
	}
		
</script>