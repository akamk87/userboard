<div class="gnb navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="/home">Open<b>Trade</b></a>
			<ul class="nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php if ($this->otd_user->get('name') == '관리자'): ?>
							<img src="/public/images/opentrade.png" class="face">
						<?php else: ?>
							<img src="<?php echo $this->otd_user->get_profile_image() ? '/thumbnail/' . $this->otd_user->get_profile_image()->id . '?w=50&h=50' : '/public/images/avatar.png';?>" class="face">
						<?php endif; ?>
						<div class="inner-wrap">
							<?php echo $this->otd_user->get('name'); ?><b class="caret"></b>
						</div>
					</a>
					<ul class="dropdown-menu">
						<?php if ($this->otd_user->get('name') == '관리자'): ?>
							<li><a href="/admin/statistic/company"><i class="icon-lock"></i> 관리페이지</a></li>
							<li class="divider"></li>
							<li><a href="/logout"><i class="icon-off"></i> 로그아웃</a></li>
						<?php else: ?>
							<li><a href="/timeline/<?php echo $this->otd_user->get('type_code_key') . '/' . $this->otd_user->get('serial_number') ?>"><i class="icon-home"></i> 홈으로</a></li>
							<li><a href="/mypage/userinfo"><i class="icon-cog"></i> <?php echo '설정'; if(in_array($this->otd_user->get('type_code_key'), array('startup'))) echo ' / 펀딩'; ?></a></li>
							<li class="divider"></li>
							<li><a href="/logout"><i class="icon-off"></i> 로그아웃</a></li>
						<?php endif; ?>
					</ul>
				</li>
			</ul>
			<form class="navbar-search pull-right search" action="/home/search" method="get">
				<input type="text" name="sv" class="search-query" placeholder="검색">
			</form>
		</div>
	</div>
</div>
<div class="subnav subnav-fixed">
	<div class="container">
		<ul class="nav nav-pills gnbmenu">
			<li><a href="/home/gnb/preparatory">예비창업자</a></li>
			<li><a href="/home/gnb/startup">스타트업</a></li>
			<li><a href="/home/gnb/angel_investor">엔젤투자자</a></li>
			<li><a href="/home/gnb/corporative_investor">법인투자자</a></li>
			<li><a href="/home/gnb/milestones">마일스톤</a></li>
			<li><a href="/home/gnb/fundings">펀딩NOW</a></li>
		</ul>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('form.search').submit(function() {
		//alert($('input').val());
	});
});
</script>