<?php $limit = (!empty($_GET['limit']))? $_GET['limit']: 20; ?>
<?php
$data = array();
?>
<ONContributeSearch category="@$_GET['cat']" page="@$_GET['p']" limit="@$limit" order="@$_GET['order']" sort="@$_GET['sort']">
<ONContributeFetch>
<?php
$rec = array(
	"id"    => $id,
	"title" => $title,
	"url"   => $url,
	"date"  => $date,
	"category_id"   => $category_id,
	"category_name" => $category_name,
);
?>
<ONContributeField>
<?php
$rec[ $field_data['code'] ] = $ONFieldValue;
?>
</ONContributeField>
<?php
$data[] = $rec;
?>
</ONContributeFetch>
</ONContributeSearch>
<?php echo !empty( $_GET['callback'] ) ? htmlspecialchars($_GET['callback']) : 'callback' ?>(<?php echo json_encode(compact('data')) ?>);