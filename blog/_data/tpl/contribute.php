<?php

	$setting=unserialize(@file_get_contents(DATA_DIR.'/setting/overnotes.dat'));
	ini_set('mbstring.http_input', 'pass');
	parse_str($_SERVER['QUERY_STRING'],$_GET);
	$keyword=isset($_GET['k'])?trim($_GET['k']):'';
	$category=isset($_GET['c'])?trim($_GET['c']):'';
	$page=isset($_GET['p'])?trim($_GET['p']):'';
	$base_title = !empty($setting['title'])? $setting['title'] : 'OverNotes';

?><?php
	$contribute=get_contribute($contribute_id);
		$title=$contribute['title'];
	$category_id=$contribute['category'];
	$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$category_id.'.dat'));
	$category_name=$category_data['name'];
	$category_text=@$category_data['text'];
	$category_url=$category_data['id'];
	$field_id=$contribute['field'];
	$id=$contribute['id'];
	$field=get_field($field_id);
	$date=$contribute['public_begin_datetime'];
	$url=$contribute['url'].'/';

	foreach($field as $field_index=>$field_data){
		${$field_data['code'].'_Name'}=$field_data['name'];
		${$field_data['code'].'_Value'}=make_value(
		$field_data['name']
				,@$contribute['data'][$field_id][$field_index]
			,$field_data['type']
			,$id
			,$field_id
			,$field_index
		);
		if($field_data['type']=='image'){
			${$field_data['code'].'_Src'}=ROOT_URI.'/_data/contribute/images/'.@$contribute['data'][$field_id][$field_index];
		}
	}

?>
<?php
$current_category_id   = $category_id;
$current_category_name = $category_name;
?>
<?php
	$category_index=get_category_index();
	foreach($category_index as $rowid=>$id){
		$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$id.'.dat'));
		$category_url=$category_data['id'];
		$category_name=$category_data['name'];
		$category_text=@$category_data['text'];
		$category_id=$id;
		${'category'.$id.'_url'}=$category_data['id'];
		${'category'.$id.'_name'}=$category_data['name'];
		${'category'.$id.'_text'}=@$category_data['text'];
		$selected=(@$_GET['c']==$id?' selected="selected"':'');

?>
  <?php if( $current_category_id==$category_id ) $current_category_url = $category_url; ?>
<?php
	}
?>
<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=-100%, user-scalable=yes" />
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $title; ?>|<?php echo $base_title; ?>｜高槻駅徒歩3分の歯医者｜河原歯科クリニック</title>
<?php
	if($keywords_Value){
?>
  <meta name="keywords" content="<?php echo $keywords_Value; ?>" />
  <?php
	}else{
?>
  <meta name="keywords" content="<?php echo $title; ?>|<?php echo $base_title; ?>" />
<?php
	}
?>
<?php
	if($description_Value){
?>
  <meta name="description" content="<?php echo $description_Value; ?>" />
  <?php
	}else{
?>
  <meta name="description" content="<?php echo $title; ?>|<?php echo $base_title; ?>" />
<?php
	}
?>
    <link rel="shortcut icon"  href="../../images/favicon.ico"/>
    <link rel="stylesheet" href="../../css/under.css">
    <link rel="stylesheet" href="../../css/under_responsive.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136025975-55"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-00000000-00');
    </script>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "item": {
                        "@id": "https://www.kawahara-dc.net/",
                        "name": "高槻駅徒歩3分の歯医者｜河原歯科クリニック｜各種専門医在籍・精密医療機器導入"
                    }
                },
               {
                    "@type": "ListItem",
                    "position": 2,
                    "item": {
                        "@id": "https://www.kawahara-dc.net/blog/",
                        "name": "<?php echo $base_title; ?>｜高槻駅徒歩3分の歯医者｜河原歯科クリニック"
                    }
                },
				{
                    "@type": "ListItem",
                    "position": 2,
                    "item": {
                        "@id": "https://www.kawahara-dc.net/blog/<?php echo $current_category_name; ?>",
                        "name": "<?php echo $current_category_name; ?>|<?php echo $base_title; ?>｜高槻駅徒歩3分の歯医者｜河原歯科クリニック"
                    }
                },
				{
                    "@type": "ListItem",
                    "position": 3,
                    "item": {
                        "@id": "https://www.kawahara-dc.net/blog/<?php echo $url; ?>",
                        "name": "<?php echo $title; ?>|<?php echo $base_title; ?>｜高槻駅徒歩3分の歯医者｜河原歯科クリニック"
                    }
                }
            ]
        }
    </script>
