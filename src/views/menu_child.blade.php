@foreach($menus as $child)
    @php
        $privileges = DB::table('cms_menus_privileges')
        ->join('cms_privileges','cms_privileges.id','=','cms_menus_privileges.id_cms_privileges')
        ->where('id_cms_menus',$child->id)->pluck('cms_privileges.name')->toArray();
        $childs = DB::table('cms_menus')->where('is_active', 1)->where('parent_id', $child->id)->orderby('sorting', 'asc')->get();
    @endphp
    <li data-id='{{$child->id}}' data-name='{{$child->name}}'>
        <div class='{{$child->is_dashboard?"is-dashboard":""}}'
                title="{{$child->is_dashboard?'This is setted as Dashboard':''}}"><i
                    class='{{($child->is_dashboard)?"icon-is-dashboard fa fa-dashboard":$child->icon}}'></i> {{$child->name}}
            <span class='pull-right'><a class='fa fa-pencil' title='Edit'
                                        href='{{route("MenusControllerGetEdit",["id"=>$child->id])}}?return_url={{urlencode(Request::fullUrl())}}'></a>&nbsp;&nbsp;<a
                        title="Delete" class='fa fa-trash'
                        onclick='{{CRUDBooster::deleteConfirm(route("MenusControllerGetDelete",["id"=>$child->id]))}}'
                        href='javascript:void(0)'></a></span>
            <br/><em class="text-muted">
                <small><i class="fa fa-users"></i> &nbsp; {{implode(', ',$privileges)}}</small>
            </em>
        </div>
        <ul>
            @if($childs->count())
                @include('crudbooster::menu_child', ['menus' => $childs])
            @endif
        </ul>
    </li>
@endforeach
