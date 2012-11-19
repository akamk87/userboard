<div class="main-wrap pull-left">
	<div class="alert alert-info">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<b>도움말!</b> 오픈트레이드에서 활동하고 있는 스타트업들을 만나보세요. 해당 페이지에서 최근의 소식들을 살펴보고, 타임라인에 글을 남기거나, 투자에 참여, 협업 제안도 하실 수 있습니다.
	</div>
	<script type="text/javascript">
		$(".alert").alert();
	</script>
	<div class="featured">
		<h1><i class="icon-big icon-update"></i>오픈트레이드가 추천하는 스타트업 TOP 5</h1>
		<ul class="card clearfix">
<?php
if (count($recommend_lists) > 0):
	foreach($recommend_lists as $recommend):
?>
			<li>
				<a href="/timeline/<?php echo $recommend->type_code->key . '/' . $recommend->serial_number ?>">
					<div class="card-profile">
						<img src=<?php echo $recommend->upload ? '/thumbnail/' . $recommend->upload->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $recommend->name; ?></strong>
					<em><?php echo $recommend->type_code->name; ?></em>
					<div class="desc">
						<strong>COM<?php echo $recommend->serial_number; ?></strong>
						<div class="biography">
							<?php echo $recommend->biography; ?>
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
	<div class="list">
		<div class="pull-right">
			<form class="form-search" action="/home/gnb/startup" method="get">
				<input type="text" class="input-medium" name="sv" value="<?php echo $sv; ?>" placeholder="회사이름 또는 기업번호">
				<button type="submit" class="btn btn-info">검색</button>
			</form>
		</div>
		<h1><i class="icon-big icon-now"></i>스타트업 찾아보기</h1>
		<ul>
<?php
if(count($startups) > 0):
	foreach($startups as $startup):
?>
			<li class="clearfix">
				<a href="/timeline/<?php echo $startup->type_code->key . '/' . $startup->serial_number ?>" class="card pull-left">
					<div class="card-profile">
						<img src=<?php echo $startup->upload ? '/thumbnail/' . $startup->upload->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $startup->name; ?></strong>
					<em><?php echo $startup->type_code->name; ?></em>
				</a>
				<div class="inner-wrap">
					<div class="meta">
						<a href="/timeline/<?php echo $startup->type_code->key . '/' . $startup->serial_number ?>" class=""><?php echo $startup->name; ?></a>
						<?php
							$last_login = $startup->last_login_at ? strtotime($startup->last_login_at) : strtotime($startup->created_at);
							if(time() > $last_login + (60 * 60 * 24 * 90)) {
								// live off
								echo '<i class="icon icon-warning-s"></i>';
							}
		                ?>
						 · <span>COM<?php echo $startup->serial_number; ?></span>
						<p class="desc"><?php echo $startup->biography; ?></p>
					</div>
					<h3><i class="icon icon-comment"></i> 최근소식</h3>
					<?php if($startup->timeline): ?>
					<p class="feed"><?php echo $startup->timeline->message; ?></p>
					<span class="date"><?php echo date('Y. m. d', strtotime($startup->timeline->created_at)); ?></span>
					<?php else: ?>
					<p class="feed">최근소식이 없습니다.</p>
					<?php endif; ?>
				</div>
			</li>
<?php
	endforeach;
else:
?>
			<li class="clearfix">
				결과가 없습니다.
			</li>
<?php endif; ?>
		</ul>
		<?php echo $pagination; ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	document.title = '스타트업 :: 오픈트레이드';  
	
	// set selected nav active
	$('ul.gnbmenu li:nth-child(2)').addClass('active');
});
</script>
