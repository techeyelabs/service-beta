

<style type="text/css">
	.carousel-caption{
		/*bottom: 20% !important;*/
	}
	.btn-cta{
		padding-top: 15px;
		padding-bottom: 15px;
	}
	.extra_div{
		background-color: white;
		/* border: 1px solid #f1f1f1; */
		border-radius: 1%;
		padding: 0%;
		box-shadow: 0 3px 7px 0 rgba(0, 0, 0, 0.2), 0 5px 15px 0 rgba(0, 0, 0, 0.19);
	}
	@media only screen and (max-width: 600px) {
		.small_collapse {
			padding-left: 5px !important;
			padding-right: 5px !important;
		}
		.big_sticker{
			height: 175px !important;
		}
	}
	@media only screen and (max-width: 600px) {
		.small_sticker {
			height: 115px !important;
		}
		.small_sticket_title{
			height: 24px !important;
		}
	}
	@media only screen and (max-width: 600px) {
		.small_screen_font {
			font-size: 9px !important;
		}
	}

	.big_sticker{
		width: 100%;
		height: 380px;
		object-fit: cover
	}
	.sticker_text{
		font-size: 12px !important;
	}
	.ratio_test {
		position: relative;
		width: 100%;
		/* padding-top: 56.25%; 16:9 Aspect Ratio */
	}
</style>

