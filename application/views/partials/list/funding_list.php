<div class="main-wrap pull-left">
	<div class="alert alert-info">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<b>도움말!</b> 현재 펀딩을 진행하고 있거나, 이미 펀딩을 진행한 예비창업자와 스타트업들을 소개해 드립니다. 진행 중인 펀딩을 누르거나, 회사의 타임라인으로 이동하여 펀딩에 직접 참여하실 수 있습니다.
	</div>
	<script type="text/javascript">
		$(".alert").alert();
	</script>
	<div class="fund list">
		<h1><i class="icon-big icon-star"></i>현재 진행중인 펀딩들</h1>
		<ul>
<?php
if (count($fundings) > 0):
	foreach ($fundings as $funding):
?>
			<li class="clearfix">
				<a href="/timeline/<?php echo $funding->user->type_code_key . '/' . $funding->user->serial_number ?>" class="card pull-left">
					<div class="card-profile">
						<img src=<?php echo $funding->user->upload ? '/thumbnail/' . $funding->user->upload->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $funding->user->name; ?></strong>
					<em><?php echo $funding->user->type_code_name; ?></em>
				</a>
				<div class="inner-wrap">
					<div class="meta">
						<a href="/timeline/<?php echo $funding->user->type_code_key . '/' . $funding->user->serial_number ?>" class=""><?php echo $funding->user->name; ?></a>
						<?php
							$last_login = $funding->user->last_login_at ? strtotime($funding->user->last_login_at) : strtotime($funding->user->created_at);
							if(time() > $last_login + (60 * 60 * 24 * 90)) {
								// live off
								echo '<i class="icon icon-warning-s"></i>';
							}
		                ?>
						 · <span>
					 		<?php switch ($funding->user->type_code_key) {
								case 'preparatory': echo 'PRE'; break;
								case 'startup': echo 'COM'; break;
								case 'angel_investor': echo 'ANGEL'; break;
								case 'corporative_investor': echo 'VC'; break;
								default: break;
						 	} echo $funding->user->serial_number;
						 	?>
						 	</span>
						<p class="desc"><?php echo $funding->user->biography; ?></p>
					</div>
					<h3><i class="icon icon-leaf"></i> <?php echo $funding->stage; ?></h3>
					<ul class="fund-desc">
						<li>- 투자유치금액 : <b><?php echo number_format($funding->funding_amount); ?></b>만원</li>
						<li>- 투자조건 :  발행가 <b><?php echo number_format($funding->funding_face_value * $funding->funding_multiple); ?></b>원, 액면가 <?php echo number_format($funding->funding_face_value); ?>원</li>
						<li>- 진행 기간 : <?php echo date('Y년 m월 d일', strtotime($funding->end_at)); ?>까지</li>
					</ul>
				</div>
			</li>
<?php
	endforeach;
else:
?>
			<li class="none-result">
				<p>진행중인 펀딩이 없습니다</p>
			</li>
<?php endif; ?>
		</ul>
	</div>
	<div class="list">
		<h1><i class="icon-big icon-now"></i>최근 진행되었던 펀딩들</h1>
		<ul>
<?php
if (count($histories) > 0):
	foreach ($histories as $history):
?>
			<li class="clearfix">
				<a href="/timeline/<?php echo $history->user->type_code_key . '/' . $history->user->serial_number ?>" class="card pull-left">
					<div class="card-profile">
						<img src=<?php echo $history->user->upload ? '/thumbnail/' . $history->user->upload->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $history->user->name; ?></strong>
					<em><?php echo $history->user->type_code_name; ?></em>
				</a>
				<div class="inner-wrap">
					<div class="meta">
						<a href="/timeline/<?php echo $history->user->type_code_key . '/' . $history->user->serial_number ?>" class=""><?php echo $history->user->name; ?></a>
						<?php
							$last_login = $history->user->last_login_at ? strtotime($history->user->last_login_at) : strtotime($history->user->created_at);
							if(time() > $last_login + (60 * 60 * 24 * 90)) {
								// live off
								echo '<i class="icon icon-warning-sign"></i>';
							}
		                ?>
						 · <span>
						 	<?php switch ($history->user->type_code_key) {
								case 'preparatory': echo 'PRE'; break;
								case 'startup': echo 'COM'; break;
								case 'angel_investor': echo 'ANGEL'; break;
								case 'corporative_investor': echo 'VC'; break;
								default: break;
						 	} echo $history->user->serial_number;
						 	?></span>
						<p class="desc"><?php echo $history->user->biography; ?></p>
					</div>
					<h3><i class="icon icon-leaf"></i> <?php echo $history->stage; ?></h3>
					<ul class="fund-desc">
						<li>- 투자유치금액 : <b><?php echo number_format($history->funding_amount); ?></b>만원</li>
						<li>- 투자조건 :  발행가 <b><?php echo number_format($history->funding_face_value * $history->funding_multiple); ?></b>원, 액면가 <?php echo number_format($history->funding_face_value); ?>원</li>
						<li>- 진행기간 : <?php echo date('y/m/d', strtotime($history->start_at)); ?> ~ <?php echo date('y/m/d', strtotime($history->end_at)); ?></li>
						<li>- 상태 : <?php echo $history->state == 'DONE' ? '펀딩 진행 완료' : '펀딩 성공'; ?></li>
					</ul>
				</div>
			</li>
<?php
	endforeach;
else:
?>
			<li class="none-result">
				<p>진행된 펀딩 내역이 없습니다.</p>
			</li>
<?php endif; ?>
		</ul>
		<?php echo $pagination; ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	document.title = '펀딩NOW :: 오픈트레이드';  
	
	// set selected nav active
	$('ul.gnbmenu li:nth-child(6)').addClass('active');
});
</script>