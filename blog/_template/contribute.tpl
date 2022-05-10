<ONContribute id="$contribute_id"></ONContribute>
<?php
$current_category_id   = $category_id;
$current_category_name = $category_name;
?>
<ONCategory>
	<?php if( $current_category_id==$category_id ) $current_category_url = $category_url; ?>
</ONCategory>
<!doctype html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=-100%, user-scalable=yes" />
	<meta name="format-detection" content="telephone=no">
	<title>{=$title=}|{=$base_title=}｜高槻駅徒歩3分の歯医者｜河原歯科クリニック</title>
	<ONIf condition="$keywords_Value">
		<meta name="keywords" content="{=$keywords_Value=}" />
		<ONElse>
			<meta name="keywords" content="{=$title=}|{=$base_title=}" />
	</ONIf>
	<ONIf condition="$description_Value">
		<meta name="description" content="{=$description_Value=}" />
		<ONElse>
			<meta name="description" content="{=$title=}|{=$base_title=}" />
	</ONIf>
	<link rel="shortcut icon" href="../../images/favicon.ico" />
	<link rel="stylesheet" href="../../css/under.css">
	<link rel="stylesheet" href="../../css/under_responsive.css">
	<link rel="stylesheet" href="../../css/styles.css">
	<link rel="stylesheet" href="../../css/responsive.css">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-136025975-55"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() { dataLayer.push(arguments); }
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
                        "name": "{=$base_title=}｜高槻駅徒歩3分の歯医者｜河原歯科クリニック"
                    }
                },
				{
                    "@type": "ListItem",
                    "position": 2,
                    "item": {
                        "@id": "https://www.kawahara-dc.net/blog/{=$category_url=}",
                        "name": "{=$current_category_name=}|{=$base_title=}｜高槻駅徒歩3分の歯医者｜河原歯科クリニック"
                    }
                },
				{
                    "@type": "ListItem",
                    "position": 3,
                    "item": {
                        "@id": "https://www.kawahara-dc.net/blog/{=$url=}",
                        "name": "{=$title=}|{=$base_title=}｜高槻駅徒歩3分の歯医者｜河原歯科クリニック"
                    }
                }
            ]
        }
    </script>
</head>

