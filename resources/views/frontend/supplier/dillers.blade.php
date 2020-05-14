@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-lg-block">
                    @include('frontend.inc.supplier_side_nav')
                </div>
				
				
                <div class="col-lg-9">
                    <!-- Page title -->
                    <div class="page-title">
                        <div class="row align-items-center">
							
                            <div class="col-md-6">
                                <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                    Мои диллеры
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <div class="float-md-right">
                                    <ul class="breadcrumb">
                                        <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                        <li class="active"><a href="{{ route('dillers.index') }}">Мои диллеры</a></li>
                                    </ul>
                                </div>
                            </div>
							
                        </div>
                    </div>
					
					
					<div class="">
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center green-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <span class="d-block title heading-3 strong-400">
										 	@if (count($tree) > 0)
												{{ count($tree) }}
										 	@else
												0
											@endif
										</span>
                                        <span class="d-block sub-title">Диллеры</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center red-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <span class="d-block title heading-3 strong-400">
											@if (count($tree) > 0)
												<?php $data = 0; function count_tree($tree,$counter = 0){
														global $data;
														foreach($tree as $key => $branch):
															if($counter > 0): $data++; endif;
															if(count($branch["children"]) > 0){
																count_tree($branch["children"],$counter+=1);
																$counter = 0;
															}
														endforeach; 
														return $data;
												} ?>
												<?php echo count_tree($tree);?> 
										 	@else
												0
											@endif
										</span>
                                        <span class="d-block sub-title">Субдиллеры</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center blue-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <span class="d-block title heading-3 strong-400">-</span>
                                        <span class="d-block sub-title">Лучший Диллер</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center yellow-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <span class="d-block title heading-3 strong-400">-</span>
                                        <span class="d-block sub-title">Лучший Субдиллер</span>
                                    </a>
                                </div>
                            </div>
                        </div>
					</div>
					
					
					<?php if(count($tree) > 0):?> 
					<div class="card no-border mt-4">
						<div class="row px-4 py-3" style="border-bottom:1px solid #f3f3f3;">
							<div class="col-2">#</div>
							<div class="col-2">ФИО</div>
							<div class="col-2">Диллеры</div>
							<div class="col-2">Субдиллеры</div>
							<div class="col-3">Email</div>
							<div class="col-1"></div>
						</div>

						<?php build_tree($tree);?> 
					</div>
					<?php endif;  ?>
						<?php
							
							 function counter_tree($tree){
								 global $data_counter;
								 $data_counter += count($tree);
								 foreach($tree as $key => $branch):
									 counter_tree($branch["children"]);
								 endforeach; 
								 return $data_counter;

							 }
						?>
						<?php  function build_tree($tree,$counter = 0,$parent_id = 0){
							foreach($tree as $key => $branch):
							$data_counter = 0;
						?>
								
								<div class="row px-4 py-3 diller diller<?php echo $branch["referal"]; ?> parent<?php if($counter > 0): echo $parent_id; endif; ?>" data-id="<?php echo $branch["id"]; ?>"
									 style="border-bottom:1px solid #f3f3f3;<?php if($counter > 0): echo 'display:none;'; endif; ?>">
									<div class="col-2" style="padding-left: <?php echo 15 + $counter*20; ?>px;"><?php echo ($key+1); ?></div>
									<div class="col-2"><?php echo $branch["name"]; ?> </div>
									<div class="col-2"><?php echo \App\User::where('referal',$branch["id"])->count(); ?></div>
									<div class="col-2"> 
										<?php echo counter_tree($branch["children"]);  unset($GLOBALS['data_counter']);?>
								 
									</div>
									<div class="col-3"><?php echo $branch["email"]; ?></div>
									<div class="col-1">
										<div class="dropdown">
											<button class="btn" type="button" id="dropdownMenuButton-<?php echo $branch["id"]; ?>" 
													data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fa fa-ellipsis-v"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-right" 
												 aria-labelledby="dropdownMenuButton-<?php echo $branch["id"]; ?>">
												<button onclick="show_diller_detail(<?php echo $branch["id"]; ?>)" class="dropdown-item">Посмотреть</button>
												<button onclick="show_diller_level(<?php echo $branch["id"]; ?>)" class="dropdown-item">Изменить уровень</button>
												<form method="POST" action="{{ route('delete.dillers') }}">
													@csrf
													<input type="hidden" name="id" value="<?php echo $branch["id"]; ?>" />
													<button class="dropdown-item">Удалить</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							
							<?php
								if(count($branch["children"]) > 0){
									build_tree($branch["children"],$counter+=1,$branch["referal"]);
									$counter = 0;
								}
							endforeach; 
						}
					?>
					
                </div>
            </div>
        </div>
    </section>
		


<div class="modal fade" id="dillers-info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom dillers-modal" id="modal-size" role="document">
		<div class="modal-content position-relative">
			<div class="c-preloader">
				<i class="fa fa-spin fa-spinner"></i>
			</div>
			<div id="dillers-info-modal-body">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="dillers-level" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom dillers-modal" id="modal-size" role="document">
		<div class="modal-content position-relative">
			<div class="c-preloader">
				<i class="fa fa-spin fa-spinner"></i>
			</div>
			<div id="dillers-level-modal-body">

			</div>
		</div>
	</div>
</div>


@endsection

@section('script')
	<script>
		$(document).ready(function(){
			
			$("body").on("click",".diller",function(){
				let user_id = $(this).attr("data-id");
				$(".diller" + user_id).toggle();
				$(".parent" + user_id).hide();
			});
		});
		
		function show_diller_level(user_id)
		{
			$('#dillers-level-modal-body').html(null);

			if(!$('#modal-size').hasClass('modal-lg')){
				$('#modal-size').addClass('modal-lg');
			}

			$.post('{{ route('diller.level') }}', { _token : '{{ @csrf_token() }}', user_id : user_id}, function(data){
				$('#dillers-level-modal-body').html(data);
				$('#dillers-level').modal();
				$('.c-preloader').hide();
			});
		}
		
	</script>

@endsection

