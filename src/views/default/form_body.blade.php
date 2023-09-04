<?php

//Loading Assets
$asset_already = [];
foreach ($forms as $form) {
    $type = @$form['type'] ?: 'text';
    $name = $form['name'];

    if (in_array($type, $asset_already)) {
        continue;
    } ?>
@if(file_exists(base_path('/vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/asset.blade.php')))
    @include('crudbooster::default.type_components.'.$type.'.asset')
@elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/asset.blade.php')))
    @include('vendor.crudbooster.type_components.'.$type.'.asset')
@endif
<?php
$asset_already[] = $type;
}

//Loading input components
$header_group_class = '';
foreach ($forms as $index => $form) {
    $name = $form['name'];
    @$join = $form['join'];
    @$value = (isset($form['value'])) ? $form['value'] : '';
    @$value = (isset($row->{$name})) ? $row->{$name} : $value;

    $old = old($name);
    $value = (! empty($old)) ? $old : $value;

    $validation = [];
    $validation_raw = isset($form['validation']) ? explode('|', $form['validation']) : [];
    if ($validation_raw) {
        foreach ($validation_raw as $vr) {
            $vr_a = explode(':', $vr);
            if ($vr_a[1]) {
                $key = $vr_a[0];
                $validation[$key] = $vr_a[1];
            } else {
                $validation[$vr] = true;
            }
        }
    }

    if (isset($form['callback_php'])) {
        @eval('$value = ' . $form['callback_php'] . ';');
    }

    if (isset($form['callback'])) {
        $value = call_user_func($form['callback'], $row);
    }

    if ($join && @$row) {
        $join_arr = explode(',', $join);
        array_walk($join_arr, 'trim');
        $join_table = $join_arr[0];
        $join_title = $join_arr[1];
        $join_query_[$join_table] = DB::table($join_table)->select($join_title)->where('id', $row['id_' . $join_table])->first();
        $value = @$join_query_[$join_table]->{$join_title};
    }
    $form['type'] = ($form['type']) ?: 'text';
    $type = @$form['type'];
    $required = (@$form['required']) ? 'required' : '';
    $required = (@strpos($form['validation'], 'required') !== false) ? 'required' : $required;
    $readonly = (@$form['readonly']) ? 'readonly' : '';
    $disabled = (@$form['disabled']) ? 'disabled' : '';
    $style = (@$form['style']) ? 'style=' . $form['style'] : '';
    $placeholder = (@$form['placeholder']) ? "placeholder='" . $form['placeholder'] . "'" : '';
    $col_width = @$form['width'] ?: 'col-sm-9';
    $col_label_width = @$form['label_width'] ?: 'col-sm-2';
    $col_group_width = @$form['group_width'] ?: '';

    if ($parent_field == $name) {
        $type = 'hidden';
        $value = $parent_id;
    }

    if ($type == 'header') {
        $header_group_class = "header-group-$index";
    } else {
        $header_group_class = ($header_group_class) ?: "header-group-$index";
    } ?>
@if(file_exists(base_path('/vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/component.blade.php')))
    @include('crudbooster::default.type_components.'.$type.'.component')
@elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/component.blade.php')))
    @include('vendor.crudbooster.type_components.'.$type.'.component')
@else
    <p class='text-danger'>{{$type}} is not found in type component system</p><br/>
@endif
<?php
}
