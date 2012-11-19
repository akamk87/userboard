<!-- main section -->
<div class="main-wrap pull-left">
	<div class="update">
		<h1><i class="icon-big icon-update"></i>최근 업데이트 된 곳은 어디어디?</h1>
		<h2>스타트업 및 예비창업자</h2>
		<ul class="card clearfix">
<?php
if (count($recent_startups) > 0):
	foreach($recent_startups as $recent_startup):
?>
			<li>
				<a href="/timeline/<?php echo $recent_startup->type_code_key . '/' . $recent_startup->serial_number ?>">
					<div class="card-profile">
						<img src=<?php echo $recent_startup->profile ? '/thumbnail/' . $recent_startup->profile->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $recent_startup->name; ?></strong>
					<em><?php echo $recent_startup->type_code_name; ?></em>
					<div class="desc">
						<strong>
							<?php switch ($recent_startup->type_code_key) {
								case 'preparatory': echo 'PRE'; break;
								case 'startup': echo 'COM'; break;
								case 'angel_investor': echo 'ANG'; break;
								case 'corporative_investor': echo 'VC'; break;
								default: break;
						 	} echo $recent_startup->serial_number;
						 	?>
						</strong>
						<div class="biography">
							<?php echo $recent_startup->biography; ?>
						</div>
						<div class="btn btn-small btn-info">자세히 보기</div>
					</div>
				</a>
			</li>
<?php
	endforeach;
else:
?>
			<div class="none-result">
				<p>추천 기업이 없습니다</p>
			</div>
<?php endif; ?>
		</ul>
		<h2>엔젤 및 법인 투자자</h2>
		<ul class="card clearfix">
<?php
if (count($recent_investors) > 0):
	foreach($recent_investors as $recent_investor):
?>
			<li>
				<a href="/timeline/<?php echo $recent_investor->type_code_key . '/' . $recent_investor->serial_number ?>">
					<div class="card-profile">
						<img src=<?php echo $recent_investor->profile ? '/thumbnail/' . $recent_investor->profile->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $recent_investor->name; ?></strong>
					<em><?php echo $recent_investor->type_code_name; ?></em>
					<div class="desc">
						<strong>
							<?php switch ($recent_investor->type_code_key) {
								case 'preparatory': echo 'PRE'; break;
								case 'startup': echo 'COM'; break;
								case 'angel_investor': echo 'ANG'; break;
								case 'corporative_investor': echo 'VC'; break;
								default: break;
						 	} echo $recent_investor->serial_number;
						 	?>
						</strong>
						<div class="biography">
							<?php echo $recent_investor->biography; ?>
						</div>
						<div class="btn btn-small btn-info">자세히 보기</div>
					</div>
				</a>
			</li>
<?php
	endforeach;
else:
?>
			<div class="none-result">
				<p>추천 기업이 없습니다</p>
			</div>
<?php endif; ?>
		</ul>
	</div>
	<div class="timeline recents">
		<h1><i class="icon-big icon-now"></i>지금 오픈트레이드에서는?</h1>
		<ul>
			
		</ul>
		<div class="more">
			<a class="btn btn-more">더 불러오기</a>
		</div>
	</div>
</div>