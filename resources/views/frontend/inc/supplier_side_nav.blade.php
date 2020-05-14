<div class="sidebar sidebar--style-3 no-border stickyfill p-0">
    <div class="widget mb-0">
        <div class="widget-profile-box text-center p-3">
            <div class="image" style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
        </div>
        <div class="sidebar-widget-title py-3">
            <span>{{__('Menu')}}</span>
        </div>
        <div class="widget-profile-menu py-3">
            <ul class="categories categories--style-3">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ areActiveRoutesHome(['dashboard'])}}">
                        <i class="la la-dashboard"></i>
                        <span class="category-name">
                            {{__('Dashboard')}}
                        </span>
                    </a>
                </li>
				<li>
                    <a href="{{ route('dillers.index') }}" class="{{ areActiveRoutesHome(['dillers.index'])}}">
                        <i class="la la-user"></i>
                        <span class="category-name">
                            Мои диллеры
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchase_history.index') }}" class="{{ areActiveRoutesHome(['purchase_history.index'])}}">
                        <i class="la la-file-text"></i>
                        <span class="category-name">
                            {{__('Purchase History')}}
                        </span>
                    </a>
                </li>
                <!--<li>
                    <a href="{{ route('seller.products') }}" class="{{ areActiveRoutesHome(['seller.products', 'seller.products.upload', 'seller.products.edit'])}}">
                        <i class="la la-diamond"></i>
                        <span class="category-name">
                            {{__('Products')}}
                        </span>
                    </a>
                </li>-->
                <li>
                    <a href="{{ route('orders.index') }}" class="{{ areActiveRoutesHome(['orders.index'])}}">
                        <i class="la la-file-text"></i>
                        <span class="category-name">
                            {{__('Orders')}}
                        </span>
                    </a>
                </li>
				<li>
                    <a href="{{ route('payment_method.index') }}" class="{{ areActiveRoutesHome(['payment_method.index'])}}">
                        <i class="las la-credit-card"></i>
                        <span class="category-name">
                            Методы оплаты
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}" class="{{ areActiveRoutesHome(['profile'])}}">
                        <i class="la la-user"></i>
                        <span class="category-name">
                            Профиль
                        </span>
                    </a>
                </li>
				<li>
                    <a href="{{ route('logout') }}" class="d-block d-sm-none" style="color:red !important;">
                        <i class="las la-sign-out-alt"></i>
                        <span class="category-name">
                            {{__('Выйти')}}
                        </span>
                    </a>
                </li>
				
            </ul>
        </div>

        
    </div>
</div>
