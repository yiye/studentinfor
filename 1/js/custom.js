// 封面创建事件
var json_logo = {
		"创新实践基地　　" : 1,
		"学院分党委　　" : 2,
		"教务办公室　　"	: 3,
		"团委　　" : 4,
		"体育馆　　" : 5,
		"图书馆　　" :6,
		"学生工作办公室　　":7,
		"实训基地办公室　　" : 8
	};
function append_list (value) {
	// body...
	$list = $("#list-page ul");
	var str_html = "<li data-icon='false' id = "+value['id']+"><a href='detail.php?id="+value['id']+"'><img src='img/logo"+json_logo[value['origin']]+".png' class='ui-li-icon origin'><h2 class='title'>"+value['title']+"</h2><p class='date' >"+value['time']+"</p><p class='author'>发表人: "+value['author']+"</p></a></li>";
	$list.append(str_html);
	$list.listview('refresh');
}
function getlist_ok (data) {
	// body...
	$.mobile.loading("hide");
	$load_more = $("#load-more");
	$list = $("#list-page ul");
	if (data.state == '1' || data.state == '2') {
		$.each(data.list,function  () {
			// body...
			append_list(this);
		});
		if (data.state == '2') {
			$load_more.addClass("ui-disabled");
			$load_more.text("已经没有了");
			$load_more.off();
		}
	} else{
		$load_more.addClass("ui-disabled");
		$load_more.text("已经没有了");
		$load_more.off();
	}
}
function get_list (times) {
	// body...
	$.mobile.loading( "show", {
		text: "loading",
		textVisible: true,
		theme: "c"
	});
	$.getJSON("action.getlist.php",{"times":times},getlist_ok);
}

$("#list-page").on("pagecreate", function () {
	// body...
	times = 1;
	$list = $("#list-page ul");
	get_list(times);
	$("#load-more").on("click",function(){
		times ++;
		get_list(times);
	});
});