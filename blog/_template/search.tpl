<?xml version='1.0' encoding='UTF-8' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>検索結果｜{=$base_title=}</title>
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<link  href="../_sys/css/sample.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../_sys/js/jquery-1.8.2.min.js"></script>

</head>
<body id="inner" class="sub">
  <div id="wrapper">
		<div id="header">
				<h1>検索結果｜{=$base_title=}</h1>
				<p class="logo"><a href="../">{=$base_title=}</a></p>
		</div>
		
		<div id="gnav">
				<ul class="clearfix">
					<li><a href="../">Home</a></li>
					<li><a href="../search.php">検索</a></li>
				</ul>
		</div>
		
		<div id="main">

      <div class="section">
        <form method="get" action="">
          <span class="section_ttl">カテゴリ：</span><select name="c">
            <option value="">全てのカテゴリ</option>
            <ONCategory>
              <option value="{=$id=}"{=$selected=}>{=$category_name=}</option>
            </ONCategory>
          </select>
          　　<span class="section_ttl">キーワード：</span><input type="text" name="k" value="{=@$_GET['k']=}">
          <input type="submit" value="検索" />
        </form>
        
        <?php $limitNum=10 ?>
      <div class="section">
        <ONContributeSearch page="@$_GET['p']" keyword="@$_GET['k']" category="@$_GET['c']" limit="$limitNum">
        <h3>検索結果：{=$max_record_count=}件</h3>
        <ONContributeFetch>
            <ONContributeField>
              {=$ONFieldName=}:{=htmlspecialchars($ONFieldValue)=}<br />
            </ONContributeField>
            <hr />
        </ONContributeFetch>
        </ONContributeSearch>
        </div>

        <div class="pager">
        <ONPagenation record_count="$max_record_count" limit="$limitNum">
        <ONIf condition="$max_record_count >= $limitNum">
        <p class="center">
        <ONIf condition="$current_page <= 1">
        <<
        <ONElse>
        <a href="?c={=urlencode(@$_GET['c'])=}&k={=urlencode(@$_GET['k'])=}&p={=$current_page-1=}"><<</a>
        </ONElse>
        </ONIf>
         
         <ONPagenationFetch>
         <ONIf condition="$current_page == $page">
        {=$page=}
        <ONElse>
        <a href="?c={=urlencode(@$_GET['c'])=}&k={=urlencode(@$_GET['k'])=}&p={=$page=}">{=$page=}</a>
        </ONElse>
        </ONIf>
        </ONPagenationFetch>
         
        <ONIf condition="$current_page<$max_record_count">
        <a href="?c={=urlencode(@$_GET['c'])=}&k={=urlencode(@$_GET['k'])=}&p={=$current_page+1=}">>></a>
        <a href="">→</a>

        <ONElse>
        >>
        </ONEse>
        </ONIf>
        </p>
        </ONIf>
        </ONPagenation>
        </div>
      </div>


        
        <div id="onlist">
          <table>
          </table>
        </div>

			</div>
		</div>
	</div>
  <div id="footer">
    Copyright(c) 2012 FREESALE INC. All Right Reserved.
  </div>
</body>
</html>