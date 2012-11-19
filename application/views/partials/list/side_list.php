<div class="side-wrap side pull-right">
	
	<!-- side > banner -->
	<div class="banner">
		<ul>
			<li><a href="/intro/about"><img src="/public/images/banner_help.jpg"></a></li>
		</ul>
	</div>
	
	<!-- side > fund-now -->
	<div class="fund-now">
		<h1>현재 펀딩 진행중 <i class="flag"></i></h1>
		<hr />
		<ul>
<?php
if (count($now_fundings) > 0):
	foreach($now_fundings as $now_funding):
?>
			<li>
				<a href="<?php echo '/timeline/' . $now_funding->user->type_code_key . '/' . $now_funding->user->serial_number; ?>" class="clearfix">
					<div class="profile pull-left">
						<img src="<?php echo $now_funding->profile ? '/thumbnail/' . $now_funding->profile->id . '?w=50&h=50' : '/public/images/avatar.png';?>" alt="">
					</div>
					<div class="inner-wrap">
						<strong><?php echo $now_funding->user->name; ?></strong>
						<span><?php echo number_format($now_funding->funding_amount); ?>만원 / <?php echo date('m월 d일', strtotime($now_funding->end_at)); ?> 까지</span>
					</div>
				</a>
			</li>
<?php endforeach; ?>
		</ul>
		<a href="/home/gnb/fundings" class="btn btn-more">펀딩중인 스타트업 더보기</a>
<?php else: ?>
		</ul>
		<div class="none-result">
			<p>진행중인 펀딩이 없습니다</p>
		</div>
<?php endif; ?>
	</div>

	<!-- side > milestone-now -->
	<div class="milestone-now">
		<h1>최근 마일스톤 달성 <!--i class="flag"></i--></h1>
		<hr />
		<ul>
<?php
if(count($complete_milestones) > 0):
	foreach($complete_milestones as $complete_milestone):
?>
			<li>
				<a href="<?php echo '/timeline/' . $complete_milestone->type_code_key . '/' . $complete_milestone->serial_number; ?>" class="clearfix">
					<div class="profile pull-left">
						<img src="<?php echo $complete_milestone->profile ? '/thumbnail/' . $complete_milestone->profile->id . '?w=50&h=50' : '/public/images/avatar.png';?>" alt="">
					</div>
					<div class="inner-wrap">
						<strong><?php echo $complete_milestone->name; ?></strong>
						<span><b>Step <?php echo $complete_milestone->position; ?></b> <?php echo $complete_milestone->title; ?></span>
						<!--<span>- <?php echo ifempty($complete_milestone->content, '내용 없음'); ?></span>-->
					</div>
				</a>
			</li>
<?php
	endforeach;
else:
?>
			<li class="none-result">
				<p>달성된 마일스톤이 없습니다.</p>
			</li>
<?php endif; ?>
		</ul>
		<a href="/home/gnb/milestones" class="btn btn-more">마일스톤 달성한 스타트업 더보기</a>
	</div>

	<!-- side > milestone-now -->
	<div class="vs-guide">
		<h1>성공 창업 가이드</h1>
		<hr />
		<ul>
<?php
if (count($startup_guides) > 0):
	foreach($startup_guides as $guide):
?>
			<li>
				<a href="<?php echo $guide['link']; ?>" target="_blank"><?php echo $guide['title']; ?></a>
			</li>
<?php
	endforeach;
	unset($guide);
else:
?>
			<li class="none-result"><p>결과가 없습니다</p></li>
<?php endif; ?>
		</ul>
		<a href="http://www.venturesquare.net/category/Startup%20Guide" target="_blank" class="btn btn-more">성공 창업 가이드 더보기</a>
	</div>

	<!-- side > banner -->
	<div class="banner">
		<ul>
			<li><a href="http://www.venturesquare.net" target="blank"><img src="/public/images/banner_vs.jpg"></a></li>
			<li><a href="http://www.bizinfo.go.kr/index.do" target="blank"><img src="/public/images/banner_bizinfo.jpg"></a></li>
		</ul>
	</div>

</div>
