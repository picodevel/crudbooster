<ul class="treeview-menu">
    @foreach($menus as $child)
        <li data-id='{{$child->id}}' class='{{(Request::is($child->url_path .= !ends_with(Request::decodedPath(), $child->url_path) ? "/*" : ""))?"active":""}}'>
            <a href='{{ ($child->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$child->url}}'
                class='{{($child->color)?"text-".$child->color:""}}'>
                <i class='{{$child->icon}}'></i> <span>{{$child->name}}</span>
                @if(!empty($child->children))<i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>@endif
            </a>
            @if(!empty($child->children))
                @include('crudbooster::sidebar_child', ['menus' => $child->children])
            @endif
        </li>
    @endforeach
</ul>
