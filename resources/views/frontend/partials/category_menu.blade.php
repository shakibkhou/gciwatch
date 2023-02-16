<div class="aiz-category-menu bg-white rounded @if(Route::currentRouteName() == 'home') shadow-sm" @else shadow-lg" id="category-sidebar" @endif>
    <div class="p-3 bg-soft-primary d-none d-lg-block rounded-top all-category position-relative text-left">
        <!-- <span class="fw-600 fs-16 mr-3">{{ translate('Categories') }}</span>
        <a href="{{ route('categories.all') }}" class="text-reset">
            <span class="d-none d-lg-inline-block">{{ translate('See All') }} ></span>
        </a> -->
    </div>
    <ul class="list-unstyled categories no-scrollbar py-2 mb-0 text-left">

        @foreach (\App\Category::select('categories.*')->orderBy('order_level', 'desc')->get() as $key => $category)
            
        <!-- @if($category->name=="Timepieces" || $category->name=="Jewelry" ||$category->name=="Accessories" ||$category->name=="Services" ||$category->name=="Others") -->

            <li class="category-nav-element" data-id="{{ $category->id }}">

                <a href="{{ route('products.category', $category->slug) }}" class="text-truncate text-reset py-2 px-3 d-block">

                    <img

                        class="cat-image lazyload mr-2 opacity-60"

                        src="{{ static_asset('assets/img/placeholder.jpg') }}"

                        data-src="{{ uploaded_asset($category->icon) }}"

                        width="16"

                        alt="{{ $category->getTranslation('name') }}"

                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"

                    >
                    @if($category->name=='Timepieces')
                    <span class="cat-name">Timepieces</span>
                    @endif
                    @if($category->name=='Jewelry')
                    <span class="cat-name">Jewelry</span>
                    @endif
                    @if($category->name=='Accessories')
                    <span class="cat-name">Accessories</span>
                    @endif
                    @if($category->name=='Services')
                    <span class="cat-name">Services</span>
                    @endif
                    @if($category->name=='Others')
                    <span class="cat-name">Others</span>
                    @endif

                   

                </a>

                @if(count(\App\Utility\CategoryUtility::get_immediate_children_ids($category->id))>0)

                    <div class="sub-cat-menu c-scrollbar-light rounded shadow-lg p-4">

                        <div class="c-preloader text-center absolute-center">

                            <i class="las la-spinner la-spin la-3x opacity-70"></i>

                        </div>

                    </div>

                @endif

            </li>

            <!-- @endif -->

        @endforeach

    </ul>

</div>

