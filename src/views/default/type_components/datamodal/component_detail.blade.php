<?php

$table = $form['datamodal_table'];
$field = explode(',', $form['datamodal_columns'])[0];
echo CRUDBooster::first($table, [$form['datamodal_result_value'] ?? 'id' => $value])->$field;
