<title><?=$head->title;?></title>
<?foreach ($head->meta_all as $key => $value):?>
<meta<?=Html::attributes(array($key => $value));?> />
<?endforeach;?>
<?foreach ($head->links as $value):?>
<link<?=Html::attributes($value);?> />
<?endforeach;?>
<?foreach ($head->styles as $value):?>
<?=html::style($value);?>
<?endforeach;?>
<?foreach ($head->scripts as $value):?>
<?=html::script($value);?>
<?endforeach;?>
<?foreach ($head->codes as $value):?>
<script<?=Html::attributes(array('type' => 'text/javascript'));?>>
	<?=$value;?>
</script>
<?endforeach;?>