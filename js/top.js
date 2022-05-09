// JavaScript Document
$(function () {
	"use strict";
	var obj = {
		init: function () {
			this.loadPostData();
		},

		loadPostData: function () {
			$.ajax({
				'url': 'blog/_custom/?limit=2',
				'dataType': 'jsonp',
				'success': function (json) {
					$.each(json.data, function (i, val) {
						var li =
							$('<li class="news-item cate0'+ val.category_id +'">' +
								'<p class="news-date">' + val.date.substr(0, 4) + '.' + val.date.substr(5, 2) + '.' + val.date.substr(8, 2) + '</p>' +
								'<p class="news-b f-serif">' +
									val.category_name +
								'</p>' +
								'<a href="blog/'+ val.url +'" class="news-t">' + val.title +'</a>' +
							'</li>');

						// console.log(json.data);
						li.appendTo(".news-list");
					});
				}
			});


		}
	};

	obj.init();

});