<body class="under">
	<div id="wrapper">
		<header id="header">
			<div class="container-full">
				<div class="header-wrap">
					<h1 class="logo">
						<a href="https://www.kawahara-dc.net/">
							<img src="../../images/logo.png" alt="{=$title=}|{=$base_title=}">
						</a>
					</h1>
					<ul class="header-btn">
						<li>
							高槻駅 徒歩3 分
						</li>
						<li>
							各専門医在籍
						</li>
						<li>
							精密医療機器導入
						</li>
					</ul>
					<p class="menu_icon">
						<span class="mline"></span>
						<span class="mtext">
							Menu
						</span>
					</p>
				</div>
			</div>
		</header>
		<div class="btn-fix">
			<ul class="bf-list">
				<li>
					<p class="bf-ic">
						<img src="../../images/ic-tell.png" width="26" height="33" alt="tell">
					</p>
					<p class="bf-tt pc f-cor">Tell</p>
					<p class="bf-tt2 sp f-lora">072-682-8148</p>
					<a href="tel:0726828148" onclick="gtag('event', 'tel', {'event_category': 'sp'});"
						class="bf-lk"></a>
				</li>
				<li>
					<p class="bf-ic">
						<img src="../../images/ic-lich.png" width="39" height="38" alt="web">
					</p>
					<p class="bf-tt f-serif">Web予約</p>
					<a href="https://ssl.haisha-yoyaku.jp/m5593528/login/serviceAppoint/index?SITE_CODE=hp"
						target="_blank" rel="noopener" class="bf-lk"></a>
				</li>
			</ul>
		</div>
		<div id="gnavi">
			<div class="gnavi-wrapper">
				<a class="gnavi-top" rel="noopener" href="https://www.kawahara-dc.net/" target="_blank">
					Top
				</a>
				<div class="gnavi-menu">
					<div class="gnavi-item">
						<p class="gnavi-head">
							<span class="f-serif">
								当院について
							</span>
							<span class="f-cor">
								About us
							</span>
						</p>
						<ul class="gnavi-list">
							<li><a href="../../clinic.html">医院情報</a></li>
							<li><a href="../../staff.html">ドクター紹介</a></li>
							<li><a href="../../relief.html">コンセプト</a></li>
							<li><a href="../../price.html">料金表</a></li>
							<li><a href="../../flow.html">治療の流れ</a></li>
							<li><a href=".././">お知らせ</a></li>
						</ul>
					</div>
					<div class="gnavi-item">
						<p class="gnavi-head">
							<span class="f-serif">
								治療メニュー
							</span>
							<span class="f-cor">
								Treatment menu
							</span>
						</p>
						<ul class="gnavi-list">
							<li><a href="../../general.html">虫歯治療・根管治療</a></li>
							<li><a href="../../prev.html">予防処置</a></li>
							<li><a href="../../perio.html">歯周病治療・再生治療</a></li>
							<li><a href="../../implant.html">インプラント治療</a></li>
							<li><a href="../../false.html">入れ歯治療</a></li>
							<li><a href="../../esthe.html">審美治療・ホワイトニング</a></li>
							<li><a href="../../child.html">小児歯科・妊娠中の歯科治療</a></li>
						</ul>
					</div>
				</div>

				<div class="gnavi-info">
					<div class="gnavi-tkb">
						<p class="tkb-ig">
							<img src="../../images/tkb-menu.jpg" width="500" height="130" alt="診療時間">
						</p>
						<p class="tkb-nte">
							<span>△ 第2週・第4週に祝日がある場合､その週の木曜は診療</span>
							<span>※最終受付は18:30となります。</span>
						</p>
					</div>

					<div class="gnavi-itm">
						<div class="gnavi-call">
							<a href="tel:0726828148" class="call-g f-lora">
								Tel. 072-682-8148
							</a>
							<span class="gnavi-time">
								電話受付 9:00～13:00 / 14:30～19:00
							</span>
						</div>

						<a href="https://ssl.haisha-yoyaku.jp/m5593528/login/serviceAppoint/index?SITE_CODE=hp"
							rel="noopener" class="gnavi-web f-serif" target="_blank">
							<img src="../../images/ic-time.png" width="28" alt="Web予約">
							Web予約
						</a>
					</div>
				</div>
			</div>
		</div>
		<main id="main">
			<div id="mainvisual">
				<div class="container">
					<h2 class="under_mv_ttl"><span class="wrap">{=$current_category_name=}</span></h2>
				</div>
			</div>
			<div id="content">
				<ul class="topic_path">
					<li><a href="https://www.kawahara-dc.net/">TOP</a></li>
					<li><a href="../">{=$base_title=}一覧</a></li>
					<li><a href="../{=$current_category_url=}">{=$current_category_name=}</a></li>
					<li>{=$title=}</li>
				</ul>
				<div class="section">
					<div class="blog_detail_item">
						<ONIf condition="$ttl1_Value">
							<h3>{=$ttl1_Value=}</h3>
						</ONIf>
						<ONIf condition="$ttl2_Value">
							<h4><span class="wrap">{=$ttl2_Value=}</span></h4>
						</ONIf>
						<ONIf condition="$img1_Value" || condition="$img2_Value">
							<ul class="blog_detail_list_img">
								<ONIf condition="$img1_Value">
									<li><img src="{=$img1_Src=}" alt="{=$title=}" /></li>
								</ONIf>
								<ONIf condition="$img2_Value">
									<li><img src="{=$img2_Src=}" alt="{=$title=}" /></li>
								</ONIf>
							</ul>
						</ONIf>
						<ONIf condition="$txt1_Value">{=$txt1_Value=}</ONIf>
					</div>
					<div class="blog_detail_item">
						<ONIf condition="$ttl3_Value">
							<h3>{=$ttl3_Value=}</h3>
						</ONIf>
						<ONIf condition="$ttl4_Value">
							<h4><span class="wrap">{=$ttl4_Value=}</span></h4>
						</ONIf>
						<ONIf condition="$img3_Value" || condition="$img4_Value">
							<ul class="blog_detail_list_img">
								<ONIf condition="$img3_Value">
									<li><img src="{=$img3_Src=}" alt="{=$title=}" /></li>
								</ONIf>
								<ONIf condition="$img4_Value">
									<li><img src="{=$img4_Src=}" alt="{=$title=}" /></li>
								</ONIf>
							</ul>
						</ONIf>
						<ONIf condition="$txt2_Value">{=$txt2_Value=}</ONIf>
					</div>
					<div class="blog_detail_item">
						<ONIf condition="$ttl5_Value">
							<h3>{=$ttl5_Value=}</h3>
						</ONIf>
						<ONIf condition="$ttl6_Value">
							<h4><span class="wrap">{=$ttl6_Value=}</span></h4>
						</ONIf>
						<ONIf condition="$img5_Value" || condition="$img6_Value">
							<ul class="blog_detail_list_img">
								<ONIf condition="$img5_Value">
									<li><img src="{=$img5_Src=}" alt="{=$title=}" /></li>
								</ONIf>
								<ONIf condition="$img6_Value">
									<li><img src="{=$img6_Src=}" alt="{=$title=}" /></li>
								</ONIf>
							</ul>
						</ONIf>
						<ONIf condition="$txt3_Value">{=$txt3_Value=}</ONIf>
					</div>
					<div class="blog_detail_item">
						<ONIf condition="$ttl7_Value">
							<h3>{=$ttl7_Value=}</h3>
						</ONIf>
						<ONIf condition="$ttl8_Value">
							<h4><span class="wrap">{=$ttl8_Value=}</span></h4>
						</ONIf>
						<ONIf condition="$img7_Value" || condition="$img8_Value">
							<ul class="blog_detail_list_img">
								<ONIf condition="$img7_Value">
									<li><img src="{=$img7_Src=}" alt="{=$title=}" /></li>
								</ONIf>
								<ONIf condition="$img8_Value">
									<li><img src="{=$img8_Src=}" alt="{=$title=}" /></li>
								</ONIf>
							</ul>
						</ONIf>
						<ONIf condition="$txt4_Value">{=$txt4_Value=}</ONIf>
					</div>
					<div class="blog_detail_item">
						<ONIf condition="$ttl9_Value">
							<h3>{=$ttl9_Value=}</h3>
						</ONIf>
						<ONIf condition="$ttl10_Value">
							<h4><span class="wrap">{=$ttl10_Value=}</span></h4>
						</ONIf>
						<ONIf condition="$img9_Value" || condition="$img10_Value">
							<ul class="blog_detail_list_img">
								<ONIf condition="$img9_Value">
									<li><img src="{=$img9_Src=}" alt="{=$title=}" /></li>
								</ONIf>
								<ONIf condition="$img10_Value">
									<li><img src="{=$img10_Src=}" alt="{=$title=}" /></li>
								</ONIf>
							</ul>
						</ONIf>
						<ONIf condition="$txt5_Value">{=$txt5_Value=}</ONIf>
					</div>
				</div>
				<div class="section">
					<div class="btn_prev_next_sec clearfix">
						<?php $current_url = $url; ?>
						<ONContributeSearch category="$current_category_id">

							<ONContributeFetch>
								<?php $pages[] = $url; ?>
							</ONContributeFetch>
						</ONContributeSearch>
						<?php $current_page = array_search($current_url,$pages); ?>
						<ul class="btn_prev_next">
							<?php if($prev = @$pages[$current_page+1]): ?>
							<li class="prevPage"><a href="../<?php echo $prev ?>">&#8592; 前の記事へ</a></li>
							<?php endif; ?>
							<li class="centerPage"><a href="../{=$current_category_url=}/">一覧に戻る</a></li>
							<?php if($next = @$pages[$current_page-1]): ?>
							<li class="nextPage"><a href="../<?php echo $next ?>">次の記事へ &#8594;</a></li>
							<?php endif ?>
						</ul>
					</div>
				</div>
				<div class="section">
					<ul class="anchor_list blog_cate_list">
						<ONCategory>
							<li class="cate{=$category_id=}"><a href="../{=$category_url=}">{=$category_name=}</a></li>
						</ONCategory>
					</ul>
				</div>
			</div>
		</main>
		<footer id="footer">
			<div class="footer-top">
				<div class="container">
					<div class="ft-wrapper">
						<div class="ft-cols">
							<div class="ft-item">
								<a href="https://www.kawahara-dc.net/" class="ft-logo">
									<img src="../../images/logo-ft.png" alt="河原歯科クリニック高槻市">
								</a>
								<div class="d-flex">
									<div class="ft-call">
										<a href="tel:0726828148"
											onclick="gtag('event', 'tel', {'event_category': 'sp'});"
											class="call-f f-lora">
											Tel. 072-682-8148
										</a>
										<span class="ft-time">
											電話受付 9:00～13:00 / 14:30～19:00
										</span>
									</div>

									<a href="https://ssl.haisha-yoyaku.jp/m5593528/login/serviceAppoint/index?SITE_CODE=hp"
										class="ft-web f-serif" rel="noopener" target="_blank">
										<img src="../../images/ic-time.png" width="28" alt="Web予約">
										Web予約
									</a>
								</div>

								<div class="ft-tkb">
									<p class="tkb-img">
										<img src="../../images/tbl.jpg" width="500" height="130" alt="診療時間">
									</p>
									<p class="tkb-note">
										<span>△ 第2週・第4週に祝日がある場合、その週の木曜は診療</span>
										<span>※最終受付は18:30となります。</span>
									</p>
								</div>
							</div>
							<div class="ft-item">
								<div class="ft-map">
									<iframe
										src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1637.1279467674424!2d135.618174!3d34.849797!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xd291f890f3e6bab!2z5rKz5Y6f5q2v56eR44Kv44Oq44OL44OD44Kv!5e0!3m2!1sja!2sjp!4v1650951170935!5m2!1sja!2sjp"
										width="500" height="300" style="border:0;" allowfullscreen="" loading="lazy"
										referrerpolicy="no-referrer-when-downgrade"></iframe>
									<a href="https://goo.gl/maps/6Uq67QjsGnWV8rUMA" class="map-link f-serif"
										target="_blank" rel="noopener">
										Google mapで見る
										<img src="../../images/ar-right.png" width="7" alt="arrow right">
									</a>
								</div>

								<ul class="ft-list">
									<li>
										〒569-0804 <br>
										大阪府高槻市紺屋町7-27桃陽2F（エレベーター有）
									</li>
									<li>
										JR京都線「高槻駅」より徒歩3分
									</li>
									<li>
										阪急電鉄京都本線「高槻市駅」より徒歩5分
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-mid">
				<div class="container">
					<div class="fm-wrapper">
						<p class="fm-desc">
							大阪府高槻市の歯医者「河原歯科クリニック」は、JR京都線（東海道本線）の「高槻駅」南出口より徒歩3分、阪急電鉄京都本線の「高槻市駅」より徒歩5分の場所にあります。一般的な虫歯や歯周病の治療をはじめ、重度の虫歯の根管治療や、重度の歯周病の歯周病外科治療、口腔外科やインプラント治療、入れ歯治療、審美治療やホワイトニング、小児歯科や妊婦さんの歯科治療、予防処置など、多種多様な診療メニューに対応しております。当クリニックの診療のゴールは、お口まわりのお悩みのない平穏な日常と、その継続のサポートです。それを実現させるために、3つの柱を立てております。それは、「日々進歩する医療の知識や技術の研鑽を重ねる」「患者様の想いに寄り添う診療」「精密な治療を叶えるためのさまざまな機器をそろえる」ことです。患者様の気持ちに寄り添うとともに、専門性を追求した知識と技術を生かした治療をご提供いたしますので、お口まわりのお悩みは何でもご相談ください。
						</p>
					</div>
				</div>
			</div>

			<div class="footer-bottom">
				<div class="container">
					<div class="fb-wrapper">
						<a class="fb-top" href="https://www.kawahara-dc.net/">
							top
						</a>
						<div class="d-flex">
							<div class="fb-item">
								<p class="fb-head">
									<span class="f-serif">
										当院について
									</span>
									<span class="f-cor">
										About us
									</span>
								</p>
								<ul class="fb-list">
									<li>
										<a href="../../clinic.html" class="fb-link">医院情報</a>
									</li>
									<li>
										<a href="../../staff.html" class="fb-link">ドクター紹介</a>
									</li>
									<li>
										<a href="../../relief.html" class="fb-link">コンセプト</a>
									</li>
									<li>
										<a href="../../price.html" class="fb-link">料金表</a>
									</li>
									<li>
										<a href="../../flow.html" class="fb-link">治療の流れ</a>
									</li>
									<li>
										<a href=".././" class="fb-link">お知らせ</a>
									</li>
								</ul>
							</div>
							<div class="fb-item">
								<p class="fb-head">
									<span class="f-serif">
										治療メニュー
									</span>
									<span class="f-cor">
										Treatment menu
									</span>
								</p>
								<ul class="fb-list">
									<li>
										<a href="../../general.html" class="fb-link">虫歯治療・根管治療</a>
									</li>
									<li>
										<a href="../../prev.html" class="fb-link">予防処置</a>
									</li>
									<li>
										<a href="../../perio.html" class="fb-link">歯周病治療・再生治療</a>
									</li>
									<li>
										<a href="../../implant.html" class="fb-link">インプラント治療</a>
									</li>
									<li>
										<a href="../../false.html" class="fb-link">入れ歯治療</a>
									</li>
									<li>
										<a href="../../esthe.html" class="fb-link">審美治療・ホワイトニング</a>
									</li>
									<li>
										<a href="../../child.html" class="fb-link">小児歯科・妊娠中の歯科治療</a>
									</li>
								</ul>
							</div>
							<div class="fb-item">
								<a href="https://ssl.haisha-yoyaku.jp/m5593528/login/serviceAppoint/index?SITE_CODE=hp"
									class="fb-web" target="_blank" rel="noopener">
									<img src="../../images/fb-img02.jpg" alt="EPARK">
								</a>
								<a href="https://www.shika-town.com/k00001318" class="fb-web" target="_blank"
									rel="noopener">
									<img src="../../images/fb-img01.jpg" alt="SHIKA-TOWN">
								</a>
							</div>
						</div>

						<div class="copyright">
							Copyright © Kawahara Dental Clinic All rights reserved.
						</div>
					</div>
				</div>
			</div>

			<p id="totop">
				<a href="#wrapper">
					<img src="../../images/ar-top.png" width="12" alt="トップへ戻る">
					Top
				</a>
			</p>
		</footer>
	</div>
	<script src="../../js/jquery.min.js"></script>
	<script src="../../js/sweetlink.js"></script>
	<script src="../../js/track-tel.js"></script>
	<script src="../../js/common.js"></script>
	<script>
		$(document).ready(function () {
			$('.blog_detail_item p').filter(function () {
				return $.trim($(this).text()) === '' && $(this).children().length == 0
			}).remove()
		})
	</script>
</body>

</html>