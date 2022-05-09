<?php

	$setting=unserialize(@file_get_contents(DATA_DIR.'/setting/overnotes.dat'));
	ini_set('mbstring.http_input', 'pass');
	parse_str($_SERVER['QUERY_STRING'],$_GET);
	$keyword=isset($_GET['k'])?trim($_GET['k']):'';
	$category=isset($_GET['c'])?trim($_GET['c']):'';
	$page=isset($_GET['p'])?trim($_GET['p']):'';
	$base_title = !empty($setting['title'])? $setting['title'] : 'OverNotes';

?><!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=-100%, user-scalable=yes" />
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $current_category_name; ?>|<?php echo $base_title; ?>｜高槻駅徒歩3分の歯医者｜河原歯科クリニック</title>
    <meta name="keywords" content="<?php echo $base_title; ?><?php echo $current_category_name; ?>">
    <meta name="description" content="<?php echo $base_title; ?><?php echo $current_category_name; ?>">
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
                        "@id": "https://www.kawahara-dc.net/blog/",
                        "name": "<?php echo $current_category_name; ?>|<?php echo $base_title; ?>｜高槻駅徒歩3分の歯医者｜河原歯科クリニック"
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
                <h1><?php echo $current_category_name; ?>｜<?php echo $base_title; ?></h1>
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
					<li><a href="../"><?php echo $base_title; ?></a>
                    <li><?php echo $current_category_name; ?></li>
                </ul>
               <section>
			   		<h3><?php echo $base_title; ?></h3>
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
						<?php $limitNum = 9 ?>
						<?php
	$contribute_index=contribute_search(
		''
		,''
		,''
		,''
		,''
		,''
	);
	$max_record_count=count($contribute_index);

	$current_page=(@$_GET['p'])?(@$_GET['p']):1;
	$contribute_index=array_slice($contribute_index,($current_page-1)*$limitNum,$limitNum);
	$record_count=count($contribute_index)

?>
							<ul class="blog_list_news">
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
									<li class="blog_list_news_item">
										<?php
										$dates = explode("/", $date);
									?>
											<p class="img">
												<?php
	if($img1_Value){
?> <img src="<?php echo $img1_Src; ?>" alt="<?php echo $title; ?>">
													<?php
	}else{
?>
														<img src="../../images/under_img01.jpg" alt="<?php echo $title; ?>">
												<?php
	}
?>
											</p>
											<div class="blog_list_news_ttl">
												<p class="cate cate<?php echo $category_id; ?>"><?php echo $category_name; ?></p>
												<p class="date"><?php echo $dates[0]; ?>.<?php echo $dates[1]; ?>.<?php echo $dates[2]; ?></p>
											</div>
											<p class="ttl"><?php echo mb_strimwidth($title, 0, 45, '…', 'UTF-8'); ?></p>
										<a href="../<?php echo $url; ?>"></a>
									</li>
								<?php
		foreach($field as $field_index=>$field_data){
			unset(${$field_data['code'].'_Name'});
			unset(${$field_data['code'].'_Value'});
			unset(${$field_data['code'].'_Src'});
		}
	}
?>
							</ul>
							<?php
	$page_count=(int)ceil($max_record_count/(float)$limitNum);
?>
								<?php
	if($max_record_count > $limitNum){
?>
									<ul class="blog_pager">
										<?php
	if($current_page <= 1){
?>
											<li class="disabled"><a href="#">&laquo;</a></li>
											<?php
	}else{
?>
												<li><a href="./?p=<?php echo $current_page-1; ?>">&laquo;</a></li>
										<?php
	}
?>
										<?php
	$page_old=@$page;
	for($page=1;$page<=$page_count;$page++){
?>
											<?php
	if($current_page == $page){
?>
												<li class="active"><a href="#"><?php echo $page; ?></a></li>
												<?php
	}else{
?>
													<li><a href="./?p=<?php echo $page; ?>"><?php echo $page; ?></a></li>
											<?php
	}
?>
										<?php
	}
$page=$page_old;
?>
										<?php
	if($current_page*$limitNum<$max_record_count){
?>
											<?php 
								$lastpage = ceil($max_record_count / $limitNum);
							?>
											<li><a href="./?p=<?php echo $current_page+1; ?>">&raquo;</a></li>
											<?php
	}else{
?>
												<li class="disabled"><a href="#">&raquo;</a></li>
										<?php
	}
?>
								</ul>
							<?php
	}
?>
						
					
			   </section>

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
</body>

</html>