<?php $done = 50; ?>
<div class="col-md-12 p-0 alternates">
    <div class="col-md-12 row flex_cont" style="margin-left: 0px !important;">
    @if (isset($big_one))
        <div class="col-md-6 extra_div ratio_test" style="margin-bottom: 30px">
            <div>
                <a href="#">
                    <div class="project_img" style="width:100%; background-color:#fff; background-repeat: no-repeat;
						background-position: center center; background-size: cover;">
                        <img class="big_sticker" src="{{url('uploads/products/'.$big_one->thumbnail_image)}}"
                            alt="First slide">
                    </div>
                </a>
            </div>
            <div class="project_text">
                <ul class="project_tags list-inline project_category_items special">
                    <li class="list-inline-item">

                        <i class="fa fa-tag"></i> <a href="#" class="category">{{$big_one->category->cat_name}}</a>
                    </li>
                </ul>
                <span class="project_title "><a style="font-size: 25px" title="btitle" class="title" href="#">{{str_limit($big_one->title, 90)}}</a></span>
                <div class="row project_progress pt-2 pb-2">
                    <div class="col-12">
                        <div class="progress project_progress">
                            <div class="progress-bar bg-primary w-{{ $done }}" style="width:{{ $done }}%;"
                                role="progressbar" aria-valuenow="{{ $done }}" aria-valuemin="0" aria-valuemax="100">
                                <p style="margin: 0px !important; color: white">&nbsp;{{ $done }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row project_item_footer">
                    <div class="col-5" style="padding-right: 0px !important">
                        <span class="big_sticker_small" style="font-size: 22px">&#2547; raised</span>
                    </div>
                    <div class="col-3 text-center" style="padding: 3px !important">
                        <p class="big_sticker_small" style="font-size: 22px; color: red"></p>
                        <p class="big_sticker_small" style="font-size: 22px"> 10 days left</p>
                    </div>
                    <div class="col-4 text-right" style="padding-left: 0px !important">
                        <span class="big_sticker_small" style="font-size: 22px"> 10 donors</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if (count($small_four) > 0)
        <div class="col-md-6 col-sm-6">
            <div class="row">
                <div class="col-6 small_collapse" style="padding-right: 5px !important">
                @if(count($small_four) >= 1)
                    <div class="project_item four_stickers extra_div small_screen_font" style="">
                        <a href="#">
                            <div class="project_img small_sticker small_sticker_styles"
                                style="background-image: url({{url('uploads/products/'.$small_four[0]->thumbnail_image)}});">
                            </div>
                        </a>
                        <div class="project_text" style="padding-top: 0px !important">
                            <ul class="project_tags list-inline project_category_items special">
                                <li class="list-inline-item">
                                    <i class="fa fa-tag"></i> <a style="font-size: 10px" href="#" class="category">{{$small_four[0]->category->cat_name}}</a>
                                </li>
                            </ul>
                            <span class="project_title title small_screen_font"><a
                                    class="small_screen_font special small_sticket_title"
                                    style="font-size: 12px;  height: 32px" title="title" href="#">{{str_limit($small_four[0]->title, 50)}}</a></span>

                            <div class="row project_progress pb-2">
                                <div class="col-12">
                                    <div class="progress project_progress">
                                        <div class="progress-bar bg-primary w-{{ $done }}" style="width:{{ $done }}%;"
                                            role="progressbar" aria-valuenow="{{ $done }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <p style="margin: 0px !important; color: white">&nbsp;{{ $done }}%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row project_item_footer special pl-3 pr-3">

                                <div class="" style="font-size: 11px; padding-right: 0px !important">
                                    <span>&#2547; 10 raised &nbsp;</span>&nbsp;

                                    <span style="font-size: 11px; color: red">10</span>&nbsp;

                                    <span style="font-size: 11px"> 10 days left</span>&nbsp;

                                    <span class="text-right">&nbsp; 10 donors</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-6 small_collapse" style="padding-left: 5px !important">
                @if(count($small_four) >= 2)
                    <div class="project_item four_stickers extra_div small_screen_font" style="">
                        <a href="#">
                            <div class="project_img small_sticker small_sticker_styles"
                                style="background-image:  url({{url('uploads/products/'.$small_four[1]->thumbnail_image)}});">
                            </div>
                        </a>
                        <div class="project_text" style="padding-top: 0px !important">
                            <ul class="project_tags list-inline project_category_items special">
                                <li class="list-inline-item">
                                    <i class="fa fa-tag"></i> <a style="font-size: 10px" href="#" class="category">{{$small_four[1]->category->cat_name}}</a>
                                </li>
                            </ul>
                            <span class="project_title title small_screen_font"><a
                                    class="small_screen_font special small_sticket_title"
                                    style="font-size: 12px;  height: 32px" title="title" class="title small_screen_font"
                                    href="">{{str_limit($small_four[1]->title, 50)}}</a></span>

                            <div class="row project_progress pb-2">
                                <div class="col-12">
                                    <div class="progress project_progress">
                                        <div class="progress-bar bg-primary w-{{ $done }}" style="width:{{ $done }}%;"
                                            role="progressbar" aria-valuenow="{{ $done }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <p style="margin: 0px !important; color: white">&nbsp;{{ $done }}%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row project_item_footer special pl-3 pr-3">
                                <div class="" style="font-size: 11px; padding-right: 0px !important">
                                    <span>&#2547; 10 raised &nbsp;</span>&nbsp;

                                    <span style="font-size: 11px; color: red">10 </span>&nbsp;

                                    <span style="font-size: 11px"> 10 days left &nbsp;</span>&nbsp;

                                    <span class="text-right">&nbsp; 10 donors </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-6 small_collapse" style="padding-right: 5px !important">
                @if(count($small_four) >= 3)
                    <div class="project_item four_stickers extra_div small_screen_font" style="">
                        <a href="#">
                            <div class="project_img small_sticker small_sticker_styles"
                                style="background-image: url({{url('uploads/products/'.$small_four[2]->thumbnail_image)}});">
                            </div>
                        </a>
                        <div class="project_text" style="padding-top: 0px !important">
                            <ul class="project_tags list-inline project_category_items special">
                                <li class="list-inline-item">
                                    <i class="fa fa-tag"></i> <a style="font-size: 10px" href="#" class="category">{{$small_four[2]->category->cat_name}}</a>
                                </li>
                            </ul>
                            <span class="project_title title small_screen_font"><a
                                    class="small_screen_font special small_sticket_title"
                                    style="font-size: 12px;  height: 32px" title="title" href="#">{{str_limit($small_four[2]->title, 50)}}</a></span>

                            <div class="row project_progress pb-2">
                                <div class="col-12">
                                    <div class="progress project_progress">
                                        <div class="progress-bar bg-primary w-{{ $done }}" style="width:{{ $done }}%;"
                                            role="progressbar" aria-valuenow="{{ $done }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <p style="margin: 0px !important; color: white">&nbsp;{{ $done }}%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row project_item_footer special pl-3 pr-3">

                                <div class="" style="font-size: 11px; padding-right: 0px !important">
                                    <span>&#2547; 10 raised &nbsp;</span>&nbsp;

                                    <span style="font-size: 11px; color: red">10</span>&nbsp;

                                    <span style="font-size: 11px"> 10 days left</span>&nbsp;

                                    <span class="text-right">&nbsp; 10 donors</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-6 small_collapse" style="padding-left: 5px !important">
                @if(count($small_four) >= 4)
                    <div class="project_item four_stickers extra_div small_screen_font" style="">
                        <a href="#">
                            <div class="project_img small_sticker small_sticker_styles"
                                style="background-image:  url({{url('uploads/products/'.$small_four[3]->thumbnail_image)}});">
                            </div>
                        </a>
                        <div class="project_text" style="padding-top: 0px !important">
                            <ul class="project_tags list-inline project_category_items special">
                                <li class="list-inline-item">
                                    <i class="fa fa-tag"></i> <a style="font-size: 10px" href="#" class="category">{{$small_four[3]->category->cat_name}}</a>
                                </li>
                            </ul>
                            <span class="project_title title small_screen_font"><a
                                    class="small_screen_font special small_sticket_title"
                                    style="font-size: 12px;  height: 32px" title="title" class="title small_screen_font"
                                    href="">{{str_limit($small_four[3]->title, 50)}}</a></span>

                            <div class="row project_progress pb-2">
                                <div class="col-12">
                                    <div class="progress project_progress">
                                        <div class="progress-bar bg-primary w-{{ $done }}" style="width:{{ $done }}%;"
                                            role="progressbar" aria-valuenow="{{ $done }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <p style="margin: 0px !important; color: white">&nbsp;{{ $done }}%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row project_item_footer special pl-3 pr-3">
                                <div class="" style="font-size: 11px; padding-right: 0px !important">
                                    <span>&#2547; 10 raised &nbsp;</span>&nbsp;

                                    <span style="font-size: 11px; color: red">10 </span>&nbsp;

                                    <span style="font-size: 11px"> 10 days left &nbsp;</span>&nbsp;

                                    <span class="text-right">&nbsp; 10 donors </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