</head>

<body class="under">
    <div id="wrapper">
        <header id="header">
            <div class="container">
                <h1><?php echo $title; ?>|<?php echo $base_title; ?></h1>
            </div>
        </header>
        <main id="main">
            <div id="mainvisual">
                <div class="container">
                    <h2 class="under_mv_ttl"><span class="wrap"><?php echo $current_category_name; ?></span></h2>
                </div>
            </div>
            <div id="content">
                <ul class="topic_path">
                    <li><a href="https://www.kawahara-dc.net/">TOP</a></li>
					<li><a href="../"><?php echo $base_title; ?>一覧</a></li>
					<li><a href="../<?php echo $current_category_url; ?>"><?php echo $current_category_name; ?></a></li>
					<li><?php echo $title; ?></li>
                </ul>
               <div class="section">
					<div class="blog_detail_item">
						<?php
	if($ttl1_Value){
?>
							<h3><?php echo $ttl1_Value; ?></h3>
						<?php
	}
?>
						<?php
	if($ttl2_Value){
?>
							<h4><span class="wrap"><?php echo $ttl2_Value; ?></span></h4>
						<?php
	}
?>
						<?php
	if($img2_Value){
?>
							<ul class="blog_detail_list_img">
								<?php
	if($img1_Value){
?>
									<li><img src="<?php echo $img1_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
									<?php
	if($img2_Value){
?>
									<li><img src="<?php echo $img2_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
							</ul>
						<?php
	}
?>
						<?php
	if($txt1_Value){
?><?php echo $txt1_Value; ?><?php
	}
?>
					</div>
					<div class="blog_detail_item">
						<?php
	if($ttl3_Value){
?>
							<h3><?php echo $ttl3_Value; ?></h3>
						<?php
	}
?>
						<?php
	if($ttl4_Value){
?>
							<h4><span class="wrap"><?php echo $ttl4_Value; ?></span></h4>
						<?php
	}
?>
						<?php
	if($img4_Value){
?>
							<ul class="blog_detail_list_img">
								<?php
	if($img3_Value){
?>
									<li><img src="<?php echo $img3_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
									<?php
	if($img4_Value){
?>
									<li><img src="<?php echo $img4_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
							</ul>
						<?php
	}
?>
						<?php
	if($txt2_Value){
?><?php echo $txt2_Value; ?><?php
	}
?>
					</div>
					<div class="blog_detail_item">
						<?php
	if($ttl5_Value){
?>
							<h3><?php echo $ttl5_Value; ?></h3>
						<?php
	}
?>
						<?php
	if($ttl6_Value){
?>
							<h4><span class="wrap"><?php echo $ttl6_Value; ?></span></h4>
						<?php
	}
?>
						<?php
	if($img6_Value){
?>
							<ul class="blog_detail_list_img">
								<?php
	if($img5_Value){
?>
									<li><img src="<?php echo $img5_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
									<?php
	if($img6_Value){
?>
									<li><img src="<?php echo $img6_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
							</ul>
						<?php
	}
?>
						<?php
	if($txt3_Value){
?><?php echo $txt3_Value; ?><?php
	}
?>
					</div>
					<div class="blog_detail_item">
						<?php
	if($ttl7_Value){
?>
							<h3><?php echo $ttl7_Value; ?></h3>
						<?php
	}
?>
						<?php
	if($ttl8_Value){
?>
							<h4><span class="wrap"><?php echo $ttl8_Value; ?></span></h4>
						<?php
	}
?>
						<?php
	if($img8_Value){
?>
							<ul class="blog_detail_list_img">
								<?php
	if($img7_Value){
?>
									<li><img src="<?php echo $img7_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
									<?php
	if($img8_Value){
?>
									<li><img src="<?php echo $img8_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
							</ul>
						<?php
	}
?>
						<?php
	if($txt4_Value){
?><?php echo $txt4_Value; ?><?php
	}
?>
					</div>
					<div class="blog_detail_item">
						<?php
	if($ttl9_Value){
?>
							<h3><?php echo $ttl9_Value; ?></h3>
						<?php
	}
?>
						<?php
	if($ttl10_Value){
?>
							<h4><span class="wrap"><?php echo $ttl10_Value; ?></span></h4>
						<?php
	}
?>
						<?php
	if($img10_Value){
?>
							<ul class="blog_detail_list_img">
								<?php
	if($img9_Value){
?>
									<li><img src="<?php echo $img9_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
									<?php
	if($img10_Value){
?>
									<li><img src="<?php echo $img10_Src; ?>" alt="<?php echo $title; ?>" /></li>
								<?php
	}
?>
							</ul>
						<?php
	}
?>
						<?php
	if($txt5_Value){
?><?php echo $txt5_Value; ?><?php
	}
?>
					</div>
			   </div>
			   <div class="section">
			   		<div class="btn_prev_next_sec clearfix">
                        <?php $current_url = $url; ?>
                        <?php
	$contribute_index=contribute_search(
		$current_category_id
		,''
		,''
		,''
		,''
		,''
	);
	$max_record_count=count($contribute_index);

?>

                        <?php
	$local_index=0;
	foreach($contribute_index as $rowid=>$index){
		$contribute=unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$index['id'].'.dat'));
		$title=$contribute['title'];
		$url=$contribute['url'].'/';
		$category_id=$index['category'];
		$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$category_id.'.dat'));
		$category_name=$category_data['name'];
		$category_text=@$category_data['text'];
		$field_id=$index['field'];
		$date=$index['public_begin_datetime'];
		$id=$index['id'];
		$field=get_field($field_id);

		foreach($field as $field_index=>$field_data){
			${$field_data['code'].'_Name'}=$field_data['name'];
			${$field_data['code'].'_Value'}=make_value(
		$field_data['name']
				,@$contribute['data'][$field_id][$field_index]
				,$field_data['type']
				,$id
				,$field_id
				,$field_index
			);
	
			if($field_data['type']=='image'){
				${$field_data['code'].'_Src'}=ROOT_URI.'/_data/contribute/images/'.@$contribute['data'][$field_id][$field_index];
			}
		}
		$local_index++;

?>
                            <?php $pages[] = $url; ?>
                        <?php
		foreach($field as $field_index=>$field_data){
			unset(${$field_data['code'].'_Name'});
			unset(${$field_data['code'].'_Value'});
			unset(${$field_data['code'].'_Src'});
		}
	}
?>
                        
						<?php $current_page = array_search($current_url,$pages); ?>
						<ul class="btn_prev_next">
							<?php if($prev = @$pages[$current_page+1]): ?>
							<li class="prevPage"><a href="../<?php echo $prev ?>">&#8592; 前の記事へ</a></li>
							<?php endif; ?>
							<li class="centerPage"><a href="../<?php echo $current_category_url; ?>/">一覧に戻る</a></li>
							<?php if($next = @$pages[$current_page-1]): ?>
							<li class="nextPage"><a href="../<?php echo $next ?>">次の記事へ &#8594;</a></li>
							<?php endif ?>
						</ul>
					</div>
			   </div>
			   <div class="section">
					<ul class="anchor_list blog_cate_list">
					<?php
	$category_index=get_category_index();
	foreach($category_index as $rowid=>$id){
		$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$id.'.dat'));
		$category_url=$category_data['id'];
		$category_name=$category_data['name'];
		$category_text=@$category_data['text'];
		$category_id=$id;
		${'category'.$id.'_url'}=$category_data['id'];
		${'category'.$id.'_name'}=$category_data['name'];
		${'category'.$id.'_text'}=@$category_data['text'];
		$selected=(@$_GET['c']==$id?' selected="selected"':'');

?>
						<li class="cate<?php echo $category_id; ?>"><a href="../<?php echo $category_url; ?>"><?php echo $category_name; ?></a></li>
					<?php
	}
?>
					</ul>
			   </div>
            </div>
        </main>
        <footer id="footer">
            <p>Copyright &copy; All Rights Reserved.</p>
        </footer>
    </div>
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/sweetlink.js"></script>
    <script src="../../js/track-tel.js"></script>
    <script src="../../js/common.js"></script>
	<script>
		$(document).ready(function(){
			$('.blog_detail_item p').filter(function() {
					return $.trim($(this).text()) === '' && $(this).children().length == 0
				}).remove()
		})
	</script>
</body>

</html>
