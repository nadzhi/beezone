@extends('layouts.app')

@section('content')

    <div class="pad-all text-center">
        <form class="" action="{{ route('seller_report.index') }}" method="GET">
            <div class="box-inline mar-btm pad-rgt">
                 Сортировать по статусу проверки:
                 <div class="select">
                     <select class="demo-select2" name="verification_status" required>
                        <option value="1">Прверен</option>
                        <option value="0">Не Проверен</option>
                     </select>
                 </div>
            </div>
            <button class="btn btn-default" type="submit">Фильтр</button>
        </form>
    </div>


    <div class="col-md-offset-2 col-md-8">
        <div class="panel">
            <!--Panel heading-->
            <div class="panel-heading">
                <h3 class="panel-title">Отчет о продавцах</h3>
            </div>

            <!--Panel body-->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped mar-no demo-dt-basic">
                        <thead>
                            <tr>
                                <th>Продовец</th>
                                <th>Email</th>
                                <th>Магазин</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sellers as $key => $seller)
                                @if($seller->user != null)
                                    <tr>
                                        <td>{{ $seller->user->name }}</td>
                                        <td>{{ $seller->user->email }}</td>
                                        <td>{{ $seller->user->shop->name }}</td>
                                        <td>
                                            @if ($seller->verification_status == 1)
                                                <div class="label label-table label-success">
                                                    {{__('Проверен')}}
                                                </div>
                                            @elseif ($seller->verification_info != null)
                                                <a href="{{ route('sellers.show_verification_request', $seller->id) }}">
                                                    <div class="label label-table label-info">
                                                        {{__('Запрос')}}
                                                    </div>
                                                </a>
                                            @else
                                                <div class="label label-table label-danger">
                                                    {{__('Не Проверен')}}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
