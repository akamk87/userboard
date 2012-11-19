<div class="main-wrap pull-left">
	<div class="alert alert-info">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<b>도움말!</b> 오픈트레이드에서 활동하고 있는 예비창업자와 스타트업들을 소개해 드립니다.<br>예비창업자 또는 스타트업이라면 지금 바로 마일스톤을 만들고 달성해 보세요.
	</div>
	<script type="text/javascript">
		$(".alert").alert();
	</script>
	<div class="milestone list">
		<h1><i class="icon-big icon-now"></i>최근 달성한 마일스톤</h1>
		<ul>
<?php
if(count($milestones) > 0):
	foreach($milestones as $milestone):
?>
			<li class="clearfix">
				<a href="/timeline/<?php echo $milestone->type_code->key . '/' . $milestone->serial_number ?>" class="card pull-left">
					<div class="card-profile">
						<img src=<?php echo $milestone->upload ? '/thumbnail/' . $milestone->upload->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $milestone->name; ?></strong>
					<em><?php echo $milestone->type_code->name; ?></em>
				</a>
				<div class="inner-wrap">
					<div class="meta">
						<a href="/timeline/<?php echo $milestone->type_code->key . '/' . $milestone->serial_number ?>" class=""><?php echo $milestone->name; ?></a>
						<?php
							$last_login = $milestone->last_login_at ? strtotime($milestone->last_login_at) : strtotime($milestone->created_at);
							if(time() > $last_login + (60 * 60 * 24 * 90)) {
								// live off
								echo '<i class="icon icon-warning-s"></i>';
							}
		                ?>
						 · <span>
					 		<?php switch ($milestone->type_code->key) {
								case 'preparatory': echo 'PRE'; break;
								case 'startup': echo 'COM'; break;
								case 'angel_investor': echo 'ANGEL'; break;
								case 'corporative_investor': echo 'VC'; break;
								default: break;
						 	} echo $milestone->serial_number;
						 	?>
						 	</span>
						<p class="desc"><?php echo $milestone->biography; ?></p>
					</div>
					<h3><i class="icon icon-flag"></i> 마일스톤</h3>
					<p class="feed milestone-desc">
						<strong><b>Step <?php echo $milestone->position; ?></b> <?php echo $milestone->title; ?></strong><br />
						<span>- <?php echo ifempty($milestone->content, '내용 없음'); ?></span>
					</p>
					<span class="date"><?php echo date('Y. m. d', strtotime($milestone->milestone_update)); ?></span>
				</div>
			</li>
<?php
	endforeach;
else:
?>
			<li class="none-result">
				<p>마일스톤 달성 내역이 없습니다</p>
			</li>
<?php endif; ?>
		</ul>
		<?php echo $pagination; ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	document.title = '마일스톤 :: 오픈트레이드';  
	
	// set selected nav active
	$('ul.gnbmenu li:nth-child(5)').addClass('active');
});
</script>
